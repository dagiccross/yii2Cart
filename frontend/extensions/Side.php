<?php

namespace frontend\extensions;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;

class Side extends Widget
{
  //配置选项，
  public $clientOptions = [];

  public $type = 'top';

  //默认配置
  protected $_options;

  protected $_category;
  /**
  * @throws \yii\base\InvalidConfigException
  */
  public function init()
  {
    $value = Yii::$app->cache->get('menu'.$this->type);
    

    $value = getCategory(0,$this->type);
    Yii::$app->cache->set('menu'.$this->type,$value,1);

    $this->_category = $value;
    parent::init();
  }

  public function run()
  {
    $shtml = '';
    if($this->type=='top'){
      $shtml = '<ul class="nav navbar-nav">';
      $i=0;

      foreach ($this->_category as $k => $v) {
        $active = ($v['id']==Yii::$app->request->get('id','')) ? 'active' : '';
        $url ='';
        if($v['key']=='index'){
          $url = ['site/index'];
        }else{
          $url = empty($v['url']) ? (empty($v['key']) ? ['site/'.$v['type'],'id'=>$v['id'],'type'=>$v['type']] : ['site/'.$v['key'],'id'=>$v['id'],'type'=>$v['type']]) : $v['url'];

        }
        $shtml .= '<li role="presentation" class="'.$active.'">'.Html::a($v['name'],$url).'</li>';
        $i++;
      }
      $shtml .= '</ul>';

    }else if($this->type=='side'){
      $shtml = '<ul>';
      $i=0;
      foreach ($this->_category as $k => $v) {

        $shtml .= '<li>'.Html::a($v['name'],['site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id']]).'</li>';
        $i++;
      }
      $shtml .= '</ul>';
    }else if($this->type=='footer'){

    }

    return $shtml;

  }

  protected function getCategory(){

  }
  /**
  * 注册客户端脚本
  */
  protected function registerClientScript()
  {
    // var_dump($this->id);
  }
}
