<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Coupon;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Coupon');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'coup_id',
            'coup_no',
            'name',
            // 'type',
            ['label'=>\Yii::t('info','Coupon Type'),
            // 'format'=>'text',
            'value'=>function($m){
                // return $m['flat_coup'];
                return Coupon::getCoupFlat($m['flat_coup']);
            }],

            ['label'=>\Yii::t('info','Calculation Method'),
            // 'format'=>'text',
            'value'=>function($m){
                return Coupon::getCoupType($m['type']);
            }],
            ['label'=>\Yii::t('info','Coupon Discount Value'),
            // 'format'=>'text',
            'value'=>function($m){
                return $m['coup_value'];
            }],
            'coup_quanity',
            // 'total_quanity',
            // 'start_date',
            // 'end_date',
            // 'status',
            // 'flat_date',
            // 'flat_coup',
            // 'monday',
            // 'tuesday',
            // 'wednesday',
            // 'thursday',
            // 'friday',
            // 'taturday',
            // 'sunday',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
