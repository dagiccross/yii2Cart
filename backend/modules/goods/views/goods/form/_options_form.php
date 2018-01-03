<?php
// use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;

$options_type = ['radio'=>Yii::t('app','radio'),'checkbox'=>Yii::t('app','checkbox'),'select'=>Yii::t('app','select')];
$prefix = ['+'=>'+','-'=>'-'];
$zaone = ['0'=>0,'1'=>1];
?>

<div class="tag-form" style="padding:10px 0px;">
    <?=Html::label(Yii::t('info','Add Attributes'))?>
    <i class="glyphicon glyphicon-plus jia_attr" style="cursor:pointer" data-id="1" onclick="javascript:GOODS.addoGroup('options_group_row')"></i>
    &nbsp;&nbsp;&nbsp;
    <?php echo Yii::t('info', 'Copy from') ?>
    <?php echo Html::textInput('CopyOptionsGroupFrom') ?>
    <?php echo Yii::t('info', 'to') ?>
    <?php echo Html::textInput('CopyOptionsGroupTo') ?>
</div>
<div class="container-fluid" id="options_group_row">
    <?php
    if(!empty($goodsoptionsgroup)&&is_array($goodsoptionsgroup)){


        for ($i=0; $i < count($goodsoptionsgroup) ; $i++) {
            $html = '<div class="row options_group bg-warning" id="g_options_group'.$i.'">';
            $html .= '<div class="col-md-2 col-xs-2">';
            $html .= 'Group '.$i.':</div>';

            $html .= Html::hiddenInput('GoodsOptionsGroup['.$i.'][g_options_group_id]',$goodsoptionsgroup[$i]['g_options_group_id'],['class'=>'delete-data','data-type'=>'group']);
            $html .= Html::textInput('GoodsOptionsGroup['.$i.'][name]',$goodsoptionsgroup[$i]['name'],['class'=>'form-control width_16','placeholder'=>'Group Name']);
            $html .= Html::textInput('GoodsOptionsGroup['.$i.'][options]',$goodsoptionsgroup[$i]['options'],['class'=>'form-control width_16','placeholder'=>'Group Options']);
            $html .= Html::dropDownlist('GoodsOptionsGroup['.$i.'][options_type]',$goodsoptionsgroup[$i]['options_type'],$options_type,['class'=>'form-control width_16']);
            $html .= '<div class="col-md-2 col-xs-2">';
            $html .= Html::checkbox('GoodsOptionsGroup['.$i.'][required]',$goodsoptionsgroup[$i]['required']==1,['class'=>'width_5']);
            $html .= \Yii::t('label','required');
            $html .= '</div>';

            $html .= '<a class="glyphicon glyphicon-plus" onclick="javascript:GOODS.addOptions(this,\''.$i.'\')">Add Options</a>&nbsp;';
            $html .= '<i class="glyphicon glyphicon-trash" onclick="javascript:GOODS.deleteRow(this)"></i>';

            if(isset($goodsoptionsgroup[$i]['options_value'])&&is_array($goodsoptionsgroup[$i]['options_value'])){
                $options_value = $goodsoptionsgroup[$i]['options_value'];
                for ($j=0; $j < count($options_value) ; $j++) {
                    $shtml = '<div class="row goods_options bg-success">';
                    $shtml .= '<div class="col-md-1 col-xs-1">';
                    $shtml .= 'options :</div>';
                    $shtml .= Html::hiddenInput('GoodsOptionsGroup['.$i.'][options_value][g_options_id][]',$options_value[$j]['g_options_id'],['class'=>'delete-data','data-type'=>'options']);
                    $shtml .= Html::textInput('GoodsOptionsGroup['.$i.'][options_value][name][]',$options_value[$j]['name'],['class'=>'form-control width_12','placeholder'=>'Option Name']);
                    $shtml .= Html::textInput('GoodsOptionsGroup['.$i.'][options_value][quanity][]',$options_value[$j]['quanity'],['class'=>'form-control width_12','placeholder'=>'Quanity']);
                    $shtml .= Html::dropDownlist('GoodsOptionsGroup['.$i.'][options_value][subtract][]',$options_value[$j]['subtract'],$zaone,['class'=>'form-control width_5']);

                    $shtml .= Html::textInput('GoodsOptionsGroup['.$i.'][options_value][price][]',$options_value[$j]['price'],['class'=>'form-control width_12','placeholder'=>'Price']);
                    $shtml .= Html::dropDownlist('GoodsOptionsGroup['.$i.'][options_value][price_prefix][]',$options_value[$j]['price_prefix'],$prefix,['class'=>'form-control width_5']);

                    $shtml .= Html::textInput('GoodsOptionsGroup['.$i.'][options_value][weight][]',$options_value[$j]['weight'],['class'=>'form-control width_12','placeholder'=>'weight']);
                    $shtml .= Html::dropDownlist('GoodsOptionsGroup['.$i.'][options_value][weight_prefix][]',$options_value[$j]['weight_prefix'],$prefix,['class'=>'form-control width_5']);


                    $shtml .= '<i class="glyphicon glyphicon-trash" onclick="javascript:GOODS.deleteRow(this)"></i>';
                    $shtml .= '</div>';
                    $html .= $shtml;
                }


            }

            $html .= '</div>';
            echo $html;
        }



    }
    ?>
    <!-- <div class="row " id="g_options_group">
        <div class="col-md-2 col-xs-2">
        <?=Html::label('分组:')?>
        </div>
        <div class="col-md-2 col-xs-2">
        <?=Html::textInput('name','',['class'=>'form-control','placeholder'=>'分组名称'])?>
        </div>
        <div class="col-md-2 col-xs-2">
        <?=Html::textInput('options','',['class'=>'form-control','placeholder'=>'分组选项'])?>
        </div>
        <div class="col-md-2 col-xs-2">
        <?=Html::dropDownlist('options_type','',$options_type,['class'=>'form-control'])?>
        </div>
        <div class="col-md-2 col-xs-2">
        <?=Html::checkbox('required',false)?>必须
        </div>
        <div class="col-md-2 col-xs-2">
        <?=Html::a('子选项','javascript:;',['class'=>'glyphicon glyphicon-plus'])?>&nbsp;<?=Html::tag('i','',['class'=>'glyphicon glyphicon-trash'])?>
        </div>
    </div> -->
</div>
<div id="options_group_delete_row"></div>
<style type="text/css">
    .width_5{width: 5% !important;float: left;}
    .width_12{width: 12% !important;float: left;}
    .width_16{width: 16.6667% !important; float: left;}
    .goods_options{clear: both;padding: 3px 15px;margin: 0;}
    .options_group{clear: both;margin-bottom:10px;padding: 10px 0px 3px;}
    .glyphicon{cursor: pointer;}
</style>
<script type="text/javascript">
     var GOODSOPTIONSGROUP = <?php echo isset($i) ? $i : 0;?>;
</script>
<?php $this->beginBlock('goods_options') ?>


<?php $this->endBlock() ?>
<?php $this->registerJsFile(Yii::getAlias('@web')."/web/js/backend.js", ['position'=>\yii\web\View::POS_END,'depends'=> [yii\web\JqueryAsset::className()]]); ?>

<?php $this->registerJs($this->blocks['goods_options'], \yii\web\View::POS_END); ?>
