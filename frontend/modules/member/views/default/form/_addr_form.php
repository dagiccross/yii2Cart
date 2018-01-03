<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\UrlManager;
?>
<div role="tabpanel" class="tab-pane <?php if($type=='addr') echo 'active';?>" id="profile">
  <div class="shouhuo-list">
    <form id="showhuo-list-form">
      <table class="mem-shouhuo">
        <tr>
          <td>&nbsp;<?=Yii::t('app','Choose')?></td>
          <td>&nbsp;<?=Yii::t('app','Shipment Name')?></td>
          <td>&nbsp;<?=Yii::t('app','Shipment City')?></td>
          <td>&nbsp;<?=Yii::t('app','Shipment Addr')?></td>
          <td>&nbsp;<?=Yii::t('app','Shipment Phone')?></td>
          <td>&nbsp;<?=Yii::t('app','Action')?></td>
        </tr>
        <?php

        foreach ($addr as $k => $v) {
          $shtml = '<tr style=" border-bottom:1px dotted #ccc;" id="addr_tr_'.$v['id'].'">';
          $shtml .= '<td>'.Html::checkBox('check').'</td>';
          $shtml .= '<td>'.$v['shipment_name'].'</td>';
          $shtml .= '<td>'.$v['shipment_city'].'</td>';
          $shtml .= '<td>'.$v['shipment_addr'].'</td>';
          $shtml .= '<td>'.$v['shipment_phone'].'</td>';
          $shtml .= '<td>'.Html::a(Yii::t('app','Edit'),'javascript:;',['data-id'=>$v['id'],'onclick'=>'javascript:ADDR.edit("'.$v['id'].'")']).'</td></tr>';
          echo $shtml;
        }
        ?>
              
      </table>
    </form>
  </div> 


    <div class="address-content">
      <form name="form_addr_1" id="form_addr_1">
      <table border="0" class="address-table">
        <tr>
          <td>&nbsp;<?=Yii::t('app','Shipment Name')?>：</td>
          <td>&nbsp;<?=Html::textInput('Shipment[shipment_name]','',['class'=>'city','id'=>'shipment_name'])?></td>
          <td>&nbsp;<?=Html::hiddenInput('Shipment[id]','',['id'=>'shipment_id'])?></td>
        </tr>

        <tr>
          <td>&nbsp;<?=Yii::t('app','Shipment Addr')?>:</td>
          <td>&nbsp;<?=Html::textArea('Shipment[shipment_addr]','',['class'=>'shouhuo-address','id'=>'shipment_addr'])?></td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td>&nbsp;<?=Yii::t('app','Shipment Phone')?>:</td>
          <td>&nbsp;<?=Html::textInput('Shipment[shipment_phone]','',['class'=>'shouhuo-phone','id'=>'shipment_phone'])?></td>
          <td>&nbsp;<input onclick="javascript:ADDR.add('form_addr_1')" class="comform-submit" name="" type="button" value="<?=Yii::t('app','Submit')?>"></td>
        </tr>
      </table>
      
    </div>
    
    <div class="add-address">
      <input class="add-btn add-addr" name="" type="button" value="<?=Yii::t('app','Add Address')?>">
    </div>
  
</div>

<script type="text/javascript">
var i=2;
  function add_addr(){
    //i++;
    var shtml = '<form id="form_addr_'+i+'"><table border="0" class="address-table" style="margin-top:10px;">';
        shtml += '<tr>';
        shtml += '<td>&nbsp;<?=Yii::t('app','Shipment Name')?>：</td>';
        shtml += '<td>&nbsp;<?=Html::textInput('Shipment[shipment_name]','',['class'=>'city'])?></td>';
        shtml += '<td>&nbsp;</td>';
        shtml += '</tr>';

        shtml += '<tr>';
        shtml += '<td>&nbsp;<?=Yii::t('app','Shipment Addr')?>:</td>';
        shtml += '<td>&nbsp;<?=Html::textArea('Shipment[shipment_addr]','',['class'=>'shouhuo-address'])?></td>';
        shtml += '<td>&nbsp;</td>';
        shtml += '</tr>';
        
        shtml += '<tr>';
        shtml += '<td>&nbsp;<?=Yii::t('app','Shipment Phone')?>:</td>';
        shtml += '<td>&nbsp;<?=Html::textInput('Shipment[shipment_phone]','',['class'=>'shouhuo-phone'])?></td>';
        shtml += '<td>&nbsp;<input onclick="javascript:ADDR.add(\'form_addr_'+i+'\')" class="comform-submit" name="" type="button" value="<?=Yii::t('app','Submit')?>"></td>';
        shtml += '</tr>';
        shtml += '</table>';
      
      return shtml;

  }

</script>