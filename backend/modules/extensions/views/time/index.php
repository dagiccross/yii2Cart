<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Time;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Times');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            ['label'=>\Yii::t('info','Type'),'value'=>function($data){
                return Time::type($data->type);
            }],
            // 'time:datetime',
            ['label'=>\Yii::t('info','Time'),'value'=>function($data){
                return $data->time;
            }],
            ['label'=>\Yii::t('info','Monday'),'value'=>function($data){
                return ($data->Monday==1) ? 'checked' : 'unchecked';
            }],
            ['label'=>\Yii::t('info','Tuesday'),'value'=>function($data){
                return ($data->Tuesday==1) ? 'checked' : 'unchecked';
            }],
            ['label'=>\Yii::t('info','Wednesday'),'value'=>function($data){
                return ($data->Wednesday==1) ? 'checked' : 'unchecked';
            }],
            ['label'=>\Yii::t('info','Thursday'),'value'=>function($data){
                return ($data->Thursday==1) ? 'checked' : 'unchecked';
            }],
            ['label'=>\Yii::t('info','Friday'),'value'=>function($data){
                return ($data->Friday==1) ? 'checked' : 'unchecked';
            }],
            ['label'=>\Yii::t('info','Saturday'),'value'=>function($data){
                return ($data->Saturday==1) ? 'checked' : 'unchecked';
            }],
            ['label'=>\Yii::t('info','Sunday'),'value'=>function($data){
                return ($data->Sunday==1) ? 'checked' : 'unchecked';
            }],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
