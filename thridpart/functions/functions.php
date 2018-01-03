<?php
// 设置用户权限
define("SITE_URL","http://".$_SERVER['HTTP_HOST'].'');

// 獲取cache信息
function cacheconfig(){
	return common\models\Config::getConfig('cache');
}

// 獲取配置信息
function getConfig($options){

	return common\models\Config::getConfig($options);
}

function userFlat($power,$flat=1){

	$model = backend\models\AuthRole::find()->asArray()->all();
	$arr = array();
	// $arr = ['spuer'=>'spuer',
	// 		'admin'=>'admin',
	// 		'user'=>'user',
	// 		'passer'=>'passer-by'];
	foreach ($model as $key => $value) {
		$arr[$value['role']] = $value['name'];
	}
	if($flat)
		return $arr;
	else
		return isset($arr[$power]) ? $arr[$power] : '';
}

// 查找分类下级ID;
function categoryChild($pid=0,$flat=TRUE){
	$query = new yii\db\Query();
	$rs = $query->select('c.id,m.name')
                ->from("{{%category}} c")
                ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id')
                ->where('c.pid=:pid ',[':pid'=>$pid])
                ->groupBy('c.id')
                ->all();
    if($flat){
    	$arr = array();
	    $arr[$pid] = '根级分类';
	    foreach ($rs as $key => $value) {
			$arr[$value['id']] = $value['name'];
		}
		return $arr;
    }else{
    	return $rs;
    }

}

// 特征组ID
function group(){
	$query = new yii\db\Query();
	$rs = $query->select('g.group_id,m.name')
                ->from("{{%group}} g")
                ->leftJoin("{{%groupmeta}} m",'g.group_id=m.group_id')
                // ->where('m.language=:language',[':language'=>\Yii::$app->language])
                ->groupBy('g.group_id')
                ->orderBy('g.order_id asc')
                ->all();
    $arr = [0=>\Yii::t('app','Please choose')];
    foreach ($rs as $k => $v) {
    	$arr[$v['group_id']] = $v['name'];
    }
    return $arr;
}

// 特征分类信息
function featureType(){
	$query = new yii\db\Query();
	$rs = $query->select('g.feature,m.name')
                ->from("{{%feature}} g")
                ->leftJoin("{{%featuremeta}} m",'g.id=m.feature_id')
                // ->where('m.language=:language',[':language'=>\Yii::$app->language])
                ->groupBy('g.id')
                ->orderBy('g.id asc,g.order_id asc')
                ->all();
    $arr = [];

	foreach ($rs as $key => $value) {
		$arr[$value['feature']] = $value['name'];
	}
		return $arr;

}
// 设置账号状态
function userStatus($status,$flat=1){
	$arr = ['0'=>'off','1'=>'on','2'=>'being'];
	if($flat)
		return $arr;
	else
		return isset($arr[$status]) ? $arr[$status] : '';
}

// 标签类型
function tagType($type='',$flat=1){
	$arr = ['page'=>Yii::t('app','page'),
			'product'=>Yii::t('app','product'),
			'list'=>Yii::t('app','list'),];
	if($flat){
		return $arr;
	}else{
		return isset($arr[$type]) ? $arr[$type] : '';
	}
}
// 标签分类
function tagCat($type='',$flat=1){
	$query = new yii\db\Query();
	$rs = $query->select('c.id,c.type,m.name')
                ->from("{{%tag}} c")
                ->leftJoin("{{%tagmeta}} m",'c.id=m.tag_id');
                // ->where('m.language=:language',[':language'=>\Yii::$app->language]);
    if(!empty($type)){
    	$rs = $rs->where('c.type=:type',[':type'=>$type]);
    }
    $rs = $rs->groupBy('c.id')->all();
    if($flat){
    	return $rs;
    }else{
    	$arr = array();
    	foreach ($rs as $k => $v) {
    		$arr[$v['id']] = $v['name'];
    	}
    	return $arr;
    }

}
// 显示图片
function showImg($url){
	if(!empty($url)){
		$arr = explode('://', $url);
		if($arr['0']=='http' || $arr['0']=='https'){
			return $url;
		}else{
			return SITE_URL.'/'.$url;
		}
	}
	return false;
}

