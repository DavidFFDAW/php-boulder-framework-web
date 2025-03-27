<?php
$users = Users::query()->orderBy('created_at', 'DESC')->get();
?>
<div class="panel down">
	<div class="title flex gap-medium">
		<h1 class="uppercase">Usuarios</h1>
		<a href="/admin/users/create" class="btn">Crear usuario</a>
	</div>
	
	<?php if (!empty($users)): ?>
		<div class="w1 users-listing flex column gap-smaller down">
			<header class="w1 flex acenter total user-item table-header">
				<div class="user-name">Nombre</div>
				<div class="user-email">Correo</div>
				<div class="user-role">Rol</div>
				<div class="user-created">Creado</div>
				<div class="user-actions">Acciones</div>
			</header>
			<?php foreach ($users as $user): ?>
				<div class="w1 flex total user-item table-row">
					<div class="user-name"><?= $user['username'] ?></div>
					<div class="user-email"><?= $user['email'] ?></div>
					<div class="user-role"><?= $user['role'] ?></div>
					<div class="user-created"><?= date('d-m-Y H:i:s', strtotime($user['created_at'])) ?></div>
					<div class="user-actions">
						<a href="/admin/users/update?id=<?= $user['id'] ?>" class="btn">Editar</a>
						<a href="/admin/users/delete?id=<?= $user['id'] ?>" class="btn">Eliminar</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<p>No hay usuarios registrados</p>
	<?php endif; ?>
</div>

<style>
	header.table-header {
		border: 1px solid #ccc;
	}
	header.table-header > div {
		font-weight: bold;
		padding: 2px 10px;
	}
	header.table-header > div {
		border-right: 1px solid #ccc;
	}
	header.table-header > div:first-child {
		border-left: 1px solid #ccc;
	}
	
	.table-row {
		border-bottom: 1px solid #ccc;
	}

	.table-row > div {
		padding: 5px 10px;
		border-right: 1px solid #ccc;
		vertical-align: middle;
	}

	.table-row > div:first-child {
		border-left: 1px solid #ccc;
	}

	.table-row > div.user-actions {
		display: flex;
		justify-content: flex-end;
		gap: 5px;
	}

	.table-row > div.user-actions a {
		flex: 1;
		display: block;
		padding: 5px 10px;
		text-align: center;
	}

	@media (max-width: 768px) {
		.table-row {
			flex-direction: column;
		}

		.table-row > div {
			border-right: none;
			border-left: none;
			border-bottom: 1px solid #ccc;
		}

		.table-row > div:first-child {
			border-top: 1px solid #ccc;
		}

		.table-row > div.user-actions {
			flex-direction: column;
			gap: 5px;
		}

		.table-row > div.user-actions a {
			flex: none;
		}
	}

</style>