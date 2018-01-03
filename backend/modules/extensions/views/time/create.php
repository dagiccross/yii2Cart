<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Time */

$this->title = Yii::t('app', 'Create Time');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Times'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
