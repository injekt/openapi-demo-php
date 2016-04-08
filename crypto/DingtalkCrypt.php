<?php
include_once "sha1.php";
include_once "pkcs7Encoder.php";
include_once "errorCode.php";


class DingtalkCrypt
{
	private $m_token;
	private $m_encodingAesKey;
	private $m_suiteKey;

	
	public function DingtalkCrypt($token, $encodingAesKey, $suiteKey)
	{
		$this->m_token = $token;
		$this->m_encodingAesKey = $encodingAesKey;
		$this->m_suiteKey = $suiteKey;
	}
	
    
	public function EncryptMsg($plain, $timeStamp, $nonce, &$encryptMsg)
	{
		$pc = new Prpcrypt($this->m_encodingAesKey);

		$array = $pc->encrypt($plain, $this->m_suiteKey);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}

		if ($timeStamp == null) {
			$timeStamp = time();
		}
		$encrypt = $array[1];

		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_token, $timeStamp, $nonce, $encrypt);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		$signature = $array[1];

		$encryptMsg = json_encode(array(
			"msg_signature" => $signature,
			"encrypt" => $encrypt,
			"timeStamp" => $timeStamp,
			"nonce" => $nonce
		));
		return ErrorCode::$OK;
	}


	public function DecryptMsg($signature, $timeStamp = null, $nonce, $encrypt, &$decryptMsg)
	{
		if (strlen($this->m_encodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_encodingAesKey);

		if ($timeStamp == null) {
			$timeStamp = time();
		}

		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_token, $timeStamp, $nonce, $encrypt);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$verifySignature = $array[1];
		if ($verifySignature != $signature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($encrypt, $this->m_suiteKey);
		if ($result[0] != 0) {
			return $result[0];
		}
		$decryptMsg = $result[1];

		return ErrorCode::$OK;
	}

}

