<?php

/**
 * error code 说明.
 * <ul>
 *    <li>-900004: encodingAesKey 非法</li>
 *    <li>-900005: 签名验证错误</li>
 *    <li>-900006: sha加密生成签名失败</li>
 *    <li>-900007: aes 加密失败</li>
 *    <li>-900008: aes 解密失败</li>
 *    <li>-900010: suiteKey 校验错误</li>
 * </ul>
 */
class ErrorCode
{
	public static $OK = 0;
	
	public static $IllegalAesKey = 900004;
	public static $ValidateSignatureError = 900005;
	public static $ComputeSignatureError = 900006;
	public static $EncryptAESError = 900007;
	public static $DecryptAESError = 900008;
	public static $ValidateSuiteKeyError = 900010;
}

?>