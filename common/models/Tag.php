<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_tag".
 *
 * @property integer $id
 * @property string $type
 * @property integer $order_id
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['type','tag_key'], 'string', 'max' => 12],
            [['tag_key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'type' => Yii::t('info', 'Tag'),
            'order_id' => Yii::t('info', 'Order ID'),
            'tag_key' => Yii::t('info', 'Key'),
        ];
    }
}
