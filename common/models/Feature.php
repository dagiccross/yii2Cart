<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%feature}}".
 *
 * @property integer $id
 * @property string $feature
 * @property string $options
 * @property integer $order_id
 * @property integer $group
 */
class Feature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feature}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['options'], 'string'],
            [['order_id', 'group_id'], 'integer'],
            [['feature'], 'string', 'max' => 64],
            [['feature'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'feature' => Yii::t('info', 'Feature'),
            'options' => Yii::t('info', 'Options Value'),
            'order_id' => Yii::t('info', 'Order ID'),
            'group_id' => Yii::t('info', 'Group ID'),
        ];
    }
}
