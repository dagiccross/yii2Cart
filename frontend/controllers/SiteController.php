<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\BookingForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\CController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\data\Pagination;

use common\models\Category;
use common\models\Config;
use common\models\OrderReview;
use common\models\Useraddr;
use common\models\Categorymeta;
use common\models\Goods;
/**
 * Site controller
 */
class SiteController extends CController
{
    public $menuinfo = '';
    public $page_info = [];

    public function init(){
        parent::init();
        $this->getView()->title = $this->getConfig('seo_title');
        $this->getView()->registerMetaTag(array("name"=>"keywords","content"=>$this->getConfig('seo_keywords')));//第一种
        $this->getView()->registerMetaTag(array("name"=>"description","content"=>$this->getConfig('seo_content')));//第一种


        $this->page_info['opentime'] = getPageByKey('opentime');
        $this->page_info['delivery'] = getPageByKey('delivery');

        if(isset($_COOKIE['startcookies'])&&$_COOKIE['startcookies']){

        }else{

            setcookie("startcookies",true,time()+315360000);

            // $_SESSION['startcookies'] = true;
            // exit();
            \Yii::$app->session->setFlash('start_cookies', $this->getConfig('start_cookies'));
        }
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        // var_dump(Yii::$app->user);
        // var_dump(\Yii::$app->user);
        // exit();
        $model = Category::find(['model'=>'home'])->asArray()->one();
        $categorymeta = Categorymeta::find()->where(['cat_id'=>$model['id']])->asArray()->one();
        $goods_list = getGoodsByCat($model['id'],1,4);
        $page = getPageByKey('home');

        $this->getView()->title = $page['title'];
        $this->menuinfo = $categorymeta['name'];

        return $this->render('index',[
                            'page'=>$page,
                            'goods_list'=>$goods_list]);
    }


    // 页面显示
    public function actionPage(){

        if(\Yii::$app->request->get('page_id')){
            $page = getPageByKey(\Yii::$app->request->get('page_id'));
            if(!isset($page['title'])){
                return $this->redirect(['index']);
            }
            $this->getView()->title = $page['title'];

            $this->menuinfo = $categorymeta['title'];
        }else{
            $id = \Yii::$app->request->get('id');

            $page = getPageByCatId($id,Yii::$app->request->get('type','page'));
            $categorymeta = \common\models\Categorymeta::find()->where(['cat_id'=>$id])->asArray()->one();
            $this->getView()->title = $categorymeta['name'];
            $this->menuinfo = $categorymeta['name'];
        }




        return $this->render('about',['page'=>$page]);

    }

    // 图片墙页
    public function actionPhoto($id){
        $page = getPageByCatId($id,Yii::$app->request->get('type','page'));
        $categorymeta = \common\models\Categorymeta::find()->where(['cat_id'=>$id])->asArray()->one();
        $this->getView()->title = $categorymeta['name'];
        $this->menuinfo = $categorymeta['name'];

        return $this->render('photo',['page'=>$page]);
    }
    // 产品列表页
    public function actionProduct(){

        $id = \Yii::$app->request->get('id');
        $categorymeta = \common\models\Categorymeta::find()->where(['cat_id'=>$id])->asArray()->one();
        $this->getView()->title = $categorymeta['name'];
        $this->menuinfo = $categorymeta['name'];

        // if(\Yii::$app->request->get('id')&&\Yii::$app->request->get('type')!='product'){
            // $category = getCategoryById(\Yii::$app->request->get('id'));
            // $category_list[] = $category;
            // $goods_list = getGoodsByCat(\Yii::$app->request->get('id'));
        // }else{
            $goods_list = Goods::getGoodsAll();
            // var_dump($goods_list);
            $category_list = getCategory('0','side');
        // }


        return $this->render('product',['goods_list'=>$goods_list,'category_list'=>$category_list]);
    }

