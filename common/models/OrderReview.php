<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_review}}".
 *
 * @property integer $review_id
 * @property integer $order_id
 * @property integer $money
 * @property integer $delivery
 * @property string $name
 * @property string $comment
 * @property integer $member_id
 */
class OrderReview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_review}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'member_id','comment','name'], 'required'],
            [['order_id', 'money', 'delivery', 'member_id','food','flat'], 'integer'],
            [['comment'], 'string', 'max' => 1024],
            [['comment'], 'filter', 'filter' => 'trim'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_id' => Yii::t('info', 'Review ID'),
            'order_id' => Yii::t('label', 'Order ID'),
            'money' => Yii::t('info', 'Money'),
            'delivery' => Yii::t('info', 'Delivery'),
            'name' => Yii::t('info', 'Name'),
            'comment' => Yii::t('info', 'Comment'),
            'member_id' => Yii::t('info', 'Member ID'),
            'flat' => Yii::t('info', 'Show')
        ];
    }
}
