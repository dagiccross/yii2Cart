<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $group_id
 * @property integer $order_id
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => Yii::t('info', 'Group ID'),
            'order_id' => Yii::t('info', 'Order ID'),
        ];
    }
}
