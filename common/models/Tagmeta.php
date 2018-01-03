<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_tagmeta".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property string $language
 * @property string $name
 */
class Tagmeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_tagmeta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id','name'], 'required'],
            [['tag_id'], 'integer'],
            [['language'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'tag_id' => Yii::t('info', '标签ID'),
            'language' => Yii::t('info', '语言code'),
            'name' => Yii::t('info', '标签名称'),
        ];
    }
}
