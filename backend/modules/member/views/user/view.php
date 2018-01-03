<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

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
            'phone',
            // ['label'=>Yii::t('label','Power'),'value'=>userFlat($model->power,0)],
            // 'fen',
            // 'money',
            // 'freezing',
            ['label'=>Yii::t('label','Creation Date'),'value'=>empty($model->addtime) ? '' : date('d-m-Y H:i',$model->addtime)],
            ['label'=>Yii::t('label','Last Modify Date'),'value'=>empty($model->modifytime) ? '' : date('d-m-Y H:i',$model->modifytime)],
            'loginip',
            // ['label'=>Yii::t('label','Status'),'value'=>userStatus($model->status,0)],
        ],
    ]) ?>

</div>
