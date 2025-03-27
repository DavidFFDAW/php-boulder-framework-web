<?php

class Crypt
{
	public static function encrypt(string $value)
	{
		$secretKey = getenv('APP_SECRET_KEY');
		$secretKey = !empty($secretKey) ? $secretKey : 'APP_SECRET_KEY_ENCRYPTATION_KEY';

		return openssl_encrypt($value, 'AES-256-CBC', $secretKey, 0, substr($secretKey, 0, 16));
	}

	public static function decrypt(string $value)
	{
		$secretKey = getenv('APP_SECRET_KEY');
		$parsedValue = str_replace(' ', '+', $value);
		$secretKey = !empty($secretKey) ? $secretKey : 'APP_SECRET_KEY_ENCRYPTATION_KEY';

		return openssl_decrypt($parsedValue, 'AES-256-CBC', $secretKey, 0, substr($secretKey, 0, 16));
	}
}
