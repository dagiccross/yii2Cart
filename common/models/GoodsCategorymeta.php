<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_categorymeta}}".
 *
 * @property integer $g_cat_id
 * @property string $name
 * @property string $language
 */
class GoodsCategorymeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_categorymeta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['g_cat_id', 'name', 'language'], 'required'],
            [['g_cat_id'], 'integer'],
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
            'g_cat_id' => Yii::t('info', 'G Cat ID'),
            'name' => Yii::t('info', 'Name'),
            'language' => Yii::t('info', 'Language'),
        ];
    }
}
