<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii2_goodsmeta".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $language
 */
class Goodsmeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_goodsmeta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'title'], 'required'],
            [['goods_id'], 'integer'],
            [['description', 'content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'title' => Yii::t('info', '标题'),
            'description' => Yii::t('info', '短描述'),
            'content' => Yii::t('info', '内容'),
            'language' => Yii::t('info', '语言'),
        ];
    }
}
