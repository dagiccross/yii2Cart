<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auth_rule}}".
 *
 * @property integer $id
 * @property string $role
 * @property integer $item_id
 * @property string $data_id
 */
class AuthRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'item_id'], 'required'],
            [['item_id'], 'integer'],
            [['data_id'], 'string'],
            [['role'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'role' => Yii::t('info', 'Role'),
            'item_id' => Yii::t('info', 'Item ID'),
            'data_id' => Yii::t('info', 'Data ID'),
        ];
    }
}
