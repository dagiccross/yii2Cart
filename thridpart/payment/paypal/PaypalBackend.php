<?php

namespace thridpart\payment\paypal;

use yii;
use yii\helpers\ArrayHelper;
use backend\models\Extensionmeta;

class PaypalBackend extends \yii\db\ActiveRecord{

    public function getData($id){
        $data = self::queryData(['ext_id'=>$id]);
        $arr = [];

        foreach ($data as $k => $v) {
            $arr[$v['language']] = $v;
        }
        return $arr;
    }

    public function doSave($id,$info,$language=null){
        $flat = 0;

        if(is_array($language)){
            foreach ($language as $k => $lang) {
                $options = serialize($info[$k]);
                $i = Extensionmeta::updateAll(['options'=>$options],['ext_id'=>$id,'language'=>$k]);
                $flat +=$i;
            }
        }
        $data['status'] = $flat==0 ? 0 : 1;
        $data['message'] = $flat==0 ? \Yii::t('app','Error') : \Yii::t('app','Success');
        return $data;
    }

    public static function queryData($condition=null,$param=null){
        $models = Extensionmeta::find()->where($condition,$param)->asArray()->all();
        return $models;
    }
}