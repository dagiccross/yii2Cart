<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_goodsattr".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $attr_name
 * @property string $attr_value
 * @property integer $order_id
 */
class Goodsattr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_goodsattr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id', 'order_id'], 'integer'],
            [['attr_value'], 'string'],
            [['language'], 'string', 'max' => 12],
            [['attr_name'], 'string', 'max' => 255],
            [['goods_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'attr_name' => Yii::t('info', 'Attributes Name'),
            'attr_value' => Yii::t('info', 'Attributes Value'),
            'order_id' => Yii::t('info', 'Order ID'),
            'language' => Yii::t('info', 'Language'),
        ];
    }
}
