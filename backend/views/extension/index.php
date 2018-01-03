<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Extension');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Extension-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create','type'=>Yii::$app->request->get('type','ext')], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label'=>Yii::t('info','Key'),'value'=>function($m){return $m['key'];}],
            //['label'=>Yii::t('info','Tag'),'value'=>function($m){return $m['tag'];}],
            ['label'=>Yii::t('info','Name'),'value'=>function($m){return $m['name'];}],
            // 'key',
            // 'tag',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
              'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                  ];
                  return Html::a(''.\Yii::t('app','Setting'), $url.'&type='.Yii::$app->request->get('type','ext'), $options);
                },
                'update' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url.'&type='.Yii::$app->request->get('type','ext'), $options);
                },
                'delete' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url.'&type='.Yii::$app->request->get('type','ext'), $options);
                },
              ]
            ],
        ],
    ]); ?>

</div>
