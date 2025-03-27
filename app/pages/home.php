<?php 
	if (Tools::isPost()) {
		$user = Users::where('role', '=', Tools::getValue('username'))->first();
		debug([
			'body' => Tools::getValue('username'),
			'user' => $user,
			'isAdminRoute' => $isAdminRoute
		]);
		die('Procesar formulario');
	}
?>

<form action="" class="w1" method="post" enctype="multipart/form-data">
	<div class="w1 panel">
		<div class="flex column gap-10 padding">
			<a href="/auth/login?next=<?= Crypt::encrypt('/admin/predictions') ?>">Iniciar sesión</a>
			<a href="/auth/login?next=<?= Crypt::encrypt('8') ?>">Simular edicion con id codificado</a>
		</div>
		<input type="text" name="username" placeholder="Usuario" value="admin">
		<button type="submit">Enviar prueba</button>
	</div>
</form>