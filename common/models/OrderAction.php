<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_action}}".
 *
 * @property integer $action_id
 * @property integer $order_id
 * @property integer $user_id
 * @property string $user_flat
 * @property integer $adddate
 * @property string $order_status
 * @property string $comment
 */
class OrderAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'user_flat', 'order_status'], 'required'],
            [['order_id', 'user_id', 'adddate'], 'integer'],
            [['comment'], 'string'],
            [['user_flat', 'order_status'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => Yii::t('info', 'Action ID'),
            'order_id' => Yii::t('info', 'Order ID'),
            'user_id' => Yii::t('info', 'User ID'),
            'user_flat' => Yii::t('info', 'User Flat'),
            'adddate' => Yii::t('info', 'Adddate'),
            'order_status' => Yii::t('info', 'Order Status'),
            'comment' => Yii::t('info', 'Comment'),
        ];
    }
}
