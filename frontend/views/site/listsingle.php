<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;


?>

<section class="content-main">
  <div>
    <?php 
    echo isset($single['image']['picture']) ? Html::img(showImg($single['image']['picture']),['class'=>'img_10']) : Html::img(IMG_URL.'/banner.jpg',['class'=>'img_10']);
    ?>
  </div>
  <i class="pre-page animated fadeInDownBig">
      <?php echo Html::a(Html::img(showImg(IMG_URL.'/left.png')),($list['prev']) ? ['/site/listsingle','id'=>$list['prev'],'tag'=>$list['tag'],'model'=>$list['model']] : '#')?>
    </i>
    <i class="next-page animated fadeInDownBig">
      <?php echo Html::a(Html::img(showImg(IMG_URL.'/right.png')),($list['next']) ? ['/site/listsingle','id'=>$list['next'],'tag'=>$list['tag'],'model'=>$list['model']] : '#')?>
    </i>
  <h1 class="news-title">
    
    <?php
    echo $single['oneMeta']['title'];
    ?>
  </h1>
  
<div class="what-we-do container width-10" >
  
  <div class="row">
    
    <div class="people-content">
      <?php echo showContent($single['oneMeta']['content']);?>

    </div>
  </div>

</div>
</section>
