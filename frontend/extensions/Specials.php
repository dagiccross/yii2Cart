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

class Specials extends Widget
{
    //配置选项，
    public $clientOptions = [];

    public $type = 'Specials';

    //默认配置
    protected $_options;

    protected $_data;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->_data = $this->getData();
        parent::init();
        // var_dump($this->_data);
    }

    public function run()
    {
        $shtml = '<div class="special">';
        $shtml .= '<h2>'.Yii::t('app','Bestsellers').'</h2>';
        $i=0;

        if(!empty($this->_data)):
        foreach ($this->_data as $k => $v) {
          $sku_exist = Goodssku::find()->where(['goods_id'=>$v['id']])->exists();
            
            $shtml .= '
                        <div class="product-wrap">
                 <div class="product">
                  <div class="product-pic"><a href="'.Url::to(['site/detail','id'=>$v['id']]).'">'.Html::img(showImg($v['pic']),['alt'=>$v['title']]).'</a>
                 </div>
                 <ol class="specail-title">
                   <li class="figcaption">'.Html::a($v['title'],['/site/detail','id'=>$v['id']]).'</li>
                   <li>'.Html::a($v['description']).'</li>
                   <li>'.Html::a(Yii::t('app','DETAILS').'<span class="glyphicon glyphicon-menu-right detail-icon"></span>',['/site/detail','id'=>$v['id']]).'</li>
                 </ol>
                 </div>
                 ';
            if(!$sku_exist){
                $shtml .= '<div class="price"><span class="price1">$'.$v['price'].'</span>  <span class="cart2" onclick="javascript:CART.add(\''.$v['id'].'\')">'.Html::img(showImg(IMG_URL.'/cart.png'),['alt'=>'icon']).'</span></div>';
            }else{
                $shtml .= '<div class="price"><span class="price1">$'.$v['price'].'</span>  <span class="cart2" onclick="javascript:window.location.href=\''.Url::to(['/site/detail','id'=>$v['id']]).'\'">'.Html::img(showImg(IMG_URL.'/cart.png'),['alt'=>'icon']).'</span></div>';
            }
                 
               
            $shtml .='</div>';

        }
        endif;
        $shtml .= '</div>';
        
        return $shtml;
    }

    protected function getData(){
        $rs_ext = (new yii\db\Query())->select("em.*")
                ->from("{{%extension}} e")
                ->leftJoin("{{%extensionmeta}} em",'e.id =em.ext_id')
                ->where('e.`key`=:key and em.language=:language',[':key'=>'Specials',':language'=>Yii::$app->language])
                ->one();
        
        if(isset($rs_ext['goods'])&&!empty($rs_ext['goods'])){
          

          $rs = (new yii\db\Query())->select("g.*,m.title,m.language,m.description")
          ->from("{{%goods}} g")
          ->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
          ->where('m.language=:language and g.status=1',[':language'=>Yii::$app->language])
          ->andWhere('g.id in ('.$rs_ext['goods'].')')
          ->orderBy("g.id asc")
          ->all();
          // var_dump($rs);
          return $rs;

        }else{
          return null;
        }
        
    }
}