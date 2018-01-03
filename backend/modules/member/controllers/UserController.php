<?php

namespace backend\modules\member\controllers;

use Yii;
use backend\models\User;
use common\models\UserBack;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update','delete','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                                'pageSize' => 15,
                            ],
        ]);

        // echo Yii::$app->language;Yii::$app->end();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->loadDefaultValues();
        $model->addtime = date('Y-m-d H:i:s');
        $model->modifytime = $model->addtime;
        $model->status = 1;

        $model->loginip = \Yii::$app->request->userIP;

        $userback = new UserBack;

        if ($model->load(Yii::$app->request->post()) && $model->validate() ) {

            $model->passwd = $model->md5Password($model->passwd);

            // $model->addtime = strtotime($model->addtime);
            // $model->modifytime = strtotime($model->addtime);
            $model->modifytime = time();
            $model->addtime = $model->modifytime;


            if($model->save()){
                $userback->load(Yii::$app->request->post());
                $userback->member_id = $model->primaryKey;
                $userback->add_date = date('Y-m-d H:i:s');
                $userback->save();

                Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
                return $this->redirect(['view', 'id' => $model->id]);
            }
            // var_dump($model->getErrors());


        }

        return $this->render('create', [
            'model' => $model,
            'userback' =>$userback,
        ]);

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userback = $this->findUserBack($id);
        if(empty($userback)){
            $userback = new UserBack;
            $userback->member_id = $model->id;
            $userback->add_date = date('Y-m-d H:i:s');
        }

        if(!empty($model->addtime)){
            // $model->addtime = date('Y-m-d H:i:s',$model->addtime);
        }else{
            // $model->addtime = date('Y-m-d H:i:s');
        }
        if(!empty($model->modifytime)){
           $model->modifytime = date('Y-m-d H:i:s',$model->modifytime);
        }else{
            $model->modifytime = $model->addtime;
        }



        if (isset($_POST['User'])) {
            $post = Yii::$app->request->post();
            // print_r($post);
            if($post['User']['passwd']!=$model->passwd && !$model->validatePassword($post['User']['passwd'])){
                $post['User']['passwd'] = $model->md5Password(Yii::$app->request->post('User')['passwd']);
            }

            $model->load($post);
            // $model->addtime = strtotime($post['User']['addtime']);
            // $model->modifytime = strtotime($post['User']['modifytime']);
            $model->modifytime = time();

            if($model->validate()){
                $model->save();

                $userback->load(Yii::$app->request->post());
                $userback->save();

                Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
                'userback' =>$userback,
            ]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'userback' =>$userback,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $exis = User::find(['power'=>'super'])->count();

        if($exis>1){
            $this->findModel($id)->delete();
            $message = Yii::t('app','Success');
        }else{
            $message = Yii::t('app','Must One Super Manager');
        }

        Yii::$app->getSession()->setFlash('message', $message);
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findUserBack($id){
        return UserBack::findOne($id);
    }
}
