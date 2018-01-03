<?php

namespace backend\modules\member\controllers;

use Yii;
use backend\models\Logininfo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LogininfoController implements the CRUD actions for Logininfo model.
 */
class LogininfoController extends Controller
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
                        'actions' => ['index', 'create', 'update','delete','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['change-password'],
                        'allow' =>true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all Logininfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Logininfo::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Logininfo model.
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
     * Creates a new Logininfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Logininfo();
        $model->logintime = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post())) {

            if($model->validate()){
                $model->passwd = $model->md5Password($model->passwd);
                $model->logintime = (string)strtotime($model->logintime);
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionChangePassword(){
        $model = Logininfo::findOne(\Yii::$app->user->id);
        $old_password = $model->passwd;


        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if(empty($post['old_password'])||empty($post['new_password'])||empty($post['confirm_password'])){
                Yii::$app->getSession()->setFlash('message', Yii::t('app','filed is empty!'));
            }else if($model->validatePassword($post['old_password'])&&$post['new_password']==$post['confirm_password']){
                $model->passwd = $model->md5Password($post['new_password']);
                if($model->validate()){
                    $model->save();
                    Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
                    return $this->redirect(['change-password']);
                }
            }else{
                Yii::$app->getSession()->setFlash('message', Yii::t('app','Password Error or Secondary passwords are not the same!'));
            }


        }

        return $this->render('change-password',[
                'model'=>$model
            ]);
    }

    /**
     * Updates an existing Logininfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_password = $model->passwd;

        $model->logintime = empty($model->logintime) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->logintime);
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            // if($post['Logininfo']['passwd']!=$old_password && !$model->validatePassword($post['Logininfo']['passwd'])){
            //     $post['Logininfo']['passwd'] = $model->md5Password(Yii::$app->request->post('Logininfo')['passwd']);
            // }

            $model->load($post);
            $model->logintime = (string)strtotime($model->logintime);
            if($old_password==$model->md5Password($model->passwd)||$old_password==$model->passwd){
                $model->passwd = $old_password;
            }else{
                $model->passwd = $model->md5Password($model->passwd);
            }
            if($model->validate()){
                $model->save();
                Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
                return $this->redirect(['index']);
            }
            $message = '';
            foreach ($model->getErrors() as $key => $value) {
                $message .= isset($value['0']) ? $value['0'] : '';
            }

            \Yii::$app->getSession()->setFlash('message', $message);
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Logininfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $exis = Logininfo::find(['power'=>'super'])->count();

        if($exis>1){
            $this->findModel($id)->delete();
            $message = Yii::t('app','Success');
        }else{
            $message = Yii::t('app','Must One Super Manager');
        }

        Yii::$app->getSession()->setFlash('message', $message);
        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Logininfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Logininfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Logininfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
