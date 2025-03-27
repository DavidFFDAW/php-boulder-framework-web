<?php 

class Session {
	public static function start() {
		session_start();
	}

	public static function set(string $key, $value) {
		$_SESSION[$key] = $value;
	}

	public static function get(string $key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}
	
	public static function delete(string $key) {
		$_SESSION[$key] = null;
		unset($_SESSION[$key]);
	}


	public static function destroy() {
		session_destroy();
	}
}