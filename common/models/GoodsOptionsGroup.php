<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%goods_options_group}}".
 *
 * @property integer $g_options_group_id
 * @property integer $goods_id
 * @property string $name
 * @property string $options
 * @property string $options_type
 * @property integer $required
 */
class GoodsOptionsGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_options_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'name', 'options_type'], 'required'],
            [['goods_id', 'required'], 'integer'],
            [['name', 'options'], 'string', 'max' => 255],
            [['options_type'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'g_options_group_id' => Yii::t('info', 'G Options Group ID'),
            'goods_id' => Yii::t('info', 'Goods ID'),
            'name' => Yii::t('info', 'Name'),
            'options' => Yii::t('info', 'Options'),
            'options_type' => Yii::t('info', 'Options Type'),
            'required' => Yii::t('info', 'Required'),
        ];
    }

    public static function groupOptions($goods_id){
        $query = new yii\db\Query();
        $rs = $query->select('o.*,g.name group_name,g.required')
            ->from("{{%goods_options}} o")
            ->leftJoin("{{%goods_options_group}} g",'o.g_options_group_id=g.g_options_group_id')
            ->where('g.goods_id=:key0',[':key0'=>$goods_id])
            ->orderBy('g.required desc,g.g_options_group_id asc')
            ->all();
        // echo base64_decode('UlYzNnRESGM4dHlI');

        return $rs;
    }
}
