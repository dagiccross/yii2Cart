<?php
use frontend\extensions\Menu;
use yii\helpers\Html;
use common\models\Config;
// var_dump($category_list);
?>
<style type="text/css">
.goods_options{clear: both;}
.goods_options li{float: left;width: 100%;}
.goods_options li:last-child{border-bottom: 1px dotted #999;}
</style>
<section class="main">
  <div class="col-sm-3 home-menu">
    <div id="home-menu-left">


      <div class="menu-down">
        <?= Menu::widget(['type'=>'side']) ?>
      </div>
    </div>
  </div>
  <div class="col-sm-5 home-main" style="padding:0;">

    <?php if(is_array($category_list)):?>
      <?php foreach ($category_list as $k => $v) {
        ?>
        <div class="online-menu"  id="mainmenu<?php echo $v['id']?>">
          <?=Html::tag('h2',$v['name'])?>
          <?=Html::tag('p',Html::tag('em',$v['description']));?>
          <ul class="soup-menu">
            <?php
            foreach ($goods_list as $_k => $_v) {
              // var_dump($_v);
              if(in_array($v['id'], $_v['cat_id'])){
                $goods_attr = \common\models\Goodsattr::find()->where(['goods_id'=>$_v['id'],
                'attr_name'=>'attr',
                'language'=>\Yii::$app->language])->asArray()->one();
                $kouwei = @unserialize($goods_attr['attr_value']);

                $kouwei_data = '';

                if(!empty($kouwei)&&is_array($kouwei)){
                  for ($j=0; $j <count($kouwei) ; $j++) {

                    if($kouwei[$j]['name']=='Vegetable'&&$kouwei[$j]['options']=='1'){
                      $kouwei_data .=Html::tag('span',Html::img(IMG_URL.'/vip.png',['style'=>'width:20px']));
                    }else if($kouwei[$j]['name']=='Spicy'&&$kouwei[$j]['options']=='1'){
                      $kouwei_data .=Html::tag('span',Html::img(IMG_URL.'/lajiao.png',['style'=>'width:20px']));
                    }else if($kouwei[$j]['name']=='Peanut'&&$kouwei[$j]['options']=='1'){
                      $kouwei_data .=Html::tag('span',Html::img(IMG_URL.'/yico.png',['style'=>'width:20px']));
                    }
                  }
                }

                $discount_info = isset($_v['coupon_type']) ? '<span class="goods_discount">('.($_v['coupon_type']=='0' ? (1-$_v['coupon_price'])*100 . '% Off' : 'LESS '.Config::currencyMoney($_v['coupon_price']) ).')</span>' : '';
                $contentHtml = isset($_v['content'])&&!empty($_v['content']) ? $_v['content'] : '';
                $shtml = '<li>';

                $shtml .= '<div class="soup-name">';
                //find out webtemp
                $webtemp = \common\models\Config::getConfig('webtemp');
                if (!isset($webtemp)){ echo ' '; }
                elseif ($webtemp == 2){
                  if(!empty($_v['pic'])){ $shtml .= '<img class="img-rounded zoom" src="'.$_v['pic'].'" height="42" width="42"> '; } else {
                    $shtml .= '<img class="img-rounded" src="http://'.$_SERVER['SERVER_NAME'].'/uploads/noimg.jpg" height="42" width="42"> ';
                  }
                }

                $shtml .= $_v['title'].$kouwei_data.' '.$discount_info.'</div>';

                $ushtml = '';
                if($_v['price']=='0'&&!empty($_v['goods_options'])){

                  $ushtml = '<ul class="goods_options">';
                  foreach ($_v['goods_options'] as $_k_o => $goods_options) {
                    // $goods_options['price'] = isset($_v['coupon_type']) ? ($_v['coupon_type']=='0' ? $_v['coupon_price']*$goods_options['price'] : ($goods_options['price']-$_v['coupon_price'])) : $goods_options['price'];
                    $ushtml .= '<li>';
                    $ushtml .= '<div class="soup-name">'.$goods_options['name'].'</div>';
                    $ushtml .= '<div class="soup-price">'.Config::currencyMoney($goods_options['old_price']).'</div>';
                    $ushtml .= '<div class="add-number"><button class="add-btn" type="button" onclick="javascript:CART.add(\''.$_v['id'].'\',\''.$goods_options['g_options_id'].'\')">+</button></div>';
                    $ushtml .= '</li>';
                  }
                  $ushtml .= '</ul>';
                }else{
                  $shtml .= '<div class="soup-price">'.Config::currencyMoney($_v['old_price']).'</div>';
                  $shtml .= '<div class="add-number"><button class="add-btn" type="button" onclick="javascript:CART.add(\''.$_v['id'].'\')">+</button></div>';

                }
                $shtml .= '<div class="clearfix"></div>';
                $shtml .= Html::tag('div',Html::tag('em',$contentHtml,['style'=>'font-size: 13px;padding-right: 35px; display: block;']));
                if(!empty($ushtml)){
                  $shtml .= Html::tag('div',$ushtml,['style'=>'font-size: 13px;']);
                }
                $shtml .= '</li>';
                echo $shtml;
                // }else{
                // break;
              }

            }
            ?>
          </ul>
        </div>
        <?php
      }?>

    <?php endif;?>
  </div>

  <!-- 右边 -->
  <div class="col-sm-4 home-right">
    <?php //echo $this->render('ext/right_cart') ?>
  </div>
  <div class="clearfix"></div>
</section>

<style>
/*.home-right{width: 300px;}*/
.home-menu,.home-right{transition: all 1s ease 0s;}
.back-top {display:none;background:#ffc64e none repeat scroll 0% 0%;border: medium none; text-align: center; position: fixed; right: 30px; bottom: 10%; border-radius: 22px; width: 45px; height: 45px; line-height: 6px; padding-top: 3px; text-transform: capitalize; font-weight: 900; font-size: 12px;z-index: 1;}
.back-top a {color: #000;}
.back-top span {font-size: 23px;font-weight: bolder;}
@media (max-width: 768px) {
  .back-top { right: 10px; bottom: 50px; }
}

}
.zoom {
  -webkit-transition: all 0.35s ease-in-out;
  -moz-transition: all 0.35s ease-in-out;
  transition: all 0.35s ease-in-out;
  cursor: -webkit-zoom-in;
  cursor: -moz-zoom-in;
  cursor: zoom-in;
}

.zoom:hover,
.zoom:active,
.zoom:focus {
  /**adjust scale to desired size,
  add browser prefixes**/
  box-shadow: 0px 0px 2px #292727;

  left: 25%;
  -ms-transform: scale(5);
  -moz-transform: scale(5);
  -webkit-transform: scale(5);
  -o-transform: scale(5);
  transform: scale(5);
  position:relative;
  z-index:100;
}
</style>
<div class="back-top -hidden-xs -hidden-sm"><a href="#">
  <span class="glyphicon glyphicon-menu-up"></span><br>
  top</a>
</div>
<script>

</script>



<?php $this->beginBlock('site_product') ?>
jQuery(function($) {
  // scroll事件

  var menu_top = $(".main").offset().top;
  //var menu_top = $(".home-right").offset().top;
  var window_width = $(window).width();
  var window_height = $(window).height();
  var doc_height = $(document).height();
  var container_width = $(".container").width();
  var left_width = (window_width-container_width)/2;

  window.onscroll = function(){
    var action_top = $(".home-right").find("#cart-info").height();
    var right_top_1 = $(".home-right").find("#cart-info .right-t1").height();
    var right_top_2 = $(".home-right").find("#cart-info .off-show").height();
    var right_top_3 = $(".home-right").find("#cart-info .order-cart").height();
    var right_top_4 = $(".home-right").find("#cart-info .order-menu").height();
    var t = document.documentElement.scrollTop || document.body.scrollTop;
    var flag_top = menu_top + right_top_1 + right_top_2 + right_top_3 + right_top_4 + 200  - window_height;
    if(t < flag_top||t< menu_top){
      $(".home-menu").css({'top':0});
      $(".home-right").removeAttr('style');
    }
    //console.log(flag_top);
    if((t>= flag_top&&t>=menu_top)&&window_width>=768){
      var doc_height = $(window).height();
      var right_height = $(".home-right").height();
      var right_cha = Number(right_height-doc_height);
      //console.log("right_height:"+right_height+"doc_height:"+doc_height);

      $(".home-menu").css({'top':(t-menu_top)});
      if(doc_height>right_height){
        $(".home-right").css({'top':(t-menu_top)});
      }else{
        $(".home-right").css({'top':(t-menu_top-right_cha-10)});
      }

      //$(".home-right").css({'bottom':0,'position':'fixed','right':left_width});
    }
    if( t >= 350 ) {
      $(".back-top").css("display","block");
    }else{
      $(".back-top").css("display","none");
    }
  }
});



<?php $this->endBlock() ?>

<?php $this->registerJs($this->blocks['site_product'], \yii\web\View::POS_END); ?>
