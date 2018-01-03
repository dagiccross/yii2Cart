<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Goods */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-create">

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
