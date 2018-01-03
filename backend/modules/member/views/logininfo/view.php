<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Logininfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logininfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logininfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'passwd',
            'email:email',
            'power',
            // 'logintime',
            ['label'=>Yii::t('label','Status'),'value'=>date('Y-m-d H:i:s',$model->logintime)],
            'loginip',
            'auth_key',
            'auth_koken',
            // 'status',
            ['label'=>Yii::t('label','Status'),'value'=>userStatus($model->status,0)],
        ],
    ]) ?>

</div>