    // 下单页
    public function actionBooking(){

        $id = \Yii::$app->request->get('id');
        $categorymeta = \common\models\Categorymeta::find()->where(['cat_id'=>$id])->asArray()->one();
        $this->getView()->title = $categorymeta['name'];
        $this->menuinfo = $categorymeta['name'];

        $model = new BookingForm();
        $hear = getExtdata('hear','ext');
        $hour = getExtdata('booking_hour','ext');

        $shipment = Useraddr::find()->where(['member_id'=>\Yii::$app->user->id,'flat'=>1])->asArray()->one();

        if ($model->load(Yii::$app->request->post())) {

            if($model->validate()){
                if ($model->sendEmail($this->getConfig('server_mail'))) {
                    Yii::$app->session->setFlash('information', 'Thank you for your booking. We will contact you shortly.');
                } else {
                    Yii::$app->session->setFlash('information', 'There was an error sending email.');
                }

                return $this->refresh();
            }

        }else{

            $model->name = isset($shipment['shipment_name'])? $shipment['shipment_name'] : '';
            $model->phone = isset($shipment['shipment_phone'])? $shipment['shipment_phone'] : '';
            $model->email = isset(\Yii::$app->user->identity->email)? \Yii::$app->user->identity->email : '';

        }



        return $this->render('booking',['model'=>$model,
                                        'hear'=>$hear,
                                        'hour'=>$hour,
                                        'shipment'=>$shipment]);
    }

    // 评价页
    public function actionReview(){
        $type = \Yii::$app->request->get('type');
        $this->menuinfo = 'Review';

        if(!empty($type)&&$type=='review'){
                $order_review = new OrderReview;

                $order_review->loadDefaultValues();
                $order_review->order_id = 0;
                // $order_review->member_id = isset(\Yii::$app->user->id) ? \Yii::$app->user->id : 0;
                if ($order_review->member_id = isset(\Yii::$app->user->id)){



            // 提交POST评价
            if ($order_review->load(\Yii::$app->request->post())&&$order_review->isNewRecord) {
                $order_review->flat = 1;

                $order_review->add_date = date('Y-m-d H:i:s');
                $order_review->member_id = (\Yii::$app->user->id);
                // var_dump($model->modifydate);
                if($order_review->validate()){
                    $order_review->save();

                    \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Thank you!'));
                    return $this->redirect(['site/review']);
                }

            }

            return $this->render('review_form',['order_review'=>$order_review]);
          } else {
            // this checks if user has been login
            \Yii::$app->getSession()->setFlash('message', \Yii::t('app','Please login to review'));
            return $this->redirect(['site/login']);
          }
        }else{
            $list = OrderReview::find()->where(['flat'=>1])
                                ->orderBy('add_date desc');

            $pages = new Pagination(['totalCount' => $list->count(), 'pageSize' => 50]);
            $data = $list->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
            return $this->render('review',['list'=>$data,'pages'=>$pages]);
        }

    }

