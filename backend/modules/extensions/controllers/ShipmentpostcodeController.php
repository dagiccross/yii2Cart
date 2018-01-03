<?php

namespace backend\modules\extensions\controllers;

use Yii;
use common\models\ShipmentPostcode;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShipmentpostcodeController implements the CRUD actions for ShipmentPostcode model.
 */
class ShipmentpostcodeController extends Controller
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update','delete','view','uploads','ajax'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all ShipmentPostcode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ShipmentPostcode::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShipmentPostcode model.
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
     * Creates a new ShipmentPostcode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShipmentPostcode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShipmentPostcode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ShipmentPostcode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    // 批量上传
    public function actionUploads(){
        return $this->render('upload');
    }

    // ajax处理
    public function actionAjax()
    {
        $type=\Yii::$app->request->post('type','updategoodscategory');

        if($type=='upload'){

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

                // echo $total_line.'|'.$total_column;
                // 设置表头的开始位置
                $rowIndex = 1;

                $column_value = [];
                // 获取表头信息
                for ($column='A'; $column <=$total_column ; $column++) { 
                    $column_value[$column] = trim($phpexcel->getCell($column.$rowIndex)->getValue());

                }

                // 获取表的内容信息
                $data_column = [];

                // 获取分组的ID
                for ($row=2; $row <= $total_line ; $row++) { 
                    $row_column_value = [];

                    for ($column='A'; $column <=$total_column ; $column++) {

                        $row_column_value[$column] = trim($phpexcel->getCell($column.$row)->getValue());

                    }
                    // var_dump($row_column_value);exit();
                    if(!empty($row_column_value)){
                       $data['message'] = $this->saveUpload($row_column_value);
                    }
                    

                }
            }
            
            echo json_encode($data);
            Yii::$app->end();
        }

    }

    // 上传数据保存操作
    protected function saveUpload($row=[]){

        $model = new ShipmentPostcode();
        $model->postcode = $row['A'];
        $model->price = $row['B'];

        if($model->validate()){
            $model->save();
            return \Yii::t('app','Success');
        }else{
            return $model->getErrors();
        }
    }

    /**
     * Finds the ShipmentPostcode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShipmentPostcode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShipmentPostcode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
