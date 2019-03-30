<?php
require('../vendor/autoload.php');
use \phpseclib\Crypt as Crypt;

$mode = @$_REQUEST['mode'];

$rsa = new Crypt\RSA;
$rsa->setPrivateKeyFormat(Crypt\RSA::PRIVATE_FORMAT_PKCS1);
$rsa->setPublicKeyFormat(Crypt\RSA::PUBLIC_FORMAT_OPENSSH);
$rsa->setEncryptionMode(Crypt\RSA::ENCRYPTION_OAEP);
$rsa->setHash('sha256');

if ($mode == 'generate') {
	$keysize = 2048;
	$key = $rsa->createKey($keysize);
	returnJSON($key);

} elseif ($mode == 'encrypt') {
	$rsa->loadKey($_REQUEST['publickey']);
	$ret['ciphertext'] = base64_encode($rsa->encrypt($_REQUEST['plaintext']));
	returnJSON($ret);

} elseif ($mode == 'decrypt') {
	$rsa->loadKey($_REQUEST['privatekey']);
	$ret['plaintext'] = $rsa->decrypt(base64_decode($_REQUEST['ciphertext']));
	returnJSON($ret);

} else {
	require('main.html');
	die;
}

function returnJSON($data) {
    header("Content-type:application/json");
	echo json_encode($data);
	die;
}
