<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GoodsCategory */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app', 'Goods Category'),
]) . ' ' . $model->g_cat_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->g_cat_id, 'url' => ['view', 'id' => $model->g_cat_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meta' => $meta,
    ]) ?>

</div>
