<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

use yii\bootstrap\Alert;
use frontend\extensions\Menu;
use common\models\Config;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= \Yii::$app->language ?>">
<head>
    <meta charset="<?= \Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
   <script type="text/javascript">
   var PAYMENT_INFO = {Minpay:"<?=Config::getConfig('Minpay')?>",Minimum:"<?=Config::getConfig('Minimum')?>"};
   </script>
   <style type="text/css">
    .navbar{margin-bottom: 5px;}
   </style>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container-fluid home">
        <div class="container">
        <header>
          

    <?php
     $header = getPageByKey('header');
     echo showContent($header['content']);

    ?>
            <div class="clearfix"></div>
        </header>
        
      <nav class="navbar navbar-default navbar-my">
  <div class="">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <div class="info-title"><?= isset($this->context->menuinfo) ? $this->context->menuinfo : '';?></div>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse navigation" id="bs-example-navbar-collapse-1">
      <?= Menu::widget(['type'=>'top']) ?>
    </div><!-- /.navbar-collapse -->
    <div class="row head-top">
            <?php if(\Yii::$app->user->isGuest):?>
            <?=Html::tag('div',Html::a('Sign up',['/site/signup'],['target'=>'_self']),['class'=>'sign-r'])?>
            <?=Html::tag('div',Html::a('Login',['/site/login']),['class'=>'login-l'])?>
            <?php else:?>
            
            <?=Html::tag('div',Html::a('Logout',['/site/logout']),['class'=>'login-l','data-method'=>"post"])?>
            <?=Html::tag('div',Html::a('My Account',['/member/default/index']),['class'=>'sign-r'])?>
            <?=Html::tag('div',Html::a('Hi,'.\Yii::$app->user->identity->username,['/member/default/index']),['class'=>'wel-user'])?>
            <?php endif;?>
      </div>
  </div><!-- /.container-fluid -->
</nav>
        </div>
      </div>
    <div class="container">
         <div class="wrap">


    
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?php // Alert::widget() ?>
        <?= $content ?>

 </div>
</div>
<footer>
    <div class="container">
     <?php
     $footer = getPageByKey('footer');
     echo showContent($footer['content']);
     ?>
    </div>
         
   </footer>
<?php
// var_dump(Config::getAllConfig());
?>
<?php $this->endBody() ?>
<!-- alert -->

<div style="" id="alert_all" class="fade modal in" role="dialog" tabindex="-1">
  <div class="modal-dialog ">
    <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h2>Alert</h2>
    </div>
    <div class="modal-body">
    <div class="upfile-create"> 
      <div class="form-group">
        <div id="alert_all_content">
          
        </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">Cancel
            </button>
    </div>
    </div>
  </div>
</div>
<!-- end alert -->

<!-- 下單時候提示是否到點了 -->
<div class="modal fade pro-order-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog candialog ">
    <div class="modal-content cancal-content" style="padding: 15px; width: 550px;">

      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h2>Restaurant opening at <?=Config::getConfig('Open_time')?></h2>
        </div>
        <div class="excontent">
        

        <p>Minimum spend for delivery <?=Config::getConfig('currency').Config::getConfig('Minimum');?></p>
      </div>
        <div><button class="checkout-button confirm" type="button" data-dismiss="modal" aria-label="Close"><?=\Yii::t('app','Pre-order for later')?></button> </div>

    </div>
  </div>
</div>

<!-- end下單 提示到點 -->

<!-- 下單時候提示是否到點了 -->
<div class="modal fade pro-delivery-order-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog candialog ">
    <div class="modal-content cancal-content" style="padding: 15px; width: 550px;">

      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h3>Please Choose Collection or Delivery</h3>
        </div>
        <div class="excontent" style="padding: 10px 110px;">
          <div>
            <div class="collect">
              <?php echo Html::tag('p',Html::radio('orderDelivery',false,['value'=>'collection']).'Collection');?>
            </div>
            <div class="deliver">
              <?php echo Html::tag('p',Html::radio('orderDelivery',false,['value'=>'deliver']).'Delivery');?>
            </div>
          </div>
          
      </div>
        <div><button class="checkout-button confirm" type="button" data-dismiss="modal" aria-label="Close"><?=\Yii::t('app','Confirm')?></button> </div>

    </div>
  </div>
