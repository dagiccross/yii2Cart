<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Goods');
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    .upfile-create{padding: 15px;}
</style>
<div class="goods-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if($goodscategory_count>1){
            Modal::begin([
                'header' => Html::tag('h2',yii::t('app','Goods Category')),
                'toggleButton' => ['label' => Yii::t('app', 'Create'),
                                    'tag' => 'a',
                                    'class'=>'btn btn-success'],
            ]);
                $g_cat_id = isset(\Yii::$app->session['g_cat_id']) ? \Yii::$app->session['g_cat_id'] : '0';
                $i=0;

                echo '<div class="upfile-create">';
                foreach ($goods_category as $k => $v) {
                    $flat = ($g_cat_id == 0) ? (($i==0) ? true : false) : ($g_cat_id==$v['g_cat_id']);
                    echo '<div class="row form-group">'.Html::radio('g_cat_id',$flat,['value'=>$v['g_cat_id']]).Html::label($v['name']).'</div>';
                    $i++;
                }
            ?>
            <div class="form-group">
                <?= Html::button(Yii::t('app', 'Next'), ['class' => 'btn btn-success','id'=>'goods_next_create']) ?>
            </div>
            <?php
                echo '</div>';
            Modal::end();

        }else{
            echo Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']);
            if(Yii::$app->user->identity->power=='admin'){
                echo Html::a(Yii::t('app', 'Upload'), ['uploads'], ['class' => 'btn btn-success','style'=>'margin-left: 10px;']);
                echo Html::a(Yii::t('app', 'Download'), ['download'], ['class' => 'btn btn-success', 'style' => 'margin-left: 10px;']);
            }
            echo Html::a(Yii::t('app', 'delete'), 'javascript:;', ['class' => 'btn btn-success deleteAll','style'=>'margin-left: 10px;']);
        }
        


        ?>
    </p>
    <div class="row">
        <?php echo $this->render('_search');?>
    </div>

    <?php $form = ActiveForm::begin(['id'=>'goods-index']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
            ['label'=>Yii::t('app','Product Id'),'format'=>'html','value'=>function($m){return $m['id'];}],
            ['label'=>Yii::t('info','Name'),'value'=>function($m){return $m['title'];}],
            ['label'=>Yii::t('app','Price'),'value'=>function($m){return $m['price'];}],
            ['label'=>Yii::t('app','Quanity'),'value'=>function($m){return $m['quanity'];}],
            ['label'=>Yii::t('app','SKU'),'value'=>function($m){return $m['sku'];}],
            ['label'=>Yii::t('info','Sort Order'),'value'=>function($m){return $m['order_id'];}],

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{update} {copy} {delete} ',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'update' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'delete' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
                'copy' => function ($url, $model, $key) {
                  $options = [
                    'title' => Yii::t('yii', 'Copy'),
                    'aria-label' => Yii::t('yii', 'Copy'),
                    'data-pjax' => '0',
                  ];
                  return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, $options);
                },
              ],

            ],
        ],
    ]); ?>
    <?php ActiveForm::begin(); ?>
</div>
<?php $this->beginBlock('image_attr') ?>  
    jQuery(function($) {
        
        $("#goods_next_create").click(function(){

            
            var id = $("input[name='g_cat_id']:checked").val();
            console.log(id);
            window.location.href="<?=Url::to(['goods/create'])?>&g_cat_id="+id; 
        });
        
        $(".deleteAll").click(function(){

            $.ajax({
                type:"post",
                data:$("#goods-index").serialize(),
                url:"<?=Url::to(['delete-all'])?>",
                success:function(data){
                    alert(data);
                    location.reload();
                },
                errro:function(data){
                    console.log(data);
                }
            });
        });
    }); 

<?php $this->endBlock() ?>

<?php $this->registerJs($this->blocks['image_attr'], \yii\web\View::POS_END); ?> 