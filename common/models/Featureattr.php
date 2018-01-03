<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%featureattr}}".
 *
 * @property integer $id
 * @property string $feature_code
 * @property string $name
 * @property string $options
 * @property integer $order_id
 */
class Featureattr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%featureattr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feature_code'], 'required'],
            [['options'], 'string'],
            [['order_id'], 'integer'],
            [['feature_code'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'feature_code' => Yii::t('info', 'Feature'),
            'name' => Yii::t('info', 'Name'),
            'options' => Yii::t('info', 'Options Value'),
            'order_id' => Yii::t('info', 'Order ID'),
        ];
    }
}
