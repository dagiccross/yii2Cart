<?php
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\extensions\Pages;
?>
<div class="acount-info">
  <h2>Ratings Review List</h2></div>
  <div class="row order-hr">              
    <div class="col-sm-2">Date</div>
    <div class="col-sm-3">About Money</div>
    <div class="col-sm-3">About Delivery</div>
    <div class="col-sm-3">About Food</div>            
    <div class="col-sm-1">Review</div>
  </div>                  
  <?php 
  foreach ($list as $k => $v) {
    $shtml = Html::beginTag('div',['class'=>'row one-order']);
    $shtml .= Html::tag('div',$v['add_date'],['class'=>'col-sm-2']);
    $data = '';
    for ($i=0; $i <$v['money'] ; $i++) { 

      $data .= Html::tag('span','',['class'=>'glyphicon glyphicon-star view-star']);
    }
    $shtml .= Html::tag('div',$data,['class'=>'col-sm-3']);
    $data = '';
    for ($i=0; $i <$v['delivery'] ; $i++) { 

      $data .= Html::tag('span','',['class'=>'glyphicon glyphicon-star view-star']);
    }
    $shtml .= Html::tag('div',$data,['class'=>'col-sm-3']);
    $data = '';
    for ($i=0; $i <$v['food'] ; $i++) { 

      $data .= Html::tag('span','',['class'=>'glyphicon glyphicon-star view-star']);
    }
    $shtml .= Html::tag('div',$data,['class'=>'col-sm-3']);
    $shtml .= Html::tag('div',Html::a('Review',['default/review','id'=>$v['order_id']],['class'=>'review-btn']),['class'=>'col-sm-1 review-col']);
    $shtml .= Html::endTag('div');
    echo $shtml;
  }
  ?>
                
<div class="clearfix"></div>
  <div class="row">
    <div class="col-sm-12">
    <?= Pages::widget(['pagination' => $pages]) ?>
    </div>
  </div>