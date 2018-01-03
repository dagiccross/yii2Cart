<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!-- <?= Html::a(Yii::t('app', 'View All'), ['index','type'=>'all'], ['class' => 'btn btn-success']) ?> -->
        <!-- <?= Html::a(Yii::t('app', 'Prev Level'), ['index','id'=>Yii::$app->request->get('pid',0)], ['class' => 'btn btn-success']) ?> -->
        <?= Html::a(Yii::t('app', 'Create'), ['create','pid'=>Yii::$app->request->get('id',0)], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['label'=>Yii::t('info','Name'),'value'=>function($m){return $m['name'];}],
            ['label'=>Yii::t('info','Type'),'value'=>function($m){return tagType($m['type'],0);}],
            ['label'=>Yii::t('info','Parent ID'),'value'=>function($m){return $m['pid'];}],
            ['label'=>Yii::t('info','Order ID'),'value'=>function($m){return $m['order_id'];}],
            ['label'=>Yii::t('info','show'),'value'=>function($m){return ($m['show']==1) ? \Yii::t('app','Show') : \Yii::t('app','Hidden');}],
            //'model',
            //'key',
            // 'link_url:url',
            // 'tag_id',
            // 'order_id',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{index} {update} {delete}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'index' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                  ];

                },
                'update' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url.'&pid='.$model['pid'], $options);
                },
                'delete' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url.'&pid='.$model['pid'], $options);
                },
              ]
            ],
        ],
    ]); ?>

</div>
