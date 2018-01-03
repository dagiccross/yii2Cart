<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GoodsCategory */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meta' => $meta,
    ]) ?>

</div>
