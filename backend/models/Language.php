<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%language}}".
 *
 * @property integer $language_id
 * @property string $name
 * @property string $code
 * @property string $image
 * @property string $directory
 * @property string $filename
 * @property integer $sort_order
 * @property integer $status
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'image', 'directory', 'filename', 'sort_order', 'status'], 'required'],
            [['sort_order', 'status'], 'integer'],
            [['name', 'directory'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 5],
            [['image', 'filename'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => Yii::t('info', '语言ID'),
            'name' => Yii::t('info', '名稱'),
            'code' => Yii::t('info', '代碼'),
            'image' => Yii::t('info', '圖標'),
            'directory' => Yii::t('info', '目錄'),
            'filename' => Yii::t('info', '文件'),
            'sort_order' => Yii::t('info', '排序'),
            'status' => Yii::t('info', '啟用'),
        ];
    }

    public static function listData($code='en-us',$flat=1){
        $value = Yii::$app->cache->get('language');

        if(empty($value)){
            $rs = static::find()
                    ->where('status=1')
                    ->orderBy('sort_order asc')
                    ->asArray()
                    ->all();
            $value = array();
            foreach ($rs as $k => $v) {
                $value[$v['code']] = $v;
            }
            Yii::$app->cache->set('language',$value,3600);
        }
        if($flat){
            return $value;
        }else{
            return $value[$code];
        }
        
    }

    public static function getDefault(){
        $model = self::listData();
        return key($model);
    }
}