// 顯示頁面內容
function showContent($content){
	$content = preg_replace("/{{IMG_URL}}/is", IMG_URL, $content);
	if(\Yii::$app->user->isGuest){
		$content = preg_replace("/\[LOGOUT\](.*)\[\/LOGOUT\]/is", '', $content);
		$content = preg_replace("/\[LOGIN\]/is", '', $content);
		$content = preg_replace("/\[\/LOGIN\]/is", '', $content);
	}else{
		$content = preg_replace("/\[LOGIN\](.*)\[\/LOGIN\]/is", '', $content);
		$content = preg_replace("/\[LOGOUT\]/is", '', $content);
		$content = preg_replace("/\[\/LOGOUT\]/is", '', $content);
		$content = preg_replace("/{{USERNAME}}/is", \Yii::$app->user->identity->username, $content);
	}
	// $config = preg_match("/\[CONFIG\]company_tel\[\/CONFIG\]/is", $content);

	$config = \common\models\Config::getConfig('company_tel');
	$content = preg_replace("/\[CONFIG\]company_tel\[\/CONFIG\]/is", $config, $content);
	$config = \common\models\Config::getConfig('logo');
	$content = preg_replace("/\[CONFIG\]logo\[\/CONFIG\]/is", $config, $content);

	return $content;
}

// 分类树级取值
function showChlid($id,$category=array()){
	$root = categoryChild($id,0);
	$html = '';
	if(empty($root)){
		return;
	}else{
		$html = '<ul class="list-style">';
		foreach ($root as $k => $v) {
			$isCheck = is_array($category) ? (in_array($v['id'], $category) ? true : false) : false;
			$html .= '<li>';
			$html .= \yii\helpers\Html::checkbox('category[]',$isCheck,['value'=>$v['id']]);
			$html .= \yii\helpers\Html::label($v['name']);
			$html .= showChlid($v['id'],$category);
			$html .= '</li>';
		}

		$html .= '</ul>';
		return $html;
	}
}

// 获取分类子分类信息
function getCategoryChlid($id=0,$key=''){
	$query = new yii\db\Query();
	$param = 'id=:condition';
	$condition = [':condition'=>$id];
	if(!empty($key)){
		$param = '`key`=:condition';
		$condition = [':condition'=>$key];
	}
	$rs = $query->select('c.*,m.cat_id,m.language,m.name,m.image')
                ->from("{{%category}} c")
                ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id')
                ->where('c.show=1 and m.language=:key0',[':key0'=>\Yii::$app->language]);
    if(!empty($key)){
		$param = '`key`=:condition';
		$condition = [':condition'=>$key];
		$rs = $rs->andWhere("c.pid=(select id from {{%category}} where ".$param." limit 0,1)",$condition);
	}else{
		$rs = $rs->andWhere("c.pid=:condition",$condition);
	}

    $rs = $rs->orderBy('c.order_id asc')
          	->groupBy('c.id')
            ->all();
    return $rs;
}

// 获取分类信息
function getCategory($id=0,$nav='top',$key='',$condition='',$param=''){
	$query = new yii\db\Query();
	$con1 = 'c.pid=:condition';
	$param1 = [':condition'=>$id];
	if(!empty($key)){
		$con1 = '`key`=:condition';
		$param1 = [':condition'=>$key];
	}


	$rs = $query->select('c.*,m.cat_id,m.language,m.name,m.image,m.description')
                ->from("{{%category}} c")
                ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id')
                ->where('m.language=:language AND c.show=1',[':language'=>Yii::$app->language])
                ->andWhere('c.'.$nav.'=:nav',[':nav'=>'1'])
                ->andWhere($con1,$param1);
    if(!empty($condition)){
    	$rs = $rs->andWhere($condition,$param);
    }

    $rs = $rs->orderBy('c.order_id asc')
          ->all();

    return $rs;
}

//獲取分類信息
function getCategoryById($id='',$key='id'){
	$query = new yii\db\Query();
	$rs = $query->select('c.*,m.cat_id,m.language,m.name,m.image,m.description')
                ->from("{{%category}} c")
                ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id')
                ->where('m.language=:language AND c.show=1',[':language'=>Yii::$app->language])
                ->andWhere('c.'.$key.'=:nav',[':nav'=>$id])
                ->one();
    return $rs;
}

//獲取分類信息
function getCategoryByGoodsId($id=''){
	$query = new yii\db\Query();
	$rs = $query->select('c.*,m.cat_id,m.language,m.name,m.image')
                ->from("{{%category}} c")
                ->leftJoin("{{%categorymeta}} m",'c.id=m.cat_id')
                ->leftJoin("{{%goods_to_category}} g2c",'c.id=g2c.cat_id')
                ->where('m.language=:language AND c.show=1',[':language'=>\Yii::$app->language])
                ->andWhere('g2c.goods_id=:nav',[':nav'=>$id])
                ->orderBy('c.pid desc,c.order_id asc')
                ->one();
    return $rs;
}

