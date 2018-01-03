<?php

namespace backend\modules\goods\controllers;

use Yii;

use common\models\Goods;
use common\models\Goodsmeta;
use common\models\Category;
use common\models\Categorymeta;
use common\models\GoodsCategory;
use common\models\GoodsToCategory;
use common\models\GoodsToTag;
use common\models\Group;
use common\models\Goodsfeature;
use common\models\Goodsattr;
use common\models\Goodssku;
use common\models\GoodsOptions;
use common\models\GoodsOptionsGroup;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\db\Query;
/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends Controller
{
    public $feature = array('size'=>'1','spec'=>'2');
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update','delete','ajax','uploads','download','delete-all','copy'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    public function actions(){
        return [
        'upload' => [
            'class' => 'thridpart\ueditor\UEditorAction',
        ]
    ];
    } 

    /**
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        // 查询条件
        $name = \Yii::$app->request->get('name');
        $sku = \Yii::$app->request->get('sku');
        $status = \Yii::$app->request->get('status');

        $query = new Query;
        $rs = $query->select('c.*,m.language,m.title')
                            ->from("{{%goods}} c")
                            ->leftJoin("{{%goodsmeta}} m",'c.id=m.goods_id');
                            // ->where("m.language=:language",[':language'=>Yii::$app->language])
        if(!empty($name)){
            $rs = $rs->andWhere(['like','m.title',$name]);
        }

        if(!empty($sku)){
            $rs = $rs->andWhere(['c.sku'=>$sku]);
        }

        if($status!=null){
            $rs = $rs->andWhere(['c.status'=>$status]);
        }
                            
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $rs->groupBy('c.id')
                            ->orderBy('c.id desc')
                            ->all(),
            'sort' => [
                'attributes' => ['id', 'order_id'],
            ],
            'key'=>'id',
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $goodscategory_count = GoodsCategory::find()->count();
        // var_dump($goodscategory_count);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'goods_category' => $this->getGoodsCategory(),
            'goodscategory_count'=>$goodscategory_count,
        ]);
    }

    /**
     * Displays a single Goods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    // 复制数据操作
    public function actionCopy($id){
        // 取出要复制的数据
        $model = $this->findModel($id);
        $meta = $this->findMeta($id);
        $attr = $this->findAttr($id,'attr');

        // $goodsfeature = $this->getGoodsFeature($id),
        // $goodssku = $this->GetSku($id),
        // $feature = $this->getFeature($model->goods_cat_id),
        // $featureattr = $this->getFeatureattr($model->goods_cat_id),
        // $goods_category = $this->getGoodsCategory(),
        $goodstocategory = $this->getGoodsToCategory($id);
        $goodstotag = $this->getGoodsToTag($id);
        $goodsoptionsgroup = $this->getGoodsOptionsGroup($id);

        $transaction = Yii::$app->db->beginTransaction();

        try {

            // 复制基本表
            $new_model = new Goods();
            $new_model->attributes = $model->attributes;

            $new_model->save();
            $flat = 0;
            // 扩展表复制
            if(!empty($meta)){
                foreach ($meta as $k => $v) {
                    $new_meta = new Goodsmeta();
                    $new_meta->attributes = $v;
                    $new_meta->goods_id = $new_model->id;

                    $new_meta->save();
                    $flat++;
                }
            }

            // 复制分类表
            if(!empty($goodstocategory)){
                foreach ($goodstocategory as $k => $v) {

                    $new_goodstocategory = new GoodsToCategory();
                    $new_goodstocategory->cat_id = $v;
                    $new_goodstocategory->goods_id = $new_model->id;

                    $new_goodstocategory->save();
                }
            }


            // 复制标签表
            if(!empty($goodstotag)){
                foreach ($goodstotag as $k => $v) {
                    $new_goodstotag = new GoodsToTag();
                    $new_goodstotag->tag_id = $v;
                    $new_goodstotag->goods_id = $new_model->id;
                    $new_goodstotag->save();
                }
            }

            // 复制attr表
            if(!empty($attr)){
                foreach ($attr as $k => $v) {
                    $new_attr = new Goodsattr();
                    $new_attr->attributes = $v;
                    $new_attr->goods_id = $new_model->id;
                    $new_attr->save();
                }
            }

            // 复制attr表
            if(!empty($goodsoptionsgroup)){
                foreach ($goodsoptionsgroup as $k => $v) {
                    $new_goodsoptionsgroup = new GoodsOptionsGroup();
                    $new_goodsoptionsgroup->attributes = $v;
                    $new_goodsoptionsgroup->goods_id = $new_model->id;
                    $new_goodsoptionsgroup->save();

                    if(!empty($v['options_value'])){
                        foreach ($v['options_value'] as $_k => $_v) {
                            $new_goodsoptions = new GoodsOptions();
                            $new_goodsoptions->attributes = $_v;
                            $new_goodsoptions->goods_id = $new_model->id;
                            $new_goodsoptions->g_options_group_id = $new_goodsoptionsgroup->primaryKey;
                            $new_goodsoptions->save();
                        }
                    }
                }
            }

            if($flat){
                $transaction->commit();
                \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success')); 
            }else{
                $transaction->rollBack();
            }
            
        } catch (Exception $e) {
            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Error')); 
        }
        return $this->redirect(['index']);
    }
    /**
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods();
        $feature = array('size'=>array(),'spec'=>array());
        $sku = array();
        $list['urlReferrer'] = \Yii::$app->request->referrer;

        if(isset($_GET['g_cat_id'])){
            \Yii::$app->session['g_cat_id'] = \Yii::$app->request->get('g_cat_id');
        }else{
            \Yii::$app->session['g_cat_id'] = $this->getGoodsCategoryOneID();
        }
        $g_cat_id = \Yii::$app->session['g_cat_id'];

        $model->loadDefaultValues();
        
        $model->goods_cat_id = $g_cat_id;
        $model->modifytime = empty($model->modifytime) ? time() : $model->modifytime;
        $model->modifytime = date('Y-m-d H:i:s',$model->modifytime);
        $model->addtime = (string)strtotime($model->modifytime);

        if ($model->load(Yii::$app->request->post())) {
            
            $data = $this->saveGoods($model,'add');
            
            \Yii::$app->getSession()->setFlash('message', $data['message']); 
            $urlReferrer = \Yii::$app->request->post('r');

            if($data['status']==1){
                
                if(empty($urlReferrer)||$urlReferrer==$list['urlReferrer']){
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect($urlReferrer);
                }
            }else{
                $model->modifytime = date('Y-m-d H:i:s',$model->modifytime);
                $list['urlReferrer'] = $urlReferrer;
                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                    'attr' => Yii::$app->request->post('goods_attr'),
                    'list' => $list,
                    'goodsfeature' => $feature,
                    'goodssku' => $sku,
                    'featureattr'=>$this->getFeature(),
                    'goodstocategory' => Yii::$app->request->post('category'),
                    'goodstotag'=>Yii::$app->request->post('tag'),
                    'goodsoptionsgroup' => [],
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'meta' => array(),
                'attr' => array(),
                'list' => $list,
                'goodsfeature' => $feature,
                'goodssku' => $sku,
                'feature'=>$this->getFeature($g_cat_id),
                'featureattr'=>$this->getFeatureattr($g_cat_id),
                'goods_category' => $this->getGoodsCategory(),
                'goodstocategory' => array(),
                'goodstotag'=>array(),
                'goodsoptionsgroup' => [],
            ]);
        }
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->modifytime = empty($model->modifytime) ? time() : $model->modifytime;
        $model->modifytime = date('Y-m-d H:i:s',$model->modifytime);

        $model->goods_cat_id = empty($model->goods_cat_id) ? $this->getGoodsCategoryOneID():$model->goods_cat_id;
        
        $list['urlReferrer'] = \Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            $data = $this->saveGoods($model,'update');
            
            \Yii::$app->getSession()->setFlash('message', $data['message']);

            $urlReferrer = \Yii::$app->request->post('r');

            if($data['status']==1){
                
                if(empty($urlReferrer)||$urlReferrer==$list['urlReferrer']){
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect($urlReferrer);
                }
            }else{
                $model->modifytime = date('Y-m-d H:i:s',$model->modifytime);
                $list['urlReferrer'] = $urlReferrer;

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                    'attr' => Yii::$app->request->post('goods_attr'),
                    'list'=>$list,
                    'goodsfeature' => $this->getGoodsFeature($id),
                    'goodssku' => $this->GetSku($id),
                    'feature'=>$this->getFeature($model->goods_cat_id),
                    'featureattr'=>$this->getFeatureattr($model->goods_cat_id),
                    'goods_category' => $this->getGoodsCategory(),
                    'goodstocategory' => $this->getGoodsToCategory($id),
                    'goodstotag'=>$this->getGoodsToTag($id),
                    'goodsoptionsgroup' => $this->getGoodsOptionsGroup($id),
                ]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
                'meta' => $this->findMeta($id),
                'attr' => $this->findAttr($id,'attr'),
                'list'=>$list,
                'goodsfeature' => $this->getGoodsFeature($id),
                'goodssku' => $this->GetSku($id),
                'feature'=>$this->getFeature($model->goods_cat_id),
                'featureattr'=>$this->getFeatureattr($model->goods_cat_id),
                'goods_category' => $this->getGoodsCategory(),
                'goodstocategory' => $this->getGoodsToCategory($id),
                'goodstotag'=>$this->getGoodsToTag($id),
                'goodsoptionsgroup' => $this->getGoodsOptionsGroup($id),
            ]);
        }
    }
    // 下載產品
    public function actionDownload()
    {
        if (Yii::$app->user->identity->power != 'admin') return $this->redirect(['index']);

        ini_set('memory_limit', '-1');

        require(dirname(Yii::$app->basePath).'/thridpart/phpexcel/PHPExcel.php'); 
        $phpexcel = new \PHPExcel();

        $sheet = $phpexcel->setActiveSheetIndex(0);

        // 頭部
        $sheet->setCellValue('A1', '位置');
        $sheet->setCellValue('B1', '菜碼');
        $sheet->setCellValue('C1', '食品的名稱');
        $sheet->setCellValue('D1', '配菜分组');
        $sheet->setCellValue('E1', '是否為必填');
        $sheet->setCellValue('F1', '配菜');
        $sheet->setCellValue('G1', '食品的描述');
        $sheet->setCellValue('H1', '口味');
        $sheet->setCellValue('K1', '價格');
        $sheet->setCellValue('L1', '顯示位置');
        $sheet->setCellValue('M1', '類別名稱');
        $sheet->setCellValue('N1', '類別說明');
        $sheet->setCellValue('O1', '貨幣');
        $sheet->setCellValue('A2', 'Position');
        $sheet->setCellValue('B2', 'Dish code');
        $sheet->setCellValue('C2', 'Food Name');
        $sheet->setCellValue('G2', 'Food Descriptions');
        $sheet->setCellValue('H2', 'vegetable');
        $sheet->setCellValue('I2', 'Spicy');
        $sheet->setCellValue('J2', 'Peanut');
        $sheet->setCellValue('K2', 'Price');
        $sheet->setCellValue('L2', 'Category Display Position');
        $sheet->setCellValue('M2', 'Category name');
        $sheet->setCellValue('N2', 'Category Descriptions');
        $sheet->setCellValue('O2', 'Currency');

        $bgColumns = range('A', 'O');
        foreach ($bgColumns as $col) {
            $sheet->getStyle("${col}2")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C0C0C0');
        }

        $row = 3;
        $allGoods = Goods::getGoodsAll();

        $currency = \common\models\Config::getConfig('currency');

        foreach ($allGoods as $key => $goods) {
            $sheet->setCellValue("A${row}", $key + 1);
            $sheet->setCellValue("B${row}", $goods['sku']);
            $sheet->setCellValue("C${row}", $goods['title']);
            $sheet->setCellValue("G${row}", $goods['content']);
            $sheet->setCellValue("K${row}", $goods['price']);
            $sheet->setCellValue("O${row}", $currency);

            $sheet->getStyle("K${row}")->getNumberFormat()->setFormatCode('0.00');

            // Goods attr
            $goodsAttr = Goodsattr::find()->where(['goods_id' => $goods['id'], 'attr_name' => 'attr', 'language' => $goods['language']])->asArray()->one();
            $goodsAttrValue = @unserialize($goodsAttr['attr_value']);
            if (is_array($goodsAttrValue) && !empty($goodsAttrValue)) {
                foreach ($goodsAttrValue as $attrValue) {
                    if ((int)$attrValue['options'] == 1) {
                        switch ($attrValue['name']) {
                            case 'Vegetable':
                                $sheet->setCellValue("H${row}", 1);
                                break;
                            case 'Spicy':
                                $sheet->setCellValue("I${row}", 1);                            
                                break;
                            case 'Peanut':
                                $sheet->setCellValue("J${row}", 1);
                                break;
                        }
                    }
                }
            }

            // Goods category
            $catQuery = (new Query())->select('c.id, c.order_id, cm.name, cm.description')
                ->from('{{%category}} c')
                ->leftjoin('{{%categorymeta}} cm', 'c.id = cm.cat_id')
                ->leftJoin('{{%goods_to_category}} gc', 'c.id = gc.cat_id')
                ->where(['gc.goods_id' => $goods['id']])
                ->andWhere(['cm.language' => $goods['language']])
                ->orderBy('c.id DESC')
                ->one();
            if ($catQuery) {
                $sheet->setCellValue("L${row}", $catQuery['order_id']);
                $sheet->setCellValue("M${row}", $catQuery['name']);
                $sheet->setCellValue("N${row}", $catQuery['description']);
            }

            $optionsGroup = GoodsOptionsGroup::find()->where(['goods_id' => $goods['id']])->asArray()->all();
            if ($optionsGroup) {
                foreach ($optionsGroup as $group) {
                    $options = GoodsOptions::find()->where(['goods_id' => $goods['id'], 'g_options_group_id' => $group['g_options_group_id']])->asArray()->all();
                    if ($options) {
                        foreach ($options as $o) {
                            $row++;

                            $sheet->setCellValue("B${row}", $goods['sku']);
                            $sheet->setCellValue("C${row}", $goods['title']);
                            $sheet->setCellValue("O${row}", $currency);
                            $sheet->setCellValue("D${row}", $group['name']);
                            if ($catQuery) {
                                $sheet->setCellValue("M${row}", $catQuery['name']);
                            }
                            if ((int)$group['required'] == 1) {
                                $sheet->setCellValue("E${row}", 1);
                            }

                            $sheet->setCellValue("F${row}", $o['name']);
                            $sheet->setCellValue("K${row}", $o['price']);

                            $sheet->getStyle("K${row}")->getNumberFormat()->setFormatCode('0.00');
                        }
                    } else {
                        $row++;
                        $sheet->setCellValue("B${row}", $goods['sku']);
                        $sheet->setCellValue("C${row}", $goods['title']);
                        $sheet->setCellValue("O${row}", $currency);
                        $sheet->setCellValue("D${row}", $group['name']);
                        if ($catQuery) {
                            $sheet->setCellValue("M${row}", $catQuery['name']);
                        }
                        if ((int)$group['required'] == 1) {
                            $sheet->setCellValue("E${row}", 1);
                        }
                    }
                }
            }

            $row++;
        }

        // 下載文件
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Products_' . date('Y-m-d_His') . '.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $writer = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
        $writer->save('php://output');
    }
    // 上傳產品
    public function actionUploads(){

        return $this->render('upload');
    }
    // ajax操作
    public function actionAjax()
    {
        ini_set('memory_limit', '-1');
        
        $type=\Yii::$app->request->post('type','updategoodscategory');

        if($type=='updategoodscategory'){
            $id = \Yii::$app->request->post('goods_id');
            $g_cat_id = \Yii::$app->request->post('g_cat_id');
            $flat = Goods::updateAll(['goods_cat_id'=>$g_cat_id],'id=:key',[':key'=>$id]);
            echo $flat;
        }else if($type=='upload'){

            $data = ['status'=>'0','message'=>'no input file'];
            if(isset($_FILES['file'])&&$_FILES['file']['error']==0){

                $filename = @explode('.', $_FILES['file']['name']);
                $excelFile = $_FILES['file']['tmp_name'];

                require(dirname(Yii::$app->basePath).'/thridpart/phpexcel/PHPExcel.php'); 
                $phpexcel = new \PHPExcel();

                // 查看是不是CSV文件
                if(isset($filename['1'])&&strtolower($filename['1'])=='csv'){
                    $excelReader = \PHPExcel_IOFactory::createReader('CSV');
                    $excelReader->setDelimiter(',');
                    if(!$excelReader->canRead($excelFile)){
                        $data['message'] = 'no CSV File';
                        echo json_encode($data);
                        $Yii::$app->end();
                    }

                }else{
                    //查看2007
                    $excelReader = \PHPExcel_IOFactory::createReader('Excel2007');
                    if(!$excelReader->canRead($excelFile)){
                        //查看表格
                        $excelReader = \PHPExcel_IOFactory::createReader('Excel5');
                        if(!$excelReader->canRead($excelFile)){
                            $data['message'] = 'no Excel File';
                            echo json_encode($data);
                            \Yii::$app->end();
                        }
                    }
                }

                // 读取每一个表格的第一页内容
                $phpexcel = $excelReader->load($excelFile)->getSheet(0);

                // 获取总行 总例
                $total_line = $phpexcel->getHighestRow();
                $total_column = $phpexcel->getHighestColumn();

                // 设置表头的开始位置
                $rowIndex = 2;

                $column_value = [];
                // 获取表头信息
                for ($column='A'; $column <$total_column ; $column++) { 
                    $column_value[$column] = trim($phpexcel->getCell($column.$rowIndex)->getValue());

                }

                // 获取表的内容信息
                $data_column = [];

                // 获取分组的ID
                $g_cat_id = $this->getGoodsCategoryOneID();
                $language = getLanguage('en-US',0);

                // var_dump($language);exit();
                for ($row=3; $row <= $total_line ; $row++) { 
                    $row_column_value = [];

                    for ($column='A'; $column <$total_column ; $column++) {

                        $row_column_value[$column] = trim($phpexcel->getCell($column.$row)->getValue());

                    }

                    if(!empty($row_column_value)){
                       $data['message'] = $this->saveUploadGoods($row_column_value,$g_cat_id,$language['code']);
                       // echo $row;
                       // print_r($data['message']);
                       // echo "\n";
                    }
                    

                }
                // if($data['message']===true)
                //     $data['message'] = \Yii::t('app','Success');
            }
            
            echo json_encode($data);
            Yii::$app->end();
        }

    }
    /**
     * Deletes an existing Goods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // start delete
    public function actionDelete($id)
    {
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $this->findModel($id)->delete();
            $this->metaDelete($id);
            $this->attrDelete($id);
            $this->goodsfeatureDelete($id);
            $this->skuDelete($id);
            $this->goodstocategoryDelete($id);
            $this->goodstotagDelete($id);
            $this->goodsOptionsGroupDelete($id);

            $transaction->commit();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success')); 
        }catch(Exception $e)
        {

            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage()); 
        }
        

        return $this->redirect(['index']);
    }

    // 刪除選定產品
    public function actionDeleteAll(){

        $goods_id = \Yii::$app->request->post('selection');

        if(!empty($goods_id)):
        $transaction=Yii::$app->db->beginTransaction();
        try{
            foreach ($goods_id as $k => $v) {
                $this->findModel($v)->delete();
                $this->metaDelete($v);
                $this->attrDelete($v);
                $this->goodsfeatureDelete($v);
                $this->skuDelete($v);
                $this->goodstocategoryDelete($v);
                $this->goodstotagDelete($v);
                $this->goodsOptionsGroupDelete($v);
            }
            

            $transaction->commit();
            echo Yii::t('app','Success');
        }catch(Exception $e)
        {

            $transaction->rollBack();
            echo $e->getMessage(); 
        }
        endif;

    }
    // 删除产品扩展属性
    protected function attrDelete($id){
        return Goodsattr::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
    }

    // 删除产品扩展
    protected function metaDelete($id){
        return Goodsmeta::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
    }
    // 删除产品特征属性
    protected function goodsfeatureDelete($id){
        return Goodsfeature::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
    }

    // 删除产品SKU属性
    protected function skuDelete($id){
        return Goodssku::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
    }

    // 刪除產品對應分類
    protected function goodstocategoryDelete($id){
        return GoodsToCategory::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
    }
    // 刪除產品對應標籤
    protected function goodstotagDelete($id){
        return GoodsToTag::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
    }

    // 删除扩展选项组
    protected function goodsOptionsGroupDelete($id,$group_id=''){
        if(empty($group_id)){
            $model = GoodsOptionsGroup::find()->where(['goods_id'=>$id])->asArray()->all();
            if ($model) {
                foreach ($model as $k => $v) {
                    GoodsOptions::deleteAll('g_options_group_id=:key0 and goods_id=:key1',[':key0'=>$v['g_options_group_id'],':key1'=>$id]);

                }
            }
            return GoodsOptionsGroup::deleteAll('goods_id=:goods_id',[':goods_id'=>$id]);
        }else{
            $model = GoodsOptionsGroup::findOne($group_id);
            GoodsOptions::deleteAll('g_options_group_id=:key0 and goods_id=:key1',[':key0'=>$group_id,':key1'=>$id]);
            
            if ($model) {
                return $model->delete();
            }

            return true;
        }
        

    }

    // 删除扩展选项
    protected function goodsOptionsDelete($id)
    {
        GoodsOptions::deleteAll('g_options_id=:key0',[':key0'=>$id]);
    }

    // end delete
    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    // start find
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findMeta($id)
    {
        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%goodsmeta}} a")
            ->where('a.goods_id=:key',[':key'=>$id])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }

    // 获取产品的扩展属性信息
    protected function findAttr($id,$attr_name){
        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%goodsattr}} a")
            ->where('a.goods_id=:key and attr_name=:attr',[':key'=>$id,':attr'=>$attr_name])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }

    // 获取产品的sku信息
    protected function GetSku($id=0,$flat=1){
        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
                ->from("{{%goodssku}} a")
                ->where('a.goods_id=:goods_id',[':goods_id'=>$id])
                ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['feature_arr']] = $v;
        }
        return $arr;
        
    }
    // 获取产品对应的特征属性信息
    protected function getGoodsFeature($id=0,$flat=1){
        $query = new Query();
        $arr = array();
        // $arr['size'] = array();
        // $arr['spec'] = array();

            $rs = $query->select("a.*,fa.name,f.feature")
                    ->from("{{%goodsfeature}} a")
                    ->leftjoin("{{%featureattr}} fa",'fa.id=a.fatt_id')
                    ->leftjoin("{{%feature}} f",'f.feature=a.feature_code')
                    ->where('a.goods_id=:goods_id',[':goods_id'=>$id])
                    ->all();
            foreach ($rs as $k => $v) {
                // if($v['feature']=='size'){
                //     $arr['size'][$v['fatt_id']] = $v;
                // }elseif($v['feature']=='spec'){
                //    $arr['spec'][$v['fatt_id']] = $v; 
                // }
                $arr[$v['feature']][$v['fatt_id']]=$v;
            }
            return $arr;
    }
    // 获取特征信息
    protected function getFeature($id=0,$flat=1){
        $query = new Query();
        $arr = array();

            $rs = $query->select("m.feature_id,m.name,f.feature,f.options")
                    ->from("{{%feature}} f")
                    ->leftjoin("{{%featuremeta}} m",'f.id=m.feature_id')
                    ->leftjoin("{{%goods_category}} gc",'gc.group_id=f.group_id')
                    ->where("gc.g_cat_id=:key",[':key'=>$id])
                    ->groupBy('f.id')
                    ->all();
            foreach ($rs as $k => $v) {
                $arr[$v['feature']] = $v;
            }
            return $arr;
        
    }

    // 获取特征属性信息
    protected function getFeatureattr($id=0,$flat=1){
        $query = new Query();
        $arr = array();

            $rs = $query->select("a.id,a.name,f.feature,f.options")
                    ->from("{{%featureattr}} a")
                    ->leftjoin("{{%feature}} f",'f.feature=a.feature_code')
                    ->leftjoin("{{%goods_category}} gc",'gc.group_id=f.group_id')
                    ->where("gc.g_cat_id=:key",[':key'=>$id])
                    ->all();
          
            return $rs;
    }
    // 获取产品类目列表
    public function getGoodsCategory(){
        $rs = (new Query())->select('g.*,m.name,m.language')
            ->from("{{%goods_category}} g")
            ->leftJoin("{{%goods_categorymeta}} m",'g.g_cat_id=m.g_cat_id')
            ->groupBy('g.g_cat_id')
            ->orderBy('g.order_id asc, g.g_cat_id asc')
            ->all();
        return $rs;
    }

    // 獲取產品關聯的分類ID
    public function getGoodsToCategory($id){
        $model = GoodsToCategory::find()->where(['goods_id'=>$id])->asArray()->all();
        $arr= array();
        foreach ($model as $key => $value) {
            $arr[]=$value['cat_id'];
        }
        return $arr;
    }

    // 獲取產品關聯的標籤ID
    public function getGoodsToTag($id){
        $model = GoodsToTag::find()->where(['goods_id'=>$id])->asArray()->all();
        $arr= array();
        foreach ($model as $key => $value) {
            $arr[]=$value['tag_id'];
        }
        return $arr;

    }

    // 获取分组
    public function getGroup($id){

    }

    // 获取第一个分组信息
    public function getGoodsCategoryOneID()
    {
        $rs = GoodsCategory::find()->asArray()->one();
        return $rs['g_cat_id'];
    }   

    // 获取分组
    public function getGoodsOptionsGroup($goods_id){

        $group = GoodsOptionsGroup::find()->where(['goods_id'=>$goods_id])->asArray()->all();

        for ($i=0; $i < count($group); $i++) { 

            $group[$i]['options_value'] = $this->getGoodsOptions($goods_id,$group[$i]['g_options_group_id']);


        }
        return $group;
    }

    // 获取扩展选项
    public function getGoodsOptions($goods_id,$group_id){
        $options = GoodsOptions::find()->where(['goods_id'=>$goods_id,'g_options_group_id'=>$group_id])->asArray()->all();
        return $options;
    }
    // end find


    // 产品添加修改操作
    public function saveGoods($model,$action='create'){

        // 初始化变量属性
        $data = array();
        $data['status'] = 0;
        $data['size_status'] = 1;//初始化Goodsfeature操作状态为真
        $data['attr_status'] = 1;//初始化Goodsattr操作状态为真
        $data['message'] = '';
        $data['attr_message'] = '';
        $message = '';

        // 获取POST的信息
        $image_attr = Yii::$app->request->post('image_attr');
        $category = Yii::$app->request->post('category');
        $tag = Yii::$app->request->post('tag');

        $model->modifytime = (string)strtotime($model->modifytime);
        $model->images = serialize($image_attr);
        // $model->cat_id = $category;
        // $model->tag_id = $tag;

        if($model->validate()){

            // 获取扩展特征POST的信息
            $feature = Yii::$app->request->post('feature');

            $goodsfeature = Yii::$app->request->post('goodsfeature');
            
            // var_dump($feature);
             // var_dump($goodsfeature);  
             // exit();  
            $sku = Yii::$app->request->post('sku');
            // $feature_price = Yii::$app->request->post('feature_price');
            // $feature_quanity = Yii::$app->request->post('feature_quanity');
            // $feature_skuno = Yii::$app->request->post('feature_skuno');

            // 获取扩展POST的信息
            $goods_attr = Yii::$app->request->post('goods_attr');
           

            $transaction=Yii::$app->db->beginTransaction();
            try{
                $model->save();

                if($action=='update'){
                        // 删除goodsfeature 和 sku表的数据然后再添加
                        $this->goodsfeatureDelete($model->id);
                        $this->metaDelete($model->id);
                        $this->attrDelete($model->id);
                        $this->skuDelete($model->id);
                        $this->goodstocategoryDelete($model->id);
                        $this->goodstotagDelete($model->id);
                }

                // 產品分類操作
                if(is_array($category)){
                    foreach ($category as $k => $v) {
                        $category_model = new GoodsToCategory();
                        $category_model->goods_id = $model->primaryKey;
                        $category_model->cat_id = $v;
                        $category_model->save();

                    }
                }

                //產品標籤操作
                if(is_array($tag)){
                    foreach ($tag as $k => $v) {
                        $tag_model = new GoodsToTag();
                        $tag_model->goods_id = $model->primaryKey;
                        $tag_model->tag_id = $v;
                        $tag_model->save();
                    }
                }


                $i = 0;
                foreach (getLanguage() as $k => $v) {
                    $i++;
                    // 添加扩展信息操作
                    $meta = new Goodsmeta();
                    
                    $meta->goods_id = $model->primaryKey;
                    $meta->language = $k;
                    $meta->attributes = Yii::$app->request->post('meta')[$k];
                    
                    if($meta->validate()){
                        $meta->save();
                        $data['status'] = $i;
                    }else{
                        $message .= $v['name'].':';
                        foreach ($meta->getErrors() as $key => $value) {
                            $message .= isset($value['0']) ? $value['0'] : '';
                        }
                        $message .= '</br/>';
                    }

                    if(!empty($goods_attr)&&isset($goods_attr[$k])){
                        $data['attr_status'] = 0;
                        $attr = new Goodsattr();
                        $attr->attr_value = serialize($goods_attr[$k]);
                        $attr->attr_name = 'attr';
                        $attr->goods_id = $model->primaryKey;
                        $attr->language = $k;

                        if($attr->validate()&&$attr->save()){
                            $data['attr_status'] = 1;
                        }else{
                            $data['attr_message'] .= $v['name'].':';
                            foreach ($attr->getErrors() as $key => $value) {
                                $data['attr_message'] .= isset($value['0']) ? $value['0'] : '';
                            }
                            $data['attr_message'] .= '</br/>';
                        }
                    }

                }
                
                // 添加扩展分组信息
                $this->saveGoodsOptionsGroup($model->primaryKey);
                // 是否複製配菜
                $copyOptionsGroupFrom = (int)Yii::$app->request->post('CopyOptionsGroupFrom', 0);
                $copyOptionsGroupTo = (int)Yii::$app->request->post('CopyOptionsGroupTo', 0);
                if ($copyOptionsGroupFrom > 0 && $copyOptionsGroupFrom <= $copyOptionsGroupTo) {
                    for ($copyId = $copyOptionsGroupFrom; $copyId <= $copyOptionsGroupTo; $copyId++) {
                        $this->saveGoodsOptionsGroup($copyId, true, true);
                    }
                }

                    // 添加产品特征信息操作
                    if(!empty($feature)&&!empty($sku)){

                        //获取feature复选框的内容
                        foreach ($feature as $k => $v) {

                            // 循环出复选框分组信息
                            foreach ($v as $_k => $_v) {
                                $data['size_status'] = 0;
                                $feature_model = new Goodsfeature();

                                $feature_model->fatt_id = $_v; //featureattr id
                                $feature_model->goods_id = $model->primaryKey; //goods id
                                $feature_model->feature_code = $k; //featureattr id
                                $feature_model->options = $goodsfeature[$k][$_v]; //扩展信息
                                
                                if($feature_model->validate()&&$feature_model->save()){
                                    $data['size_status'] = 1;
                                }

                            }

                        }
                        

                        // 添加GOODS SKU表信息
                        foreach ($sku as $k => $v) {
                            $data['size_status'] = 0;
                            $sku_model = new Goodssku();
                            $sku_model->attributes = $v;
                            $sku_model->goods_id = $model->primaryKey;

                            if($sku_model->validate()&&$sku_model->save()){
                                $data['size_status'] = 1;
                            }
                        }

                    }

                    if($data['attr_status']&&$data['size_status']&&$data['status']==$i){
                        $transaction->commit(); 
                        $data['status'] = 1;
                        $data['message'] = Yii::t('app','success');
                        // \Yii::$app->getSession()->setFlash('message', Yii::t('app','success'));
                    }else{
                        $data['status'] = 0;
                        $data['message'] = Yii::t('app','Goods extensions error').'<br/>'.$message.$data['attr_message'];
                        // \Yii::$app->getSession()->setFlash('message', '产品扩展信息出错');
                    }
                                            
            }
            catch(Exception $e)
            {
                $data['status'] = 0;
                $transaction->rollBack();
                $data['message'] = $e->getMessage();
            }
        }else{
            $message = '';
            $data['status'] = 0;
            foreach ($model->getErrors() as $k => $v) {
               $message .= $v['0'];
            }
            $data['message'] = $message;
                
        }
            
            
        return $data;
    }

    // 扩展选项组保存操作
    public function saveGoodsOptionsGroup($goods_id, $isCopy = false, $isCopyReplace = true){

        $group = \Yii::$app->request->post('GoodsOptionsGroup');
        $groupdelete = \Yii::$app->request->post('groupdelete');
        $optionsdelete = \Yii::$app->request->post('optionsdelete');

        if (!is_array($group)) $group = [];

        // 複製配菜，覆蓋原產品所有配菜
        if ($isCopy) {
            $model = GoodsOptionsGroup::findOne(['goods_id'=>$goods_id]);
            if ($isCopyReplace) {
                $this->goodsOptionsGroupDelete($goods_id);
            }
            foreach ($group as $ck => $cv) {
                // 處理 g_options_group_id
                if (!$isCopyReplace && $model) {
                    $group[$ck]['g_options_group_id'] = $model->g_options_group_id;
                } else {
                    $group[$ck]['g_options_group_id'] = null;
                }
                // 處理 g_options_id
                $copyOptionsValue = isset($cv['options_value']) ? $cv['options_value'] : '';
                if (!empty($copyOptionsValue)) {
                    foreach ($copyOptionsValue['g_options_id'] as $cok => $cov) {
                        $group[$ck]['options_value']['g_options_id'][$cok] = null;
                    }
                }
            }
        }

        // 删除选项组操作
        if(!empty($groupdelete)&&is_array($groupdelete)){

            for ($i=0; $i <count($groupdelete) ; $i++) { 

                $this->goodsOptionsGroupDelete($goods_id,$groupdelete[$i]);
            }
        }

        // 删除选项操作
        if(!empty($optionsdelete)&&is_array($optionsdelete)){

            for ($i=0; $i <count($optionsdelete) ; $i++) { 

                $this->goodsOptionsDelete($optionsdelete[$i]);
            }
        }

        // 操作POST过来的数组进行更新或添加
        if(!empty($group)&&is_array($group)){
            foreach ($group as $k => $v) {
                $options_value = isset($v['options_value']) ? $v['options_value'] : '';
                unset($v['options_value']);
                $v['required'] = isset($v['required']) ? $v['required'] : 0;

                if(empty($v['g_options_group_id'])&&!empty($v['name'])){
                    
                    unset($v['g_options_group_id']);

                    $goodsoptionsgroup = new GoodsOptionsGroup();
                    $goodsoptionsgroup->attributes = $v;
                    $goodsoptionsgroup->goods_id = $goods_id;
                    if($goodsoptionsgroup->validate()){
                        $goodsoptionsgroup->save();

                        // 保存options
                        $this->saveGoodsOptions($goods_id,$goodsoptionsgroup->primaryKey,$options_value);

                    }

                }else if(!empty($v['g_options_group_id'])&&!empty($v['name'])){
                    $goodsoptionsgroup = GoodsOptionsGroup::findOne($v['g_options_group_id']);
                    $goodsoptionsgroup->attributes = $v;
                    $goodsoptionsgroup->save();

                    // 保存options
                    $this->saveGoodsOptions($goods_id,$goodsoptionsgroup->g_options_group_id,$options_value);
                }
            }
        }
        

    }

    // 扩展选项操作
    public function saveGoodsOptions($goods_id,$group_id,$options_value){

        if(!empty($options_value)&&is_array($options_value)){

            foreach ($options_value['g_options_id'] as $k => $v) {

                // 当options_id为空名称不为空时 添加
                if(empty($v)&&!empty($options_value['name'][$k])){

                    $goodsoptions = new GoodsOptions;
                    
                    $goodsoptions->goods_id = $goods_id;
                    $goodsoptions->g_options_group_id = $group_id;

                    $goodsoptions->name = $options_value['name'][$k];
                    $goodsoptions->quanity = $options_value['quanity'][$k];
                    $goodsoptions->subtract = $options_value['subtract'][$k];
                    $goodsoptions->price = $options_value['price'][$k];
                    $goodsoptions->price_prefix = $options_value['price_prefix'][$k];
                    $goodsoptions->weight = $options_value['weight'][$k];
                    $goodsoptions->weight_prefix = $options_value['weight_prefix'][$k];

                    $goodsoptions->save();

                }else if(!empty($v)&&!empty($options_value['name'][$k])){
                    // 当options_id不为空名称不为空时 更新
                    $goodsoptions = GoodsOptions::findOne($v);

                    $goodsoptions->name = $options_value['name'][$k];
                    $goodsoptions->quanity = $options_value['quanity'][$k];
                    $goodsoptions->subtract = $options_value['subtract'][$k];
                    $goodsoptions->price = $options_value['price'][$k];
                    $goodsoptions->price_prefix = $options_value['price_prefix'][$k];
                    $goodsoptions->weight = $options_value['weight'][$k];
                    $goodsoptions->weight_prefix = $options_value['weight_prefix'][$k];

                    $goodsoptions->save();
                }

            }

        }

    }
    // 上傳保存產品
    protected function saveUploadGoods($row,$goods_cat_id='0',$language='en-US'){
        // var_dump($row['L']);
        $category = Categorymeta::find()->where(['name'=>$row['M']])->asArray()->one();
        
        $session_row = \Yii::$app->session['uploadRow'];

        // echo 'cat_id:'.$category['cat_id']."\n";

        $transaction=Yii::$app->db->beginTransaction();
        try{
            $updateGoodsFlag = true;
            if ($updateGoodsFlag) {
                // 開始
                // 判斷產品是否存在，根據 sku，表中 B 列
                $existedGoods = null;
                if (!empty($row['B'])) {
                    $existedGoods = Goods::findOne(['sku' => $row['B']]);
                } elseif (isset($session_row['goods_id']) && $session_row['goods_id'] > 0) {
                    $existedGoods = Goods::findOne($session_row['goods_id']);
                }

                // 查看上一条记录的产品名称和当前记录产品名称是否一至
                if(isset($session_row['C'])&&$session_row['C']==$row['C']&&!empty($row['D'])&&!empty($row['F'])){
                    // 如果一至时 直接添加配菜信息
                    // echo $row['C']."配菜\n";
                    // 当配菜分组相同的时候
                    if(isset($session_row['D'])&&$session_row['D']==$row['D']){
                        $g_options_group_id = $session_row['g_options_group_id'];
                    }else{
                        // 檢查是否已有分組
                        if ($existedGoods) {
                            $goodsoptiongroup = GoodsOptionsGroup::findOne(['goods_id' => $existedGoods->primaryKey, 'name' => $row['D']]);
                        }
                        if (!$goodsoptiongroup) {
                            // 当配菜分组不相同的时候 添加一个分组
                            $goodsoptiongroup = new GoodsOptionsGroup();
                        }

                        $goodsoptiongroup->loadDefaultValues();

                        $goodsoptiongroup->name = $row['D'];
                        $goodsoptiongroup->goods_id = $session_row['goods_id'];
                        $goodsoptiongroup->options_type = 'radio';
                        $goodsoptiongroup->required = isset($row['E'])&&!empty($row['E']) ? 1 : 0;

                        if($goodsoptiongroup->validate()){
                            $goodsoptiongroup->save();
                            
                            $g_options_group_id = $goodsoptiongroup->primaryKey;
                            // 把配菜分组ID保存在SESSION中
                            $row['g_options_group_id'] = $goodsoptiongroup->primaryKey;
                            $row['goods_id'] = $session_row['goods_id'];
                            $row['cat_id'] = $session_row['cat_id'];

                            \Yii::$app->session['uploadRow'] = $row;

                        }else{

                            $transaction->rollBack();
                            return $row['A'].':'.\Yii::t('app','goods options group cannot validate'); 
                        }
                    }

                    $goodsoptions = GoodsOptions::findOne(['g_options_group_id' => $g_options_group_id, 'name' => $row['F']]);
                    if (!$goodsoptions) {
                        // 添加配菜
                        $goodsoptions = new GoodsOptions();
                    }

                    $goodsoptions->loadDefaultValues();
                    $goodsoptions->name = $row['F'];
                    $goodsoptions->price = $row['K'];
                    $goodsoptions->g_options_group_id = $g_options_group_id;
                    $goodsoptions->goods_id = $session_row['goods_id'];

                    if($goodsoptions->validate()){
                        $goodsoptions->save();
                        $transaction->commit();
                        return \Yii::t('app','Success'); 
                    }else{
                        $transaction->rollBack();
                        return $row['A'].':'.\Yii::t('app','goods options cannot validate'); 
                    }
                }

                // 假如分类没有的话就添加一个新的分类
                if (!empty($category)) {
                    $cat_id = $category['cat_id'];
                } elseif (!$existedGoods) { // 當更新產品時對分類更改忽略
                    // 新加分類
                    $category_model = new Category();
                    $categorymeta = new Categorymeta();

                    $category_model->loadDefaultValues();

                    $category_model->type = 'product';
                    $category_model->show = '1';
                    $category_model->side = '1';
                    $category_model->order_id = $row['L'];

                    if($category_model->validate()){
                        $category_model->save();

                        $categorymeta->loadDefaultValues();
                        $categorymeta->cat_id = $category_model->primaryKey;
                        $categorymeta->name = $row['M'];
                        $categorymeta->language = $language;

                        if(isset($row['N'])&&!empty($row['N'])){
                            $categorymeta->description = $row['N'];
                        }

                        if($categorymeta->validate()){
                            $categorymeta->save();

                            $cat_id = $categorymeta->cat_id;
                        }else{
                            $transaction->rollBack();
                            return \Yii::t('app','category meta cannot validate');
                        }
                        
                    }else{
                        $transaction->rollBack();
                        return \Yii::t('app','category cannot validate');
                    }
                } else {
                    $tmpGoodsCat = GoodsToCategory::findOne(['goods_id' => $existedGoods->primaryKey]);
                    if ($tmpGoodsCat) {
                        $cat_id = $tmpGoodsCat->cat_id;
                    } else {
                        $cat_id = 0;
                    }
                }

                // 产品操作
                if ($existedGoods) {
                    $goods = $existedGoods;
                } else {
                    $goods = new Goods();
                }

                if(!empty($row['B']))
                    $goods->sku = $row['B'];
                $goods->status =1;
                $goods->price = $row['K'];
                $goods->addtime = (string)strtotime(date('Y-m-d H:i:s'));
                $goods->order_id = $row['A'];
                $goods->goods_cat_id = $goods_cat_id;
                $goods->quanity = 10000;

                if($goods->validate()){
                    $goods->save();

                    // 产品分类操作
                    if (!$existedGoods && $cat_id > 0) {
                        $goodstocategory = new GoodsToCategory();
                        $goodstocategory->goods_id = $goods->primaryKey;
                        $goodstocategory->cat_id = $cat_id;

                        if($goodstocategory->validate()){
                            $goodstocategory->save();
                        }else{
                            $transaction->rollBack();
                            return $row['A'].':'.\Yii::t('app','goods category cannot validate');
                        }
                    }

                    if ($existedGoods) {
                        $goodsmeta = Goodsmeta::findOne(['goods_id' => $goods->primaryKey, 'language' => $language]);
                    } else {
                        $goodsmeta = new Goodsmeta();
                    }

                    $goodsmeta->goods_id = $goods->primaryKey;
                    $goodsmeta->title = $row['C'];
                    $goodsmeta->content = $row['G'];
                    $goodsmeta->language = $language;

                    if($goodsmeta->validate()){
                        $goodsmeta->save();
                    }else{
                        $transaction->rollBack();
                        return $row['A'].':'.\Yii::t('app','goods meta cannot validate');
                    }

                    // 口味操作
                    $kouwi = array();
                    $kouwi[] = ['name'=>'Vegetable','options'=>empty($row['H'])? 0:1,'sales'=>'0'];
                    $kouwi[] = ['name'=>'Spicy','options'=>empty($row['I'])? 0:1,'sales'=>'0'];
                    $kouwi[] = ['name'=>'Peanut','options'=>empty($row['J'])? 0:1,'sales'=>'0'];

                    if ($existedGoods) {
                        $goods_attr = Goodsattr::findOne(['goods_id' => $goods->primaryKey]);
                    } else {
                        $goods_attr = new Goodsattr();
                    }

                    $goods_attr->goods_id = $goods->primaryKey;
                    $goods_attr->attr_name = 'attr';
                    $goods_attr->language = $language;
                    $goods_attr->attr_value = serialize($kouwi);
                    $goods_attr->save();

                    // 数据库提交
                    $transaction->commit();

                    $row['goods_id'] = $goods->primaryKey;
                    $row['cat_id'] = $cat_id;                
                    \Yii::$app->session['uploadRow'] = $row;

                    return \Yii::t('app','Success');
                } else {
                    // 数据回滚
                    $transaction->rollBack();
                    return $row['A'].':'.\Yii::t('app','goods cannot validate');
                }
                // 結束
            } else {
                // 假如分类没有的话就添加一个新的分类
                if (empty($category)) {

                    // 新加分類
                    $category_model = new Category();
                    $categorymeta = new Categorymeta();

                    $category_model->loadDefaultValues();

                    $category_model->type = 'product';
                    $category_model->show = '1';
                    $category_model->side = '1';
                    $category_model->order_id = $row['L'];

                    if($category_model->validate()){
                        $category_model->save();

                        $categorymeta->loadDefaultValues();
                        $categorymeta->cat_id = $category_model->primaryKey;
                        $categorymeta->name = $row['M'];
                        $categorymeta->language = $language;

                        if(isset($row['N'])&&!empty($row['N'])){
                            $categorymeta->description = $row['N'];
                        }

                        if($categorymeta->validate()){
                            $categorymeta->save();

                            $cat_id = $categorymeta->cat_id;
                        }else{
                            $transaction->rollBack();
                            return \Yii::t('app','category meta cannot validate');
                        }
                        
                    }else{
                        $transaction->rollBack();
                        return \Yii::t('app','category cannot validate');
                    }
                    
                }else{
                    $cat_id = $category['cat_id'];
                }


                // 查看上一条记录的产品名称和当前记录产品名称是否一至

                if(isset($session_row['C'])&&$session_row['C']==$row['C']&&!empty($row['D'])&&!empty($row['F'])){
                    // 如果一至时 直接添加配菜信息
                    // echo $row['C']."配菜\n";
                    // 当配菜分组相同的时候
                    if(isset($session_row['D'])&&$session_row['D']==$row['D']){
                        $g_options_group_id = $session_row['g_options_group_id'];
                    }else{
                        // 当配菜分组不相同的时候 添加一个分组

                        $goodsoptiongroup = new GoodsOptionsGroup();

                        $goodsoptiongroup->loadDefaultValues();

                        $goodsoptiongroup->name = $row['D'];
                        $goodsoptiongroup->goods_id = $session_row['goods_id'];
                        $goodsoptiongroup->options_type = 'radio';
                        $goodsoptiongroup->required = isset($row['E'])&&!empty($row['E']) ? 1 : 0;

                        if($goodsoptiongroup->validate()){
                            $goodsoptiongroup->save();
                            
                            $g_options_group_id = $goodsoptiongroup->primaryKey;
                            // 把配菜分组ID保存在SESSION中
                            $row['g_options_group_id'] = $goodsoptiongroup->primaryKey;
                            $row['goods_id'] = $session_row['goods_id'];
                            $row['cat_id'] = $session_row['cat_id'];

                            \Yii::$app->session['uploadRow'] = $row;

                        }else{

                            $transaction->rollBack();
                            return $row['A'].':'.\Yii::t('app','goods options group cannot validate'); 
                        }
                    }

                    // 添加配菜
                    $goodsoptions = new GoodsOptions();

                    $goodsoptions->loadDefaultValues();
                    $goodsoptions->name = $row['F'];
                    $goodsoptions->price = $row['K'];
                    $goodsoptions->g_options_group_id = $g_options_group_id;
                    $goodsoptions->goods_id = $session_row['goods_id'];

                    if($goodsoptions->validate()){
                        $goodsoptions->save();

                        $transaction->commit();

                        return \Yii::t('app','Success'); 
                    }else{
                        $transaction->rollBack();

                        return $row['A'].':'.\Yii::t('app','goods options cannot validate'); 
                    }

                    

                }
                
                // 产品操作
                $goods = new Goods();

                if(!empty($row['B']))
                    $goods->sku = $row['B'];
                $goods->status =1;
                $goods->price = $row['K'];
                $goods->addtime = (string)strtotime(date('Y-m-d H:i:s'));
                $goods->order_id = $row['A'];
                $goods->goods_cat_id = $goods_cat_id;
                $goods->quanity = 10000;

                if($goods->validate()){
                    $goods->save();

                    // 产品分类操作
                    $goodstocategory = new GoodsToCategory();
                    $goodstocategory->goods_id = $goods->primaryKey;
                    $goodstocategory->cat_id = $cat_id;

                    if($goodstocategory->validate()){
                        $goodstocategory->save();
                    }else{
                        $transaction->rollBack();
                        return $row['A'].':'.\Yii::t('app','goods category cannot validate');
                    }

                    $goodsmeta = new Goodsmeta();

                    $goodsmeta->goods_id = $goods->primaryKey;
                    $goodsmeta->title = $row['C'];
                    $goodsmeta->content = $row['G'];
                    $goodsmeta->language = $language;

                    if($goodsmeta->validate()){
                        $goodsmeta->save();
                    }else{
                        $transaction->rollBack();
                        return $row['A'].':'.\Yii::t('app','goods meta cannot validate');
                    }
                    

                    // 口味操作
                    $kouwi = array();
                    $kouwi[] = ['name'=>'Vegetable','options'=>empty($row['H'])? 0:1,'sales'=>'0'];
                    $kouwi[] = ['name'=>'Spicy','options'=>empty($row['I'])? 0:1,'sales'=>'0'];
                    $kouwi[] = ['name'=>'Peanut','options'=>empty($row['J'])? 0:1,'sales'=>'0'];

                    $goods_attr = new Goodsattr();

                    $goods_attr->goods_id = $goods->primaryKey;
                    $goods_attr->attr_name = 'attr';
                    $goods_attr->language = $language;
                    $goods_attr->attr_value = serialize($kouwi);
                    $goods_attr->save();

                    // 数据库提交
                    $transaction->commit();
                    
                    
                    $row['goods_id'] = $goods->primaryKey;
                    $row['cat_id'] = $cat_id;                
                    \Yii::$app->session['uploadRow'] = $row;

                    return \Yii::t('app','Success');
                }else{
                    
                    // 数据回滚
                    $transaction->rollBack();

                    return $row['A'].':'.\Yii::t('app','goods cannot validate');
                }
            
            }

        }catch(Exception $e){
            // 数据回滚
            $transaction->rollBack();
        }

        return true;
    }
}
