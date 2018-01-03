<?php

namespace frontend\extensions\menu;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;
use common\models\Config;
class Menu extends Widget
{
    //配置选项，
    public $clientOptions = [];

    public $type = 'top';
    public $pid = 0;
    public $condition = '';
    public $param = '';
    public $title = '';
    public $key = '';
    //默认配置
    protected $_options;

    protected $_category;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $value = Yii::$app->cache->get('menu'.$this->type.$this->pid);
        $flat = Config::getConfig('cache');

        $flat = 0;
        if(empty($value)&&$flat){
            $value = $this->getData($this->pid,$this->type,$this->condition,$this->param);

            Yii::$app->cache->set('menu'.$this->type,$value,0);
        }else if(!$flat){

            $value = $this->getData($this->pid,$this->type,$this->condition,$this->param);
        }
        $this->_category = $value;
        // var_dump($value);
        parent::init();
    }

    public function run()
    {
        $this->registerClientScript();


        $shtml = '';
        if($this->type=='top'){
            // NAV菜单
            $shtml = '<ul class="nav navbar-nav home-nav">';
            $i=0;
            $get_id = \Yii::$app->request->get('id','0');

            $action_id = \Yii::$app->controller->action->id;
            $controller_open = isset(\Yii::$app->controller->open) ? \Yii::$app->controller->open : '';
            $controller_active = isset(\Yii::$app->controller->active) ? \Yii::$app->controller->active : '';


            // 一級
            foreach ($this->_category as $k => $v) {
                // 二級菜單
                $child_category = $this->getData($v['id'],$this->type);
                $flat_chlid = empty($v['key']) ? (isset($v['type']) ? $v['type'] : 'index') : $v['key'];

                $active = $flat_chlid==$controller_open ? 'open kai' : '';


                $a_options = ['class'=>'animsition-link',
                            'data-animsition-out-class'=>'fade-out-down',
                            ];
                $li_options = [];
                // 一級菜單
                $url ='';

                $path = ['path'=>$v['id']];
                $url = $this->setUrl($v,$path);


                if(!empty($child_category)){
                    $url = '#';
                    $a_options = ['tabindex'=>"0",'data-toggle'=>"dropdown",'data-submenu'=>''];//,'data-toggle'=>"dropdown"
                    $li_options = ['class'=>'dropdown-submenu'];
                }

                $child_html = '';
                $j = 0;
                foreach ($child_category as $child_k => $child_v) {

                    // 三級菜單
                    $children_category = $this->getData($child_v['id'],$this->type);
                    // 二級菜單URL
                    $secend_url ='';
                    $path = ['path'=>$v['id'].'_'.$child_v['id']];
                    $secend_url = $this->setUrl($child_v,$path);

                    $children_html = '';
                    $child_options = [];
                    $child_a_options = ['class'=>'animsition-link',
                            'data-animsition-out-class'=>'fade-out-down',
                            ];

                    if($controller_open=='page'){
                        $child_options['class'] = $controller_active==$j ? 'active' : '';
                    }else if($controller_open=='contact'){
                        $child_options['class'] = $controller_active==$j ? 'active' : '';
                    }else{
                        $child_options['class'] = $controller_active == $child_v['id'] ? 'active' : '';
                    }

                    foreach ($children_category as $children_k => $children_v) {
                        $third_url ='';
                        $path = ['path'=>$v['id'].'_'.$child_v['id'].'_'.$children_v['id']];
                        $third_url = $this->setUrl($children_v,$path);

                        $children_html .= Html::tag('li',Html::a($children_v['name'],$third_url,['tabindex'=>"0"]));
                    }

                    if(!empty($children_category)){
                        $secend_url ='#';
                        $children_html =Html::tag('ul',$children_html,['class'=>'dropdown-menu']);

                        $child_options = ['class'=>'dropdown-submenu'];
                    }

                    // 设置当KEY为index和contact时#不能有翻页效果
                    if($action_id == 'index'&&$child_v['key']=='index'){
                        $child_a_options = [];
                    }
                    if($action_id == 'contact'&&$child_v['key']=='contact'){
                        $child_a_options = [];
                    }
                    $child_html .= Html::tag('li',Html::a($child_v['name'],$secend_url,$child_a_options).$children_html,$child_options);
                    $j++;
                }
                // end 循環三級菜單
                $child_html =Html::tag('ul',$child_html,['class'=>'dropdown-menu down-nav']);
                $shtml .= Html::tag('li',Html::a($v['name'],$url,$a_options).$child_html,['class'=>'col-sm-3 dropdown  '.$active,]);

                $i++;
            }
            $shtml .= '</ul>';

        }else if($this->type=='side'){

            // 侧边菜单
            $shtml = '<div class="cate"><h2>'.$this->title.'</h2>
                    <ul class="nav nav-pills nav-stacked category-menu">';
            $i=0;

            $this->_category = is_array($this->_category) ? $this->_category : [];
            foreach ($this->_category as $k => $v) {
                $child = getCategoryChlid($v['id']);
                // $do = !empty($child)&&is_array($child) ? 'dropdown' : '';
                $do = '';
                $shtml .= '<li class="dropdown"><a href="'.Url::to(['/site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id'],'pid'=>$this->pid],true).'" class="dropdown-toggle" data-toggle="'.$do.'" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-triangle-right" style="color:#999;margin-right:5px;"></span>'.$v['name'].'<span class="glyphicon glyphicon-menu-down menudown-icon"></span></a>';

                if(!empty($child)&&is_array($child)){
                    $shtml .= '<ul class="dropdown-menu" style="position:relative;border:none;    box-shadow: none;width:100%;">';
                    foreach ($child as $_k => $_v) {
                        // $shtml .= '<li>'.Html::a($_v['name'], ['/site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id'],'pid'=>$this->pid], ['onclick' => 'triggerLi("cat-'.$_v['id'].'")']).'</li>';
                        $shtml .= '<li><a href="'.Url::to(['/site/'.(empty($v['key'])? $v['type'] : $v['key']),'id'=>$v['id'],'pid'=>$this->pid, 'cat' => $_v['id'], 'offset' => $_k],true).'" data-cat="'.$_v['id'].'" data-offset="'.$_k.'">'.$_v['name'].'</a></li>';
                    }
                    $shtml .= '</ul>';
                }

                $shtml .= '</li>';
                $i++;
            }
            $shtml .= '</ul></div>';
        }else if($this->type=='footer'){

        }

        return $shtml;

    }

    protected function getData($id=0,$nav='top',$condition='',$param=''){
        $query = new yii\db\Query();

        $rs = $query->select('c.*,m.cat_id,m.language,m.name,m.image')
                    ->from("{{%category}} c")
                    ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id')
                    ->where('m.language=:language AND c.show=1',[':language'=>Yii::$app->language])
                    ->andWhere('c.'.$nav.'=:nav',[':nav'=>'1'])
                    ->andWhere('c.pid=:key0',[':key0'=>$id]);
        if(!empty($condition)){
            $rs = $rs->andWhere($condition,$param);
        }

        $rs = $rs->orderBy('c.order_id asc')
              ->all();
        // var_dump(Yii::$app->language);
        return $rs;
    }

    // 設置URL
    protected function setUrl($category,$extends=[]){

        $url = '';
        $ext_key='';
        $ext_value='';
        foreach ($extends as $k => $v) {
            $ext_key = $k;
            $ext_value = $v;
        }
        if($category['key']=='index'&&empty($category['link_url'])){
            $url = ['/site/index'];
        }else{
            // 當後臺設置有URL的時候就取URL，
            //沒有的話就當KEY是否為空不為空就以KEY為actionID 為空就以type為actionId
            $url = empty($category['link_url']) ? (empty($category['key']) ? ['/site/'.$category['type'],'id'=>$category['id'],'type'=>$category['type'],$ext_key=>$ext_value] : ['site/'.$category['key'],'id'=>$category['id'],'type'=>$category['type'],$ext_key=>$ext_value]) : $category['link_url'];

        }
        return $url;
    }
    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        // var_dump($this->id);
        if($this->type=='top'){
            MenuAsset::register($this->view);
        }
        // $clientOptions = Json::encode($this->clientOptions);
        // //$script = "UE.getEditor('" . $this->id . "', " . $clientOptions . ")";
        // $script = " $('.dropdown > a[tabindex]').on('keydown', function(event) {
        //         // 13: Return

        //         if (event.keyCode == 13) {
        //           $(this).dropdown('toggle');
        //         }
        //       });
        //     $('[data-submenu]').submenupicker();";
        // $this->view->registerJs($script, View::POS_END);
    }

}
