<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label'=>Yii::t('info','Title'),'value'=>function($m){return $m['title'];}],
            ['label'=>Yii::t('info','Status'),'value'=>function($m){return $m['status']==1 ? \Yii::t('info','Show') : \Yii::t('info','Hidden');}],
            
            // 'id',
            // 'tag_id',
            // 'status',
            'key',
            'url:url',
            // 'order_id',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
