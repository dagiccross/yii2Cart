<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShipmentPostcode */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shipment Postcode'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipment-postcode-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
