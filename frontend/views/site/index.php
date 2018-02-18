<?php
use frontend\extensions\Menu;
use yii\helpers\Html;
use common\models\Config;
// echo md5("GBP:0.01:0:1085582");
?>

<section class="main">
  <div class="col-sm-3 home-menu">
  
    <div class="menu-down">
      <?= Menu::widget(['type'=>'side']) ?>
    </div>
  </div>
  <div class="col-sm-5 home-main" style="padding:0;">
    <div class="main-content">
      <?=$page['content']?>
    </div>
    <div class="home-slide">
      <?= frontend\extensions\Slider::widget(['type'=>'side']) ?>
    </div>
    <!-- end home-slider -->
    <div class="home-exam">
      <?php
      foreach ($goods_list as $k => $v) {
        echo '<div class="col-sm-6 example"><div class="example1">';
        echo Html::img(showImg($v['pic']),['alt'=>$v['title']]);
        echo '</div></div>';
      }
      ?>
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="col-sm-4 home-right">
    <?= $this->render('ext/right_cart') ?>
  </div>
  <div class="clearfix"></div>
</section>
