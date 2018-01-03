<?php

/* @var $this yii\web\View */

use yii\helpers\Html;


?>
<?php $this->registerCssFile(SITE_URL."/frontend/web/js/easing/jquery.jscrollpane.css"); ?>

<style type="text/css">
	.content-svg{position: relative;}
</style>
<div class="what-we-do container width-10" >

<?= isset($page['oneMeta']['content']) ? showContent($page['oneMeta']['content']) : ''?>

	<div class="row">
		<ul class="list-link">			
			<li class="link-li">
				<?php echo Html::a(\Yii::t('app','CONTACT'),['/site/contact'],['class'=>'animsition-link',
                            												'data-animsition-out-class'=>'fade-out-left',]);?>
                <i class="arrow-right"></i>
            </li>
			<li class="link-li">
				<?php echo Html::a(\Yii::t('app','PROJECTS'),['/site/contact'],['class'=>'animsition-link',
                            												'data-animsition-out-class'=>'fade-out-left']);?>
				
				<i class="arrow-right"></i>
			</li>
			<li class="link-li">
				<?php
				// echo $list['what'][$page['id']];
				?>
				<div id="ca-container" class="ca-container">
					<div class="ca-wrapper">
						<?php 
						$i = 1;
						foreach ($list['what'] as $k => $v) {
							$class_div = 'ca-item-'.$i;
							echo Html::tag('div',Html::a($v,['/site/what','id'=>$list['id'],'path'=>$list['path'],'page_id'=>$k],['class'=>'animsition-link',
                            												'data-animsition-out-class'=>'fade-out-left']),
											['class'=>'ca-item '.$class_div]);
							$i++;

						}?>
						
					</div>
				</div>
				<?php //echo Html::a(\Yii::t('app','LWK LANDSCAPE'),['/site/contact']);?>
				<!-- <i class="arrow-right"></i> -->
			</li>

		</ul>
	</div>
</div>

<?php $this->beginBlock('what') ?>  
    $(".bobo").bobo();
    $('#ca-container').contentcarousel();
<?php $this->endBlock() ?>

<?php $this->registerJsFile(SITE_URL."/frontend/web/js/bobo.js", ['position'=>\yii\web\View::POS_END,'depends'=> [frontend\assets\AppAsset::className()]]); ?>
<?php $this->registerJsFile(SITE_URL."/frontend/web/js/easing/jquery.easing.1.3.js", ['position'=>\yii\web\View::POS_END,'depends'=> [frontend\assets\AppAsset::className()]]); ?>
<?php //$this->registerJsFile(SITE_URL."/frontend/web/js/easing/jquery.mousewheel.js", ['position'=>\yii\web\View::POS_END,'depends'=> [frontend\assets\AppAsset::className()]]); ?>
<?php $this->registerJsFile(SITE_URL."/frontend/web/js/easing/jquery.contentcarousel.js", ['position'=>\yii\web\View::POS_END,'depends'=> [frontend\assets\AppAsset::className()]]); ?>
<?php $this->registerJs($this->blocks['what'], \yii\web\View::POS_END); ?>