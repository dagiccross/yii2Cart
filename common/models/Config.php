<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property string $options
 * @property string $values
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['options'], 'required'],
            [['values'], 'string'],
            [['options'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', Yii::t('label','ID')),
            'options' => Yii::t('info', Yii::t('label','Options')),
            'values' => Yii::t('info', Yii::t('label','Values')),
        ];
    }

    public static function getConfig($option)
    {
        $model = static::getAllConfig();

        return isset($model[$option]) ? $model[$option] : NULL;
    }

    public static function getAllConfig(){

        $config = \Yii::$app->cache->get('allconfig');

        if(empty($config)||!$config['cache']){
            $config = [];

            $model = static::find()->asArray()->all();

            for ($i=0; $i < count($model) ; $i++) {

                $config[$model[$i]['options']] = $model[$i]['values'];
            }
            // 當cache不啟用時
            if(!$config['cache']){
                \Yii::$app->cache->set('allconfig',$config);
            }

        }
        return $config;
    }

    public static function getCoupFlat($key=''){

        $arr = ['0'=>\Yii::t('info','coupons'),'1'=>\Yii::t('info','discount'),'2'=>\Yii::t('info','Up FREE'),'3'=>\Yii::t('info','FREE Shipment')];

        return ($key=='') ? $arr : $arr[$key];
    }

    public static function getCoupType($key=''){

        $arr = ['0'=>\Yii::t('label','Percentages Off'),'1'=>\Yii::t('label','Subtract Amount')];//,'2'=>\Yii::t('label','FREE')
        // echo $key;exit();
        return ($key=='') ? $arr : $arr[$key];
    }

    public static function moneyFormat($money=0){
        return sprintf("%.2f",$money);
    }

    public static function currencyMoney($money){
        $currency =Yii::$app->controller->getConfig('currency');
        return $currency.sprintf("%.2f",$money);
    }

    public static function orderFormat($order_no){
        return sprintf("%05d", $order_no);
    }
}
