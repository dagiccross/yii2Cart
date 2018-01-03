<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

            <div class="row myacount-row">
              <div class="col-sm-5">
                <h2>My Account details</h2>
                <p class="info-a"><?=Html::a('Account info',['default/index'])?></p>
                <p class="info-a"><a href="javascript:;" onclick="javascript:MEMBER.address();">Address Book</a></p>
              </div>
              <div class="col-sm-7">
                <h2>My Orders</h2>
                <p class="info-a"><a href="#">Order overiew</a></p>
                <p class="info-a"><a href="#">Ratings & reviews</a></p>
              </div>
            </div>
            <div class="row myacount-row" id="member_content">

              <?php //$this->render('_base_form',['member'=>$member,'addr'=>$addr])?>
            </div>
             
             <div class="row">
              <div class="col-sm-12">
                <p class="pageback"><a href="#">[-]Page Feedback</a></p>
              </div>
             </div>
             
