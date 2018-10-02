<?php
require_once 'vendor/autoload.php';
require_once 'swift/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Respect\Validation\Validator as v;
$nome = $_POST['nome'];
$data = $_POST['data'];
$email = $_POST['email'];

$validarEmail = v::email()->validate($email);
$validarData = v::date('d-m-Y')->validate($data);

if($validarEmail && $validarData){
	$qrCode = new QrCode($nome);

	header('Content-Type: '.$qrCode->getContentType());
	echo $qrCode->writeString();


	//Swift_Mailer
	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
		->setUsername('rosivan7qi@gmail.com')
		->setPassword('***')
	;

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);

	$msg = "Os dados do formulário são: <br>
	Nome: $nome <br>
	Data de Nascimento: $data <br>
	Email: $email.<br>
	QRCode: $qrCode><br>";

	// Create a message
	$message = (new Swift_Message('Email enviado com sucesso'))
		->setFrom(['rosivan7qi@gmail.com' => 'Rosivan Nascimento Santos'])
		->setTo(['rosivan7qi@gmail.com'])
		->setBody($msg, 'text/html')
	;

	// Send the message
	$result = $mailer->send($message);

}else{
	die('Preecha todos os campos');
} 



?>