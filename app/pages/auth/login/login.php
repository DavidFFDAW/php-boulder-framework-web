<?php
$next = $_GET['next'] ?? null;
$parsedNext = $next ? rawurldecode(base64_decode($next)) : null;
$redirect = $parsedNext ? $parsedNext : '/admin/dashboard';
if (strpos($redirect, 'http') === 0 || strpos($redirect, 'https') === 0) {
	$redirect = '/admin/dashboard';
}
$accessToken = Cookie::get('predictions-session-token');
$storedUser = Session::get('user');

if ($accessToken && !empty($storedUser)) {
    redirect('/admin/dashboard');
    exit;
}

?>
<form action="" method="post" style="padding: 20px;">
    <div class="panel flex column gap-medium">
        <input type="email" name="email" placeholder="Correo electrónico">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="hidden" name="_redirect" value="<?= $redirect ?>">

        <button type="submit">Iniciar sesión</button>
    </div>
</form>