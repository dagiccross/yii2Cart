<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model common\models\Goods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php //echo thridpart\ueditor\UEditor::widget(['name'=>'dd']);?>
    <?php //echo $form->field($model,'sku')->widget('thridpart\ueditor\UEditor',[]);?>
    <?php echo Html::hiddenInput('r',isset($list['urlReferrer']) ? $list['urlReferrer'] : '');?>
    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => \Yii::t('label','Basic Infomation'),
                'content' => $this->render('form/_base_form', [
                                'model' => $model,
                                'goods_category'=>$goods_category,
                                'goodstotag'=>$goodstotag,
                            ]),
                'active' => true
            ],
            [
                'label' => \Yii::t('label','Extension Infomation'),
                'content' => $this->render('form/_meta_form', [
                                'meta' => $meta,
                            ]),
            ],
            [
                'label' => \Yii::t('label','Goods Category'),
                'content' => $this->render('form/_category_form', [
                                // 'model' => $model,
                                'goodstocategory' => $goodstocategory,
                                // 'goodstotag'=>$goodstotag;
                            ]),
                
            ],
            // [
            //     'label' => \Yii::t('label','Categories Specifications'),
            //     'content' => $this->render('form/_feature_form', [
            //                     'goodsfeature' => $goodsfeature,
            //                     'goodssku' => $goodssku,
            //                     'featureattr'=>$featureattr,
            //                     'feature'=>$feature,
            //                 ]),
                
            // ],
            [
                'label' => \Yii::t('label','Extension Attributes'),
                'content' => $this->render('form/_attr_form', [
                                'attr' => $attr,
                            ]),
                
            ],
            [
                'label' => \Yii::t('label','Extension Options'),
                'content' => $this->render('form/_options_form', [
                                'goodsoptionsgroup' => $goodsoptionsgroup,
                            ]),
                // 'active' => true
                
            ],
        ],
    ]);
    ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
