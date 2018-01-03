<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username',  'password'], 'required','message'=>Yii::t('info','{attribute} ').Yii::t('info','required')],
            ['verifyCode', 'captcha','message'=>Yii::t('info','verify code error!')],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {   
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            // echo $password;Yii::$app->end();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }

        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            $flat =  Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            // $flat =1;
            if($flat){
                // $model = Logininfo::findOne(['username'=>$this->username]);
                // $model->logintime = time();
                // $model->loginip = Yii::$app->request->userIP;

                // $model->save();
                Logininfo::updateAll(['logintime'=>time(),'loginip'=>Yii::$app->request->userIP],'username=:name',[':name'=>$this->username]);
                return $flat;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Logininfo::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'User Name'),
            'password' => Yii::t('app', 'Password'),
            'verifyCode' => Yii::t('app', 'Verify Code'),
            'rememberMe' => Yii::t('app', 'Remember Me'),
        ];
    }
}
