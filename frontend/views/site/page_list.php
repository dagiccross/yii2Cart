<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use common\models\Config;

?>
<style type="text/css">
	.row-show{display: none;}
</style>

<?= isset($page['oneMeta']['content']) ? showContent($page['oneMeta']['content']) : ''?>
<?php


if(isset($list['list_info'])):
	if(isset($page['key'])&&$page['key']=='Career'):
		echo Html::beginTag('div',['class'=>'container width-10']);
		$i=0;
		foreach ($list['list_info'] as $k => $list_info) {
			$class_row = $i%2==0 ? 'row-two' : '';
			$apply_html = '';
			if(!empty($list_info['oneMeta']['description'])){
				$shtml0 = Html::tag('div',Html::tag('h3',$list_info['oneMeta']['title']),['class'=>'biaoti']);
				$shtml1 = Html::tag('div',Html::tag('div',$list_info['oneMeta']['description'].Html::tag('p',Html::a(\Yii::t('app','Learn More'),'javascript:;',['class'=>'more-btn','data-div'=>'row'.$list_info['id']])),['class'=>'row']),['class'=>'people-content']);
				echo Html::tag('div',$shtml0.$shtml1,['class'=>'row tx_center '.$class_row]);
				$class_row .= ' row-show row'.$list_info['id'];

				$apply_html = Html::tag('p',Html::a(\Yii::t('app','Apply'),'mailto:'.Config::getConfig('service_email'),['class'=>'more-btn']));
			}
			
			$shtml0 = Html::tag('div',Html::tag('div',$list_info['oneMeta']['content'].$apply_html,['class'=>'row']),['class'=>'people-content']);
			echo Html::tag('div',$shtml0,['class'=>'row '.$class_row]);
			$i++;
		}
		echo Html::endTag('div');
	else:
		echo Html::beginTag('div',['class'=>'container width-10']);
		$i=0;
		foreach ($list['list_info'] as $k => $list_info) {
			$class_row = $i%2==0 ? 'row-two' : '';
			
			$shtml0 = Html::tag('div',Html::tag('h3',$list_info['oneMeta']['title']),['class'=>'biaoti']);
			$shtml1 = Html::tag('div',Html::tag('div',$list_info['oneMeta']['content'],['class'=>'row']),['class'=>'people-content']);
			echo Html::tag('div',$shtml0.$shtml1,['class'=>'row '.$class_row]);

		}
		echo Html::endTag('div');

	endif;

endif;
?>