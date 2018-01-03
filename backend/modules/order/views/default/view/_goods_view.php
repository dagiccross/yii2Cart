<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Config;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
?>
<div class="order-view">
<table class="table table-bordered">
    <tr>
    <th>#</th>
    <th><?=\Yii::t('info','Name')?></th>
    <th><?=\Yii::t('app','Price')?></th>
    <th><?=\Yii::t('app','Quanity')?></th>
    <th><?=\Yii::t('app','Total Price')?></th>
    </tr>
    <?php
    if(isset($goods)&&is_array($goods)):
        $total = 0;
        // 產品
        for ($i=0; $i <count($goods) ; $i++) {

            $shtml = '<tr>';
            // $shtml .= '<td>'.$i.'</td>';
            $sku = empty($goods[$i]['sku_no']) ? $goods[$i]['goods_id'] : $goods[$i]['sku_no'];
            $shtml .= '<td>'.$sku.'</td>';

            $shtml .= '<td>'.$goods[$i]['name'];

            $price = $goods[$i]['price'];

            if(!empty($goods[$i]['goods_options'])){

                $ohtml = '<ul>';
                foreach ($goods[$i]['goods_options'] as $k => $v) {

                    $ohtml .= Html::beginTag('div',['class'=>'row']);

                    if(isset($v['options']['group']['options_type']) && $v['options']['group']['options_type']=='checkbox'){
                        $ohtml .= Html::tag('div','+ &nbsp;'.$goods[$i]['quanity'].'×'.$v['quanity'].'&nbsp;'.$v['name'],['class'=>'col-md-6']);
                        $ohtml .= Html::tag('div',$v['price'],['class'=>'col-md-3']);
                        $ohtml .= Html::tag('div','Extras',['class'=>'col-md-3']);
                    }else{
                        $ohtml .= Html::tag('div',$v['name'],['class'=>'col-md-9']);
                        if($v['options']['price_prefix']=='-'){
                            $price -= $v['price'];
                        }else{
                             $price += $v['price'];
                        }
                    }
                    $ohtml .= Html::endTag('div');
                }
                $ohtml .= '</ul>';
                $shtml .= $ohtml;
            }
            $shtml .= '</td>';
            $shtml .= '<td>'.Config::moneyFormat($price).'</td>';
            $shtml .= '<td>'.$goods[$i]['quanity'].'</td>';
            $shtml .= '<td>'.Config::moneyFormat($goods[$i]['subtotal']).'</td>';
            $shtml .= '</tr>';
            echo $shtml;
            $total += $goods[$i]['subtotal'];
        }

        $shtml = Html::tag('td','Total:',['colspan'=>4,'style'=>'text-align: right;']);
        $shtml .= Html::tag('td',Config::moneyFormat($total));
        echo Html::tag('tr',$shtml);

        // 打折
        $free_goods = '';

        if(!empty($list['coupon'])){
            foreach ($list['coupon'] as $k => $v) {

                $shtml = '';
                if ($v['flat_coup']=='3') {
                  $list['shipment_price'] = 0;
                  continue;
                }else if ($v['flat_coup']=='5') {
                  $free_goods = $v;
                  $shtml .= Html::tag('td','FREE '.$v['memo'],['colspan'=>'4','style'=>'text-align: right;']);
                  $shtml .= Html::tag('td','');
                  $shtml = Html::tag('tr',$shtml,['class'=>'tal-row']);
                  echo $shtml;
                  continue;
                }


                $last_total = $total;
                $total = ($v['type']=='0') ? $total*$v['coup_value'] : ($total - $v['coup_value']);
                $discount = ($v['type']=='0') ? ((1-$v['coup_value'])*100) . '% Off' : '-'.$v['coup_value'];

                if($v['flat_coup']=='4'){
                    // 首单优惠
                  $shtml = Html::tag('td',$v['name'].' '.$discount,['colspan'=>'4','style'=>'text-align: right;']);

                }else if ($v['flat_coup']=='0') {
                    // 优惠卷
                  $shtml = Html::tag('td',$v['name'].' '.$discount,['colspan'=>'4','style'=>'text-align: right;']);
                }else if ($v['flat_coup']=='2') {
                    // 满就送
                  $shtml = Html::tag('td',$v['name'].' '.$discount,['colspan'=>'4','style'=>'text-align: right;']);

                }else{
                  $shtml = Html::tag('td',''.$v['name'].' '.$discount,['colspan'=>'4','style'=>'text-align: right;']);

                }

                if($v['flat_coup']!='3'&&$v['flat_coup']!='5'){
                  $shtml .= Html::tag('td','-'.Config::moneyFormat($last_total-$total));
                  $shtml = Html::tag('tr',$shtml,['class'=>'tal-row']);
                  echo $shtml;
                }
            }
        }

        if($model->order_type=='deliver'){
            // 邮费
            $total = $total+$list['shipment_price'];
            $shtml = Html::tag('td','Delivery fee',['colspan'=>'4','style'=>'text-align: right;']);
            // $data = ($coupon['2']['type']=='0') ? ($coupon['2']['coup_value']*100).'%' : '-'.$coupon['2']['coup_value'];
            $shtml .= Html::tag('td',Config::moneyFormat($list['shipment_price']),['class'=>'col-sm-4 e-price t-right']);
            $shtml = Html::tag('tr',$shtml,['class'=>'tal-row ']);
            echo $shtml;
        }

        if($model->card_fee){
            // 手續費
            $total = $total+(float)$model->card_fee;
            $shtml = Html::tag('td','Card fee',['colspan'=>'4','style'=>'text-align: right;']);
            // $data = ($coupon['2']['type']=='0') ? ($coupon['2']['coup_value']*100).'%' : '-'.$coupon['2']['coup_value'];
            $shtml .= Html::tag('td',Config::moneyFormat($model->card_fee),['class'=>'col-sm-4 e-price t-right']);
            $shtml = Html::tag('tr',$shtml,['class'=>'tal-row ']);
            echo $shtml;
        }

        // 顯示所有總價
        $shtml = Html::tag('td','Total:',['colspan'=>4,'style'=>'text-align: right;']);
        $shtml .= Html::tag('td',Config::moneyFormat($total));
        echo Html::tag('tr',$shtml);
    endif;
    ?>

</table>

</div>
