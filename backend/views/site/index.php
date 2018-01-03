<?php
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
use common\models\Order;
use common\models\Config;
//$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">

    </div>

    <div class="body-content">

    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6"><div class="tile">
            <?=Html::tag('div',\Yii::t('app','Total Orders'),['class'=>'tile-heading'])?>
            <div class="tile-body"><i class="glyphicon glyphicon-shopping-cart"></i>


                <?php if(Yii::$app->user->identity->power=='admin'){
                    echo '<h2 class="pull-right">'.$count['order'].'</h2>';
                }; ?>

            </div>
            <div class="tile-footer">
                <?=Html::a(\Yii::t('app','View more...'),['/order/default/index'])?>
            </div>
        </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6"><div class="tile">
            <?=Html::tag('div',\Yii::t('app','Total Sales'),['class'=>'tile-heading'])?>
            <div class="tile-body"><i class="glyphicon glyphicon-credit-card"></i>

              <?php if(Yii::$app->user->identity->power=='admin'){
                  echo '<h2 class="pull-right">'.$count['order'].'</h2>';
              }; ?>

            </div>
            <div class="tile-footer">
                <?=Html::a(\Yii::t('app','View more...'),['/order/default/index'])?>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6"><div class="tile">
            <?=Html::tag('div',\Yii::t('app','Total Customers'),['class'=>'tile-heading'])?>
            <div class="tile-body"><i class="glyphicon glyphicon-user"></i>
            <h2 class="pull-right"><?=$count['user']?></h2>
            </div>
            <div class="tile-footer">
                <?=Html::a(\Yii::t('app','View more...'),['/member/user/index'])?>
            </div>
        </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6"><div class="tile">
            <?=Html::tag('div',\Yii::t('app','Total Goods'),['class'=>'tile-heading'])?>
            <div class="tile-body"><i class="glyphicon glyphicon-list-alt"></i>
                <h2 class="pull-right"><?=$count['goods']?></h2>
            </div>
            <div class="tile-footer">
                <?php if(Yii::$app->user->identity->power=='admin'){ echo '<a href="?r=goods/goods/index">View more...</a>'; }
                else if(Yii::$app->user->identity->power=='user') {
                  echo '-';
                }; ?>
            </div>
            </div>
        </div>
    </div>
    <!-- Enable this to see all orders included archived -->
    <!-- <div class="row">
        <div class="order-view">
        <table class="table table-bordered">
            <tr>
            <th>#</th>
            <th><?=\Yii::t('info','Order No.')?></th>
            <th><?=\Yii::t('info','Member ID')?></th>
            <th><?=\Yii::t('app','Total Price')?></th>
            <th><?=\Yii::t('label','Payment Status')?></th>
            <th><?=\Yii::t('info','Add Date')?></th>
            </tr>
            <?php
            if(isset($order)&&is_array($order)):
                for ($i=0; $i <count($order) ; $i++) {

                    $shtml = '<tr>';
                    $shtml .= '<td>'.$i.'</td>';
                    $shtml .= '<td>#'.(!empty($order[$i]['order_no']) ? Config::orderFormat($order[$i]['order_no']) : $order[$i]['order_id']).'</td>';
                    $shtml .= '<td>'.$order[$i]['member_id'].'</td>';
                    $shtml .= '<td>'.$order[$i]['total'].'</td>';
                    $shtml .= '<td>'.Order::getPaymentStatus($order[$i]['order_status'],$order[$i]['payment_type'],$order[$i]['order_type']).'</td>';
                    $shtml .= '<td>'.date('Y-m-d H:i:s',$order[$i]['add_date']).'</td>';
                    $shtml .= '</tr>';
                    echo $shtml;
                }
            else:
                echo '<tr><td colspan="6">'.\Yii::t('app','No Results').'</td></tr>';
            endif;
            ?>

        </table>

        </div>
    </div> -->
    </div>
</div>
<style type="text/css">
    /* Tiles */
.tile {
    margin-bottom: 15px;
    border-radius: 3px;
    background-color: #279FE0;
    color: #FFFFFF;
    transition: all 1s;
}
.tile:hover {
    opacity: 0.95;
}

.tile a {
    color: #FFFFFF;
}
.tile-heading {
    padding: 5px 8px;
    text-transform: uppercase;
    background-color: #1E91CF;
    color: #FFF;
}
.tile .tile-heading .pull-right {
    transition: all 1s;
    opacity: 0.7;
}
.tile:hover .tile-heading .pull-right {
    opacity: 1;
}
.tile-body {
    padding: 15px;
    color: #FFFFFF;
    line-height: 48px;
}
.tile .tile-body i {
    font-size: 50px;
    opacity: 0.3;
    transition: all 1s;
}
.tile:hover .tile-body i {
    color: #FFFFFF;
    opacity: 1;
}
.tile .tile-body h2 {
    font-size: 42px;
}
.tile-footer {
    padding: 5px 8px;
    background-color: #3DA9E3;
}
</style>
