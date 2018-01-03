<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_pagemeta".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $language
 * @property string $content
 * @property string $description
 * @property string $image
 */
class Pagemeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_pagemeta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id','title'], 'required'],
            [['page_id'], 'integer'],
            [['content', 'description', 'image'], 'string'],
            [['language'], 'string', 'max' => 12],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'page_id' => Yii::t('info', 'Page ID'),
            'language' => Yii::t('info', 'Language'),
            'content' => Yii::t('info', 'Content'),
            'description' => Yii::t('info', 'Description'),
            'image' => Yii::t('info', 'Image'),
            'title' => Yii::t('info', 'Title'),
        ];
    }
}
