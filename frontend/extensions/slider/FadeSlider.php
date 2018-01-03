<?php

namespace frontend\extensions\slider;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;

class FadeSlider extends Widget
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
        $this->registerClientScript();

        $this->options['id'] = isset($this->options['id']) ? $this->options['id'] : 'fadeslider-'.$this->key;
        $this->options['class'] = isset($this->options['class']) ? $this->options['class'] : '';
        parent::init();
    }

    public function run()
    {
        $options['id'] = $this->options['id'];
        $options['class'] = $this->options['class'];
        
        $slider_html = '';
        $ol_html = '';

        $i = 0;
        if(!empty($this->_data['options'])):
        foreach ($this->_data['options'] as $k => $v) {
            $inner = '';
            $inner_ol = '';

            if(!empty($v['images'])){
                $inner .= Html::a(Html::img(showImg($v['images']),['title'=>'','id'=>'wows1_'.$i]),empty($v['url']) ? '#' : $v['url']);
                $inner_ol .= Html::a(Html::img(showImg($v['images'])).$i,empty($v['url']) ? '#' : $v['url'],
                                    ['title'=>'']);
            }
            if(!empty($v['options'])){
                $inner .= $v['options'];
            }

            $slider_html .= Html::tag('li',$inner);
            $ol_html .= $inner_ol;
            // $ol_html .= Html::tag('div',$inner_ol);

            $i++;
        }
        endif;
        $slider_html = Html::tag('ul',$slider_html);
        $slider_html = Html::tag('div',$slider_html,['class'=>'ws_images']);

        $ol_html = Html::tag('div',$ol_html,['class'=>'ws_bullets']);
        // 设置轮播指标
        
        if(!isset($this->options['ol'])||!$this->options['ol']){
            $ol_html = '';
        }
        // end 指标

        $ohter_html = '<div class="ws_shadow"></div>';

        $shtml = Html::tag('div',$slider_html.$ol_html.$ohter_html,['class'=>$options['class'],'id'=>$options['id']]);
        return $shtml;
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        // var_dump($this->id);
        FadeSliderAsset::register($this->view);
        // $sourcePath = './frontend/extensions/slider/assets';
        $script = 'wowReInitor(jQuery("#'.$this->options['id'].'"), {
                    effect: "blur",
                    prev: "",
                    next: "",
                    duration: 29 * 100,
                    delay: 19 * 100,
                    width: 960,
                    height: 360,
                    autoPlay: true,
                    playPause: true,
                    stopOnHover: false,
                    loop: false,
                    bullets: true,
                    caption: true,
                    captionEffect: "move",
                    controls: true,
                    onBeforeStep: 0,
                    images: 0
                });';
        // $this->view->registerJs($script, View::POS_END,);

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