<?php

namespace backend\controllers;

use Yii;
use backend\models\Config;
use common\models\Currency;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends Controller
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
                        'actions' => ['index', 'create', 'update','delete','test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }

    
    /**
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $model = Config::find()->asArray()->all();
        $arr = [];
        $list = null;

        foreach ($model as $k => $v) {
            $arr[$v['options']] = $v;

        }
        $currency = Currency::listData();

        if(\Yii::$app->request->post('Config')){
            $transaction=Yii::$app->db->beginTransaction();
            try {

                foreach (\Yii::$app->request->post('Config') as $k => $v) {

                    $flat = Config::updateAll(['values'=>$v],['id'=>$k]);
                    if(isset($arr[$k])&&$arr[$k]=='currency_code'){
                        Config::updateAll(['values'=>$currency[$v]['currency']],['options'=>'currency']);
                    }
                }
                $data['message'] = \Yii::t('app','Success');

                $transaction->commit();

                \Yii::$app->getSession()->setFlash('message', $data['message']);
                return $this->redirect(['index']);

            } catch (Exception $e) {
                $data['message'] = \Yii::t('app','Error');
                $transaction->rollback();
            }
            \Yii::$app->getSession()->setFlash('message', $data['message']);
        }

        foreach ($currency as $k => $v) {
            $list['currency'][$k] = $v['name'];
        }
        return $this->render('index', [
            'model' => $arr,
            'list'=>$list,
        ]);
    }

    /**
     * Displays a single Config model.
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
     * Creates a new Config model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Config();

        if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
            $model->save();
            \Yii::$app->getSession()->setFlash('message', Yii::t('app','success'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Config model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            Yii::$app->getSession()->setFlash('message', Yii::t('app','Success'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Config model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTest(){
        $content = \Yii::$app->request->get('content');
        $email = \Yii::$app->request->get('email');

        require_once(dirname(\Yii::$app->basePath).'/thridpart/phpmailer/class.phpmailer.php');
        $mail = new \PHPMailer();
        $mail->IsSMTP();                  // send via SMTP   
        $mail->Host = Config::getConfig('smtp_server');   // SMTP servers  
        $ssl = Config::getConfig('smtp_ssl');
        $ssl = empty($ssl)||$ssl==false ? false : true;
        $port = Config::getConfig('smtp_port');
        $port = empty($port) ? '25' : $port;

        $mail->SMTPAuth = $ssl;           // turn on SMTP authentication   
        $mail->Username = Config::getConfig('smtp_user');     // SMTP username  注意：普通邮件认证不需要加 @域名   
        $mail->Password = Config::getConfig('smtp_password'); // SMTP password  
        $mail->Port = Config::getConfig('smtp_port'); // SMTP Port 
        $mail->From = $email;      // 发件人邮箱   
        $mail->FromName =  Config::getConfig('smtp_user');  // 发件人   

        $mail->CharSet = "UTF-8";   // 这里指定字符集！   
        $mail->Encoding = "base64";   
        $mail->AddAddress($email);  // 收件人邮箱和姓名   

        $mail->IsHTML(true);  // send as HTML   
        // 邮件主题   
        $mail->Subject = 'test';
        // 邮件内容  

        $mail->Body = empty($content) ? 'test' : $content;                                            
        $mail->AltBody ="text/html"; 
        // echo $from_email; 
        // exit(); 
        return $mail->Send();
    }
    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
