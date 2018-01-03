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

        if(isset($v['child'])){
          foreach ($v['child'] as $_k => $_v) {

            if($_v['price_prefix']=='+'){
                $v['price'] += $_v['price'];
            }else{
                $v['price'] -=$_v['price'];
            }

            $sshtml = Html::tag('div',$_v['name'],['class'=>'payorder-name']);
            $sshtml .= Html::tag('div','',['class'=>'order-quanity']);
            // $sshtml .= Html::tag('div','×'.$v['quanity'],['class'=>'order-quanity']);
            $child_html .= Html::tag('div',$sshtml);
            $child_html .= '<div class="clearfix"></div>';
          }
        }

        // 初始化多选项目时
        $options_html = '';

        if(isset($v['options'])){
          foreach ($v['options'] as $_k => $_v) {

            if($_v['group']['options_type']=='radio'){

              if($_v['price_prefix']=='+'){
                $v['price'] += $_v['price'];
              }else{
                $v['price'] -= $_v['price'];
              }
              // $goods['quanity'].'&nbsp;x&nbsp;'.
              $sshtml = Html::tag('div',$_v['name'],['class'=>'order-name1']);
              $sshtml .= Html::tag('div','',['class'=>'order-quanity']);
              $sshtml .= Html::tag('div','',['class'=>'pre-price1']);
              
            }else{
              $sshtml = Html::tag('div','+&nbsp;'.($_v['quanity']*$v['quanity']).'&nbsp;×&nbsp;'.$_v['name'],['class'=>'payorder-name']);
              $sshtml .= Html::tag('div','',['class'=>'order-quanity']);
              $sshtml .= Html::tag('div',Config::moneyFormat($_v['price']*$_v['quanity']*$v['quanity']),['class'=>'payorder-preprice']);
              // $sshtml .= Html::tag('div','×'.($_v['quanity']*$v['quanity']),['class'=>'order-quanity']);
              // $sshtml .= Html::tag('div',Config::moneyFormat($_v['price']*$_v['quanity']*$v['quanity']),['class'=>'pre-price1']);
              
            }
              
            $options_html .= Html::tag('div',$sshtml);
            $options_html .= '<div class="clearfix"></div>';
          }
        }

        $goods_subtotal = $v['price']*$v['quanity'];
        // var_dump($goods_subtotal);
        $goods_subtotal = ($v['price']=='0'&& isset($v['options']) && !isset($v['required'])) ? '' : $v['price'];
        $goods_quanity = ($v['price']=='0'&& isset($v['options']) && !isset($v['required'])) ? '' : $v['quanity'].' x ';
        $goods_subtotal = (empty($goods_subtotal) ? '' : Config::moneyFormat($v['price']*$v['quanity']));
              
        $shtml .= Html::tag('div',$goods_quanity.$v['title'],['class'=>'payorder-name']);
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
            
            <?php if(isset($list['free_goods'])):
              // 4滿就送餐 $list['free_goods']['memo']
              echo '<p><span>FREE '.$order->additional.'</span><span class="to-price">'.sprintf("%.2f",0).'</span></p>';
            endif;?>

            <?php if(isset($list['free_first_discount'])):
                // 1首单优惠
                $last_total = $total;
                $total = ($list['free_first_discount']['type']=='0') ? $list['free_first_discount']['coup_value']*$total : $list['free_first_discount']['coup_value'];

                $discount = ($list['free_first_discount']['type']=='0') ? ((1-$list['free_first_discount']['coup_value'])*100) . '% Off' : $list['free_first_discount']['coup_value'];
                
                echo '<p>'.$list['free_first_discount']['name'].' '.$discount.' <span class="to-price"> - '.sprintf("%.2f",($last_total-$total)).'</span></p>';
            endif;?>

            <?php if(isset($list['coupon'])):
                // 2优惠卷
                $last_total = $total;
                $total = ($list['coupon']['type']=='0') ? $total*$list['coupon']['coup_value'] : ($total - $list['coupon']['coup_value']);
                $discount = ($list['coupon']['type']=='0') ? ((1-$list['coupon']['coup_value'])*100) . '% Off' : $list['coupon']['coup_value'];
                
                echo '<p>'.$list['coupon']['name'].' '.$discount.' <span class="to-price"> - '.sprintf("%.2f",($last_total-$total)).'</span></p>';

            endif;?>

            <?php if(isset($list['free_up'])&&!empty($list['free_up']['coup_value'])):
                // 3满就优惠
                $last_total = $total;
                $total = ($list['free_up']['type']=='0') ? $total*$list['free_up']['coup_value'] : ($total - $list['free_up']['coup_value']);
                $discount = ($list['free_up']['type']=='0') ? ((1-$list['free_up']['coup_value'])*100) . '% Off' : $list['free_up']['coup_value'];

                echo '<p>'.$list['free_up']['name'].' '.$discount.' <span class="to-price"> - '.sprintf("%.2f",($last_total-$total)).'</span></p>';

            endif;?>

            <?php if(isset($list['free_member'])&&!empty($list['free_member']['coup_value'])):
              // 5會員打折
              $last_total = $total;
                $total = ($list['free_member']['type']=='0') ? $total*$list['free_member']['coup_value'] : ($total - $list['free_member']['coup_value']);
                $discount = ($list['free_member']['type']=='0') ? ((1-$list['free_member']['coup_value'])*100) . '% Off' : $list['free_member']['coup_value'];
                
                echo '<p>'.$list['free_member']['name'].' Free '.$discount.' <span class="to-price"> - '.sprintf("%.2f",($last_total-$total)).'</span></p>';

            endif;?>
                
            <?php if(!empty($order->card_fee)):
                // 4信用卡费用
                $total += $order->card_fee;
                echo '<p>Card Fee <span class="to-price">'.Config::moneyFormat($order->card_fee).'</span></p>';
            endif;?>

            <?php if($order->order_type=='deliver'):
                $total +=$shipment_postcode['price'];
            ?>
                <p><?=isset($list['free_ship'])&&!empty($list['free_ship']) ? 'FREE Delivery' : 'Delivery fee ';?><span class="to-price"><?=sprintf("%.2f",$shipment_postcode['price']);?></span></p>
            <?php endif;?>
                
                <p class="pay-paidtotal">Total<span class="to-price"><?=Config::currencyMoney($total);?></span></p>

            
        </div>
       