<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Logininfo */

$this->title = Yii::t('app', 'Create Logininfo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logininfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logininfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
