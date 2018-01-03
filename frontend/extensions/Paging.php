<?php

namespace frontend\extensions;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\base\Widget;

class Paging extends Widget
{
    public $pagination;
    public $maxButtonCount = 5;
    public $tab = '#tabpanel1';
    public $url = [];

    public function init()
    {
        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }

    }

    public function run()
    {
        $this->registerLinkTags();

        $shtml = '<nav class="pages">';
        $shtml .= '<ul class="pagination">';
        $shtml .= Html::tag('li',Html::a('<<','javascript:;',['rel'=>$this->url['first'],'onclick'=>'javascript:LINK.to(this,"'.$this->tab.'")']));
        $shtml .= Html::tag('li',Html::a('<','javascript:;',['rel'=>$this->url['prev'],'onclick'=>'javascript:LINK.to(this,"'.$this->tab.'")']));

        $currentPage = $this->pagination->getPage();
        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        if($endPage<=1){
            return null;
        }
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $active = ($currentPage == $i) ? 'active' : '';
            $shtml .= Html::tag('li',Html::a(($i+1),'javascript:;',['rel'=>$this->pagination->createUrl($i),'onclick'=>'javascript:LINK.to(this,"'.$this->tab.'")']),['class'=>$active]);
        }

        $shtml .= Html::tag('li',Html::a('>','javascript:;',['rel'=>$this->url['next'],'onclick'=>'javascript:LINK.to(this,"'.$this->tab.'")']));
        $shtml .= Html::tag('li',Html::a('>>','javascript:;',['rel'=>$this->url['last'],'onclick'=>'javascript:LINK.to(this,"'.$this->tab.'")']));

        $shtml .= '</ul>';
        $shtml .= '</nav>';
        
        return $shtml;
    }

    

    protected function registerLinkTags()
    {
        // $view = $this->getView();
        // var_dump($this->pagination->getLinks());
        $data = $this->pagination->getLinks();
        $this->url['first'] = isset($data['first']) ? $data['first'] : '';
        $this->url['prev'] = isset($data['prev']) ? $data['prev'] : '';
        $this->url['next'] = isset($data['next']) ? $data['next'] : '';
        $this->url['last'] = isset($data['last']) ? $data['last'] : '';

    }


    protected function getPageRange()
    {
        $currentPage = $this->pagination->getPage();
        $pageCount = $this->pagination->getPageCount();

        $beginPage = max(0, $currentPage - (int) ($this->maxButtonCount / 2));
        if (($endPage = $beginPage + $this->maxButtonCount - 1) >= $pageCount) {
            $endPage = $pageCount - 1;
            $beginPage = max(0, $endPage - $this->maxButtonCount + 1);
        }

        return [$beginPage, $endPage];
    }
}