<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_options}}".
 *
 * @property integer $g_options_id
 * @property integer $goods_id
 * @property integer $g_options_group_id
 * @property string $name
 * @property integer $quanity
 * @property integer $subtract
 * @property string $price
 * @property string $price_prefix
 * @property string $weight
 * @property string $weight_prefix
 */
class GoodsOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'g_options_group_id', 'name'], 'required'],
            [['goods_id', 'g_options_group_id', 'quanity', 'subtract'], 'integer'],
            [['price', 'weight'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['price_prefix', 'weight_prefix'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_options_id' => Yii::t('info', 'G Options ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'g_options_group_id' => Yii::t('info', 'G Options Group ID'),
            'name' => Yii::t('info', 'Name'),
            'quanity' => Yii::t('info', 'Quanity'),
            'subtract' => Yii::t('info', 'Subtract'),
            'price' => Yii::t('info', 'Price'),
            'price_prefix' => Yii::t('info', 'Price Prefix'),
            'weight' => Yii::t('info', 'Weight'),
            'weight_prefix' => Yii::t('info', 'Weight Prefix'),
        ];
    }

    public function getGroup(){
        return $this->hasOne(GoodsOptionsGroup::className(),['g_options_group_id'=>'g_options_group_id']);
    }
}
