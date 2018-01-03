<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%featuremeta}}".
 *
 * @property integer $feature_id
 * @property string $name
 * @property string $language
 */
class Featuremeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%featuremeta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feature_id', 'name', 'language'], 'required'],
            [['feature_id'], 'integer'],
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
            'feature_id' => Yii::t('info', 'Feature ID'),
            'name' => Yii::t('info', 'Name'),
            'language' => Yii::t('info', 'Language'),
        ];
    }
}
