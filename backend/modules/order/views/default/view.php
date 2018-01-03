<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Manager'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode(\Yii::t('info','Order No.').$this->title) ?></h1>


    <?php

    if(Yii::$app->user->identity->power=='admin'){
        echo Tabs::widget([
            'items' => [
                [
                    'label' => \Yii::t('app','Basic Information'),
                    'content' => $this->render('view/_base_view', [
                                    'model' => $model,
                                ]),
                    'active' => true
                ],
                [
                    'label' => \Yii::t('app','Address Information'),
                    'content' => $this->render('view/_addr_view', [
                                    'model' => $model,
                                    'member'=>$member,
                                ]),
                ],
                [
                    'label' => \Yii::t('app','Goods Information'),
                    'content' => $this->render('view/_goods_view', [
                                    'goods' => $goods,
                                    'model' => $model,
                                    'list'=>$list,
                                ]),
                ],
                [
                    'label' => \Yii::t('app','Modify Payment Status'),
                    'content' => $this->render('form/_status_form', [
                                    'model' => $model,
                                ]),
                ],
            ],
        ]);
    }else{
      echo Tabs::widget([
          'items' => [
              [
                  'label' => \Yii::t('app','Basic Information'),
                  'content' => $this->render('view/_base_view', [
                                  'model' => $model,
                              ]),
                  'active' => true
              ],
              [
                  'label' => \Yii::t('app','Address Information'),
                  'content' => $this->render('view/_addr_view', [
                                  'model' => $model,
                                  'member'=>$member,
                              ]),
              ],
              [
                  'label' => \Yii::t('app','Goods Information'),
                  'content' => $this->render('view/_goods_view', [
                                  'goods' => $goods,
                                  'model' => $model,
                                  'list'=>$list,
                              ]),
              ],
              [
                  'label' => \Yii::t('app','Modify Payment Status'),
                  'content' => $this->render('form/_status_form', [
                                  'model' => $model,
                              ]),
              ],
          ],
      ]);
    }
    ?>

</div>
