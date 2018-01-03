<?php

namespace backend\models;

use Yii;
// use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $passwd
 * @property string $email
 * @property string $phone
 * @property string $power
 * @property integer $fen
 * @property double $money
 * @property double $freezing
 * @property string $addtime
 * @property string $modifytime
 * @property string $loginip
 * @property string $status
 * @property string $auth_key
 */
class User extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return '{{%user}}';
    }

    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'passwd', 'email', 'addtime'], 'required'],
            [['email'],'unique'],
            [['fen','member_discount'], 'integer'],
            [['money', 'freezing'], 'number'],
            [['username'], 'string', 'max' => 32],
            [['passwd'], 'string', 'max' => 126],
            [['email'], 'string', 'max' => 64],
            [['phone', 'loginip'], 'string', 'max' => 18],
            [['power'], 'string', 'max' => 8],
            // [['addtime', 'modifytime'], 'string', 'max' => 64],
            [['status'], 'integer', 'max' => 2],
            [['username', 'email'], 'unique', 'targetAttribute' => ['username', 'email'], 'message' => 'The combination of Username and Email has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'username' => Yii::t('label', 'User name'),
            'passwd' => Yii::t('label', 'Password'),
            'email' => Yii::t('label', 'E-mail'),
            'phone' => Yii::t('label', 'Phone'),
            'power' => Yii::t('label', 'Power'),
            'fen' => Yii::t('label', 'Points'),
            'money' => Yii::t('label', 'Money'),
            'freezing' => Yii::t('label', 'Freezing'),
            'addtime' => Yii::t('label', 'Creation Date'),
            'modifytime' => Yii::t('label', 'Last Modify Date'),
            'loginip' => Yii::t('label', 'Login ip'),
            'status' => Yii::t('label', 'Status'),
            'member_discount' => Yii::t('label', 'Member Discount'),
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
   

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->passwd === md5($password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->passwd = md5($password);
    }

    public function md5Password($password)
    {
        return md5($password);
    }
    

}
