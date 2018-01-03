<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%upfile}}".
 *
 * @property integer $id
 * @property string $pic
 * @property string $thumb
 * @property integer $order_id
 */
class Upfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upfile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['pic', 'thumb'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'pic' => Yii::t('info', 'Picture Url'),
            'thumb' => Yii::t('info', 'Thumb Nail'),
            'order_id' => Yii::t('info', 'Sort Order'),
        ];
    }
}
