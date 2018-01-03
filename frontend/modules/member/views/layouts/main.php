<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

use yii\bootstrap\Alert;
use frontend\extensions\Menu;

?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>

            <div class="row myacount-row">
              <div class="col-sm-5">
                <h2 data-time="<?php echo date('Y-m-d H:i:s')?>">My Account details</h2>
                <p class="info-a"><?=Html::a('Account Info',['default/index'])?></p>
                <p class="info-a"><?=Html::a('Address Book',['default/address'])?></p>            </div>
              <div class="col-sm-7">
                <h2>My Orders</h2>
                <p class="info-a"><?=Html::a('Order Overview',['default/order'])?></p>
                <p class="info-a"><?=Html::a('Ratings & Reviews',['default/ratings'])?></p>
              </div>
            </div>
            <div class="row myacount-row" id="member_content">
              <?= $content ?>
              <?php //$this->render('_base_form',['member'=>$member,'addr'=>$addr])?>
            </div>

             <!-- <div class="row">
              <div class="col-sm-12">
                <p class="pageback"><a href="javascript:;">[-]Page Feedback</a></p>
              </div>
             </div> -->




<?php $this->endContent(); ?>
