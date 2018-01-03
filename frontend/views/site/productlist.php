<?php
use frontend\extensions\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\extensions\Paging;
?>
<div class="product-list">
  <h4 class="door-title"><?=$catmeta['name']?></h4>
  <div class="cate-tabcontent">
  <?php
  foreach ($data as $k => $v) {
    $sku_exist = common\models\Goodssku::find()->where(['goods_id'=>$v['id']])->exists();

    $shtml = '<div class="col-sm-4">
      <div class="product-wrap">
        <div class="product">
          <div class="product-pic">
            <a href="'.Url::to(['site/detail','id'=>$v['id']]).'">'.Html::img(showImg($v['pic']),['alt'=>$v['title']]).'</a>
          </div>
          <ol class="specail-title">
            <li>'.Html::a($v['title'],['/site/detail','id'=>$v['id']]).'</li>
            <li>'.Html::a($v['description']).'</li>
            <li>'.Html::a(\Yii::t('app','DETAILS').'<span class="glyphicon glyphicon-menu-right detail-icon"></span>',['detail','id'=>$v['id']]).'</li>
          </ol>
        </div>
        <div class="price"><span class="price1">$'.$v['price'].'</span>';
        if($sku_exist){
          $shtml .='<span class="cart2" onclick="javascript:window.location.href=\''.Url::to(['/site/detail','id'=>$v['id']]).'\'">'.Html::img(showImg(IMG_URL.'/cart.png'),['alt'=>'icon']).'</span>';
        }else{
          $shtml .='<span class="cart2" onclick="javascript:CART.add(\''.$v['id'].'\')">'.Html::img(showImg(IMG_URL.'/cart.png'),['alt'=>'icon']).'</span>';
        }
        $shtml .='</div>
      </div>
    </div>';
    echo $shtml;

  }
  ?>
  </div>
  <div class="clearfix"></div>
  <?php
  // var_dump($tab);
  ?>
  <?= Paging::widget(['pagination' => $pages,'tab'=>$tab]); ?>

</div>
