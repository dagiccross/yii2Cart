<?php

namespace backend\controllers;

use Yii;
use backend\models\Language;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Extensionmeta;
use common\models\Categorymeta;
use common\models\Pagemeta;
use common\models\Tagmeta;
use common\models\Goodsmeta;
use common\models\Goodsattr;
/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends Controller
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
                        'actions' => ['index', 'create', 'update','delete','view','change'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->power=='admin';
                        }
                    ],
                    [
                        'actions'=>['index','update'],
                        'allow'=>true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all Language models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Language::find(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Language model.
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
     * Creates a new Language model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Language();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->language_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Language model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->language_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionChange(){
        Yii::$app->cache->flush();
        $code = Yii::$app->request->get('code',Yii::$app->language);
        Yii::$app->set('language',$code);
        Yii::$app->session['language'] = $code;

        return $this->redirect(Yii::$app->request->referrer);
    }
    /**
     * Deletes an existing Language model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $model = $this->findModel($id);
            Extensionmeta::deleteAll('language=:language',[':language'=>$model->code]);
            Extensionmeta::deleteAll('language=:language',[':language'=>$model->code]);
            Categorymeta::deleteAll('language=:language',[':language'=>$model->code]);
            Pagemeta::deleteAll('language=:language',[':language'=>$model->code]);
            Tagmeta::deleteAll('language=:language',[':language'=>$model->code]);
            Goodsmeta::deleteAll('language=:language',[':language'=>$model->code]);
            Goodsattr::deleteAll('language=:language',[':language'=>$model->code]);
            $model->delete();
            $transaction->commit(); 
            Yii::$app->cache->flush();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
        }catch(Exception $e){
            $transaction->rollBack();
            \Yii::$app->getSession()->setFlash('message', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Language the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
