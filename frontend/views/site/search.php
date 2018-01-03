<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\extensions\Pages;

?>
<section class="about-main">
	<div class="col-sm-12 blog-wrap">
   
   <h2 class="about-h2">
    <ul class="right-nav">
      <li><a href="<?= Url::to(['site/index']) ?>"><?=Yii::t('app','Home')?></a></li> <li>> </li>
      <li><a href="#"><?= Yii::t('app', 'Search') ?></a></li>
    </ul>
  </h2> 
  
    <div class="row">
     <div class="col-sm-12">
      <p class=" search-title"><?= Yii::t('app', 'Search list') ?></p>
     </div>           
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="search-sel"><?= Yii::t('app', 'Sort by') ?>:<select><option><?= Yii::t('app', 'default') ?></option></select></div>
      </div>
    </div>

    <?php if ($goods) : ?>
    <div class="row">
      
      <?php foreach ($goods as $key => $item) : ?>
      <div class="col-sm-3">
       <div class="product-wrap">
         <div class="product">
          <div class="product-pic">
           <a href="<?= Url::to(['site/detail','id' => $item['id']]) ?>"><img src="<?= $item['pic'] ?>" alt="<?= $item['title'] ?>"/></a>
         </div>
         <ol class="specail-title">
           <li><?= Html::a($item['title'], ['site/detail', 'id' => $item['id']])?></li>
           <li><?= Html::a($item['description']) ?></a></li>
           <li><a href="<?= Url::to(['site/detail', 'id' => $item['id']]) ?>">DETAILS<span class="glyphicon glyphicon-menu-right detail-icon"></span></a></li>
         </ol>
         </div>
         <div class="price">
          <?php if(isset($item['sku_price'])&&!empty($item['sku_price'])):?>
          <span class="price1">$<?= $item['price'] ?></span>
          <span class="cart2" onclick="javascript:window.location.href='<?=Url::to(['/site/detail','id'=>$item['id']])?>'"><?= Html::img(showImg(IMG_URL.'/cart.png'),['alt' => 'icon']) ?></span>
          <?php else:?>
          <span class="price1">$<?= $item['price'] ?></span>
          <span class="cart2" onclick="javascript:CART.add('<?= $item['id'] ?>')"><?= Html::img(showImg(IMG_URL.'/cart.png'),['alt' => 'icon']) ?></span>
          <?php endif;?>
        </div>
       </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="row">
      <div class="col-sm-12">
      <?= Pages::widget(['pagination' => $pages]); ?>
      </div>
    </div>
    <?php else : ?>
    <div class="row">
      <div class="col-sm-12">
        <h3 style="text-align: center;"><?= Yii::t('app', 'No products') ?></h3>
      </div>
    </div>        
    <?php endif; ?>
   

   <div class="clearfix"></div>
  </div>
	<div class="clearfix"></div>
</section>