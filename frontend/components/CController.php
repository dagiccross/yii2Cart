<?php

namespace frontend\components;
use yii\web\Controller;
use common\models\Config;

class CController extends Controller
{
	public $wrap = 'about-wrap';
	public $config = [];
	public $allergy = '';
	public $agent = '';
	public function init(){

		$this->config = Config::getAllConfig();
		$this->allergy = getPageByKey('allergy');
		$this->agent = getAgent();
		parent::init();
	}

	public function getConfig($key){
		return isset($this->config[$key]) ? $this->config[$key] : null;
	}
}
