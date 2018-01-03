<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Logininfo */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => '',
]) . ' ' . $model->name;
?>
<div class="logininfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_password', [
        'model' => $model,
    ]) ?>

</div>
