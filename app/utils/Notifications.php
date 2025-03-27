<?php

class Notifications {
	public static function get() {
		if (!isset($_SESSION['notifications'])) return [];
		$notifications = $_SESSION['notifications'];
		
		$_SESSION['notifications'] = [];
		return $notifications;
	}

	public static function add(string $message, string $type = 'success') {
		$_SESSION['notifications'][] = [
			'message' => $message,
			'type' => $type
		];
	}

	public static function success(string $message) {
		self::add($message, 'success');
	}

	public static function error(string $message) {
		self::add($message, 'error');
	}
}