<?php
use frontend\extensions\Menu;
use yii\helpers\Html;

?>

        <style>
        .photo-wrap img {
          width: 100%;
          min-height: 300px;
          max-height: 300px;
        }
        </style>
        <section class="photo-wrap">
          <div class="row">
            <?php
            foreach ($page as $k => $v) {
                $shmtl = '<div class="col-sm-6">';
                $v['image'] = @unserialize($v['image']);
                $img = Html::img(showImg(isset($v['image']['0']) ? $v['image']['0'] :''),['alt'=>$v['title']]);
                $shmtl .= Html::a($img);
                $shmtl .= '<p class="gallery-caption"><a href="#">'.$v['title'].'</a></p>';
                $shmtl .= '</div>';
                echo $shmtl;
            }

            ?>

          </div>
          <div class="clearfix"></div>
        </section>
