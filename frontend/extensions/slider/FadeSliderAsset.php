<?php
namespace frontend\extensions\slider;


use yii\web\AssetBundle;
use yii\web\View;

class FadeSliderAsset extends AssetBundle
{
    public $js = [
        'wowslider.js',
        'script.js',
    ];
   	public $css = [
        'lunbo-style.css',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];

    public $jsOptions = ['defer'=>'','position'=>View::POS_END];
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
}