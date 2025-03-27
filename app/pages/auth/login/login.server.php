<?php
function POST(Request $request)
{
    $parsedNext = $request->post('_redirect');
    $email = $request->post('email');
    $password = $request->post('password');
    $redirectionRoute = !empty($parsedNext) ? $parsedNext : '/admin/dashboard';
	if (strpos($redirectionRoute, 'http') === 0 || strpos($redirectionRoute, 'https') === 0) {
		$redirectionRoute = '/admin/dashboard';
	}

    $user = Users::where('email', '=', $email)->first();
    if (!$user) {
        die('Usuario no encontrado');
    }
    if (!password_verify($password, $user['password'])) {
        die('Contraseña incorrecta');
    }

    $payload = [
        'id' => $user['id'],
        'role' => $user['role'],
        'username' => $user['username']
    ];

    Cookie::set('refresh_token', $user['refresh_token'], 3600 * 24 * 30);
	JWT::setSession($payload, null);
    redirect($redirectionRoute);
    exit;
}
