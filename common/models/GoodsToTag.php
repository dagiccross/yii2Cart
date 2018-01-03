<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_to_tag}}".
 *
 * @property integer $goods_id
 * @property integer $tag_id
 */
class GoodsToTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_to_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'tag_id'], 'required'],
            [['goods_id', 'tag_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => Yii::t('info', 'Goods ID'),
            'tag_id' => Yii::t('info', 'Tag ID'),
        ];
    }
}
