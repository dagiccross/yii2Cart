<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_goodssku".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property double $price
 * @property integer $quanity
 * @property string $feature_arr
 */
class Goodssku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_goodssku';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'price', 'feature_arr'], 'required'],
            [['goods_id', 'quanity'], 'integer'],
            [['price'], 'number'],
            [['feature_arr'], 'string'],
            [['skuno'], 'string','max'=>24]
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
            'price' => Yii::t('info', 'Price'),
            'quanity' => Yii::t('info', 'Quanity'),
            'feature_arr' => Yii::t('info', 'Feature Arr'),
            'skuno' => Yii::t('info', 'Sku No.'),
        ];
    }
}
