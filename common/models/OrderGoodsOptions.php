<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_goods_options}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $name
 * @property string $price
 * @property integer $quanity
 */
class OrderGoodsOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_goods_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'name'], 'required'],
            [['order_id', 'quanity','g_options_id','required','goods_id','order_goods_id','g_options_group_id'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'order_id' => Yii::t('info', 'Order ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'name' => Yii::t('info', 'Name'),
            'price' => Yii::t('info', 'Price'),
            'quanity' => Yii::t('info', 'Quanity'),
            'required' => Yii::t('info', 'Required'),
            'g_options_id' => Yii::t('info', 'Goods Options ID'),
            'order_goods_id' => Yii::t('info', 'Order Goods ID'),
            'g_options_group_id' => Yii::t('info', 'Goods Options group ID'),
        ];
    }

    public function getGroup(){
        return $this->hasOne(GoodsOptionsGroup::className(),['g_options_group_id'=>'g_options_group_id']);
    }

    public function getOptions(){
        return $this->hasOne(GoodsOptions::className(),['g_options_id'=>'g_options_id'])->with('group');
    }
}
