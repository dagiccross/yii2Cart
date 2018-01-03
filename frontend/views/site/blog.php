<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\extensions\Pages;
use frontend\extensions\slider\Slider;

$w2 = [0,1,10,12,21,26];
$h2 = [1,4,10,11,19,21];
$c = [1,3,4,7,8,9,10,12,13,15,16,17,18,19,21,22,23,25,27,28,29];
$fangge = 30;
$count = count($data);

$jishu = count($c);
$paper = (int)($count/$jishu);
$yushu = $count%$jishu;

$paper = $yushu==0 ? $paper : ($paper+1);

?>
<section class="content-main">
<style type="text/css">
  /*.grid-item{border: 1px solid #ccc;}*/
</style>
<div>
  <?php echo Slider::widget(['key'=>'new_slider',
                                'options'=>['id'=>'myCarousel',
                                            'class'=>'carousel slide',
                                            'ol'=>true,
                                            'next'=>false]]);?>
</div>
  <h1 class="news-title">Branch Founding Ceremony of Dongguan<br/>
  Foreign-Invested Enterprises</h1>
<div class="what-we-do container width-10" >
 
  <div class="row">
    
    <div class="news-content">

      <div class="row">
        <div class="grid">
          <!-- width of .grid-sizer used for columnWidth -->
          <div class="grid-sizer"></div>

          <!-- <div class="grid-item item-w2 item-h" data="1">0</div>

          <div class="grid-item item-w2 item-h2" data="2">
            1
          </div>

          <div class="grid-item item-h" data="3">2</div>
          <div class="grid-item item-h" data="4">3</div>

          <div class="grid-item item-h2" data="5">
            4
          </div>

          <div class="grid-item item-h" data="6">
            5
          </div>

          <div class="grid-item item-h" data="7">
            6
          </div>

          <div class="grid-item item-h" data="8">
            7
          </div>
          <div class="grid-item item-h" data="9">
            8
          </div>
          <div class="grid-item item-h" data="10">
            9
          </div>

          <div class="grid-item item-w2 item-h2" data="11">
            10
          </div>
          <div class="grid-item item-h2" data="12">11</div>
          <div class="grid-item item-w2 item-h" data="13">
            12
          </div>

          <div class="grid-item item-h" data="14">13</div>
          <div class="grid-item item-h" data="15">
            14
          </div>

          <div class="grid-item item-h" data="16">
            15
          </div>
          <div class="grid-item item-h" data="17">
            16
          </div>
          <div class="grid-item item-h" data="18">
            17
          </div>
          <div class="grid-item item-h" data="19">
            18
          </div>
          <div class="grid-item item-h2" data="20">
            19
          </div>
          <div class="grid-item item-h" data="21">
            20
          </div>
          <div class="grid-item item-w2 item-h2" data="22">
            21
          </div>

          <div class="grid-item item-h" data="23">22</div>
          <div class="grid-item item-h" data="24">
            23
          </div>
          <div class="grid-item item-h" data="25">24</div>
          <div class="grid-item item-h" data="26">
              25
          </div>

          <div class="grid-item item-w2 item-h" data="27">
              26
          </div>

          <div class="grid-item item-h" data="28">
              27
          </div>
          <div class="grid-item item-h" data="29">
              28
          </div>
          
          <div class="grid-item item-h" data="30">
              29
          </div> -->

          <?php

          for ($i=0; $i <$paper ; $i++) { 

            for ($j=0; $j <$fangge ; $j++) {

              $item_h = in_array($j, $h2) ? 'item-h2' : 'item-h';
              $item_w = in_array($j, $w2) ? 'item-w2' : '';

              // $shtml_is =  in_array($j, $c) ? 1 : 0;

              $c_key = array_search($j, $c);

              $data_key = '';

              if($c_key!==false){
                $data_key = ($i*$jishu)+$c_key;
              }
              
              if($data_key>=$count){
                break 2;
              }

              $shtml = '';

              if(isset($data[$data_key])){

                $day = empty($data[$data_key]['modifydate']) ? date('j') : date('j',$data[$data_key]['modifydate']);
                $month = empty($data[$data_key]['modifydate']) ? date('M') : date('M',$data[$data_key]['modifydate']);
                $w_i = ($item_w=='item-w2') ? ($item_h=='item-h2' ? 1 : 4) : ($item_h=='item-h2' ? 2 : 3);
                $i_class ='items-text items-text'.$w_i;

                $blog_img = '';
                switch ($w_i) {
                  case 2:
                    $blog_img = isset($data[$data_key]['thumb_2h']) ? $data[$data_key]['thumb_2h'] : $data[$data_key]['image']['0'];
                    break;
                  case 4:
                    $blog_img = isset($data[$data_key]['thumb_2w']) ? $data[$data_key]['thumb_2w'] : $data[$data_key]['image']['0'];
                    break;
                  default:
                    $blog_img = isset($data[$data_key]['thumb']) ? $data[$data_key]['thumb'] : $data[$data_key]['image']['0'];
                    break;
                  
                }
                $shtml = Html::img($blog_img,['class'=>'img_10']).Html::tag('div',Html::tag('i',$day.' <br/>'.$month,['class'=>$i_class]),['class'=>'items-img animated zoomIn']);
                $shtml = Html::a($shtml,['/site/blogsingle','id'=>$data[$data_key]['id'],'tag'=>$list['tag_id'],'model'=>$list['category']['tag_id']]);
                $shtml = Html::tag('div',Html::tag('div',$shtml,['class'=>'items-wrap']),['class'=>'grid-item-wrap']);
              }

              $class = 'grid-item '.$item_w.' '.$item_h;

              echo Html::tag('div',$shtml,['class'=>$class]);
            }


          }
          ?>
        </div>

      </div>
    </div>
  </div>


  <!-- <div class="row">
    <ul class="list-link">      
      <li class="link-li">
        <?php echo Html::a(\Yii::t('app','Awards'),['/site/page','id'=>'87','path'=>'18_97'],['class'=>'animsition-link',
                                                    'data-animsition-out-class'=>'fade-out-left',]);?>
                <i class="arrow-right"></i>
            </li>
      <li class="link-li">
        <?php echo Html::a(\Yii::t('app','Event'),['/site/contact'],['class'=>'animsition-link',
                                                    'data-animsition-out-class'=>'fade-out-left']);?>
        <i class="arrow-right"></i>
      </li>
      <li class="link-li">
        <?php echo Html::a(\Yii::t('app','Media'),['/site/contact'],['class'=>'animsition-link',
                                                    'data-animsition-out-class'=>'fade-out-left']);?>
        <i class="arrow-right"></i>
      </li>
      <li class="link-li">
        <?php echo Html::a(\Yii::t('app','Corporate Responsibility'),['/site/contact'],['class'=>'animsition-link',
                                                    'data-animsition-out-class'=>'fade-out-left']);?>
        <i class="arrow-right"></i>
      </li>
    </ul>
  </div> -->

</div>
</section>
<script type="text/javascript">
  
</script>
<?php $this->beginBlock('what') ?>  
    $('.grid').isotope({
    // set itemSelector so .grid-sizer is not used in layout
    itemSelector: '.grid-item',
    percentPosition: true,
    masonry: {
      // use element for option
      columnWidth: '.grid-sizer'
    }
  });
<?php $this->endBlock() ?>

<?php $this->registerJsFile(SITE_URL."/frontend/web/js/isotope/isotope.pkgd.min.js", ['position'=>\yii\web\View::POS_END,'depends'=> [frontend\assets\AppAsset::className()]]); ?>
<?php $this->registerJs($this->blocks['what'], \yii\web\View::POS_END); ?>

