<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Customers Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-review-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Customers Review'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'order_id',
            'money',
            'delivery',
            
            // 'comment:ntext',
            
            'food',
            'member_id',
            'name',
            'add_date',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>(Yii::$app->user->identity->power=='admin') ? '{view} {update} {delete}' : '{view} {update}'],
        ],
    ]); ?>

</div>
