<?php
use frontend\extensions\Menu;
use yii\helpers\Html;

?>
<script type="text/javascript">
  var SKU = <?=json_encode($goods_sku)?>;
  var GOODS = {quanity:"<?=$goods['quanity']?>",sku:<?=json_encode($goods['sku'])?>}
</script>
<section class="about-main">
  <div class="col-sm-2 left-menu">
    <?= Menu::widget(['type'=>'side',
                'condition'=>'type=:type',
                'param'=>[':type'=>"product"],
                'title'=>Yii::t('app','CATEGORIES')]);?>

    <?= frontend\extensions\Specials::widget();?>
  </div>

  <!-- 右邊 -->

  <div class="col-sm-10 right-show">
           
           <h2 class="digital-h2">
            <ul class="right-nav">
              <li><?=\Yii::t('app','Home')?></li> <li>> </li>
              <li><?=(isset($category['name']) ? $category['name'] : '')?></li>
            </ul>
          </h2> 
          <?php
          // var_dump($goods);
          $flat_feature = '';
          ?>
            <div class="row secuty-detail">
            <div class="col-sm-4 digi-pic">
              <div class="digi-picture">
                <?=Html::img(showImg($goods['pic'],['alt'=>$goods['title']]))?>
              </div>
            </div>
            <div class="col-sm-8 digital-choose">
              <p class="digi-title"><?=$goods['title']?></p>
              <form id="order-add" onsubmit="return false;">
              <div class="row wrap-row">
                <div class="detail-col1"><?=\Yii::t('app','Price')?>:</div>
                <div class="detail-price">$<?=$goods['price']?></div>                
              </div>
              <div class="row wrap-row">
                <div class="detail-col1"><?=\Yii::t('app','Qty')?>:</div>
                <div class="detail-qty">
                  <button class="cut-num" type="button" onclick="CART.quanity('cart_quanity','-');">-</button>
                  <?=Html::textInput('Cart[quanity]',1,['class'=>'detail-num','id'=>'cart_quanity'])?>
                  <button class="add-num" type="button" onclick="CART.quanity('cart_quanity','+');">+</button></div>                
              </div>
              <?php if(isset($goods['group'])&&is_array($goods['group'])):?>
              <?php
                $size = 'size';
                $spec = 'shack';
                $i=0;
                

                foreach ($goods['group'] as $k => $v) {
                  if(isset($goods[$v['feature']])){
                    $flat_feature = 'feature';

                    echo '<div class="row wrap-row">';
                    echo Html::tag('div',$v['name'],['class'=>'detail-col1']);
                    echo '<div class="detail-'.(($i==0) ? $size : $spec).'">';
                  
                    foreach ($goods[$v['feature']] as $_k => $_v) {
                      // var_dump($v);
                      echo Html::tag('span',$_v['options'],['class'=>'size-box','data-id'=>$_v['fatt_id']]);
                    }
                  echo '</div></div>';
                  }
                  

                  $i++;
                  
                }
              ?>

              <?php endif;?>
              <?php
              echo Html::hiddenInput('Cart[goods_id]',$goods['id']);
              echo Html::hiddenInput('Cart[price]',$goods['price']);
              echo Html::hiddenInput('Cart[size]','');
              echo Html::hiddenInput('Cart[spec]','');
              echo Html::hiddenInput('Cart[flat_feature]',$flat_feature);
              echo Html::hiddenInput('Cart[extension]','');
              ?>
              <div CLASS="detail-note"></div>
              <div class="detail-comfirm-wrap">
                <button class="buynow-btn" type="button" onclick="CART.buy('order-add')"><?=\Yii::t('app','Buy now')?></button>
                <button class="buynow-btn" type="button" onclick="CART.addform('order-add')"><?=\Yii::t('app','Add to cart')?></button>
              </div>
              </form>
            </div>
          </div>
           <div class="col-sm-12 digital-detail">
             <?=showContent($goods['content'])?>
           </div>

           <div class="clearfix"></div>
           <div class="feature">
             <h2><?=\Yii::t('app','Features')?></h2>
             <?php
             if(isset($page['content'])){
              echo showContent($page['content']);
             }
             ?>
             <!-- <p class="feature-title">The Yale Digital Door Lock Collection offers smarter solution for your home. </p>
             <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para10.jpg"></div>
              <div class="para-name">Fingerprint/card/key/<br/>password 4 access methods</div>
              </div>
             <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para9.jpg"></div>
              <div class="para-name">Voice Guide</div>
            </div>
            <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para8.jpg"></div>
              <div class="para-name">In-line Scanning </div>
            </div>
            <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para7.jpg"></div>
              <div class="para-name">Fake Pin Code</div>
            </div>
             <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para6.jpg"></div>
              <div class="para-name">Automatic Locking</div>
            </div>
            <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para5.jpg"></div>
              <div class="para-name">Anti-Panic Egress <br/>with Safety Handle  </div>
            </div>
            <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para4.jpg"></div>
              <div class="para-name">Mechanical Key Override</div>
            </div>
             <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para3.jpg"></div>
              <div class="para-name">Alarm (Break/Damage)</div>
            </div>
            <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para2.jpg"></div>
              <div class="para-name">Low Battery and<br/>Emergency Power </div>
            </div>
            <div class="col-sm-4">
              <div class="para-pic"><img src="<?=IMG_URL?>/para1.jpg"></div>
              <div class="para-name">Remote Control<br/>(Optional)  </div>
            </div> -->
           </div>
          </div>
          <div class="clearfix"></div>

  <div class="clearfix"></div>
</section>
<style type="text/css">
  .size-box{
    cursor: pointer;
  }
</style>