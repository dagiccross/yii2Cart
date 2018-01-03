<?php

namespace backend\models;

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

    public static function showStatus($key=null){
        $arr = [null=>\Yii::t('app','Please Select'),
                '1'=>\Yii::t('app','Show'),
                '0'=>\Yii::t('app','No Show'),];
        if(empty($key)){
            return $arr;
        }else{
            return isset($arr[$key]) ? $arr[$key] : $arr[null];
        }
    }
}
