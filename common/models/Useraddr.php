<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_addr}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $shipment_name
 * @property string $shipment_city
 * @property string $shipment_addr
 * @property string $shipment_phone
 * @property integer $default
 */
class Useraddr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_addr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id',], 'required'],
            [['alias','shipment_city','shipment_addr'], 'required','on'=>['address']],
            [['member_id', 'default','flat'], 'integer'],
            [['shipment_name','alias'], 'string', 'max' => 128],
            [['shipment_city'], 'string', 'max' => 68],
            [['shipment_addr','shipment_addr2','shipment_addr3'], 'string', 'max' => 255],
            [['shipment_phone','shipment_postcode','shipment_postcode2'], 'string', 'max' => 18]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'shipment_name' => Yii::t('app', 'Shipment Name'),
            'shipment_city' => Yii::t('app', 'Shipment City'),
            'shipment_addr' => Yii::t('app', 'Shipment Addr'),
            'shipment_addr2' => Yii::t('app', 'Shipment Addr').'2',
            'shipment_addr3' => Yii::t('app', 'Shipment Addr').'3',
            'shipment_phone' => Yii::t('app', 'Shipment Phone'),
            'shipment_postcode' => Yii::t('app', 'Shipment Code'),
            'default' => Yii::t('app', 'Default'),
        ];
    }
}
