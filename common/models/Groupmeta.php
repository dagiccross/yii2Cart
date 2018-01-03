<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%groupmeta}}".
 *
 * @property integer $group_id
 * @property string $name
 * @property string $language
 */
class Groupmeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%groupmeta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'name', 'language'], 'required'],
            [['group_id'], 'integer'],
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
            'group_id' => Yii::t('info', 'Group ID'),
            'name' => Yii::t('info', 'Name'),
            'language' => Yii::t('info', 'Language'),
        ];
    }
}