// 通过分类ID获取产品
function getGoodsByCat($cat=0,$flat=1,$limit=0,$offset=0){
	$query = new yii\db\Query();
	$rs = $query->select('g.*,m.title,m.content,m.language')
		->from("{{%goods}} g")
		->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
		->leftJoin("{{%goods_to_category}} g2c",'g.id=g2c.goods_id')
		->where('m.language=:language and status=1',[':language'=>Yii::$app->language])
		->andWhere('g2c.cat_id=:cat_id',[':cat_id'=>$cat])
		->orderBy('g.order_id asc');
	if($limit){
		$rs = $rs->limit($limit)->offset($offset);
	}
		$rs  = $rs->all();
	foreach ($rs as $k => $v) {
		$cat_id = getGoodsCategory($v['id']);
		if($v['price']=='0'){
			$options = common\models\GoodsOptions::find()->where(['goods_id'=>$v['id']])->asArray()->all();
			$rs[$k]['goods_options'] = $options;
		}
		$rs[$k]['cat_id'] = $cat_id;

	}
	// var_dump($rs);
	return $rs;
}

// 通过分类ID获取产品
function getGoodsOrderBy($cat='g.gorder_id asc',$flat=1,$limit=0){
	$query = new yii\db\Query();
	$rs = $query->select('g.*,m.title,m.description,m.language')
		->from("{{%goods}} g")
		->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
		->where('m.language=:language and status=1',[':language'=>Yii::$app->language])
		// ->andWhere(['like','g.cat_id',$cat])
		->orderBy($cat);
	if($limit){
		$rs = $rs->limit($limit)->offset(0);
	}
		$rs  = $rs->all();
	return $rs;
}

// 获取所有产品列表
function getGoodsAll(){
	$query = new yii\db\Query();
	$rs = $query->select('g.*,m.title,m.content,m.language')
		->from("{{%goods}} g")
		->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
		->where('m.language=:language and status=1',[':language'=>\Yii::$app->language])
		->orderBy('g.order_id asc')
		->all();
	foreach ($rs as $k => $v) {
		$cat_id = getGoodsCategory($v['id']);
		if($v['price']=='0'){
			$options = common\models\GoodsOptions::find()->where(['goods_id'=>$v['id']])->asArray()->all();
			$rs[$k]['goods_options'] = $options;
		}
		$rs[$k]['cat_id'] = $cat_id;

	}
	// var_dump($rs);
	return $rs;
}
//通过ID获取所有产品信息
function getGoodsById($id,$flat=1){
	$query = new yii\db\Query();
	$rs = $query->select('g.*,m.title,m.language,m.content,m.description,a.attr_value,f.fatt_id,f.feature_code,f.options')
		->from("{{%goods}} g")
		->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
		->leftJoin("{{%goodsattr}} a",'g.id=a.goods_id and a.attr_name="attr"')
		->leftJoin("{{%goodsfeature}} f",'g.id=f.goods_id')
		->where('m.language=:language',[':language'=>Yii::$app->language])
		->andWhere('g.id=:id',[':id'=>$id])
		->all();


	$data = array();
	if(!empty($rs)){
		$data = $rs['0'];
		$data['attr'] = @unserialize($data['attr_value']);
		$data['images'] = @unserialize($data['images']);
		unset($data['fatt_id']);
		unset($data['feature_code']);
		unset($data['options']);
		unset($data['attr_value']);
	}

	if(!empty($data['goods_cat_id'])):
	$feature_rs = (new yii\db\Query())->select("f.id,f.feature,m.name,m.language")
				->from("{{%feature}} f")
				->leftJoin("{{%featuremeta}} m",'m.feature_id=f.id')
				->where("f.group_id = (select g.group_id from {{%goods_category}} g where g.g_cat_id=:key)",[':key'=>$data['goods_cat_id']])
				->andWhere('m.language=:key2',[':key2'=>\Yii::$app->language])
				->orderBy("f.order_id asc")
				->all();


	foreach ($feature_rs as $k => $v) {
		foreach ($rs as $_k => $_v) {
			if($v['feature'] == $_v['feature_code'])
				$data[$v['feature']][] = array('fatt_id'=>$_v['fatt_id'],'feature_code'=>$_v['feature_code'],'options'=>$_v['options']);
		}
		$data['group'][$k] = $v;
	}
	endif;

	return $data;
}

// 获取产品的SKU信息
function getGoodsSku($id){
	$rs = (new yii\db\Query())->select("*")
				->from("{{%goodssku}}")
				->all();
	return $rs;
}

