<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\UrlManager;
?>

<div class="acount-info">
  <?=Html::tag('h2',Yii::t('app','Address Book'))?>

  <?=Html::tag('p',Yii::t('app','Create and save multiple delivery addresses, so you can easily choose your delivery location.'),['class'=>"acount-answer"])?>

</div>

<div class="acount-info">
  <?php
  foreach ($addr as $k => $v) {
    $shtml = Html::a(!empty($v['alias']) ? $v['alias'] : $v['shipment_name'],['address','id'=>$v['id']]);

    if(!$v['flat']){
      $shtml .= '<span>('.Html::a(Yii::t('app','Set Default').')',['set-default','id'=>$v['id']],['data-id'=>$v['id']]).'</span></li>';
      $shtml .= '<span>'.Html::a(Yii::t('app','Delete'),['addressdelete','id'=>$v['id']],['data-id'=>$v['id'],'onclick'=>'javascript:ADDR.delete("'.$v['id'].'")']).'</span></li>';
    }
    echo Html::tag('p','>>'.$shtml,['class'=>'acount-answer']);
  }
  ?>

</div>

<?php $form = ActiveForm::begin(['id' => 'address-form']); ?>

<p class="address-caption"><?php echo $useraddr->isNewRecord ? 'Add Address' : 'Update';?></p>

<div class="row">

  <div class="col-sm-6">
    <div class="row form-group">
      <div class="col-sm-4 control-lable">Address:</div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($useraddr,'shipment_addr',['class'=>'acount-input','id'=>'shipment_addr','style'=>'text-transform:capitalize;','maxlength'=> 24])?>
        <span class="control-icon"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable"></div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($useraddr,'shipment_addr2',['class'=>'acount-input','id'=>'shipment_addr2','style'=>'text-transform:capitalize;','maxlength'=> 24])?>
      </div>
    </div>

    <div class="row form-group hide">
      <div class="col-sm-4 control-lable"></div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($useraddr,'shipment_addr3',['class'=>'acount-input','id'=>'shipment_addr3','style'=>'text-transform:capitalize;','maxlength'=> 24])?>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable">Name:*</div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($useraddr,'shipment_name',['class'=>'acount-input','id'=>'alias'])?>
        <span class="control-icon"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable">Phone Number:*</div>
      <div class="col-sm-8 control">
        <?=Html::activeTextInput($useraddr,'shipment_phone',['class'=>'acount-input','id'=>'alias'])?>
        <span class="control-icon"></span>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-sm-4 control-lable">City / Town:*</div>
      <div class="col-sm-8 control">
        <?php
        $map_calculation = \common\models\Config::getConfig('map_calculation');
        if ($map_calculation == 0){
          ?>
          <?=Html::activeDropdownlist($useraddr,'shipment_city',getShipmentPostcode(),['class'=>'acount-input','id'=>'alias'])?>
          <?php } elseif ($map_calculation == 1){ ?>
            <?=Html::activeTextInput($useraddr,'shipment_city',['class'=>'acount-input','id'=>'alias'])?>
            <?php } ?>
            <span class="control-icon"></span>
          </div>
        </div>
        <div class="row form-group">
          <div class="col-sm-4 control-lable">Postcode:*</div>
          <div class="col-sm-8 control">
            <?php
            $map_calculation = \common\models\Config::getConfig('map_calculation');
            if ($map_calculation == 0){
              ?>
              <?=Html::activeTextInput($useraddr,'shipment_postcode2',['class'=>'acount-input','style'=>'text-transform:uppercase;','id'=>'alias','maxlength'=> 8])?>
              <?php } elseif ($map_calculation == 1){ ?>
                <?=Html::activeDropdownList($useraddr,'shipment_postcode',getShipmentPostcode(),['class'=>'postcode-input','id'=>'alias'])?>
                <?=Html::activeTextInput($useraddr,'shipment_postcode2',['class'=>'postcode2-input','id'=>'alias','placeholder'=>'last 3 characters','maxlength'=> 3])?>
                <?php } ?>
                <span class="control-icon"></span>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-sm-4 control-lable">What is it (e.g. Home)?</div>
              <div class="col-sm-8 control">
                <?=Html::activeTextInput($useraddr,'alias',['class'=>'acount-input','id'=>'alias'])?>
                <span class="control-icon"></span></div>
              </div>



              <div class="row form-group">
                <div class="col-sm-4 control-lable"></div>
                <div class="col-sm-8 control">
                  <?php echo Html::submitButton($useraddr->isNewRecord ? Yii::t('app','Store new address') : Yii::t('app','Update'),['class'=>'save-btn acount-input'])?>
                </div>
              </div>

            </div>

            <div class="col-sm-6">
              <?php
              $errors = $useraddr->getErrors();
              if(!empty($errors)){

                echo '<ul style="color: rgb(203, 0, 0);">';
                foreach ($errors as $key => $value) {
                  echo Html::tag('li',$value['0']);
                }
                echo '</ul>';
              }
              ?>
            </div>
          </div>
          <?php ActiveForm::end(); ?>
