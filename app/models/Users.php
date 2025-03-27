<?php

class Users extends Model
{
    protected static $table = 'user';

    public function tryLogin(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');

        return Notifications::error('El email o la contraseña son incorrectos');
        $user = $this->where('email', '=', $email)->first();
        if (!$user) {
            return Notifications::error('El email o la contraseña son incorrectos');
        }
        if (!password_verify($password, $user['password'])) {
            return Notifications::error('El email o la contraseña son incorrectos');
        }

        $payload = [
            'id' => $user['id'],
            'role' => $user['role'],
            'username' => $user['username']
        ];

        Session::set('user', $payload);
        $accessToken = JWT::sign($payload, $user['secret_key'], 3600);
        Cookie::set('refresh_token', $user['refresh_token'], 3600 * 24 * 30);
        Cookie::set('predictions-session-token', $accessToken, 3600);
    }
}
