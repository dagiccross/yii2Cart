<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shipment_postcode}}".
 *
 * @property integer $id
 * @property string $postcode
 * @property string $price
 */
class ShipmentPostcode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipment_postcode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postcode', 'price'], 'required'],
            [['price'], 'number'],
            [['postcode'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'postcode' => Yii::t('info', 'Postcode'),
            'price' => Yii::t('info', 'Price'),
        ];
    }
}
