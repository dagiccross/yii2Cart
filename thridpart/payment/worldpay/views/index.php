<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\bootstrap\Tabs;

$paydollar_langs = array(
            'C'=>'C-Traditional Chinese',
            'E'=>'E-English',
            'X'=>'X-Simplified Chinese',
            'K'=>'K-Korean',
            'J'=>'J-Japanese',
            'T'=>'T-Thai',
            'F'=>'F-French',
            'G'=>'G-German',
            'R'=>'R-Russian',
            'S'=>'S-Spanish',
            'V'=>'V-Vietnamese',
        );

$paydollar_mps_modes = array(
            'NIL'=>'NIL-',
            'SCP'=>'SCP-',
            'DCC'=>'DCC-',
            'MCP'=>'MCP-'
            /*'NIL-没有提供,关闭MPS（没有货币转换）',
            'SCP-开启MPS简单货币转换',
            'DCC-开启MPS动态货币转换',
            'MCP-开启MPS多货币计价'*/);

$paydollar_paymethods = array('ALL','CC','VISA','Master','JCB','AMEX','Diners','PPS','PAYPAL','CHINAPAY','ALIPAY','TENPAY','99BILL','MEPS','SCB','BPM','KTB','UOB','KRUNGSRIONLINE','TMB','IBANKING','BancNet','GCash','SMARTMONEY'); 


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