</div>

<!-- end下單 提示到點 -->

<!-- 提示選擇產品 -->
<div class="modal fade order-options-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog candialog ">
    <div class="modal-content cancal-content" style="padding: 15px; width: 550px;height:auto;">

      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          
        </div>
        <div class="content">



        </div>
    </div>
  </div>
</div>

<!-- end提示選擇產品 -->

<!-- 不是彈出的提示條 -->
<div class="alert-success alert-div">
  <div class="alert-content"><div class="info">action success</div><div style="float:right;"><span onclick="javascript:INFO.alert_hide();">×</span></div></div>
</div>
<!-- end不是彈出的提示條 -->

<style type="text/css">

.page-feedback li{float: left;}
.oo_waypoint_child1 a, .oo_waypoint_child2 a, .oo_waypoint_child3 a {
    display: block;
    height: 260px;
    width: 230px;
    background: #EEE none repeat scroll 0% 0%;
    margin: 5px;
    padding: 5px;
    
}

.oo_waypoint_child1 a {
    background: #FFF url("./frontend/web/images/oo_waypoint_child1.gif") no-repeat scroll 0px 0px;
}
.oo_waypoint_child1 a:hover {
    background-position: 0px -270px;
}

.oo_waypoint_child2 a {
    background: #FFF url("./frontend/web/images/oo_waypoint_child2.gif") no-repeat scroll 0px 0px;
}
.oo_waypoint_child2 a:hover {
    background-position: 0px -270px;
}
.oo_waypoint_child3 a {
    background: #FFF url("./frontend/web/images/oo_waypoint_child3.gif") no-repeat scroll 0px 0px;
}
.oo_waypoint_child3 a:hover {
    background-position: 0px -270px;
}
.oo_waypoint{height: 260px;}
</style>
<!-- 弹出连接页面 -->
<div class="modal fade page-feedback" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog candialog" style="width:755px">
    <div class="modal-content cancal-content" style="padding: 0px; width: 752px;">
      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <?php
           $modal_1 = getPageByKey('modal1');
           echo showContent($modal_1['content']);
          ?>
          
        </div>

    </div>
  </div>
</div>
<!--end 弹出连接页面 -->

<!-- 弹出開店時間页面 -->
<div class="modal fade page-open-time" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog candialog" style="width:403px;">
    <div class="modal-content cancal-content" style="padding: 0px; width: 400px;border: 2px solid rgb(255, 198, 78);">
      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <?php
           $modal_1 = $this->context->page_info['opentime'];
           echo showContent($modal_1['content']);
          ?>
          
        </div>

    </div>
  </div>
</div>
<!--end 弹出開店時間页面 -->

<!-- 弹出送餐信息页面 -->
<div class="modal fade page-delivery-information" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog candialog" style="width:403px;">
    <div class="modal-content cancal-content" style="padding: 0px; width: 400px;border: 2px solid rgb(255, 198, 78);">
      <div class="modal-header mymodal-head">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <?php
           $modal_1 = $this->context->page_info['delivery'];
           echo showContent($modal_1['content']);
          ?>
          
        </div>

    </div>
  </div>
</div>
<!--end 弹出送餐信息页面 -->

<?php
  if( \Yii::$app->getSession()->hasFlash('message') ) {
      echo '<script>modal_alert("'.\Yii::$app->getSession()->getFlash('message').'")</script>';
  }
?>
<?php
  if( \Yii::$app->getSession()->hasFlash('information') ) {
      echo '<script>INFO.alert_info("'.\Yii::$app->getSession()->getFlash('information').'");</script>';
  }
?>
<?php
  if( \Yii::$app->getSession()->hasFlash('start_cookies') ) {
      echo '<script>modal_alert(\''.\Yii::$app->getSession()->getFlash('start_cookies').'\');</script>';
  }
?>


</body>
</html>
<?php $this->endPage() ?>
