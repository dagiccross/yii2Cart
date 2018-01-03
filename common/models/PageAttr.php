<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%page_attr}}".
 *
 * @property integer $page_id
 * @property string $attr_name
 * @property string $attr_value
 * @property integer $order_id
 * @property string $language
 */
class PageAttr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_attr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id'], 'required'],
            [['page_id', 'order_id'], 'integer'],
            [['attr_value'], 'string'],
            [['attr_name'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('info', 'Page ID'),
            'attr_name' => Yii::t('info', '扩展名称'),
            'attr_value' => Yii::t('info', '扩展值'),
            'order_id' => Yii::t('info', '排序'),
            'language' => Yii::t('info', 'Language'),
        ];
    }
}
