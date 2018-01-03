<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Extionsion */
 if(Yii::$app->user->identity->power=='admin'){
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension'), 'url' => ['index','type'=>Yii::$app->request->get('type','ext')]];
$this->params['breadcrumbs'][] = $this->title;
}else if(Yii::$app->user->identity->power=='user'){ echo '<h1>Holiday settings</h1>'; }
?>
<div class="extionsion-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <!-- <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p> -->

    <!-- <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            'tag',
        ],
    ]) ?> -->

    <?php
    $view_path = $model->extsions.'/views/index';

    try {
      if(file_exists(@\Yii::getAlias($view_path.'.php'))){
            echo \Yii::$app->view->render($view_path,[
                'model'=>$meta,
                'list'=>$list,
                ]);
        }
    } catch (Exception $e) {

    }


    ?>
</div>
