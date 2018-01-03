<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\extensions\Pages;
use frontend\extensions\slider\Slider;

$h2 = [0,1,4,11,12,15,21];
$w2 = [1,10,12,21];
$c = [1,4,5,6,7,8,10,12,14,15,16,17,18,19,20,21,23,25,26,27,28];

$count = count($data);

$jishu = 21;
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
    <div class="col-sm-12 cate-news">
      <div class="newscat-wrap">
        <div class="input-group news-search">      
            <input type="text" class="form-control" placeholder="Search for...">      
            <span class="input-group-btn">        
              <button class="btn btn-default search-btn" type="button"><i class="icon-search"></i></button>      
            </span>    
           </div><!-- /input-group -->
          <?php echo Html::button(\Yii::t('app','ALL GALLERIES').' ('.$count.')',['class'=>'cate-btn','onclick'=>'javascript:window.location.href="'.Url::to(['/site/list','id'=>$list['category']['id']]).'";']);?>
          <?php if(isset($list['tag'])):

          foreach ($list['tag'] as $k => $tag) {

            echo Html::button($tag.' ('.$list['tag_count'][$k].')',['class'=>'cate-btn','onclick'=>'javascript:window.location.href="'.Url::to(['/site/list','id'=>$list['category']['id'],'tag'=>$k]).'";']);


          }
          endif;
          ?>
          </div> 
        </div>
  </div>
  <div class="row">
    
    <div class="news-content">

      <div class="row">
        <div class="grid">
          <!-- width of .grid-sizer used for columnWidth -->
          <div class="grid-sizer"></div>

          <?php

          for ($i=0; $i <$paper ; $i++) { 

            for ($j=0; $j <31 ; $j++) {

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
                $day = date('j',$data[$data_key]['modifydate']);
                $month = date('M',$data[$data_key]['modifydate']);

                $i_class = ($item_w=='item-w2') ? ($item_h=='item-h2' ? 'items-text items-text1' : 'items-text items-text4') : ($item_h=='item-h2' ? 'items-text items-text2' : 'items-text items-text3');
                $shtml = Html::img($data[$data_key]['image']['0'],['class'=>'img_10']).Html::tag('div',Html::tag('i',$day.' <br/>'.$month,['class'=>$i_class]),['class'=>'items-img animated zoomIn']);
                $shtml = Html::a($shtml,['/site/listsingle','id'=>$data[$data_key]['id'],'tag'=>$list['tag_id'],'model'=>$list['category']['tag_id']]);
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
<!-- 
<div class="grid-item item-h2" data="1"></div>

          <div class="grid-item item-w2 item-h2" data="2">
            <div class="grid-item-wrap">
            <div class="items-wrap">
              <a href="news_info.html">
              <img class="img_10" src="<?=IMG_URL?>/size1.png">
                 <div class="items-img"><i class="items-text items-text1 zoomIn animated">12 <br>DEC</i></div>
                 </a>
            </div>
            </div>
            
          </div>
          <div class="grid-item item-h" data="3"></div>
          <div class="grid-item item-h" data="4"></div>

          <div class="grid-item item-h2" data="5">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size3.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text2">12 <br>DEC</i></div>
                </a>
              </div>
            </div>
          </div>

          <div class="grid-item item-h" data="6">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>

          <div class="grid-item item-h" data="7">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>

          <div class="grid-item item-h" data="8">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="9">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="10">
            
          </div>

          <div class="grid-item item-w2 item-h" data="11">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <img class="img_10" src="<?=IMG_URL?>/size4.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text4">12 <br>DEC</i></div>
              </div>
            </div>
          </div>
          <div class="grid-item item-h2" data="12"></div>
          <div class="grid-item item-w2 item-h2" data="13">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <img class="img_10" src="<?=IMG_URL?>/w2hw2_2.png">
                   <div class="items-img animated zoomIn"><i class="items-text items-text1">12 <br>DEC</i></div>
              </div>
            </div>
          </div>

          <div class="grid-item item-h" data="14"></div>
          <div class="grid-item item-h" data="15">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>

          <div class="grid-item item-h2" data="16">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size3.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text2">12 <br>DEC</i></div>
                </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="17">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="18">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="19">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="20">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-h" data="21">
            <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
            </div>
          </div>
          <div class="grid-item item-w2 item-h2" data="22">
            <div class="grid-item-wrap">
            <div class="items-wrap">
              <img class="img_10" src="<?=IMG_URL?>/size1.png">
                 <div class="items-img animated zoomIn"><i class="items-text items-text1">12 <br>DEC</i></div>
            </div>
            </div>
          </div>

          <div class="grid-item item-h" data="23"></div>
          <div class="grid-item item-h" data="24">
            <div class="grid-item-wrap">
                <div class="items-wrap">
                  <a href="news_info.html">
                  <img class="img_10" src="<?=IMG_URL?>/size2.png">
                  <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
                </a>
                </div>
              </div>
          </div>
          <div class="grid-item item-h" data="25"></div>
          <div class="grid-item item-h" data="26">
              <div class="grid-item-wrap">
                <div class="items-wrap">
                  <a href="news_info.html">
                  <img class="img_10" src="<?=IMG_URL?>/size2.png">
                  <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
                </a>
                </div>
              </div>
          </div>

          <div class="grid-item item-h" data="27">
              <div class="grid-item-wrap">
              <div class="items-wrap">
                <a href="news_info.html">
                <img class="img_10" src="<?=IMG_URL?>/size2.png">
                <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
              </a>
              </div>
              </div>
          </div>

          <div class="grid-item item-h" data="28">
              <div class="grid-item-wrap">
                <div class="items-wrap">
                  <a href="news_info.html">
                  <img class="img_10" src="<?=IMG_URL?>/size2.png">
                  <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
                </a>
                </div>
              </div>
          </div>
          <div class="grid-item item-h" data="29">
              <div class="grid-item-wrap">
                <div class="items-wrap">
                  <a href="news_info.html">
                  <img class="img_10" src="<?=IMG_URL?>/size2.png">
                  <div class="items-img animated zoomIn"><i class="items-text items-text3">12 <br>DEC</i></div>
                </a>
                </div>
              </div>
          </div>

          <div class="grid-item item-h" data="30">
          </div>

          <div class="grid-item item-h" data="31">
          </div>
 -->