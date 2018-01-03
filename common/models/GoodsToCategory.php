<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_to_cateogry}}".
 *
 * @property integer $goods_id
 * @property integer $cat_id
 */
class GoodsToCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_to_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'cat_id'], 'required'],
            [['goods_id', 'cat_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => Yii::t('info', 'Goods ID'),
            'cat_id' => Yii::t('info', 'Cat ID'),
        ];
    }
}
