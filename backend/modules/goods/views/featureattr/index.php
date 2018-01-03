<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Attribute');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featureattr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'teature_id',
            ['label'=>Yii::t('app','Feature'),'value'=>function($m){

                    return $m['metaname'];
                }
            ],
            ['label'=>Yii::t('app','Feature Attribute'),'value'=>function($m){

                    return $m['name'];
                }
            ],
            ['label'=>Yii::t('app','Order'),'value'=>function($m){

                    return $m['order_id'];
                }
            ],
            // 'name',
            // 'options:ntext',
            // 'order_id',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
