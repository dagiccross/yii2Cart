<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div role="tabpanel" class="tab-pane <?php if($type=='password') echo 'active';?>" id="messages">
  <div class="change-password">
    <?php $form = ActiveForm::begin(['id' => 'password-form']); ?>
    <table>
  <tr>
    <td><?=Yii::t('app','Old Password')?>：</td>
    <td><?=Html::passwordInput('Password[old]','',['class'=>'pw1'])?></td>
  </tr>
  <tr>
    <td><?=Yii::t('app','New Password')?>：</td>
    <td><?=Html::passwordInput('Password[new]','',['class'=>'pw2'])?></td>
  </tr>
  <tr>
    <td><?=Yii::t('app','Confirm Password')?>：</td>
    <td><?=Html::passwordInput('Password[confirm]','',['class'=>'pw3'])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="comform-submit2" name="" type="submit" value="<?=Yii::t('app','Submit')?>"></td>
  </tr>
</table>

<?php ActiveForm::end(); ?>
              
            </div>
             </div>

            