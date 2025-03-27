<?php

class Tools
{
	public static function sanitize($value)
	{
		if (!isset($value)) return null;
		if (is_array($value)) return array_map(function ($item) {
			return self::sanitize($item);
		}, $value);
		return htmlspecialchars(trim(strip_tags(filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS))));
	}

	public static function getValue($key)
	{
		if (isset($_POST[$key])) return self::sanitize($_POST[$key]);
		if (isset($_GET[$key])) return self::sanitize($_GET[$key]);
		return null;
	}

    public static function getParsedPageURI(): string
    {
		$nonParameterizedURI = explode('?', strtolower(trim(URI)))[0];
		if ($nonParameterizedURI === '/') return 'home';
		if (substr($nonParameterizedURI, -1) === '/') $nonParameterizedURI = substr($nonParameterizedURI, 0, -1);
		$nonParameterizedURI = substr($nonParameterizedURI, 1);
		return str_replace('/', '-', $nonParameterizedURI);
    }

    public static function isUserLoggedIn(): bool
    {
		// additional logic can be added anytime
        return isset($_SESSION['user']);
    }

	public static function isUserAdmin(): bool {
		if (!isset($_SESSION['user'])) return false;
		$userRole = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;
		return $userRole === 'admin';
	}

	public static function getRequestMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public static function isPost(): bool {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

    public static function isAdminRoute()
    {
        return strpos($_SERVER['REQUEST_URI'], '/admin') !== false;
    }

	public static function response($data, $status = 200)
	{
		http_response_code($status);
		header('Content-Type: application/json');
		die(json_encode($data, JSON_PRETTY_PRINT));
		exit;
	}
}
