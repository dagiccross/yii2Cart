<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%time}}".
 *
 * @property integer $id
 * @property integer $time
 * @property integer $Monday
 * @property integer $Tuesday
 * @property integer $Wednesday
 * @property integer $Thursday
 * @property integer $Friday
 * @property integer $Saturday
 * @property integer $Sunday
 */
class Time extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%time}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time'], 'required'],
            [['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 'integer'],
            [['type'],'string','max'=>12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'time' => Yii::t('info', 'Time'),
            'type' => Yii::t('info', 'Type'),
            'Monday' => Yii::t('info', 'Monday'),
            'Tuesday' => Yii::t('info', 'Tuesday'),
            'Wednesday' => Yii::t('info', 'Wednesday'),
            'Thursday' => Yii::t('info', 'Thursday'),
            'Friday' => Yii::t('info', 'Friday'),
            'Saturday' => Yii::t('info', 'Saturday'),
            'Sunday' => Yii::t('info', 'Sunday'),
        ];
    }

    public static function type($key=''){
        $arr = ['collection'=>Yii::t('info', 'Collection'),
                'delivery'=>Yii::t('info', 'Delivery')];

        return empty($key) ? $arr : $arr[$key];

    }

    // 獲取送餐時間列表
    public static function getTimeList($type='collection'){
        $m = date('w');
        // echo $m;exit();
        $week = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $M = date("l");

        $data = [];
        for ($i=$m; $i <7 ; $i++) {

            if($week[$i]==$M){
                $data[$week[$i]] = self::getTimeAll($week[$i],$type,1);
                // var_dump($data);exit();
            }else{
                $data[$week[$i]] = self::getTimeAll($week[$i],$type,0);
                // var_dump($data);
            }
            // echo $i;
        }
        // var_dump($data);exit();
        if($m!=0){
            $data['Sunday'] = self::getTimeAll('Sunday',$type,0);
        }

        $time = [];
        // Added for ASAP WORKAROUND
        $time['ASAP'] = 'ASAP';

        foreach($data as $k => $v) {

            foreach ($v as $_k => $_v) {
                // $_v_time = date('H:i',$_v['time']);
                $_v['time'] = strtotime($_v['time']);
                $_v['time'] = date('H:i',$_v['time']);
                $time[$k.' '.$_v['time']] = $k.' '.$_v['time'];
            }

        }
        return $time;
    }

    // 獲取最近的送餐時間
    public static function getTime($type){
        $M = date("l");

        // $time = time();
        $time = date('H:i');

        $model = static::find()
                ->where(['type'=>$type])
                ->andWhere($M.'=1')
                ->andWhere('time >="'.$time.'"')
                ->orderBy('time asc')
                ->asArray()
                ->one();

        return empty($model) ? null : $model['time'];
    }

    // 獲取所有的送餐時間
    public static function getTimeAll($M,$type='collection',$flat=0){

        $model = static::find()
                ->where(['type'=>$type])
                ->andWhere($M.'=1');
        // var_dump($M);exit();
        if($flat){
            $c_key = $type == 'collection' ? 'Collection_Time' : 'Delivery_Time';
            $time_type = Config::getConfig($c_key);
            $time_type = preg_replace('/\–|\,/is','-',$time_type);
            $time_type = explode('-', $time_type);

            $time_trim = !empty($time_type['0']) ? trim($time_type['0']) : '';
            $time_trim = (int)$time_trim>0 ? $time_trim : 0;
            // var_dump((int)$time_trim); exit();

            $time = strtotime(date('H:i'))+(int)$time_trim*60;
            $time = date('H:i',$time);
            // var_dump($time);
            // exit();
            $model = $model->andWhere('time >=:k0',[':k0'=>$time]);
            // $model = $model->orderBy('time asc')
            // ->asArray()
            // ->all();
            // var_dump($model);
            // exit();
        }
        $model = $model->orderBy('time asc')
            ->asArray()
            ->all();

        return $model;
    }
}
