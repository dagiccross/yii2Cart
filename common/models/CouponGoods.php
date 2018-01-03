<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%coupon_goods}}".
 *
 * @property integer $coup_id
 * @property integer $goods_id
 * @property integer $quanity
 */
class CouponGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coup_id', 'goods_id'], 'required'],
            [['coup'], 'number'],
            [['coup_id', 'goods_id', 'quanity'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coup_id' => Yii::t('info', 'Coup ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'quanity' => Yii::t('info', 'Quanity'),
            'quanity' => Yii::t('info', 'Coupon'),
        ];
    }

    public static function getCouponGoods($coup_id= ''){
        if(!empty($coup_id)){
            $query = new \yii\db\Query();
            $rs = $query->select('g.*,m.title,m.content,m.language')
                ->from("{{%goods}} g")
                ->leftJoin("{{%goodsmeta}} m",'g.id=m.goods_id')
                ->leftJoin(self::tableName()." cg",'g.id=cg.goods_id')
                ->where('m.language=:language and status=1',[':language'=>\Yii::$app->language])
                ->andWhere('cg.coup_id=:k0',[':k0'=>$coup_id])
                ->orderBy('g.order_id asc')
                ->all();
            return $rs;
        }
        return null;
    }
}
