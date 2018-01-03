<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\extensions\slider\SliderInner;
use frontend\extensions\Evenlist;
// var_dump($single);
?>

<section class="content-main">
  <div>
    <?php 
    echo isset($single['image']['picture']) ? Html::img(showImg($single['image']['picture']),['class'=>'img_10']) : Html::img(IMG_URL.'/banner.jpg',['class'=>'img_10']);
    ?>
  </div>
  <h1 class="news-title">
    <i class="pre-page animated fadeInDownBig">
      <?php echo Html::a(Html::img(showImg(IMG_URL.'/left.png')),($list['prev']) ? ['/site/blogsingle','id'=>$list['prev'],'tag'=>$list['tag'],'model'=>$list['model']] : '#')?>
    </i>
    <i class="next-page animated fadeInDownBig">
      <?php echo Html::a(Html::img(showImg(IMG_URL.'/right.png')),($list['next']) ? ['/site/blogsingle','id'=>$list['next'],'tag'=>$list['tag'],'model'=>$list['model']] : '#')?>
    </i>
    <?php
    echo $single['oneMeta']['title'];
    ?>
  </h1>
<div class="what-we-do container" >
  <div class="row">
    <div style="margin:0 auto;width:70%;">
    <?php
    $slider_data = isset($single['image']['pictures']) ? $single['image']['pictures'] :'';
    $slider_data = @unserialize($slider_data) ? @unserialize($slider_data) : [];
    echo SliderInner::widget(['data'=>$slider_data,
                                'options'=>['id'=>'myCarousel',
                                            'class'=>'carousel slide',
                                            'ol'=>true,
                                            'next'=>false]]);

    ?>
    </div>
  </div>
  <style type="text/css">
  .carousel-indicators{bottom: -42px;}
  </style>
  <div class="row" style="padding:5% 0;line-height:32px;">
    <div class="col-md-8 people-info">
      <?php echo showContent($single['oneMeta']['content']);?>
    </div>
    <div class="col-md-4 people-info">
      <?php if(isset($single['oneAttr']['attr_value'])&&!empty($single['oneAttr']['attr_value'])):?>
      <?php 
      $attr = @unserialize($single['oneAttr']['attr_value']);
      foreach ($attr as $k => $v) {

        echo Html::beginTag('ul',['class'=>'list-unstyled','style'=>'font-size:18px;']);
        echo Html::tag('li',Html::tag('i',$v['name']).$v['options'],['class'=>'lable-grey']);
        echo Html::endTag('ul');
      }?>
      <?php endif;?>
    </div>
  </div>
  <div class="row" style=" padding: 30px 0;">
    <?php
      $count = count($list['tag_category']);
      
      $column_number = 4;
      $line_number = (int)($count/$column_number);

      $line_number_rem = $count%$column_number;
      $line_number = $line_number_rem==0 ? ($line_number+1) : $line_number;
      $int_line = $line_number*$column_number;
      

      $j=0;
      foreach ($list['tag_category'] as $k => $v) {
        $content = '';
        $content_ext = '';
        $content_class = 'col-md-3';

        if($line_number_rem==1&&$j>$int_line){
          $content_class = 'col-md-12';
        }else if($line_number_rem==3&&$j>$int_line){
          $content_class = 'col-md-4';
        }else if($line_number_rem==2&&$j==$int_line){
          // 当余两个时的第一个
          $content .= Html::tag('div','',['class'=>$content_class.' ']);
        }else if($line_number_rem==2&&$j>$int_line){
          // 当余两个时的第二个
          $content_ext .= Html::tag('div','',['class'=>$content_class.' ']);
        }
        

        $people = Html::img(showImg($v['picture']),['class'=>'right-info-tou-img']);
        $people .= Html::tag('p',Html::tag('strong',$_v['title']),['class'=>'people-name']);
        $people .= Html::tag('p',Html::tag('strong',$_v['description']),['class'=>'people-caree']);

        $people = Html::a($people,['/site/people-info','id'=>$_v['page_id']]);
        $content .= Html::tag('div',$people,['class'=>$content_class.' ']);
        $content .= $content_ext;

        echo $content;
        $j++;

      }
    ?>

  </div>


  
</div>
  <?php echo Evenlist::widget();?>
</section>
