<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Extionsion */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension'), 'url' => ['index','type'=>Yii::$app->request->get('type','ext')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Extension-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meta' => $meta,
        'list' => $list,
    ]) ?>

</div>
