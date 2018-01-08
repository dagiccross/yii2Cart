<?php

namespace frontend\extensions;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;

class Menu extends Widget
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
    Yii::$app->cache->set('menu'.$this->type,$value,60);

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
        $action_id =Yii::$app->controller->action->id;
        $active = ($v['id']==Yii::$app->request->get('id','')) ? 'active' : '';
        if($v['key']=='index'){
          $url = ['/site/index'];
          $active = ($active=='active') ? 'active' : ($action_id=='index' ? 'active' : '');
        }else{
          $url = empty($v['url']) ? (empty($v['key']) ? ['/site/'.$v['type'],'id'=>$v['id'],'type'=>$v['type']] : ['/site/'.$v['key'],'id'=>$v['id'],'type'=>$v['type']]) : $v['url'];

        }
        $shtml .= '<li role="presentation" class="'.$active.'">'.Html::a($v['name'],$url).'</li>';
        $i++;
      }
      $shtml .= '</ul>';
    }else if($this->type=='side'){
      //$shtml = '<ul>';
      // use code above when catergory is too short
      $shtml = '<ul style="overflow-y:scroll; height:60vh;">';
      $i=0;
      $action_id = Yii::$app->controller->action->id;

      foreach ($this->_category as $k => $v) {

        $active = ($v['id']==Yii::$app->request->get('id','')&&$action_id=='product') ? 'active' : '';
        if($action_id=='product'){
          $shtml .= '<li class="'.$active.'">'.Html::a($v['name'],'#mainmenu'.$v['id']).'</li>'; //Url::to(['/site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id']]).
        }else{
          $shtml .= '<li class="'.$active.'">'.Html::a($v['name'],Url::to(['/site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id']]).'#mainmenu'.$v['id']).'</li>'; //Url::to(['/site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id']]).
        }
        $i++;
      }
      //$shtml .= '</ul>';
      // use code above when catergory is too short
      $shtml .= '</ul><center><p style="font-size:14px;"><span class="glyphicon glyphicon-arrow-down"></span> Scroll for more</p></center>';
    }else if($this->type=='footer'){

    }

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
