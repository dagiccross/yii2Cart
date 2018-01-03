<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div role="tabpanel" class="tab-pane <?php if($type=='base') echo 'active';?>" id="home">
  <?php $form = ActiveForm::begin(['id' => 'member-form']); ?>
    <table class="member-info">
      <tr>
        <td><?=Yii::t('app','Member Name')?>：</td>
        <td><?=$member->username?></td>
      </tr>
      <tr>
        <td><?=Yii::t('app','Email')?>：</td>
        <td><?=$member->email?></td>
      </tr>
      <tr>
        <td><?=Yii::t('app','Sex')?>：</td>
        <td>
        <?php
          echo Html::radio('User[sex]',($member->sex==1), ['value' => '1','class'=>'sex']);
          echo Html::label(Yii::t('app','Man'));
          echo ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
          echo Html::radio('User[sex]',($member->sex==0), ['value' => '0','class'=>'sex']);
          echo Html::label(Yii::t('app','Female'));
        ?></td>
      </tr>
      <tr>
        <td><?=Yii::t('app','BirthDate')?>：</td>
        <td>
          <select class="birth" name="year">
            <?php

            $birth = explode("-", $member->birthdate);
            $year = isset($birth['0']) ? $birth['0'] : '';
            $month = isset($birth['1']) ? $birth['1'] : '';
            $day = isset($birth['2']) ? $birth['2'] : '';

            for ($i=1900; $i < (int)date('Y'); $i++) { 
              $select = ($year==$i) ? 'selected' : '';
              echo '<option value="'.$i.'" '.$select.'>'.$i.Yii::t('app','year').'</option>';
            }

            ?>
          </select>
          <select class="birth" name="month">
            <?php
            for ($i=1; $i < 13; $i++) { 
              $select = ($month==$i) ? 'selected' : '';
              echo '<option value="'.$i.'" '.$select.'>'.$i.Yii::t('app','month').'</option>';
            }

            ?>
            </select>
          <select class="birth" name="day">
            <?php
            for ($i=1; $i < 32; $i++) { 
              $select = ($day==$i) ? 'selected' : '';
              echo '<option value="'.$i.'" '.$select.'>'.$i.Yii::t('app','day').'</option>';
            }

            ?>
          </select></td>
        </tr>
      <tr>
        <td>電話：</td>
        <td><?=Html::activeTextInput($member,'phone',['class'=>'mem-phone'])?></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" name="comfirm" value="<?=Yii::t('app','Submit')?>" class="comfirm-btn"></td>
      </tr>
    </table>
  <?php ActiveForm::end(); ?>
</div>


            