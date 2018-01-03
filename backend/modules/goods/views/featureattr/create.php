<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Featureattr */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attribute'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featureattr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
