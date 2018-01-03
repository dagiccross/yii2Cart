<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_goods".
 *
 * @property integer $id
 * @property string $cat_id
 * @property string $tag_id
 * @property integer $status
 * @property string $sku
 * @property string $pic
 * @property string $images
 * @property integer $quanity
 * @property double $price
 * @property string $addtime
 * @property string $modifytime
 * @property integer $order_id
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'quanity', 'order_id', 'goods_cat_id'], 'integer'],
            [['images'], 'string'],
            [['price'], 'number'],
            [['pic'], 'string', 'max' => 255],
            [['sku'], 'string', 'max' => 128],
            [['addtime', 'modifytime'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            // 'cat_id' => Yii::t('info', 'Cat ID'),
            // 'tag_id' => Yii::t('info', 'Tag ID'),
            'status' => Yii::t('info', 'Status'),
            'sku' => Yii::t('info', 'Sku'),
            'pic' => Yii::t('info', 'Pic'),
            'images' => Yii::t('info', 'Images'),
            'quanity' => Yii::t('info', 'Quanity'),
            'price' => Yii::t('info', 'Price'),
            'addtime' => Yii::t('info', 'Addtime'),
            'modifytime' => Yii::t('info', 'Modifytime'),
            'order_id' => Yii::t('info', 'Order ID'),
            'goods_cat_id' => Yii::t('app', 'Group ID'),
        ];
    }
    // 获取不同类型的产品
    public static function getTagGoods($tag_key = ''){
        $query = new \yii\db\Query();
        $rs = $query->select('g.*,m.title,m.content,m.language')
            ->from("{{%goods}} g")
            ->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
            ->leftJoin("{{%goods_to_tag}} g2t",'g.id=g2t.goods_id')
            ->leftJoin("{{%tag}} t",'t.id=g2t.tag_id')
            ->where('m.language=:language and status=1',[':language'=>\Yii::$app->language])
            ->andWhere('t.tag_key=:k0',[':k0'=>$tag_key])
            ->orderBy('g.order_id asc')
            ->all();
        return $rs;
    }
    // 获取所有的产品
    public static function getGoodsAll(){
        //產品過多導致前臺展示頁面超出内存限制
        // Allowed memory size of 33554432 bytes exhausted (tried to allocate 4104 bytes) 
        // 老嚴有時間再優化下 :)
        /*
        $query = new yii\db\Query();
        $rs = $query->select('g.*,m.title,m.content,m.language')
            ->from("{{%goods}} g")
            ->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
            ->leftJoin("{{%goods_to_tag}} g2t",'g.id=g2t.goods_id')
            ->leftJoin("{{%tag}} t",'t.id=g2t.tag_id')
            ->where('m.language=:language and status=1',[':language'=>\Yii::$app->language])
            ->andWhere('t.tag_key!="additional" or t.id is null')
            ->orderBy('g.order_id asc')
            ->all();
        foreach ($rs as $k => &$v) {
            $cat_id = getGoodsCategory($v['id']);
            $coupon = Coupon::getGoodsCoup($v['id']);
            
            $v['old_price'] = $v['price'];

            if(isset($coupon['type'])){
                $v['price'] = $coupon['type']=='0' ? $coupon['coupon']*$v['price'] : ($v['price']-$coupon['coupon']);
                
                $v['coupon_id'] =  $coupon['coup_id'];
                $v['coupon_name'] =  $coupon['name'];
                $v['coupon_type'] =  $coupon['type'];
                $v['coupon_price'] =  $coupon['coupon'];
            }

            $v['price'] = $v['price']>=0 ? $v['price'] : 0;

            // $goods_options_group = GoodsOptionsGroup::find()->where(['goods_id'=>$id])->andWhere('options_type="select"')->asArray()->all();

            if($v['price']=='0'){
                $options = GoodsOptions::find()->where(['goods_id'=>$v['id']])->asArray()->all();
                $v['goods_options'] = $options;

                foreach ($v['goods_options'] as $_o_k => &$_o_v) {
                    $_o_v['old_price'] = $_o_v['price'];
                    $_o_v['price'] = !empty($coupon)&&$v['price']==0 ? (isset($coupon['type'])&&$coupon['type']=='0' ? $coupon['coupon']*$_o_v['price'] : ($_o_v['price']-$coupon['coupon'])) : $_o_v['price'];
                    
                }
            }

            $v['cat_id'] = $cat_id;

        }
        
        return $rs;
        */

        // 避免連接數據庫次數過多
        // ini_set('memory_limit', '-1');
        ini_set('memory_limit', '256M');
        $baseQuery = (new \yii\db\Query())->select('g.*, gm.title, gm.content, gm.language, g2c.cat_id')
            ->from('{{%goods}} g')
            ->leftJoin('{{%goodsmeta}} gm', 'g.id = gm.goods_id')
            ->leftJoin('{{%goods_to_tag}} g2t', 'g.id = g2t.goods_id')
            ->leftJoin('{{%goods_to_category}} g2c', 'g.id = g2c.goods_id')
            ->leftJoin('{{%tag}} t', 't.id = g2t.tag_id')
            ->where(['status' => 1])
            ->andWhere(['gm.language' => \Yii::$app->language])
            ->andWhere(['or', ['!=', 't.tag_key', 'additional'], ['t.id' => null]])
            ->orderBy('g.order_id ASC');

        // echo $couponQuery->createCommand()->getRawSql();
        $data = $baseQuery->all();
        $goodsIds = \yii\helpers\ArrayHelper::getColumn($data, 'id');

        // Coupon
        $currentTime = time();
        $couponQuery = (new yii\db\Query())->select('c.*, g.*')
            ->from('{{%coupon}} c')
            ->leftJoin('{{%coupon_goods}} g', 'c.coup_id = g.coup_id')
            ->where(['c.status' => 1])
            ->andWhere(['c.flat_coup' => 1])
            ->andWhere(['>', 'c.coup_quanity', 0])
            ->andWhere(['g.goods_id' => $goodsIds])
            ->andWhere(['<=', 'c.start_date', $currentTime])
            ->andWhere(['>=', 'c.end_date', $currentTime])
            ->all();
        $couponData = [];
        foreach ($couponQuery as $val) {
            $couponData[$val['goods_id']] = $val;
        }

        // Options
        $optionsQuery = GoodsOptions::find()->where(['goods_id' => $goodsIds])->asArray()->all();
        $optionsData = [];
        foreach ($optionsQuery as $val) {
            if (!isset($optionsData[$val['goods_id']])) {
                $optionsData[$val['goods_id']] = [];
            }
            $optionsData[$val['goods_id']][] = $val;
        }

        $newData = [];
        foreach ($data as $val) {
            $gId = $val['id'];
            if (isset($newData[$gId])) {
                $newData[$gId]['cat_id'][] = $val['cat_id'];
                continue;
            }

            $val['cat_id'] = [$val['cat_id']];

            $tmpCoupon = isset($couponData[$gId]) ? $couponData[$gId] : [];
            $tmpOptions = isset($optionsData[$gId]) ? $optionsData[$gId] : [];

            $val['old_price'] = $val['price'];

            if (isset($tmpCoupon['type'])) {
                $val['price'] = $tmpCoupon['type'] == '0' ? $tmpCoupon['coupon'] * $val['price'] : ($val['price'] - $tmpCoupon['coupon']);
                $val['coupon_id'] =  $tmpCoupon['coup_id'];
                $val['coupon_name'] =  $tmpCoupon['name'];
                $val['coupon_type'] =  $tmpCoupon['type'];
                $val['coupon_price'] =  $tmpCoupon['coupon'];
            }

            $val['price'] = $val['price'] >= 0 ? $val['price'] : 0;

            if ($val['price'] == '0') {

                $val['goods_options'] = $tmpOptions;

                foreach ($val['goods_options'] as $_o_k => &$_o_v) {
                    $_o_v['old_price'] = $_o_v['price'];
                    $_o_v['price'] = (!empty($tmpCoupon) && $val['price'] == 0) ? ((isset($tmpCoupon['type']) && $tmpCoupon['type'] == '0') ? ($tmpCoupon['coupon'] * $_o_v['price']) : ($_o_v['price'] - $tmpCoupon['coupon'])) : $_o_v['price'];
                }
            }

            $newData[$gId] = $val;
        }

        $newData = array_values($newData);

        return $newData;
    }
    // 获取扩展信息
    public static function optionsData($id){
        $goods_options_group = GoodsOptionsGroup::find()->where(['goods_id'=>$id])->andWhere('options_type!="select"')->asArray()->all();

        $data = [];

        $i =0;
        $j =0;
        if(empty($goods_options_group)){
            return null;
        }

        foreach ($goods_options_group as $k => $v) {
            if($v['options_type']!='select'){
                $goods_options = GoodsOptions::find()
                        ->where(['goods_id'=>$id,'g_options_group_id'=>$v['g_options_group_id']])
                        ->asArray()->all();
                $v['goods_options'] = $goods_options;
                $data['options'][] = $v;
            }
            
        }
        return $data;
    }
}
