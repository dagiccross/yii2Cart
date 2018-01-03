<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\GoodsCategory;
use common\models\GoodsCategorymeta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ArrayDataProvider;

/**
 * GoodscategoryController implements the CRUD actions for GoodsCategory model.
 */
class GoodscategoryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all GoodsCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new Query;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->select('t.*,m.language,m.name')
                            ->from("{{%goods_category}} t")
                            ->leftJoin("{{%goods_categorymeta}} m",'t.g_cat_id=m.g_cat_id')
                            // ->where('m.language=:language',[':language'=>Yii::$app->language])
                            ->groupBy('t.g_cat_id')
                            ->all(),
            'sort' => [
                'attributes' => ['g_cat_id', 'order_id'],
            ],
            'key'=>'g_cat_id',
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoodsCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoodsCategory();

        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $data = $this->saveInfo($model);
            \Yii::$app->getSession()->setFlash('message', $data['message']);
            if($data['status']==1){
                return $this->redirect(['index']);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                ]);
            }
           
        } else {
            return $this->render('create', [
                'model' => $model,
                'meta' => [],
            ]);
        }
    }

    /**
     * Updates an existing GoodsCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $data = array();

            $data = $this->saveInfo($model,'update');
            \Yii::$app->getSession()->setFlash('message', $data['message']);
            
            if($data['status']==1){
                return $this->redirect(['index']);
            }else{

                return $this->render('create', [
                    'model' => $model,
                    'meta' => Yii::$app->request->post('meta'),
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'meta' => $this->findMeta($id),
            ]);
        }
    }

    // 保存信息
    public function saveInfo($model,$action='create'){
        $data = array();
        $data['status'] = 0;
        $transaction=Yii::$app->db->beginTransaction();

        try{
            $model->save();
            $i = 0;
            $message = '';

            if($action=='update'){
                $this->metaDelete($model->g_cat_id);
            }

                foreach (getLanguage() as $k => $v) {
                    $i++;
                    $meta = new GoodsCategorymeta();
                    $meta->attributes = Yii::$app->request->post('meta')[$k];
                    $meta->g_cat_id = $model->primaryKey;
                    $meta->language = $k;
                    
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
                }
            

            if($data['status'] ==$i && $data['status']){
                
                $transaction->commit(); 
                $data['status'] = 1;
                $data['message'] = Yii::t('app','success');
            }else{
                $transaction->rollBack();
                $data['status'] = 0;
                $data['message'] = $message;
            }
                
        }
        catch(Exception $e)
        {

            $transaction->rollBack();
            $data['status'] = 0;
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

    /**
     * Deletes an existing GoodsCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $data = array();
        $data['status'] = 0;
        $transaction=Yii::$app->db->beginTransaction();

        try{
            
            $model = $this->findModel($id);//->delete()
            $this->metaDelete($id);
            $model->delete();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
            $transaction->commit();
        }
        catch(Exception $e)
        {

            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage()); 
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the GoodsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function metaDelete($id){
        GoodsCategorymeta::deleteAll('g_cat_id=:tag_id',[':tag_id'=>$id]);
    }

    protected function findMeta($id){

        $query = new Query();
        $arr = array();

        $rs = $query->select("a.*")
            ->from("{{%goods_categorymeta}} a")
            ->where('a.g_cat_id=:key',[':key'=>$id])
            ->all();
        foreach ($rs as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }
}
