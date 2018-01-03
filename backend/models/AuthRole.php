<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auth_role}}".
 *
 * @property integer $id
 * @property string $role
 * @property string $name
 */
class AuthRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'name'], 'required'],
            [['role'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 64],
            [['role'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'role' => Yii::t('info', '角色标识'),
            'name' => Yii::t('info', '角色名称'),
        ];
    }
}
