<?php

class Middlewares
{
	public static function refreshToken()
	{
		$storedUser = Session::get('user');
		$refreshToken = Cookie::get('refresh_token');
		$jwtToken = Cookie::get('predictions-session-token');
		if ($jwtToken) header('Authorization: Bearer ' . $jwtToken);

		if (!$jwtToken && !$refreshToken) return false;

		// if there is not a jwt token but there is a refresh_one
		if (!$jwtToken && $refreshToken) {
			$user = Users::where('refresh_token', '=', $refreshToken)->first();
			if (!$user) return false;

			$payload = [
				'id' => $user['id'],
				'role' => $user['role'],
				'username' => $user['username']
			];

			JWT::setSession($payload, null);
			return true;
		}

		if ($jwtToken && isset($storedUser)) { 
			$verifiedDecodedToken = JWT::verify($jwtToken);
			if (!$verifiedDecodedToken) {
				Cookie::delete('predictions-session-token');
				Cookie::delete('refresh_token');
				Session::delete('user');
				return false;
			}

			Session::set('user', $verifiedDecodedToken);
		}
	}

	public static function canUserAccess($requiredRole)
	{
		$accessToken = Cookie::get('predictions-session-token');
		if (!$accessToken) return false;

		$user = Session::get('user');
		if (!$user) return false;
		if ($user['role'] !== $requiredRole) return false;

		return true;
	}
}
