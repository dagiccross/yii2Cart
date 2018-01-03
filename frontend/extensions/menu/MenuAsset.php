<?php
namespace frontend\extensions\menu;


use yii\web\AssetBundle;

class MenuAsset extends AssetBundle
{
    public $js = [
        // 'dist/js/bootstrap-submenu.min.js',
        // 'js/docs.js',
    ];
   	public $css = [
        // 'dist/css/bootstrap-submenu.min.css',
    ];
    public $jsOptions = ['defer'=>''];
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
}