<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>
    <?php
    if(is_array($goods)){

    	for ($i=0; $i <count($goods) ; $i++) { 

    		$shtml = Html::checkbox('CouponGoodsCheckbox[]',isset($coupongoods[$goods[$i]['id']]),['value'=>$i,'class'=>'col-xs-6 col-sm-1']);

    		$shtml .= Html::tag('span',$goods[$i]['title'],['class'=>'col-xs-3']);
    		$shtml .= Html::hiddenInput('CouponGoods['.$i.'][goods_id]',$goods[$i]['id']);
    		$shtml .= Html::beginTag('div',['class'=>'col-lg-2']);
    		$shtml .= Html::beginTag('div',['class'=>'input-group']);
    		$shtml .= Html::tag('span',Yii::t('label','coup'),['class'=>'input-group-addon']);
    		$shtml .= Html::textInput('CouponGoods['.$i.'][coup]',isset($coupongoods[$goods[$i]['id']]['coup']) ? $coupongoods[$goods[$i]['id']]['coup'] : '',['class'=>'col-xs-6 col-sm-3 form-control']);
    		$shtml .= Html::endTag('div');
    		$shtml .= Html::endTag('div');

    		$shtml .= Html::beginTag('div',['class'=>'col-lg-2']);
    		$shtml .= Html::beginTag('div',['class'=>'input-group']);
    		$shtml .= Html::tag('span',Yii::t('label','quanity'),['class'=>'input-group-addon']);
    		$shtml .= Html::textInput('CouponGoods['.$i.'][quanity]',isset($coupongoods[$goods[$i]['id']]['quanity']) ? $coupongoods[$goods[$i]['id']]['quanity'] : '',['class'=>'col-xs-6 col-sm-3 form-control']);
    		$shtml .= Html::endTag('div');
    		$shtml .= Html::endTag('div');
    		echo Html::tag('div',$shtml,['class'=>'row']);

    	}

    }

    ?>
 
