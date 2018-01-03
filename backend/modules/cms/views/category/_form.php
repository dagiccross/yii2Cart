<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
        $pid = Yii::$app->request->get('pid',0);
        $pid = $model->isNewRecord ? $pid : (empty($model->pid) ? 0 : $model->pid);?>

    <?php //echo $form->field($model, 'pid')->dropDownList(categoryChild($pid)) ?>
    <?= Html::activeHiddenInput($model,'pid',['value'=>$pid])?>
    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(tagType(),['prompt' => \Yii::t('app','Please Select')]) ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tag_id')->textInput() ?>

    <?= $form->field($model, 'order_id')->textInput() ?>
    <?= $form->field($model, 'top')->checkbox() ?>
    <!-- <?= $form->field($model, 'footer')->checkbox() ?> -->
    <?= $form->field($model, 'side')->checkbox() ?>
    <?= $form->field($model, 'show')->checkbox() ?>
    <?php
    $items = array();
    $i = 0;
    foreach (getLanguage() as $k => $v) {
        $items[] = ['label'=>\Yii::t('app','Extension Infomations').$v['name'],
                    'content'=>$this->render('form/_meta_form', [
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
