<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $curr_id
 * @property string $name
 * @property string $currency_code
 * @property string $currency_value
 * @property string $currency
 * @property integer $status
 * @property string $modifydate
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'currency_code', 'currency_value', 'currency'], 'required'],
            [['currency_value'], 'number'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['currency_code', 'currency'], 'string', 'max' => 12],
            [['modifydate'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'curr_id' => Yii::t('info', 'Curr ID'),
            'name' => Yii::t('info', 'Name'),
            'currency_code' => Yii::t('info', 'Currency Code'),
            'currency_value' => Yii::t('info', 'Currency Value'),
            'currency' => Yii::t('info', 'Currency'),
            'status' => Yii::t('info', 'Status'),
            'modifydate' => Yii::t('info', 'Modifydate'),
        ];
    }

    public static function listData(){
        $model = static::find()->where('status=1')
                ->asArray()->all();
        $arr = null;
        foreach ($model as $k => $v) {
            $arr[$v['currency_code']] = $v;
        }
        return $arr;
    }
}
