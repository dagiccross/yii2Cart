<?php

namespace frontend\extensions;

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
    public $clientOptions = [];

    public $type = 'top';

    //默认配置
    protected $_options;

    protected $_data;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->_data = getExtSlider('home_slider');
        parent::init();
    }

    public function run()
    {
        $shtml = '<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">';
        $img = '<div class="carousel-inner" role="listbox">';
        $ol = '<ol class="carousel-indicators">';
        $i=0;

        foreach ($this->_data['options'] as $k => $v) {
            $active = ($i==0) ? 'active' : '';
            $img .= '<div class="item '.$active.'">'.Html::img(showImg($v["images"]),['alt'=>'slider'.$i]).'</div>';
            $ol .= '<li data-target="#carousel-example-generic" class="'.$active.'" data-slide-to="'.$i.'"></li>';
            $i++;
        }
        $img .= '</div>';
        $ol .= '</ol>';
        $shtml .= $img.$ol;
        $shtml .= '<!-- Controls -->
                  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left icon-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right icon-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>';
        $shtml .= '</div>';
        
        return $shtml;
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        // var_dump($this->id);
    }
}