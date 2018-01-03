<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_goodsfeature".
 *
 * @property integer $id
 * @property integer $fatt_id
 * @property integer $goods_id
 * @property integer $feature_id
 * @property string $options
 */
class Goodsfeature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_goodsfeature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fatt_id', 'goods_id', 'feature_code'], 'required'],
            [['fatt_id', 'goods_id',], 'integer'],
            [['feature_code'], 'string', 'max' => 24],
            [['options'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'fatt_id' => Yii::t('info', 'Fatt ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'feature_id' => Yii::t('info', 'Feature ID'),
            'options' => Yii::t('info', 'Options'),
        ];
    }
}
