<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $coup_id
 * @property string $coup_no
 * @property string $name
 * @property string $type
 * @property string $coup_value
 * @property integer $coup_quanity
 * @property integer $total_quanity
 * @property string $start_date
 * @property string $end_date
 * @property integer $status
 * @property integer $flat_date
 * @property integer $flat_coup
 * @property integer $monday
 * @property integer $tuesday
 * @property integer $wednesday
 * @property integer $thursday
 * @property integer $friday
 * @property integer $saturday
 * @property integer $sunday
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coup_no', 'name'], 'required'],
            [['coup_value','total'], 'number'],
            [['coup_quanity', 'total_quanity', 'status', 'flat_date', 'flat_coup', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 'integer'],
            [['coup_no'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 2],
            [['start_date', 'end_date'], 'string', 'max' => 25],
            [['memo'], 'string'],
            [['coup_no'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coup_id' => Yii::t('info', 'Coupon ID'),
            'coup_no' => Yii::t('info', 'Coupon Code'),
            'name' => Yii::t('info', 'Coupon Description'),
            'type' => Yii::t('info', 'Calculation Method'),
            'coup_value' => Yii::t('info', 'Coupon Discount Value (Input Less Than 1 If "Persentage Off". Example: 0.8 = 20% off)'),
            'coup_quanity' => Yii::t('info', 'Coupon Issue Quanity'),
            'total_quanity' => Yii::t('info', 'Total Quanity'),
            'start_date' => Yii::t('info', 'Start Date'),
            'end_date' => Yii::t('info', 'End Date'),
            'status' => Yii::t('info', 'Status'),
            'flat_date' => Yii::t('info', 'Use Week In Coupon'),
            'flat_coup' => Yii::t('info', 'Coupon Type'),
            'monday' => Yii::t('info', 'Monday'),
            'tuesday' => Yii::t('info', 'Tuesday'),
            'wednesday' => Yii::t('info', 'Wednesday'),
            'thursday' => Yii::t('info', 'Thursday'),
            'friday' => Yii::t('info', 'Friday'),
            'saturday' => Yii::t('info', 'Saturday'),
            'sunday' => Yii::t('info', 'Sunday'),
            'total' => Yii::t('info', 'Apply Coupon If Total Order Value Exceed Or Equivalent To:'),
            'memo' => Yii::t('info', 'Memo'),
        ];
    }

    // 產品打折
    public static function getGoodsCoup($goods_id){
        $time = time();

        $rs = (new yii\db\Query())->select("c.*,g.*")
            ->from("{{%coupon}} c")
            ->leftJoin("{{%coupon_goods}} g",'c.coup_id=g.coup_id')
            ->where('c.flat_coup=1 and c.status=1 and c.coup_quanity>0')
            ->andWhere('g.goods_id=:key0',[':key0'=>$goods_id])
            ->andWhere('c.start_date<=:key1 and c.end_date>=:key1',[':key1'=>$time])
            ->one();
        $rs['coupon'] = isset($rs['coup'])&&!empty($rs['coup']) ? $rs['coup'] : (isset($rs['coup_value']) ? $rs['coup_value'] : '');
        return $rs;
    }

    // 滿就包郵
    public static function freeShip($total){
        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>3])
                                ->andWhere('coup_quanity>0')
                                ->andWhere('total<=:key0',[':key0'=>$total])
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->asArray()->one();
        return $model;
    }
    // 優惠卷
    public static function coupon($coup_no){
        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>0,'coup_no'=>$coup_no])
                                ->andWhere('coup_quanity>0')
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->asArray()->one();

        return $model;
    }

    // 滿就減
    public static function freeUp($total){
        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>2])
                                ->andWhere('total<=:key0',[':key0'=>$total])
                                ->andWhere('coup_quanity>0')
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->orderBy('total desc')
                                ->asArray()->one();
        return $model;
    }

    // 滿就減
    public static function freeUpData($condition=null,$param=nul){
        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>2])
                                ->andWhere('coup_quanity>0')
                                ->andWhere($condition,$param)
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->asArray()->one();
        return $model;
    }

    // 第一次打折
    public static function firstCoupon($flat=0){
        $order_exists = empty(\Yii::$app->user->identity->fen) ? 0 : 1;
        // var_dump(\Yii::$app->user->identity->fen);
        // exit();
        // $order_exists = Order::find()->where(['member_id'=>$member_id])->exists();

        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>4])
                                ->andWhere('coup_quanity>0')
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->asArray()->one();

        return !$order_exists||$flat ? $model : null;
    }

    // 满就送产品
    public static function upGoods($total){
        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>5])
                                ->andWhere('coup_quanity>0')
                                ->andWhere('total<=:key0',[':key0'=>$total])
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->orderBy('total desc')
                                ->asArray()->one();
        return $model;
    }

    // 會員打折
    public static function freeMember(){
        $model = null;
        if(isset(\Yii::$app->user->identity->member_discount)){
            $time = time();
            $M = strtolower(date("l"));
            $model = static::find()->where(['status'=>1,'flat_coup'=>6])
                                    ->andWhere('coup_quanity>0')
                                    ->andWhere('coup_id=:key0',[':key0'=>\Yii::$app->user->identity->member_discount])
                                    ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                    ->asArray()->one();
        }

        return $model;
    }
    // 折扣类型
    public static function getCoupFlat($key=''){

        $arr = ['0'=>\Yii::t('info','Coupon Code'),
                '1'=>\Yii::t('info','Specific Goods Discount (Need To Fill In Goods Information)'),
                '2'=>\Yii::t('info','Discount Offer If Purchase Over X Total Value'),
                '3'=>\Yii::t('info','FREE Shipment If Purchase Over X Total Value'),
                '4'=>\Yii::t('info','First Order Discount'),
                '5'=>\Yii::t('info','FREE Goods If Purchase Over X Total Value (Fill Details In Memo)'),
                '6'=>\Yii::t('info','Member Discount')
                ];

        return ($key==='') ? $arr : (isset($arr[$key]) ? $arr[$key] : null);
    }

    // 折扣方法
    public static function getCoupType($key=''){

        $arr = ['0'=>\Yii::t('label','Percentage Off'),'1'=>\Yii::t('label','Subtract Amount')]; //,'2'=>\Yii::t('label','FREE')
        // echo $key;exit();
        return ($key==='') ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }


    // 折扣处理
    public static function couponCart($total=0,$coupon_no=null){
        // 0首單免費计算是按百分比
        $free_first = self::firstCoupon();

        if(!empty($free_first)){
            $total = $free_first['type']=='0' ? $total*$free_first['coup_value'] : ($total-$free_first['coup_value']);
        }
        // 0是首次打折
        $list[0] = $free_first;

        //1優惠卷
        $coupon = null;

        if(!empty($coupon_no)){
            $coupon = self::coupon($coupon_no);

            $total = !empty($coupon['coup_value']) ? ($coupon['type']=='0' ? $total*$coupon['coup_value'] : ($total-$coupon['coup_value'])) : $total;

        }
        // 1是优惠券
        $list[1] = $coupon;

        // 2滿就減
        $free_up = self::freeUp($total);
        if(!empty($free_up)){
            $total = $free_up['type']=='0' ? $total*$free_up['coup_value'] : ($total-$free_up['coup_value']);
        }
        // 2是满就减
        $list[2] = $free_up;

        // 3滿就包郵件
        $free_ship = self::freeShip($total);
        // 3是满就包邮
        $list[3] = $free_ship;

        // 4满就送菜
        $up_goods = self::upGoods($total);

        $list[4] = $up_goods;

        // 5會員打折
        $member_discount = self::freeMember();

        $list[5] = $member_discount;

        // 6折扣后的总价
        $list[6] = $total;

        return $list;

    }

    // 會員折扣列表
    public static function memberList(){
        $time = time();
        $M = strtolower(date("l"));
        $model = static::find()->where(['status'=>1,'flat_coup'=>6])
                                ->andWhere('CASE flat_date WHEN 1 THEN '.$M.'=1 ELSE start_date<="'.$time.'" and end_date>="'.$time.'" END')
                                ->orderBy('coup_id asc')
                                ->asArray()->all();
        $arr = [];
        foreach ($model as $k => $v) {
            $arr[$v['coup_id']] = $v['name'];
        }
        return $arr;
    }
}
