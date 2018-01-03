<?php

use yii\helpers\Html;
use yii\widgets\ListView;

//Starts Hon's member CSV generator
use common\models\Order;
use common\models\Config;
use common\models\Coupon;
use common\models\OrderGoods;
use common\models\OrderGoodsOptions;
use common\models\OrderAction;
use common\models\User;

use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\db\Query;
//Ends


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Member');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->identity->power=='admin'):?>
        <?= Html::a(Yii::t('app', 'Create Member'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif;?>
    </p>
    <p>
      
    </p>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_items',
        'layout'=>'<div><table class="table table-striped table-bordered">
                    <tr>
                    <th>#</th>
                    <th>'.Yii::t('label','User name').'</th>
                    <th>'.Yii::t('label','E-mail').'</th>
                    <th>'.Yii::t('label','Phone').'</th>

                    <!-- <th>'.Yii::t('label','Login IP').'</th> -->
                    <th>'.Yii::t('label','Creation Date').'</th>
                    <!-- <th>'.Yii::t('label','Last Modify Date').'</th> -->

                    <th>'.Yii::t('app','Action').'</th>
                    </tr>
                    {items}</table></div><div>{pager}</div>',
        'pager'=>[
            'maxButtonCount'=>10,
            'nextPageLabel'=>Yii::t('app','next'),
            'prevPageLabel'=>Yii::t('app','prev'),
        ],
    ]) ?>

</div>
