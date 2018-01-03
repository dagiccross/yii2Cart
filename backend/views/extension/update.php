<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Extionsion */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => Yii::t('app','Extension'),
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension'), 'url' => ['index','type'=>Yii::$app->request->get('type','ext')]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="Extension-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meta' => $meta,
        'list' => $list,
    ]) ?>

</div>
