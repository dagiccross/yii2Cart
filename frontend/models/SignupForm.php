<?php
namespace frontend\models;

use common\models\User;
use common\models\Config;

use common\models\Useraddr;
use yii\base\Model;
use Yii;
use yii\helpers\Html;
/**
 * Signup form
 */

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $offers;
    public $policy;
    public $rememberMe;

    public $shipment_name;
    public $shipment_city;
    public $shipment_addr;
    public $shipment_addr2;
    public $shipment_addr3;
    public $shipment_phone;
    public $shipment_postcode;
    public $shipment_postcode2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['shipment_phone', 'string', 'min' => 11, 'max' => 13],

            // ['email', 'filter', 'filter' => 'trim'],
            ['policy', 'required'],
            ['shipment_name', 'required'],
            ['shipment_addr', 'required'],
            ['shipment_postcode2', 'required'],
            ['shipment_phone', 'required'],
            // ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            [['password','confirm_password'], 'required'],
            [['password','confirm_password'], 'string', 'min' => 6],
            ['password','compare', 'compareAttribute'=>'confirm_password'],
            [['shipment_addr','shipment_addr2','shipment_addr3'],'string','max' => 24],
            [['offers','rememberMe','shipment_name','shipment_city','shipment_phone','shipment_postcode','shipment_postcode2'],'string', 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->username;
            $user->setPassword($this->password);
            $user->offers = $this->offers;
            $user->phone = $this->shipment_phone;
            $user->addtime = time();
            $user->modifytime = $user->addtime;
            $user->status = 1;
            $user->loginip = \Yii::$app->request->userIP;
            // var_dump($this);
            // return false;
            $transaction=Yii::$app->db->beginTransaction();

            try {
                if ($user->save()) {
                    if(!empty($this->shipment_name)){
                        $useraddr = new Useraddr();
                        $useraddr->member_id = $user->primaryKey;
                        $useraddr->shipment_name = $this->shipment_name;
                        $useraddr->shipment_city = $this->shipment_city;
                        $useraddr->shipment_addr = $this->shipment_addr;
                        $useraddr->shipment_addr2 = $this->shipment_addr2;
                        $useraddr->shipment_addr3 = $this->shipment_addr3;
                        $useraddr->shipment_phone = $this->shipment_phone;
                        $useraddr->shipment_postcode = $this->shipment_postcode;
                        $useraddr->shipment_postcode2 = $this->shipment_postcode2;

                        $useraddr->flat = 1;

                        if($useraddr->validate()){
                            $useraddr->save();

                        }
                    }
                    $transaction->commit();

                    $this->sendEmail($user->email);

                    return $user;
                }
            } catch (Exception $e) {
                $transaction->rollback();
            }

        }

        return null;
    }

    public function sendEmail($email,$from_email='')
    {
        $from_email = empty($from_email) ? \common\models\Config::getConfig('smtp_user') : $from_email;

        require_once(dirname(\Yii::$app->basePath).'/thridpart/phpmailer/class.phpmailer.php');
        $mail = new \PHPMailer();
        $mail->IsSMTP();                  // send via SMTP
        $mail->Host = \common\models\Config::getConfig('smtp_server');   // SMTP servers

        $ssl = \common\models\Config::getConfig('smtp_ssl');
        $ssl = empty($ssl)||$ssl==false ? false : true;
        $port = \common\models\Config::getConfig('smtp_port');
        $port = empty($port) ? '25' : $port;

        $mail->SMTPAuth = $ssl;           // turn on SMTP authentication
        $mail->Port = \common\models\Config::getConfig('smtp_port'); // SMTP Port $mail->Port = Config::getConfig('smtp_port'); // SMTP Port

        $mail->Username = \common\models\Config::getConfig('smtp_user');     // SMTP username  注意：普通邮件认证不需要加 @域名
        $mail->Password = \common\models\Config::getConfig('smtp_password'); // SMTP password
        $mail->From = $from_email;      // 发件人邮箱
        $mail->FromName =  \common\models\Config::getConfig('smtp_user');  // 发件人

        $mail->CharSet = "UTF-8";   // 这里指定字符集！
        $mail->Encoding = "base64";
        $mail->AddAddress($email);  // 收件人邮箱和姓名

        $mail->IsHTML(true);  // send as HTML
        // 邮件主题
        $mail->Subject = 'Signup infomation';
        // 邮件内容
        $mail_html = Html::tag('p','Hello '.$this->shipment_name.',');
        $mail_html .= Html::tag('p','Thank you for registering on our web site.');
        $mail_html .= Html::tag('p','Name : '.$this->shipment_name);
        $mail_html .= Html::tag('p','Email : '.$email);
        $mail_html .= Html::tag('p','You can now sign in to our website with your e-mail address and password.');
        $mail_html .= Html::tag('p','Use the "Forgot your password?" function on our web site to create a new password at any time.');
        $mail_html .= Html::tag('p','If you did not perform this registration, you do not have to do anything.');
        $mail_html .= Html::tag('p',\common\models\Config::getConfig('company_name'));
        $mail->Body = $mail_html;

        $mail->AltBody ="text/html";
        // echo $this->cusTemplate($email);
        // exit();
        return $mail->Send();
    }

}
