<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_back}}".
 *
 * @property integer $member_id
 * @property integer $flat
 * @property string $add_date
 */
class UserBack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_back}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'required'],
            [['member_id', 'flat'], 'integer'],
            [['add_date'], 'safe'],
            [['member_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => Yii::t('info', 'Member ID'),
            'flat' => Yii::t('info', 'In Backlist'),
            'add_date' => Yii::t('info', 'Add Date'),
        ];
    }
}
