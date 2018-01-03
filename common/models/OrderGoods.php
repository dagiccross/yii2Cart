<?php

namespace common\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property integer $order_id
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
    }

    public static function findOrderGoods($order_id){
        $rs = (new Query())->select("og.*,g.pic,g.sku as sku_no")
                ->from("{{%order_goods}} og")
                ->leftJoin("{{%goods}} g",'g.id=og.goods_id')
                ->where(['og.order_id'=>$order_id])
                ->all();

        for ($i=0; $i <count($rs) ; $i++) {
            $rs[$i]['sku'] = [];
            if(!empty($rs[$i]['feature'])){
                $feature = explode(':', $rs[$i]['feature']);
                if(isset($feature['0'])){
                    $rs_freature = (new Query())->select("gf.*")
                        ->from("{{%goodsfeature}} gf")
                        ->where(['gf.goods_id'=>$rs[$i]['goods_id'],'gf.fatt_id'=>$feature['0']])
                        ->one();
                    $rs[$i]['sku'][] = $rs_freature['options'];
                }

                if(isset($feature['1'])){
                    $rs_freature = (new Query())->select("gf.*")
                        ->from("{{%goodsfeature}} gf")
                        ->where(['gf.goods_id'=>$rs[$i]['goods_id'],'gf.fatt_id'=>$feature['1']])
                        ->one();
                    $rs[$i]['sku'][] = $rs_freature['options'];
                }
            }

            $rs[$i]['goods_options'] =self::findOrderGoodsOptions($order_id,$rs[$i]['goods_id'],$rs[$i]['id']);

        }

        return $rs;

    }

    public static function findOrderGoodsOptions($order_id,$goods_id,$order_goods_id){
        $rs = OrderGoodsOptions::find()->with('options')->where(['order_id'=>$order_id,
            'goods_id'=>$goods_id,
            'order_goods_id'=>$order_goods_id
            ])->asArray()->all();
        return $rs;
    }

}
