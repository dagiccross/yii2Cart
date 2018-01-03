<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_category}}".
 *
 * @property integer $g_cat_id
 * @property integer $group_id
 * @property integer $order_id
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'order_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_cat_id' => Yii::t('info', 'G Cat ID'),
            'group_id' => Yii::t('info', 'Group ID'),
            'order_id' => Yii::t('info', 'Order ID'),
        ];
    }
}
