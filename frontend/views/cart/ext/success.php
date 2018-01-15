<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Config;
use yii\bootstrap\ActiveForm;
?>

        <div class="payorder-menu confirm-order">
           <ul class="payorder-list">
              <?php
              $total = 0;
      if(isset($cart)&&!empty($cart)):?>

      <?php foreach ($cart as $k => $v) {

        $shtml = Html::beginTag('li');

        // 初始化子产品的內容
        $child_html = '';

        $options_html = '';

        if(isset($v['goods_options'])&&!empty($v['goods_options'])){
          foreach ($v['goods_options'] as $_k => $_v) {

          // 单选产品时
          if(isset($_v['group']['options_type'])&&$_v['group']['options_type']=='checkbox'){

              $sshtml = Html::tag('div','+&nbsp;'.($_v['quanity']*$v['quanity']).'&nbsp;×&nbsp;'.$_v['name'],['class'=>'payorder-name']);
              $sshtml .= Html::tag('div','',['class'=>'order-quanity']);
              $sshtml .= Html::tag('div',Config::moneyFormat($_v['price']*$_v['quanity']*$v['quanity']),['class'=>'payorder-preprice']);

              $options_html .= Html::tag('div',$sshtml);
              $options_html .= '<div class="clearfix"></div>';

          }else{

            if($_v['options']['price_prefix']=='+'){
                $v['price'] += $_v['price'];
              }else{
                $v['price'] -= $_v['price'];
              }

              $sshtml = Html::tag('div',$_v['name'],['class'=>'payorder-name']);
              $sshtml .= Html::tag('div','',['class'=>'order-quanity']);
              $options_html .= Html::tag('div',$sshtml);
              $options_html .= '<div class="clearfix"></div>';

          }

          }
        }


        $goods_subtotal = $v['price']*$v['quanity'];
        // var_dump($goods_subtotal);
        $goods_subtotal = ($v['price']=='0'&& isset($v['options']) && !isset($v['required'])) ? '' : $v['price'];
        $goods_quanity = ($v['price']=='0'&& isset($v['options']) && !isset($v['required'])) ? '' : $v['quanity'].' x ';
        $goods_subtotal = (empty($goods_subtotal) ? '' : Config::moneyFormat($v['price']*$v['quanity']));

        $shtml .= Html::tag('div',$goods_quanity.$v['name'],['class'=>'payorder-name']);
        $shtml .= Html::tag('div','&nbsp;&nbsp;'.$goods_subtotal,['class'=>'payorder-preprice']);
        // $shtml .= Html::tag('div','×'.$v['quanity'].'&nbsp;&nbsp;'.Config::moneyFormat($v['subtotal']),['class'=>'payorder-preprice']);
        $shtml .= Html::tag('div','',['class'=>'clearfix']);


        // 显示有子产品
        $shtml .= $child_html;

        // 显示多选项目
        $shtml .= $options_html;

        $shtml .= Html::endTag('li');
        $total +=$v['subtotal'];
        echo $shtml;

      }?>

      <?php endif;?>
           </ul>
        </div>
        <div class="t-total">
          <p class="pay-subtotal">Subtotal <span class="to-price"><?=sprintf("%.2f",$total);?></span></p>

          <?php
          $free_goods = null;
          $free_ship_flag = false;
          if(!empty($list['coupon'])):
          foreach ($list['coupon'] as $k => $v) {
            $shtml = '';

            if ($v['flat_coup']=='3') {
              // 滿就免運費
              $shipment_postcode['price'] = 0;
              $free_ship_flag = true;
              continue;
            }else if ($v['flat_coup']=='5') {
              // 送餐 $v['memo']
              $free_goods = $v;
              $shtml = Html::tag('div','FREE '.$order->additional,['class'=>'col-sm-8']);
              $shtml .= Html::tag('div',Config::currencyMoney(0),['class'=>'col-sm-4 e-price t-right']);
              $shtml = Html::tag('div',$shtml,['class'=>'row tal-row']);
              continue;
            }


            $last_total = $total;
            $total = ($v['type']=='0') ? $total*$v['coup_value'] : ($total - $v['coup_value']);
            $discount = ($v['type']=='0') ? ((1-$v['coup_value'])*100) . '% Off' : '-'.$v['coup_value'];

            $shtml = Html::tag('div',''.$v['name'].' '.$discount,['class'=>'col-sm-8']);

            if($v['flat_coup']!='3'&&$v['flat_coup']!='5'){
              $shtml .= Html::tag('div','-'.Config::currencyMoney($last_total-$total),['class'=>'col-sm-4 e-price t-right']);
              $shtml = Html::tag('div',$shtml,['class'=>'row tal-row']);
              echo $shtml;
            }

          }
          endif;
          ?>


            <?php if(!empty($order->card_fee)):
                // service charge
                $total += $order->card_fee;
                echo '<p>Service Charge <span class="to-price">'.Config::moneyFormat($order->card_fee).'</span></p>';
            endif;?>

            <?php if($order->order_type=='deliver'):
                $total +=$shipment_postcode['price'];
            ?>
                <p><?=isset($free_ship_flag)&&$free_ship_flag ? 'FREE Delivery' : 'Delivery fee ';?><span class="to-price"><?=sprintf("%.2f",$shipment_postcode['price']);?></span></p>
            <?php endif;?>

                <p class="pay-paidtotal">Total<span class="to-price"><?=Config::currencyMoney($total);?></span></p>


        </div>
