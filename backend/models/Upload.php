<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Upload extends Model
{
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['file'], 'required','message'=>Yii::t('info','{attribute} ').Yii::t('info','required')],
            [['file'], 'file', 'maxFiles' => 10,
                                'extensions' => 'jpg, png,gif,jpeg,bmp',
                                'mimeTypes' => 'image/jpeg, image/png, image/gif, image/jpeg, image/bmp',], 
            [['file'], 'file', 'maxFiles' => 300,
                                'extensions' => 'pdf',
                                'mimeTypes' => 'application/pdf','on'=>'pdf'], 
        ];
    }


    public function attributeLabels()
    {
        return [
            'file' => Yii::t('app', 'File'),
            
        ];
    }
}
