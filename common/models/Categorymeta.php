<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_categorymeta".
 *
 * @property integer $id
 * @property integer $cat_id
 * @property string $language
 * @property string $name
 * @property string $image
 */
class Categorymeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_categorymeta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id','name'], 'required'],
            [['cat_id'], 'integer'],
            [['language'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 255],
            [['description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'cat_id' => Yii::t('info', 'Cat ID'),
            'language' => Yii::t('info', 'Language'),
            'name' => Yii::t('info', 'Name'),
            'image' => Yii::t('info', 'Image'),
            'description'=> Yii::t('info','Description'),
        ];
    }
}
