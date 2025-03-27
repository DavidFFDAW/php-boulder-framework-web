<?php
$paramId = $request->get('id');
debug($paramId);
if (!$paramId) {
	Notifications::error('No se ha encontrado el usuario');
	die(redirect('/admin/users'));
}
?>

<div class="panel down">
	<div class="title">
		<h1 class="uppercase">Editar usuario</h1>
	</div>
</div>