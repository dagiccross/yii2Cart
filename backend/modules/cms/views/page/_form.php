<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>
<?php echo Html::jsFile(SITE_URL.'/thridpart/kindeditor/assets/kindeditor-min.js');?>
<?php echo Html::jsFile(SITE_URL.'/thridpart/kindeditor/assets/lang/zh_CN.js');?>
<?php echo Html::cssFile(SITE_URL.'/thridpart/kindeditor/assets/themes/default/default.css');?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tag_id')->textInput(['maxlength' => true]) ?>

    
    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_id')->textInput() ?>
    <?php //echo $form->field($model,'key')->widget('thridpart\kindeditor\KindEditor',[]);?>
    <?php //echo $form->field($model,'key')->widget('thridpart\ueditor\UEditor',[]);?>
    <!-- <textarea id="testt"></textarea> -->
    <?= $form->field($model, 'status')->checkbox(['maxlength' => true]) ?>

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
<script type="text/javascript">
    // KindEditor.ready(function(K) {
    //             K.create('#testt', {
    //                 filterMode : false
    //             });
    //         });
</script>
