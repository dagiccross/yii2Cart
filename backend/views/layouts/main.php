<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;

use common\models\Config;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>
<body>
  <?php $this->beginBody() ?>

  <div class="wrap">
    <?php
    NavBar::begin([
      'brandLabel' => Yii::t('app','Backend'),
      'brandUrl' => Yii::$app->homeUrl,
      'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
      ],
    ]);

    if (Yii::$app->user->isGuest) {
      $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else if(Yii::$app->user->identity->power=='admin') {
      $menuItems = [
        ['label' => Yii::t('app','Home'), 'url' => ['/site/index'],
      ],
    ];
    // $menuItems[] = ['label' => Yii::t('app','Authenticate'), 'url' => ['/auth/default/index'],
    //                 'items'=>[
    //                 ['label'=>Yii::t('app','AuthRole'),'url'=>['/auth/role/index']],
    //                 ['label'=>Yii::t('app','AuthItem'),'url'=>['/auth/item/index']],
    //                 ['label'=>Yii::t('app','AuthRule'),'url'=>['/auth/default/index']],
    //                 ]];
    $menuItems[] = ['label' => Yii::t('app','Config'), 'url' => ['/config/index'],
    'items'=>[
      ['label' => Yii::t('app','Config'), 'url' => ['/config/index']],
      ['label' => Yii::t('app','Languages'), 'url' => ['/language/index']],
      ['label' => Yii::t('app','Currency'), 'url' => ['/currency/index']],
      ['label' => Yii::t('app','Slider'), 'url' => ['/extension/index','type'=>'slider']],
      ['label' => Yii::t('app','Payment'), 'url' => ['/extension/index','type'=>'account']],
      ['label' => Yii::t('app','Extension'), 'url' => ['/extension/index','type'=>'ext']],
    ]
  ];
  $menuItems[] = ['label' => Yii::t('app','User manager'), 'url' => ['/user/index'],
  'items'=>[
    ['label' => Yii::t('app','Administrator'), 'url' => ['/member/logininfo/index']],
    ['label' => Yii::t('app','Member'), 'url' => ['/member/user/index']],
    ]];
    $menuItems[] = ['label' => Yii::t('app','CMS'), 'url' => ['/cms/default/index'],
    'items'=>[
      // ['label' => Yii::t('app','Tag'), 'url' => ['/cms/tag/index']],
      ['label' => Yii::t('app','Category'), 'url' => ['/cms/category/index']],
      ['label' => Yii::t('app','Page'), 'url' => ['/cms/page/index']],
    ],
  ];
  $menuItems[] = ['label' => Yii::t('app','Goods'), 'url' => ['/goods/default/index'],
  'items'=>[
    // ['label' => Yii::t('app','Goods Category'), 'url' => ['/goods/goodscategory/index']],
    // ['label' => Yii::t('app','Group'), 'url' => ['/goods/group/index']],
    // ['label' => Yii::t('app','Feature'), 'url' => ['/goods/feature/index']],
    // ['label' => Yii::t('app','Feature Attribute'), 'url' => ['/goods/featureattr/index']],
    ['label' => Yii::t('app','Goods'), 'url' => ['/goods/goods/index']],
  ],
];
$menuItems[] = ['label'=> Yii::t('app','Order Manager'),'url' => ['/order/default/index'],
'items'=>[
  ['label' => Yii::t('app','Order Manager'), 'url' => ['/order/default/index']],
  ['label' => Yii::t('app','Order Archive'), 'url' => ['/order/default/index','flat'=>1]],
  ['label' => Yii::t('app','Customers Reviews'), 'url' => ['/order/order-review/index']],
],];
$menuItems[] = ['label' => Yii::t('app','Images'), 'url' => ['/upfile/index']];
$menuItems[] = ['label' => Yii::t('app','Extension'), 'url' => ['/extensions/default/index'],
'items'=>[
  ['label' => Yii::t('app','Shipment PostCode'), 'url' => ['/extensions/shipmentpostcode/index']],
  ['label' => Yii::t('app','Coupon'), 'url' => ['/extensions/coupon/index']],
  ['label' => Yii::t('app','Time'), 'url' => ['/extensions/time/index']],
  ['label' => Yii::t('app','Holiday'), 'url' => ['/extension/view','id'=>13,'type'=>'ext']],
],
];

$menuItems[] = [
  'label' => Yii::t('app','Logout').' (' . Yii::$app->user->identity->username . ')',
  'url' => ['/site/logout'],
  'linkOptions' => ['data-method' => 'post']
];
$languageItems = array();
foreach (language() as $k => $v) {
  $languageItems[] = ['label'=>$v,'url'=>['/language/change','code'=>$k]];
}
$menuItems[] = ['label'=>Yii::t('app','Languages'),'url'=>'#',
'items'=>$languageItems,
];
}else{
  $menuItems = [
    ['label' => Yii::t('app','Home'), 'url' => ['/site/index'],
  ],
];
// $menuItems[] = ['label' => Yii::t('app','Config'), 'url' => ['/config/index'],
//                 'items'=>[
//                 // ['label' => Yii::t('app','Payment'), 'url' => ['/extension/index','type'=>'account']],
//
//                 ]
//                 ];
$menuItems[] = ['label'=> Yii::t('app','View Orders'),'url' => ['/order/default/index'],


// ASK IF REVIEW IS NEEDED
//['label' => Yii::t('app','Order Archive'), 'url' => ['/order/default/index']],
// UNCOMMENT IF ARCHIVE IS NEEDED!
];
$menuItems[] = ['label' => Yii::t('app','User manager'), 'url' => ['#'],
'items'=>[
  ['label' => Yii::t('app','Change Password'), 'url' => ['/member/logininfo/change-password']],
  // ['label' => Yii::t('app','Memeber'), 'url' => ['/member/user/index']],


  ['label' => Yii::t('app','Customers Reviews'), 'url' => ['/order/order-review/index']],
],];




$menuItems[] = ['label' => Yii::t('app','Extension'), 'url' => ['#'],
'items'=>[
  ['label' => Yii::t('app','Config'), 'url' => ['/config/index']],
  // ['label' => Yii::t('app','Category'), 'url' => ['/cms/category/index']],
  ['label' => Yii::t('app','Holiday'), 'url' => ['/extension/view','id'=>13,'type'=>'ext']],
  ['label' => Yii::t('app','Coupon'), 'url' => ['/extensions/coupon/index']],
  // ['label' => Yii::t('app','Time'), 'url' => ['/extensions/time/index']],
],
];

$menuItems[] = [
  'label' => Yii::t('app','Logout').' (' . Yii::$app->user->identity->username . ')',
  'url' => ['/site/logout'],
  'linkOptions' => ['data-method' => 'post']
];

}

echo Nav::widget([
  'options' => ['class' => 'navbar-nav navbar-right'],
  'items' => $menuItems,
]);
NavBar::end();
?>

<div class="container" style="clear: both; padding-top: 56px;">
  <?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?php
    if( Yii::$app->getSession()->hasFlash('message') ) {
      echo Alert::widget([
        'options' => [
          'class' => 'alert-success', //这里是提示框的class
          'fade'=>'300',
        ],
        'body' => Yii::$app->getSession()->getFlash('message'), //消息体
      ]);
    }
    ?>
    <!-- <?= Alert::widget() ?> -->
    <?= $content ?>
  </div>
</div>

<footer class="footer">
  <div class="container">
    <p class="pull-left">&copy; <?php echo Config::getConfig('copyright');?> <?= date('Y') ?></p>

    <p class="pull-right">Powered by Milpo Technologies</p>
  </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