// 获取产品分类
function getGoodsCategory($goods_id){
	$query = new yii\db\Query();
	$rs = $query->select('g.cat_id')
		->from("{{%goods_to_category}} g")
		->where('g.goods_id=:key0',[':key0'=>$goods_id])
		->all();
	$arr = [];
	foreach ($rs as $k => $v) {
		$arr[] = $v['cat_id'];
	}
	return $arr;
}
// 获取语言信息列表
function getLanguage($code='en-us',$flat=1){
	$value = Yii::$app->cache->get('language');
	if(empty($value)){
		$rs = (new yii\db\Query())->select("*")
				->from("{{%language}}")
				->orderBy('sort_order asc')
				->all();
		$value = array();
		foreach ($rs as $k => $v) {
			$value[$v['code']] = $v;
		}
		Yii::$app->cache->set('language',$value,3600);
	}
	if($flat){
		return $value;
	}else{
		return $value[$code];
	}

}

// 后台语言
function language($code='zh-CN',$flat=1){
	$arr = array('zh-CN'=>'中文','en-US'=>'English');
	if($flat){
		return $arr;
	}else{
		return isset($arr[$code]) ? $arr[$code] : $arr['en-US'];
	}

}

// 获取扩展信息slider
function getExtSlider($key='slider'){
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

// 获取扩展信息slider
function getExtdata($key='slider',$tag='slider'){
	$rs = (new yii\db\Query())->select("e.*,m.*")
		->from("{{%extension}} e")
		->leftJoin("{{%extensionmeta}} m",'e.id=m.ext_id')
		->where('`key`=:key and tag=:tag',[':key'=>$key,'tag'=>$tag])
		->andWhere('m.language=:language',[':language'=>\Yii::$app->language])
		->orderBy("id asc")
		->one();
	$rs['options'] = @unserialize($rs['options']);
	return $rs;
}

// 获取页面信息key
function getPageByKey($key,$condition='key'){
	$rs = (new yii\db\Query())->select("p.*,m.*")
		->from("{{%page}} p")
		->leftJoin("{{%pagemeta}} m",'p.id=m.page_id')
		->where('p.`'.$condition.'`=:key and p.status=1',[':key'=>$key])
		->andWhere('m.language=:language',[':language'=>Yii::$app->language])
		->one();
	$rs['image'] = @unserialize($rs['image']);
	return $rs;
}

// 通过分类id查找对应的页面
function getPageByCatId($cat,$type='page'){

	if($type=='page'){
		$rs = (new yii\db\Query())->select("p.*,m.*")
			->from("{{%page}} p")
			->leftJoin("{{%pagemeta}} m",'p.id=m.page_id')
			->where('p.`key`=(select `model` from {{%category}} where id =:key) and p.status=1',[':key'=>$cat])
			->andWhere('m.language=:language',[':language'=>Yii::$app->language])
			->orderBy("id asc")
			->one();
		$rs['image'] = @unserialize($rs['image']);
	}else if($type=='list'){
		// 通过分类ID查找对应页面列表
		$rs = (new yii\db\Query())->select("p.*,m.*")
			->from("{{%page}} p")
			->leftJoin("{{%pagemeta}} m",'p.id=m.page_id')
			->leftJoin("{{%category}} c",'c.model=p.tag_id')
			->where('c.id=:key and p.status=1',[':key'=>$cat])
			->andWhere('m.language=:language',[':language'=>Yii::$app->language])
			->orderBy("p.order_id asc")
			->all();
	}

	return $rs;
}

// 获取页脚
function getFooter(){
	$rs = (new yii\db\Query())->select("m.content")
			->from("{{%page}} p")
			->leftJoin("{{%pagemeta}} m",'p.id=m.page_id')
			->where('p.`key`=:key and p.status=1',[':key'=>'footer'])
			->andWhere('m.language=:language',[':language'=>Yii::$app->language])
			->one();
	return $rs['content'];
}

// 获取运费邮编
function getShipmentPostcode($type='list'){
	$rs = common\models\ShipmentPostcode::find()->asArray()->all();

	if($type=='list'){
		$arr = [];
		for ($i=0; $i < count($rs) ; $i++) {

			$arr[$rs[$i]['id']] = $rs[$i]['postcode'];
		}
		return $arr;
	}else{
		return $rs;
	}


}
// 获取用户agent
function getAgent(){
	$sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
	if(stristr($_SERVER['HTTP_USER_AGENT'],'Android')) {
	   	//echo '你的手机是：Android系统';
		return 'Android';
	}else if(stristr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
	   	//echo '你的手机是：ISO系统';
		return 'iPhone';
	}else{
	   	//echo '你使用的是其他系统';
		return 'Other';
	}
}
