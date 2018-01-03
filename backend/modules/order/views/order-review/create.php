<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderReview */

$this->title = Yii::t('app', 'Create Customers Review');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-review-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
