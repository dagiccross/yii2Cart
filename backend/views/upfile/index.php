<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Upfiles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upfile-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //Html::a(Yii::t('app', 'Create Upfile'), ['create'], ['class' => 'btn btn-success']) ?>
    
    <?php
    Modal::begin([
        'header' => '<h2>Upload Pictures</h2>',
        'toggleButton' => ['label' => Yii::t('app', 'Upload'),
                            'tag' => 'a',
                            'class'=>'btn btn-success'],
    ]);
        echo '<div class="upfile-create">';
        echo $this->render('_upload_form', [
                'model' => $model,
            ]);
        echo '</div>';
    Modal::end();


    if(Yii::$app->user->identity->power=='admin'){
        Modal::begin([
            'header' => '<h2>Upload PDF</h2>',
            'toggleButton' => ['label' => Yii::t('app', 'PDF'),
                                'tag' => 'a',
                                'class'=>'btn btn-success'],
        ]);
            echo '<div class="upfile-create">';
            echo $this->render('_upload_form', [
                    'model' => $model,
                    'type' => 'pdf',
                ]);
            echo '</div>';
        Modal::end();
    }

    ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label'=>Yii::t('info','Image'),
                'format' => 'html',
                'value'=>function($m){return  Html::img(showImg($m['pic']),['width'=>'100px','height'=>'100px']);}],
            
            // 'id',
            'pic',
            // 'thumb',
            // 'order_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<?php
/*
Modal::widget([
    'id' => 'contact-modal',
    'toggleButton' => [
        'label' => 'Обратная связь',
        'tag' => 'a',
        'data-target' => '#contact-modal',
        // 'href' => Url::toRoute(['/main/contact']),
    ],
    'clientOptions' => false,
]); 
*/
?>