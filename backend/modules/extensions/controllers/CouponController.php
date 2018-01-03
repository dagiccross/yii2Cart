<?php

namespace backend\modules\extensions\controllers;

use Yii;
use common\models\Coupon;
use common\models\CouponGoods;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\data\ArrayDataProvider;
use yii\db\Query;

use yii\bootstrap\Tabs;
/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
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
                        'actions' => ['index', 'create', 'update','delete','ajax','uploads'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Coupon::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coupon model.
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
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();

        $model->loadDefaultValues();

        $model->start_date = date('d-m-Y');
        $model->end_date = date('d-m-Y');

        if ($model->load(Yii::$app->request->post())) {

            $data = $this->saveInfo($model);

            \Yii::$app->getSession()->setFlash('message', $data['message']);
            if($data['status']==1){
                return $this->redirect(['index']);

            }else{
                return $this->render('create', [
                    'model' => $model,
                    'coupongoods'=>[],
                    'goods' => $this->findGoods(),
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'coupongoods'=>[],
                'goods' => $this->findGoods(),
            ]);
        }
    }

    /**
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->start_date = empty($model->start_date) ? date('d-m-Y') : date('d-m-Y',$model->start_date);
        $model->end_date = empty($model->end_date) ? date('d-m-Y') : date('d-m-Y',$model->end_date);

        if ($model->load(Yii::$app->request->post())) {

            $data = $this->saveInfo($model,'update');

            \Yii::$app->getSession()->setFlash('message', $data['message']);
            if($data['status']==1){
                return $this->redirect(['index']);

            }else{
                return $this->render('update', [
                    'model' => $model,
                    'coupongoods'=>$this->findCoupGoods($id),
                    'goods' => $this->findGoods(),
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'coupongoods'=>$this->findCoupGoods($id),
                'goods' => $this->findGoods(),
            ]);
        }
    }

    /**
     * Deletes an existing Coupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->findModel($id)->delete();
            $this->coupongoodsDelete($id);

            $transaction->commit();
            \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Success'));
        } catch (Exception $e) {
            $transaction->rollback();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function coupongoodsDelete($id){
        return CouponGoods::deleteAll(['coup_id'=>$id]);

    }
    /**
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // 获得产品的信息
    protected function findGoods(){
        $rs = (new Query())->select("g.id,g.price,m.title,m.language")
            ->from("{{%goods}} g")
            ->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
            ->where(['g.status'=>1])
            ->groupBy('g.id')
            ->orderBy('g.order_id asc,g.id DESC')
            ->all();
        return $rs;
    }

    // 获取对应优惠券的产品
    protected function findCoupGoods($id){
        $model = CouponGoods::find()->where(['coup_id'=>$id])->asArray()->all();
        $arr = [];

        for ($i=0; $i <count($model) ; $i++) {

            $arr[$model[$i]['goods_id']] = $model[$i];
        }
        return $arr;
    }

    // 保存操作
    protected function saveInfo($model,$action='create'){

        // 初始化变量属性
        $data = array();
        $data['status'] = 0;
        $data['message'] = '';

        $model->total_quanity = $model->coup_quanity;
        $model->start_date = (string)strtotime($model->start_date);
        $model->end_date = (string)strtotime($model->end_date);

        if($model->validate()){
            $transaction=Yii::$app->db->beginTransaction();

            try{
                $model->save();

                $coupgoodsselect = \Yii::$app->request->post('CouponGoodsCheckbox');
                $coupongoods = \Yii::$app->request->post('CouponGoods');

                if($action=='update'){
                    $this->coupongoodsDelete($model->coup_id);
                }
                if(!empty($coupgoodsselect)&&is_array($coupgoodsselect)){
                    for ($i=0; $i <count($coupgoodsselect) ; $i++) {
                        $coupongoods_model = new CouponGoods();

                        $coupongoods_model->attributes = $coupongoods[$coupgoodsselect[$i]];
                        $coupongoods_model->coup_id = $model->primaryKey;

                        if($coupongoods_model->validate()){
                            $coupongoods_model->save();
                        }

                    }
                }

                $transaction->commit();

                $data['status'] =1;
                $data['message'] =\Yii::t('app','Success');

            }catch(Exception $e){
                $data['message'] = $e->getMessage();
                $transaction->rollback();
            }
        }else{
            $data['message'] = json_encode($model->getErrors());
        }

        return $data;
    }
}
