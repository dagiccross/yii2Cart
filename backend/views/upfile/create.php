<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Upfile */

$this->title = Yii::t('app', 'Create Upfile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Upfiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upfile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
