<?php 
class Auth {
	public static function isAuthenticated() {
		return isset($_SESSION['user']);
	}

	public static function isUserRoleValid(string $role): bool {
		if (!isset($_COOKIE['predictions-session-token'])) return false;
		if (!isset($_SESSION['user'])) return false;
		return $_SESSION['user']['role'] === $role;
	}

	public static function checkAuthUserRole(string $role = 'admin'): bool {
		if (!self::isUserRoleValid($role)) {
			Notifications::error('You are not authorized to access this page');
			return false;
		}

		return true;
	}

	public static function getStoredUser() {
		if (!isset($_SESSION['user'])) return false;
		return $_SESSION['user'];
	}

	public static function getStoredUserRole() {
		if (!isset($_SESSION['user'])) return false;
		return strtolower($_SESSION['user']['role']);
	}

	public static function getRoleFromUser($user) {
		return strtolower($user['role']);
	}

	public static function logout() {
		session_destroy();
		return redirect('/');
	}
}