    // 回顧頁
    public function actionFeedback(){
        $model = new \frontend\models\FeedbackForm;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail($this->getConfig('server_mail'))) {
                echo 1;
                // Yii::$app->session->setFlash('success', 'Thank you for booking us. We will respond to you as soon as possible.');
            } else {
                echo 0;
                // Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }
            exit();
            // return $this->refresh();
        }
        return $this->renderpartial('feedback',['model'=>$model]);
    }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        // echo Yii::$app->request->getReferrer();
        $model = new LoginForm();
        $this->menuinfo = 'Login';
        // echo($this->goBack());
        // return Yii::$app->request->getReferrer();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(isset(\Yii::$app->session['return_url'])){
                return $this->redirect(\Yii::$app->session['return_url']);
            }else{
                return $this->redirect(Yii::$app->request->getReferrer());
            }

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact($id)
    {
        $page = getPageByCatId($id,Yii::$app->request->get('type','page'));
        $categorymeta = Categorymeta::find()->where(['cat_id'=>$id])->asArray()->one();


        $this->getView()->title = $page['title'];

        $this->menuinfo = $categorymeta['name'];

        $model = new ContactForm();


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail($this->getConfig('server_mail'))) {
                Yii::$app->session->setFlash('message', 'Thank you for contacting us. We will respond to you as soon as possible.');
                \Yii::$app->session['contact_eamil_time'] = time();
            } else {
                Yii::$app->session->setFlash('message', isset(\Yii::$app->session['error_email']) ? \Yii::$app->session['error_email'] : 'There was an error sending email.');
            }

            return $this->refresh();
        } else {

            $shipment = Useraddr::find()->where(['member_id'=>\Yii::$app->user->id,'flat'=>1])->asArray()->one();

            $model->name = isset($shipment['shipment_name'])? $shipment['shipment_name'] : '';
            $model->phone = isset($shipment['shipment_phone'])? $shipment['shipment_phone'] : '';
            $model->email = isset(\Yii::$app->user->identity->email)? \Yii::$app->user->identity->email : '';

            return $this->render('contact', [
                'model' => $model,
                'page'=>$page,
                'shipment'=>$shipment,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        Yii::$app->cache->flush();
        return $this->redirect(['index']);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        $this->menuinfo = 'Sign Up';

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $rememberMe = isset(Yii::$app->request->post('SignupForm')['rememberMe']);
                if (Yii::$app->getUser()->login($user,$rememberMe ? 3600 * 24 * 30 : 0)) {
                    return $this->goHome();
                }
            }
        }

        return $this->renderpartial('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        $this->menuinfo = 'Request Password';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $flat = $model->sendEmail();
            if ($flat) {
                Yii::$app->session->setFlash('message', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('message', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $this->menuinfo = 'Reset Password';

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    // 下載菜單
    public function actionDownload(){

        error_reporting(E_ALL);
        require(dirname(Yii::$app->basePath).'/thridpart/phpexcel/PHPExcel.php');
        $objPHPExcel = new \PHPExcel();

        // 產品列表
        $data = getGoodsAll();

        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'Position')
                        ->setCellValue('B1', 'Dish code')
                        ->setCellValue('C1', 'Food Name')
                        ->setCellValue('D1', 'Options Group')
                        ->setCellValue('E1', 'Options Type')
                        ->setCellValue('F1', 'Options')
                        ->setCellValue('G1', 'Vegetable')
                        ->setCellValue('H1', 'Spicy')
                        ->setCellValue('I1', 'Peanut')
                        ->setCellValue('J1', 'Price')
                        ->setCellValue('K1', 'Currency');
        $num = 2;
        for ($i=0; $i <count($data) ; $i++) {

            // 一條產品
            $group_options = \common\models\GoodsOptionsGroup::groupOptions($data[$i]['id']);

            $goods_attr = \common\models\Goodsattr::find()->where(['goods_id'=>$data[$i]['id'],
                                        'attr_name'=>'attr',
                                        'language'=>\Yii::$app->language])->asArray()->one();
            $kouwei = @unserialize($goods_attr['attr_value']);

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$num, $num-1)
                        ->setCellValue('B'.$num, $data[$i]['sku'])
                        ->setCellValue('C'.$num, $data[$i]['title'])
                        ->setCellValue('D'.$num, '')
                        ->setCellValue('E'.$num, '')
                        ->setCellValue('F'.$num, '');
            if(!empty($kouwei)&&is_array($kouwei)){
                for ($j=0; $j <count($kouwei) ; $j++) {

                    if($kouwei[$j]['name']=='Vegetable'){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('G'.$num, $kouwei[$j]['options']);
                    }else if($kouwei[$j]['name']=='Spicy'){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('H'.$num, $kouwei[$j]['options']);
                    }else if($kouwei[$j]['name']=='Peanut'){
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('I'.$num, $kouwei[$j]['options']);
                    }



                }
            }

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('J'.$num, $data[$i]['price'])
                        ->setCellValue('K'.$num, $this->getConfig('currency'));
            // end 產品信息時

            // 當有擴展選項時
            if(!empty($group_options)&&is_array($group_options)){

                // var_dump($group_options); exit();
                foreach ($group_options as $_k => $_v) {

                    // 換行
                    $num++;
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$num, $num-1)
                            ->setCellValue('B'.$num, $data[$i]['sku'])
                            ->setCellValue('C'.$num, $data[$i]['title'])
                            ->setCellValue('D'.$num, $_v['group_name'])
                            ->setCellValue('E'.$num, ($_v['required']==1) ? 'Required' : 'Extras')
                            ->setCellValue('F'.$num, $_v['name'])
                            ->setCellValue('J'.$num, ($_v['required']==1) ? 0 : ($_v['price_prefix'].$_v['price']))
                            ->setCellValue('K'.$num, $this->getConfig('currency'));
                }
            }
            // 換行
            $num++;
        }


        // $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.time().'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}
