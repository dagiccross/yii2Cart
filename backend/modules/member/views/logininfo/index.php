<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logininfos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logininfo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Logininfo'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'username',
            // 'passwd',
            'email:email',
            'power',
            // 'logintime',
            ['label'=>Yii::t('label','Login time'),'value'=>function($m){ return (empty($m->logintime)) ? date('d-m-Y H:i') : date('d-m-Y H:i',$m->logintime);}],
            'loginip',
            // 'auth_key',
            // 'auth_koken',
            // 'status',
            ['label'=>Yii::t('label','Status'),'value'=>function($m){ return userStatus($m->status,0);}],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
