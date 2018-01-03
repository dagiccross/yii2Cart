<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Features');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            ['label'=>Yii::t('info','Name'),'value'=>function($m){return $m['name'];}],
            ['label'=>Yii::t('info','Feature'),'value'=>function($m){return $m['feature'];}],
            ['label'=>Yii::t('info','Group ID'),'value'=>function($m){return $m['group_id'];}],
            ['label'=>Yii::t('info','Order ID'),'value'=>function($m){return $m['order_id'];}],
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
