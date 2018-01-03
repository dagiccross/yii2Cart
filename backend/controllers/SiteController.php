<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\Order;
use common\models\OrderGoods;
use common\models\Goods;
use common\models\User;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public $layout = 'main';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
                'maxLength' => 5,
                'minLength' => 5
            ],
        ];
    }

    public function actionIndex()
    {
        // var_dump(Yii::$app->user);
        // var_dump(\Yii::$app->user);
        // exit();

        $this->getView()->title = 'backend';

        $count['order'] = Order::find()->count();
        $count['ordergoods'] = Ordergoods::find()->count();
        $count['goods'] = Goods::find()->count();
        $count['user'] = User::find()->count();

        $order = Order::find()->orderBy('add_date desc')->offset(0)->limit(10)->asArray()->all();
        return $this->render('index',['count'=>$count,'order'=>$order]);
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
