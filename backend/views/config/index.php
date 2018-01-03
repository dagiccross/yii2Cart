<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('info', 'Config');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a(Yii::t('info', 'Create Config'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php /*echo  GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // ['label'=>Html::a('dd'),'value'=>function($m){return $m->id;}],
            'options',
            'values:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>
    <div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if(Yii::$app->user->identity->power=='admin') {
    echo Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('app','Basic Information'),
                'content' => $this->render('form/_base_form', [
                                'model' => $model,
                            ]),
                'active' => true
            ],
            [
                'label' => \Yii::t('app','Server Information'),
                'content' => $this->render('form/_ext_form', [
                                'model' => $model,
                                'list'=>$list,
                            ]),
            ],
            [
                'label' => \Yii::t('app','Company Information'),
                'content' => $this->render('form/_addr_form', [
                                'model' => $model,
                                'list'=>$list,
                            ]),
            ],

        ],
    ]);
  } else if(Yii::$app->user->identity->power=='user') {
    echo Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('app','Configurations'),
                'content' => $this->render('form/_user_form', [
                                'model' => $model,
                            ]),
                'active' => true
            ],
        ],
    ]);
  }
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>

<?php $this->beginBlock('image_attr') ?>
$(".test-btn").click(function(){
    var test_content = $("input[name='test_input']").val();
    var test_email = $("input[name='test_email']").val();
    $.ajax({
        type:"get",
        url:"<?=Url::to(['/config/test'])?>",
        data:{email:test_email,content:test_content},
        success:function(data){
            alert(data);
        },
        error:function(msg){
            console.log(msg);
        }
    });
});
<?php $this->endBlock() ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/jq.insertimg.js", ['position'=>\yii\web\View::POS_END,'depends'=> [yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJs($this->blocks['image_attr'], \yii\web\View::POS_END); ?>
