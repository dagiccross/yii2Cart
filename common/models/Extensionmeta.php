<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%extensionmeta}}".
 *
 * @property integer $ext_id
 * @property string $name
 * @property string $options
 * @property string $language
 */
class Extensionmeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%extensionmeta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ext_id','name'], 'required'],
            [['ext_id'], 'integer'],
            [['options'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ext_id' => Yii::t('info', 'Ext ID'),
            'name' => Yii::t('info', 'Name'),
            'goods' => Yii::t('info', 'Product ID'),
            'options' => Yii::t('info', 'Options'),
            'language' => Yii::t('info', 'Language'),
        ];
    }
}
