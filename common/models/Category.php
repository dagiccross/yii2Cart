<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_category".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $model
 * @property string $type
 * @property string $key
 * @property string $link_url
 * @property integer $tag_id
 * @property integer $order_id
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'tag_id', 'order_id','top','footer','side'], 'integer'],
            [['model'], 'string', 'max' => 64],
            [['type'], 'string', 'max' => 8],
            [['show'], 'string', 'max' => 4],
            [['key'], 'string', 'max' => 12],
            [['link_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'pid' => Yii::t('info', \Yii::t('label','Parent ID')),
            'model' => Yii::t('info', \Yii::t('label','Page')),
            'type' => Yii::t('info', \Yii::t('label','Type')),
            'key' => Yii::t('info', \Yii::t('label','Key')),
            'link_url' => Yii::t('info', \Yii::t('label','Url')),
            'tag_id' => Yii::t('info', \Yii::t('label','Tag ID')),
            'order_id' => Yii::t('info', \Yii::t('label','Sort Order')),
            'show' => Yii::t('info', 'show'),
            'top' => Yii::t('info', 'nav'),
            'footer' => Yii::t('info', 'footer'),
            'side' => Yii::t('info', 'side'),
        ];
    }
}
