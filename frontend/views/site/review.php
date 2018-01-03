<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\extensions\Pages;
?>

<section class="review-wrap">
  <div class="col-sm-12">
    <h2>Customers Reviews</h2>
    <div class="customer-feedback">
      <span class="fespan"><?=Html::a(\Yii::t('app','Leave Us Feedback'),['/site/review','type'=>'review'])?></span>
      <div class="rating"><p>Overall User Rating</p>
        <p><span class=" glyphicon glyphicon-star view-star"></span>
          <span class="glyphicon glyphicon-star view-star"></span>
          <span class="glyphicon glyphicon-star view-star"></span>
          <span class="glyphicon glyphicon-star view-star"></span>
          <span class="glyphicon glyphicon-star view-star"></span>
        </p></div>
      </div>
      <div class="clearfix"></div>

      <table class="views-table">
        <tbody>
          <?php if(!empty($list)):?>
            <?php
            foreach ($list as $k => $v) {
              $data = '';
              for ($i=0; $i <=$v['food'] ; $i++) {

                $data .= Html::tag('span','',['class'=>'glyphicon glyphicon-star view-star']);
              }
              $shtml = Html::tag('td',$data);
              $cmt = $v['comment'];
              // Strip HTML Tags
              $clear = strip_tags($cmt);
              // Clean up things like &amp;
              $clear = html_entity_decode($clear);
              // Strip out any url-encoded stuff
              $clear = urldecode($clear);
              // Replace non-AlNum characters with space
              $clear = preg_replace('/[^A-Za-z0-9]/', ' ', $clear);
              // Replace Multiple spaces with single space
              $clear = preg_replace('/ +/', ' ', $clear);
              // Trim the string of leading/trailing space
              $clear = trim($clear);
              $data = $clear.Html::tag('p',$v['name'].'-'.$v['add_date']);
              $shtml .= Html::tag('td',$data);

              $shtml = Html::tag('tr',$shtml);
              echo $shtml;

            }
            ?>
          <?php endif;?>

        </tbody>
      </table>

    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-sm-12">
        <?= Pages::widget(['pagination' => $pages]) ?>
      </div>
    </div>

    <div class="clearfix"></div>
  </section>
