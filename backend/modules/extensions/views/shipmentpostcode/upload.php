<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Goods */
$this->title = Yii::t('app', 'Upload');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shipment PostCode'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="goods-view">
    <?php $form = ActiveForm::begin(['id'=>'upload-form']); ?>
    <div class="form-group" style="padding:10px 0px;">
    <?=Html::label(Yii::t('label','files'))?>
    <?=Html::fileInput('file')?>
    <?=Html::hiddenInput('type','upload')?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-primary','id'=>'upload-submit']) ?>
        <span id="ajaxloading" style="display:none;"><?=Html::img(showImg('uploads/loading.gif'))?></span>
    </div>    
    <?php ActiveForm::end();?>
</div>

<?php $this->beginBlock('upload') ?>  

    $("#upload-submit").click(function(){
        //console.log($("input[name='file']"));
        if($("input[name='file']").val()==''){

            alert('Please select file for upload');
            return false;
        }

        $("#upload-form").ajaxSubmit({
            type:"post",
            url:"index.php?r=extensions/shipmentpostcode/ajax",
            beforeSubmit:function(){
                $("#ajaxloading").show();
            },
            success:function(data){
                $("#ajaxloading").hide();
                console.log(data);
                data = jQuery.parseJSON(data);
                if(data.status){
                    alert(data.message);
                }
                

            },
            error:function(data){
                console.log(data);
            }
        });
        return false;
    });
            
            
         
        


<?php $this->endBlock() ?>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/jquery.form.js", ['position'=>\yii\web\View::POS_END,'depends'=> [yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJs($this->blocks['upload'], \yii\web\View::POS_END); ?> 