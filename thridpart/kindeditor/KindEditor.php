<?php

namespace thridpart\kindeditor;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;

class KindEditor extends InputWidget
{
    //配置选项，参阅Ueditor官网文档(定制菜单等)
    public $clientOptions = [];

    //默认配置
    protected $_options;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        // var_dump($this->model);
        // var_dump($this->hasModel());
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        // var_dump($this->id);
        $this->_options = [
            'width' => '100%',
            'filterMode' => 'false',
            'height' => '400px',
            'langType' => (strtolower(Yii::$app->language) == 'en-us') ? 'en' : 'zh_CN',
            'fileManagerJson' => Yii::$app->urlManager->createUrl('upfile/json',true),
        ];
        $this->clientOptions = ArrayHelper::merge($this->_options, $this->clientOptions);
        parent::init();
    }

    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            return Html::activeTextarea($this->model, $this->attribute, ['id' => $this->id]);
        } else {
            return Html::textarea($this->name, $this->value, ['id' => $this->id]);
        }
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        // var_dump($this->id);
        KindEditorAsset::register($this->view);
        $clientOptions = Json::encode($this->clientOptions);
        //$script = "UE.getEditor('" . $this->id . "', " . $clientOptions . ")";
        $script = "KindEditor.ready(function(K) {
                        K.create('#" . $this->id . "', ".$clientOptions.");
                    });";
        $this->view->registerJs($script, View::POS_END);
    }
}