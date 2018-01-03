<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Goods */

$this->title = Yii::t('app', 'Update') . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => '#'];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meta' => $meta,
        'attr' => $attr,
        'list' => $list,
        'goodsfeature' => $goodsfeature,
		'goodssku' => $goodssku,
		'feature'=>$feature,
		'featureattr'=>$featureattr,
        'goods_category'=>$goods_category,
        'goodstocategory' => $goodstocategory,
        'goodstotag'=>$goodstotag,
        'goodsoptionsgroup' => $goodsoptionsgroup,
    ]) ?>

</div>
