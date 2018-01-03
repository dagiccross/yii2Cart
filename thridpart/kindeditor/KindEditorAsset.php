<?php
namespace thridpart\kindeditor;


use yii\web\AssetBundle;

class KindEditorAsset extends AssetBundle
{
    public $js = [
        'kindeditor-min.js',
        'lang/zh_CN.js',
    ];
   	public $css = [
        'themes/default/default.css',
    ];
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
}