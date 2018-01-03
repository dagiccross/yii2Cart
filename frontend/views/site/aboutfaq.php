<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;


?>
<section class="about-main">
	<div class="col-sm-12 blog-wrap">
   
   <h2 class="about-h2">
    <ul class="right-nav">
      <li><a href="<?= Url::to(['site/index']) ?>"><?=Yii::t('app','Home')?></a></li> <li>> </li>
      <li><a href="#"><?=$page['title']?></a></li>
    </ul>
  </h2> 
  
   <?=isset($page['content']) ? $page['content'] : '';?>
   <div class="clearfix"></div>
  </div>
	<div class="clearfix"></div>
</section>