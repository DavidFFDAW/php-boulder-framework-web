<?php


class JWT {
	private static function base64UrlEncode($data) {
		return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
	}

	private static function generate($payload, $secret = null, $algo = 'HS256') {
		$secret = $secret ? $secret : getenv('JWT_SECRET');
		$header = json_encode(['typ' => 'JWT', 'alg' => $algo]);

		$base64UrlHeader = self::base64UrlEncode($header);
		$base64UrlPayload = self::base64UrlEncode(json_encode($payload));

		$signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secret, true);
		$base64UrlSignature = self::base64UrlEncode($signature);

    	return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
	}

	private static function base64UrlDecode($data) {
		$urlSafeData = str_replace(['-', '_'], ['+', '/'], $data);
		$padding = strlen($urlSafeData) % 4;
		if ($padding) {
			$urlSafeData .= str_repeat('=', 4 - $padding);
		}
		return base64_decode($urlSafeData);
	}
	
	private static function validate($jwt, $secret) {
		$parts = explode('.', $jwt);
		if (count($parts) !== 3) return false; // Formato inválido
		list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
	
		$header = json_decode(self::base64UrlDecode($base64UrlHeader), true);
		if (!$header || $header['alg'] !== 'HS256') return false; // Algoritmo no soportado
	
		// Verificar la firma
		$signature = self::base64UrlDecode($base64UrlSignature);
		$expectedSignature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secret, true);
	
		if (!hash_equals($signature, $expectedSignature)) return false; // Firma inválida
	
		// Verificar si ha expirado
		$payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);
		if (!$payload || (isset($payload['exp']) && time() > $payload['exp'])) return false; // Token expirado
	
		return $payload; // JWT válido, devolvemos los datos
	}

	public static function sign($payload, $secret = null, $expiration = null) {
		$secret = $secret ? $secret : getenv('JWT_SECRET');
		$jwtPayload = array_merge($payload, [
			'exp' => $expiration ? time() + $expiration : time() + 3600,
			'iat' => time()
		]);

		return self::generate($jwtPayload, $secret, 'HS256');
	}
	
	public static function verify($jwt, $secret = null) {
		$secret = $secret ? $secret : getenv('JWT_SECRET');
		return self::validate($jwt, $secret);
	}

	public static function setSession($payload, $secret = null) {
		$jwt = self::sign($payload, $secret, 3600 / 2);
		Cookie::set('predictions-session-token', $jwt, 3600 / 2);
		Session::set('user', $payload);

		return $jwt;
	}
}