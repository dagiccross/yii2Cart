<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\bootstrap\Tabs;

?>
<div class="ext-form">
<?php $form = ActiveForm::begin(); ?>

<?php
    $items = array();
    $i = 0;
    foreach ($list['language_listData'] as $k => $v) {

        $items[] = ['label'=>\Yii::t('app','Extension Informations').': '.$v['name'],
                    'content'=>$this->render('form/_meta', [
                                'model' => isset($model[$k]) ? $model[$k] : array(),
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
   <?= Html::submitButton(\Yii::t('app', 'Update'), ['class' =>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
</div>

<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/jq.insertimg.js", ['position'=>\yii\web\View::POS_END,'depends'=> [yii\web\JqueryAsset::className()]]); ?>
