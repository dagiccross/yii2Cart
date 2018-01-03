<?php
// 用于checkout的confirm页和手机的info页
use yii\helpers\Html;
use common\models\Config;
?>
<div class="payorder-menu">
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


<div class="paytotal">
  <p class="pay-subtotal">Subtotal <span class="to-price"><?=Config::moneyFormat($total);?></span></p>

  <?php if(isset($list['free_goods'])):
    // 4滿就送餐
    // echo '<p><span>FREE '.$list['free_goods']['memo'].'</span><span class="to-price"> '.Config::moneyFormat(0).'</span></p>';

    echo '<p><span>FREE '.$post_info['cart']['additional'].'</span><span class="to-price"> '.Config::moneyFormat(0).'</span></p>';
  endif;?>

  <?php if(isset($list['free_first_discount'])):
    // 1首单优惠
    $last_total = $total;
    $total = ($list['free_first_discount']['type']=='0') ? $list['free_first_discount']['coup_value']*$total : $list['free_first_discount']['coup_value'];
    $discount = ($list['free_first_discount']['type']=='0') ? ((1-$list['free_first_discount']['coup_value'])*100) . '% Off' : $list['free_first_discount']['coup_value'];

    echo '<p>'.$list['free_first_discount']['name'].' '.$discount.'<span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></p>';
  endif;?>

  <?php if(isset($list['coupon'])):
    // 2优惠卷
    $last_total = $total;
    $total = ($list['coupon']['type']=='0') ? $total*$list['coupon']['coup_value'] : ($total - $list['coupon']['coup_value']);
    $discount = ($list['coupon']['type']=='0') ? ((1-$list['coupon']['coup_value'])*100) . '% Off' : $list['coupon']['coup_value'];

    echo '<p>'.$list['coupon']['name'].' '.$discount.'<span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></p>';

  endif;?>

  <?php if(isset($list['free_up'])&&!empty($list['free_up']['coup_value'])):
    // 3满就优惠
    $last_total = $total;
    $total = ($list['free_up']['type']=='0') ? $total*$list['free_up']['coup_value'] : ($total - $list['free_up']['coup_value']);
    $discount = ($list['free_up']['type']=='0') ? ((1-$list['free_up']['coup_value'])*100) . '% Off' : $list['free_up']['coup_value'];

    echo '<p>'.$list['free_up']['name'].' '.$discount.' <span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></p>';

  endif;?>

  <?php if(isset($list['free_member'])&&!empty($list['free_member']['coup_value'])):
    // 5會員打折
    $last_total = $total;
    $total = ($list['free_member']['type']=='0') ? $total*$list['free_member']['coup_value'] : ($total - $list['free_member']['coup_value']);
    $discount = ($list['free_member']['type']=='0') ? ((1-$list['free_member']['coup_value'])*100) . '% Off' : $list['free_member']['coup_value'];

    echo '<p>'.$list['free_member']['name'].' '.$discount.' <span class="to-price"> - '.Config::moneyFormat($last_total-$total).'</span></p>';

  endif;?>

  <?php if(!empty($list['payment']['card_fee'])):
    // 4信用卡手续费
    $total += $list['payment']['card_fee'];
    echo '<p>Card Fee <span class="to-price"> '.sprintf("%.2f",$list['payment']['card_fee']).'</span></p>';

  endif;?>

  <?php
  $map_calculation = \common\models\Config::getConfig('map_calculation');
  $addr_city = \common\models\Config::getConfig('city');
  if ($map_calculation == 0){
    $shipment_city_new = $list['shipment']['shipment_postcode'];
    $shipment_postcode2_new = strtoupper($list['shipment']['shipment_postcode2']);
  } elseif ($map_calculation == 1){
    $shipment_city_new = $list['shipment']['shipment_city'];
    $shipment_postcode2_new = strtoupper($list['shipment']['shipment_postcode'].$list['shipment']['shipment_postcode2']);
  }

  if($post_info['cart']['send']=='deliver'):?>

  <p> <?=isset($list['free_ship'])&&!empty($list['free_ship']) ? 'FREE Delivery' : 'Delivery fee';?><span class="to-price"><?=sprintf("%.2f",$list['shipment_price']);?></span></p>

  <p class="pay-subtotal">Total<span class="to-price"><?=Config::currencyMoney($total+$list['shipment_price']);?></span></p>
</div>
<p class="spicy"><?=$post_info['cart']['note']?></p>


<p class="detail-address">
  <?=$list['shipment']['shipment_addr1'].', '.(!empty($list['shipment']['shipment_addr2']) ? $list['shipment']['shipment_addr2'].',' : '').' '.$addr_city.', '.$shipment_postcode2_new; ?>
</p>
<p><?=Html::img(showImg(IMG_URL.'/deliver.png'));?>Delivery time requested: <?=$post_info['cart']['time']?></p>
<?php else:?>

  <p class="pay-subtotal">Total<span class="to-price"><?=Config::moneyFormat($total);?></span></p>
</div>
<p class="spicy"><?=$post_info['cart']['note']?></p>
<p class="detail-address"><?=$this->context->getConfig('address').' '.$this->context->getConfig('city').'<br/>'.$this->context->getConfig('postcode')?></p>
<p><?=Html::img(showImg(IMG_URL.'/deliver.png'));?>Collection time requested: <?=$post_info['cart']['time']?></p>
<?php endif;?>
</div>
