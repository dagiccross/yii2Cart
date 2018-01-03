<?php

namespace frontend\extensions\slider;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;

class SliderInner extends Widget
{
    //配置选项，
    public $options = [];

    public $data = [];

    //默认配置
    protected $_options;

    protected $_data;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init(){
        parent::init();
        $this->_data = $this->data;
    }

    public function run()
    {
        $options['id'] = isset($this->options['id']) ? $this->options['id'] : 'slider-'.$this->key;
        $options['class'] = isset($this->options['class']) ? $this->options['class'] : 'carousel slide';
        
        // 设置轮播（Carousel）指标
        $ol_html = '';
        if(isset($this->options['ol'])&&$this->options['ol']){
            $i = count($this->_data);
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
        if(!empty($this->_data)):
        foreach ($this->_data as $k => $v) {
            $inner = '';
           
            $inner .= Html::a(Html::img(showImg($v)), '#');
         
            

            $slider_html .= Html::tag('div',$inner,['class'=>'item '.($i==0 ? 'active' : '')]);
            $i++;
        }
        endif;
        $slider_html = Html::tag('div',$slider_html,['class'=>'carousel-inner']);
        $shtml = Html::tag('div',$ol_html.$slider_html.$next_html,['class'=>$options['class'],'id'=>$options['id']]);
        return $shtml;
    }


  
}