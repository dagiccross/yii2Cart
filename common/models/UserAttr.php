<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_attr}}".
 *
 * @property integer $member_id
 * @property string $name
 * @property string $phone
 * @property string $city
 * @property string $postcode
 * @property string $postcode2
 */
class UserAttr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_attr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','email'], 'string', 'max' => 128],
            [['phone', 'postcode', 'postcode2'], 'string', 'max' => 18],
            [['city'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => Yii::t('info', 'Member ID'),
            'name' => Yii::t('info', 'Name'),
            'phone' => Yii::t('info', 'Phone'),
            'city' => Yii::t('info', 'City'),
            'email' => Yii::t('info', 'email'),
            'postcode' => Yii::t('info', 'Postcode'),
            'postcode2' => Yii::t('info', 'Postcode2'),
        ];
    }
}
