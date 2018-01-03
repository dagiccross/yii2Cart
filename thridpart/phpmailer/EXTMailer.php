<?php
namespace thridpart\phpmailer;

// require(dirname(\Yii::$app->basePath).'/thirdpart/phpexcel/PHPExcel.php');
require('class.phpmailer.php');

class EXTMailer{
	private $mailer;
	private $to;
	public $from;
	public $subject;
	public $body;

	public function __construct(){
		$this->mailer['host'] = \common\models\Config::getConfig('smtp_server');
		$ssl = \common\models\Config::getConfig('smtp_ssl');
		$ssl = empty($ssl)||$ssl==false ? false : true;
		$port = \common\models\Config::getConfig('smtp_port');
		$port = empty($port) ? '25' : $port;

		$this->mailer['ssl'] = $ssl;
		$this->mailer['port'] = $port;

		$this->mailer['smtp_user'] = \common\models\Config::getConfig('smtp_user');
		$this->mailer['smtp_password'] = \common\models\Config::getConfig('smtp_password');

	}

	public function AddAddress($email){
		$this->to = $email;
	}

	// public function AddBCC($bccmail) {
	// 	$this->bcc = $bccmail;
	// }


	public function Send(){

		$mailer = new \PHPMailer();
		$mailer->ClearAllRecipients(); // clear all TESTING duplicate emails
		$mailer->IsSMTP();                  // send via SMTP
		$mailer->Host = $this->mailer['host'];   // SMTP servers



		$mailer->SMTPAuth = $this->mailer['ssl'];           // turn on SMTP authentication
		$mailer->Port = $this->mailer['port']; // SMTP Port $mail->Port = Config::getConfig('smtp_port'); // SMTP Port

		$mailer->Username = $this->mailer['smtp_user'];     // SMTP username  注意：普通邮件认证不需要加 @域名
		$mailer->Password = $this->mailer['smtp_password']; // SMTP password

		$mailer->CharSet = "UTF-8";   // 这里指定字符集！
		//$mailer->Encoding = "base64";   // No encoded for Vincent printing

		$mailer->FromName =  $this->mailer['smtp_user'];  // 发件人

		$mailer->IsHTML(true);  // send as HTML

		if(!empty($this->from)){
			$mailer->From = $this->from;      // 发件人邮箱
		}else{
			$mailer->From = $this->mailer['smtp_user'];      // 发件人邮箱
		}
		$mailer->Subject = empty($this->subject) ? 'Test' : $this->subject;

		$mailer->AddAddress($this->to);

		//$mailer->AddBCC($this->bcc);

		$mailer->Body = empty($this->body) ? '' : $this->body;

		$mailer->AltBody ="text/html";

		try {
			$flat = $mailer->Send();

		}catch (phpmailerException $e) {
			// $e->errorMessage();
		}

		return $flat;
	}
}

?>
