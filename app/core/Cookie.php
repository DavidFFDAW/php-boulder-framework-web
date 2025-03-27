<?php

class Cookie {
	public static function all() {
		return $_COOKIE;
	}

	public static function get($name) {
		if (!isset($_COOKIE[$name])) return null;
		$cookie = json_decode($_COOKIE[$name], true);
		return $cookie['value'];
	}
	
	public static function getExpiration($name) {
		if (!isset($_COOKIE[$name])) return null;
		$cookie = json_decode($_COOKIE[$name], true);
		return $cookie;
	}

	public static function set($name, $value, $expiration = 3600, $path = '/') {
		$payload = json_encode([
			'value' => $value,
			'expiration' => date('Y-m-d H:i:s', time() + $expiration)
		]);
		setcookie($name, $payload, time() + $expiration, $path, '', true, true);
	}

	public static function delete($name) {
		setcookie($name, '', time() - 3600, '/');
	}
}