<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property integer $id
 * @property string $picture
 * @property string $pictures
 * @property integer $page_id
 * @property string $thumb
 * @property string $thumb_2h
 * @property string $thumb_2w
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pictures'], 'string'],
            [['page_id'], 'required'],
            [['page_id'], 'integer'],
            [['picture', 'thumb', 'thumb_2h', 'thumb_2w'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'picture' => Yii::t('info', 'Picture'),
            'pictures' => Yii::t('info', 'Pictures'),
            'page_id' => Yii::t('info', 'Page ID'),
            'thumb' => Yii::t('info', 'Thumb'),
            'thumb_2h' => Yii::t('info', 'Thumb 2h'),
            'thumb_2w' => Yii::t('info', 'Thumb 2w'),
        ];
    }
}
