<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{%logininfo}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $passwd
 * @property string $email
 * @property string $power
 * @property string $logintime
 * @property string $loginip
 * @property string $auth_key
 * @property string $auth_koken
 */
class Logininfo extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;//1=>active
    public $auth_key;
    public $password_reset_token;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%logininfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'passwd', 'email', 'power'], 'required'],
            [['username', 'auth_key'], 'string', 'max' => 64],
            [['passwd'], 'string', 'max' => 126],
            [['email'], 'string', 'max' => 48],
            [['power'], 'string', 'max' => 12],
            // [['logintime'], 'string', 'max' => 10],
            [['loginip'], 'string', 'max' => 45],
            [['auth_koken'], 'string', 'max' => 128],
            [['status'], 'string', 'max' => 2],
            [['username'], 'unique']
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
            'power' => Yii::t('label', 'Power'),
            'logintime' => Yii::t('label', 'Login time'),
            'loginip' => Yii::t('label', 'Login ip'),
            'auth_key' => Yii::t('label', 'Auth Key'),
            'auth_koken' => Yii::t('label', 'Auth Koken'),
            'status' => Yii::t('label', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getName(){
        return $this->username;
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {   
        // var_dump(md5($password.Yii::$app->params['acckey']));exit();
        
        return $this->passwd === md5($password.Yii::$app->params['acckey']);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->passwd = md5($password.Yii::$app->params['acckey']);
    }

    public function md5Password($password)
    {
        return md5($password.Yii::$app->params['acckey']);
    }
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function userFlat($key=null){
        $arr = ['admin'=>'admin','user'=>'user'];
        return $key===null ? $arr : $arr[$key];
    }
}
