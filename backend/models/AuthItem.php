<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property integer $id
 * @property string $controller_name
 * @property string $action_name
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller_name', 'action_name'], 'required'],
            [['controller_name', 'action_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'controller_name' => Yii::t('info', 'Controller Name'),
            'action_name' => Yii::t('info', 'Action Name'),
        ];
    }
}
