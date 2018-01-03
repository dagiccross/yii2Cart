<?php

namespace common\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%Extension}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $tag
 */
class Extension extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%extension}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key'], 'string', 'max' => 48],
            [['sort_order','status'], 'integer'],
            [['tag'], 'string', 'max' => 14],
            [['alias','card_fee'], 'string', 'max' => 255],
            [['key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('info', 'ID'),
            'key' => Yii::t('info', 'Key'),
            'tag' => Yii::t('info', 'Tag'),
            'alias' => Yii::t('info', 'Alias'),
            'card_fee' => Yii::t('info', 'Card Fee'),
            'sort_order' => Yii::t('info', 'Sort Order'),
            'status' => Yii::t('info', 'Status'),
        ];
    }

    public static function getPayment($key=''){
        $rs = (new \yii\db\Query())->select("e.*,m.*")
            ->from("{{%extension}} e")
            ->leftJoin("{{%extensionmeta}} m",'e.id=m.ext_id')
            ->where('e.tag="account" and e.status!=0')
            ->andWhere('m.language=:language',[':language'=>\Yii::$app->language]);
        if(!empty($key)){
            $rs = $rs->andWhere('e.`key`=:key',[':key'=>$key])
                ->one();
            $rs['options'] = @unserialize($rs['options']);


        }else{
            $rs = $rs->orderBy("e.sort_order asc")
                ->all();
        }
        
        return $rs;  
        
    }

    public static function getMetaById($id){
        
    }
}
