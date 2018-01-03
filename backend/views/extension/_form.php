<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model common\models\Extionsion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extionsion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo Html::hiddenInput('urlReferrer',isset($list['urlReferrer']) ? $list['urlReferrer'] : '');?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'extsions')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'backendModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkBox() ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'picture')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true]) ?>

    <?php if(Yii::$app->request->get('type')&&Yii::$app->request->get('type')=='account'):?>
    <?= $form->field($model, 'card_fee')->textInput(['maxlength' => true]) ?>
    <?php endif;?>
    <?php if(Yii::$app->request->get('type')):?>
    
    <?= $form->field($model, 'tag')->hiddenInput(['maxlength' => true,'value'=>Yii::$app->request->get('type','ext')])->label('') ?>

    <?php else:?>
    
    <?= $form->field($model, 'tag')->hiddenInput(['maxlength' => true])->label('') ?>
    
    <?php endif;?>
    <?php
    $items = array();
    $i = 0;
    foreach ($list['language_listData'] as $k => $v) {
        $items[] = ['label'=>\Yii::t('app','Extension Informations').$v['name'],
                    'content'=>$this->render('form/_meta_slider', [
                                'model' => isset($meta[$k]) ? $meta[$k] : array(),
                                'language'=>$k
                            ]),
                    'active' => $i==0 ? true : false,
                    ];
        $i++;
    }
    echo Tabs::widget([
        'items' => $items,
    ]);
    
    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
