<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\extensions\Pages;

?>
<section class="content-main">
<h1 class="people-h1"><?php echo $category['oneMeta']['name']?></h1>
<div class="what-we-do container width-10" >
  
  
  <?php
  $i=0;
  foreach ($list['page_tag'] as $k => $tag) {

    $shtml = '';
    $tow = ($i%2==0) ? '' : 'row-two';

    echo Html::beginTag('div',['class'=>'row '.$tow]);

    if($i!=0){
      echo $shtml = Html::tag('div',Html::tag('h3',$tag),['class'=>'biaoti']);
    }

    if(isset($list['page_list'][$k])):
      
      $count = count($list['page_list'][$k]);
      $count_number = $count;
      if($i==0){
        $count -= 2; 
      }
      $column_number = 4;
      $line_number = (int)($count/$column_number);

      $line_number_rem = $count%$column_number;
      $line_number = $line_number_rem==0 ? ($line_number+1) : $line_number;
      $int_line = $line_number*$column_number;
      $int_line = $i==0 ? $int_line+1 : $int_line;

      $people = '';

      $j = 0;
      $content = '';
      foreach ($list['page_list'][$k] as $_k => $_v) {

        $content_ext = '';
        $content_class = 'col-md-3';

        // 当是people的时候
        if($i==0){
          if($count_number<2){
            $content_class = 'col-md-12';
          }else if($j==0){
            // 当是people的时候总数大于或2个时第一个显示
            $content .= Html::tag('div','',['class'=>$content_class.' people']);

          }else if($j==1){
            // 当是people的时候总数大于或2个时第二个显示
            $content_ext .= Html::tag('div','',['class'=>$content_class.' people']);
          }else{
            goto if_line_number_rem;
          }
          
        }else{

          if_line_number_rem:

          if($line_number_rem==1&&$j>$int_line){
            $content_class = 'col-md-12';
          }else if($line_number_rem==3&&$j>$int_line){
            $content_class = 'col-md-4';
          }else if($line_number_rem==2&&$j==$int_line){
            // 当余两个时的第一个
            $content .= Html::tag('div','',['class'=>$content_class.' people']);
          }else if($line_number_rem==2&&$j>$int_line){
            // 当余两个时的第二个
            $content_ext .= Html::tag('div','',['class'=>$content_class.' people']);
          }
        }

        // end 列表逻辑
        $img = @unserialize($_v['image']);

        $people = Html::img($img['0'],['class'=>'right-info-tou-img img-circle']).'<div class="people-img"></div>';
        $people .= Html::tag('p',Html::tag('strong',$_v['title']),['class'=>'people-name']);
        $people .= Html::tag('p',Html::tag('strong',$_v['description']),['class'=>'people-caree']);

        $people = Html::a($people,['/site/people-info','id'=>$_v['page_id']]);
        $content .= Html::tag('div',$people,['class'=>$content_class.' people']);
        $content .= $content_ext;

        $j++;
      }
      $content = Html::tag('div',Html::tag('div',$content,['class'=>'row']),['class'=>'people-content']);
      echo $content;
    endif;
    
    echo Html::endTag('div');
    $i++;
  }
  ?>
  
</div>
</section>