<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_page".
 *
 * @property integer $id
 * @property string $tag_id
 * @property string $status
 * @property string $key
 * @property string $url
 * @property integer $order_id
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['tag_id', 'url'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 4],
            [['key'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'tag_id' => Yii::t('info', 'Tag ID'),
            'status' => Yii::t('info', 'Status'),
            'key' => Yii::t('info', 'Key'),
            'url' => Yii::t('info', 'Url'),
            'order_id' => Yii::t('info', 'Order ID'),
        ];
    }
}
