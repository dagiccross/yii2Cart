<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
?>
<div class="order-view">

  <?php
  $map_calculation = \common\models\Config::getConfig('map_calculation');
  if ($map_calculation == 0){
    $shipment_city_new = $model->shipment_postcode;
    $shipment_postcode2_new = strtoupper($model->shipment_postcode2);

  } elseif ($map_calculation == 1){
    $shipment_city_new = $model->shipment_city;
    $shipment_postcode2_new = strtoupper($model->shipment_postcode.$model->shipment_postcode2);

  }
  ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      ['label'=>\Yii::t('info','Member Name'),
      'value'=>$member->username],
      // ['label'=>\Yii::t('info','Sex'),
      // 'value'=>($member->sex==1) ? \Yii::t('app','Man') : \Yii::t('app','Female')],
      // ['label'=>\Yii::t('info','BirthDate'),
      // 'value'=>$member->birthdate],
      ['label'=>\Yii::t('label','Shipment Name'),
      'value'=>$model->shipment_name],
      ['label'=>\Yii::t('label','Shipment Phone'),
      'value'=>$model->shipment_phone],
      ['label'=>\Yii::t('label','Shipment City'),
      'value'=>$shipment_city_new],
      ['label'=>\Yii::t('label','Shipment Address'),
      'value'=>$model->shipment_addr1],
      ['label'=>\Yii::t('label','Shipment Address 2'),
      'value'=>$model->shipment_addr2],
      ['label'=>\Yii::t('label','Shipment Postcode'),
      'value'=>$shipment_postcode2_new],

    ],
    ]) ?>

  </div>
