<?php

namespace frontend\extensions\slider;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;

class Slider extends Widget
{
    //配置选项，
    public $options = [];

    public $key = 'top';

    //默认配置
    protected $_options;

    protected $_data;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->_data = $this->getExtSlider($this->key);
        parent::init();
    }

    public function run()
    {
        $options['id'] = isset($this->options['id']) ? $this->options['id'] : 'slider-'.$this->key;
        $options['class'] = isset($this->options['class']) ? $this->options['class'] : 'carousel slide';
        
        // 设置轮播（Carousel）指标
        $ol_html = '';
        if(isset($this->options['ol'])&&$this->options['ol']){
            $i = count($this->_data['options']);
            $ol = '';
            for ($j=0; $j <$i ; $j++) { 
                $ol .= Html::tag('li','',['data-target'=>'#'.$options['id'],'data-slide-to'=>$j,'class'=>$j==0 ? 'active' : '']);
            }
            $ol_html = Html::tag('ol',$ol,['class'=>'carousel-indicators']);
        }
        // end 轮播（Carousel）指标

        // 轮播（Carousel）导航
        $next_html = '';
        if(isset($this->options['next'])&&$this->options['next']){
            $next_html .= Html::a(Html::tag('i',Html::img(showImg(IMG_URL.'/left.png')),['class'=>'left-i']),'#'.$options['id'],['class'=>'carousel-control left','data-slide'=>'prev']);
            $next_html .= Html::a(Html::tag('i',Html::img(showImg(IMG_URL.'/right.png')),['class'=>'right-i']),'#'.$options['id'],['class'=>'carousel-control right','data-slide'=>'next']);
        }
        // end 轮播（Carousel）导航
        
        $slider_html = '';
        $i = 0;
        if(!empty($this->_data['options'])):
        foreach ($this->_data['options'] as $k => $v) {
            $inner = '';
            if(!empty($v['images'])){
                $inner .= Html::a(Html::img(showImg($v['images'])),empty($v['url']) ? '#' : $v['url']);
            }
            if(!empty($v['options'])){
                $inner .= $v['options'];
            }

            $slider_html .= Html::tag('div',$inner,['class'=>'item '.($i==0 ? 'active' : '')]);
            $i++;
        }
        endif;
        $slider_html = Html::tag('div',$slider_html,['class'=>'carousel-inner']);
        $shtml = Html::tag('div',$ol_html.$slider_html.$next_html,['class'=>$options['class'],'id'=>$options['id']]);
        return $shtml;
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        // var_dump($this->id);
    }

    // 获取扩展信息slider
    protected function getExtSlider($key='slider'){
    $rs = (new yii\db\Query())->select("e.*,m.*")
        ->from("{{%extension}} e")
        ->leftJoin("{{%extensionmeta}} m",'e.id=m.ext_id')
        ->where('`key`=:key and tag="slider"',[':key'=>$key])
        ->andWhere('m.language=:language',[':language'=>\Yii::$app->language])
        ->orderBy("id asc")
        ->one();
    $rs['options'] = @unserialize($rs['options']);
    return $rs;
}
}