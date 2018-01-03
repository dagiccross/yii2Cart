<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'web/css/site.css',
        // 'web/uploadify/uploadify.css',
        //'web/uploader/css/simple-demo.css',
    ];
    public $js = [
        //'web/js/ajaxfileupload.js',
        //'web/uploader/js/dmuploader.min.js',
        // 'web/js/jq.insertimg.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
