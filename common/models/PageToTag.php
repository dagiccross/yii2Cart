<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%page_to_tag}}".
 *
 * @property integer $tag_id
 * @property integer $page_id
 */
class PageToTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_to_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'page_id'], 'required'],
            [['tag_id', 'page_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => Yii::t('info', 'Tag ID'),
            'page_id' => Yii::t('info', 'Page ID'),
        ];
    }
}
