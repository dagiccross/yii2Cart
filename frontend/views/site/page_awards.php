<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\Config;

?>

<section class="content-main">
<h1 class="people-h1"><?php echo isset($page['oneMeta']['title']) ? $page['oneMeta']['title'] : ''?></h1>
<div class="what-we-do container width-10" >
  
  <?php //echo isset($page['oneMeta']['content']) ? $page['oneMeta']['content'] : ''?>
  <?php
  $i=0;
  foreach ($list['page_tag'] as $k => $tag) {

    $shtml = '';
    $tow = ($i%2==0) ? 'row-two' : '';

    echo Html::beginTag('div',['class'=>'row '.$tow]);
    echo Html::tag('div',Html::tag('h3',$tag['oneMeta']['name']),['class'=>'biaoti']);

    echo Html::beginTag('div',['class'=>'people-content ']);
    echo Html::beginTag('div',['class'=>'row awards-con ']);
    echo Html::tag('p',$tag['oneMeta']['description'],['style'=>'margin-bottom:25px;']);
    if(isset($list['page_list'][$tag['id']])):
      
      foreach ($list['page_list'][$tag['id']] as $_k => $_v) {
      	echo Html::tag('p',$_v['oneMeta']['title']);
        echo Html::tag('p',Html::a(\Yii::t('app','Learn More'),['/site/listsingle','id'=>$_v['id'],'model'=>$list['model'],'tag'=>$list['tag']],['class'=>'more-btn']));
      }

    endif;

    echo Html::endTag('div');
    echo Html::endTag('div');
    echo Html::endTag('div');
    $i++;
  }
  ?>
  
</div>
</section>