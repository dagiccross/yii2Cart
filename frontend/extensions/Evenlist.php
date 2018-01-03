<?php

namespace frontend\extensions;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;
use common\models\Tag;
use common\models\Page;

class Evenlist extends Widget
{
    //配置选项，
    public $clientOptions = [];

    public $type = 'even';

    //默认配置
    protected $_options;

    protected $_data;
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->_data = $this->getData();
        parent::init();
        // var_dump($this->_data);
    }

    public function run()
    {
        $shtml = Html::beginTag('div',['class'=>'row row-two']);
        $shtml .= Html::tag('div',Html::tag('h3',Yii::t('app','Also See')),['class'=>'biaoti']);
        $i=0;

        if(!empty($this->_data)):
          $shtml .= Html::beginTag('div',['class'=>'people-content','style'=>'margin-top: 0px;']);
          $shtml .= Html::beginTag('div',['class'=>'row']);
          
          foreach ($this->_data as $k => $v) {
            $img_url = isset($v['image']['thumb']) ? $v['image']['thumb'] : (@unserialize($v['oneMeta']['image']));
            $img_url = is_array($img_url) ? $img_url['0'] : $img_url;

            $url = $i<3 ? '/site/blogsingle' : '/site/listsingle';
            $day = date('j',$v['modifydate']);
            $month = date('M',$v['modifydate']);

              $shtml .= Html::beginTag('div',['class'=>'col-md-2 also-people']);
              $shtml .= Html::beginTag('div',['class'=>'people-see']);
              if($i>2):
              $shtml .= Html::tag('div',Html::tag('div',
                          Html::a(Html::img(showImg($img_url),['class'=>'seeimg img_10']).Html::tag('div',Html::tag('i',$month.'</br>'.$day,['class'=>'items-text items-text3']),['class'=>'items-img animated zoomIn']),
                            [$url,'id'=>$v['id'],'model'=>$v['tag_id']]),
                        ['class'=>'items-wrap']),
                        ['class'=>'img_show_10']);
              else:
              $shtml .= Html::a(Html::img(showImg($img_url),['class'=>'seeimg']),[$url,'id'=>$v['id'],'model'=>$v['tag_id']]);
              endif;
              $shtml .= Html::tag('p',Html::a('Location'));
              $shtml .= Html::tag('p',Html::tag('span','Location'));
              $shtml .= Html::endTag('div');
              $shtml .= Html::endTag('div');

            $i++;
          }
          $shtml .= Html::endTag('div');
          $shtml .= Html::endTag('div');
        endif;
        $shtml .= Html::endTag('div');
        
        return $shtml;
    }

    protected function getData(){
        $rs_ext = (new yii\db\Query())->select("em.*")
                ->from("{{%extension}} e")
                ->leftJoin("{{%extensionmeta}} em",'e.id =em.ext_id')
                ->where('e.`key`=:key and em.language=:language',[':key'=>$this->type,':language'=>Yii::$app->language])
                ->one();
        $options = isset($rs_ext['options']) ? @unserialize($rs_ext['options']) : null;
        if(empty($options)){
          $project_id = Tag::find()->select("id")->where(['group'=>'project'])->asArray()->all();
          $new_id = Tag::findOne(['tag_key'=>'news']);

          $project_id_arr = [];
          foreach ($project_id as $k => $v) {
            $project_id_arr[] = $v['id'];
          }

          $project_list = Page::find()->with(['image','oneMeta'])
                          ->where(['in','tag_id',$project_id_arr])
                          ->andWhere(['status'=>1])
                          ->orderBy('modifydate desc')
                          ->limit(3)
                          ->asArray()
                          ->all();

          $news_list = Page::find()->with(['image','oneMeta'])
                          ->where(['tag_id'=>$new_id['id']])
                          ->andWhere(['status'=>1])
                          ->orderBy('modifydate desc')
                          ->limit(2)
                          ->asArray()
                          ->all();

          $options = ArrayHelper::merge($project_list, $news_list);
          return $options;
        }
        return $options; 
        
    }
}