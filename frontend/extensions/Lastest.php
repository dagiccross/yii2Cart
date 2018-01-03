<?php

namespace frontend\extensions;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;
use common\models\Goodssku;
class Lastest extends Widget
{
    //配置选项，
    public $clientOptions = [];

    public $type = 'Lastest';

    //默认配置
    protected $_options;

    protected $_data;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->_data = getGoodsOrderBy('g.modifytime desc',1,6);
        parent::init();

    }

    public function run()
    {
        $shtml = '<div class="col-sm-10 right-show">';
        $shtml .= '<h2 class="home-lastest">'.Yii::t('app','Lastest Product').'<span class="more"><a href="#">'.Yii::t('app','More').'</a></span></h2>';
        $shtml .= '<div class="row">';
        $i=0;

        foreach ($this->_data as $k => $v) {

            $sku_exist = Goodssku::find()->where(['goods_id'=>$v['id']])->exists();
            // var_dump($sku_exist);
            $shtml .= '<div class="col-sm-4">
                        <div class="product-wrap">
                 <div class="product">
                  <div class="product-pic"><a href="'.Url::to(['site/detail','id'=>$v['id']]).'">'.Html::img(showImg($v['pic']),['alt'=>$v['title']]).'</a>
                 </div>
                 <ol class="specail-title">
                   <li>'.Html::a($v['title'],['/site/detail','id'=>$v['id']]).'</li>
                   <li>'.Html::a($v['description']).'</li>
                   <li>'.Html::a(Yii::t('app','DETAILS').'<span class="glyphicon glyphicon-menu-right detail-icon"></span>',['detail','id'=>$v['id']]).'</li>
                 </ol>
                 </div>';
            if(!$sku_exist){
                $shtml .= '<div class="price"><span class="price1">$'.$v['price'].'</span>  <span class="cart2" onclick="javascript:CART.add(\''.$v['id'].'\')">'.Html::img(showImg(IMG_URL.'/cart.png'),['alt'=>'icon']).'</span></div>';
            }else{
                $shtml .= '<div class="price"><span class="price1">$'.$v['price'].'</span>  <span class="cart2" onclick="javascript:window.location.href=\''.Url::to(['/site/detail','id'=>$v['id']]).'\'">'.Html::img(showImg(IMG_URL.'/cart.png'),['alt'=>'icon']).'</span></div>';
            }


            $shtml .='</div>
             </div>';

        }

        $shtml .= '</div>';
        $shtml .= '</div>';

        return $shtml;
    }

}
