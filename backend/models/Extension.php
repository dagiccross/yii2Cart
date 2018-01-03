<?php

namespace backend\models;

use Yii;

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
            [['key','card_fee'], 'string', 'max' => 48],
            [['tag'], 'string', 'max' => 14],
            [['alias','extsions','backendModel','picture'], 'string', 'max' => 255],
            [['status','sort_order'], 'integer'],
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
            'status' => Yii::t('info', 'Status'),
            'extsions' => Yii::t('info', 'Module Path'),
            'backendModel' => Yii::t('info', 'Model'),
            'sort_order' => Yii::t('info', 'Sort Order'),
            'picture' => Yii::t('info', 'Picture'),
        ];
    }

    // 关联meta表进行多表查
    public function getMeta($condition=null,$param=[]){
        if(empty($condition)){
            return $this->hasMany(Extensionmeta::className(), ['ext_id' => 'id']);
        }else{
            return $this->hasMany(Extensionmeta::className(), ['ext_id' => 'id'])
                        ->where($condition,$param);
        }
        
    }

    // 关联一个meta
    public function getOneMeta(){
        return $this->hasOne(Extensionmeta::className(), ['ext_id' => 'id'])->where(['language'=>\Yii::$app->language]);
    }

    static function paymentList(){
        $model = static::find()->with(['oneMeta'])
                ->where(['tag'=>'account','status'=>1])
                ->orderBy('sort_order asc')
                ->asArray()->all();
        return $model;
    }
    
}
