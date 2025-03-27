<style>
	.sidebar.admin-sidebar.sidebar-closed {
		transform: translateX(-100%);
	}

	.sidebar.admin-sidebar .open-button {
		position: absolute;
		bottom: 0;
		left: unset;
		right: 0;
	}
	.sidebar.admin-sidebar.sidebar-closed .open-button {
		left: 100%;
	}
	.sidebar.admin-sidebar .open-button button {
		width: 45px;
		height: 45px;
		background-color: #333;
		color: #fff;
		border: none;
		padding: 10px;
		cursor: pointer;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 5px;
		position: relative;
		z-index: 1000;
	}

	.sidebar.admin-sidebar .open-button button span {
		display: block;
		width: 25px;
		height: 2px;
		top: 0;
		left: 0;
		background-color: #fff;
		transition: all 0.3s;
		opacity: 1;
	}
	
	.sidebar.admin-sidebar .open-button button span:nth-child(1) {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%) rotate(45deg);
	}
	.sidebar.admin-sidebar .open-button button span:nth-child(2) {
		opacity: 0;
	}

	.sidebar.admin-sidebar .open-button button span:nth-child(3) {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%) rotate(-45deg);
	}
	
	
	.sidebar.admin-sidebar.sidebar-closed .open-button button span {
		transform: translate(0, 50%) rotate(0);
		position: relative;
		top: unset;
		left: unset;
		opacity: 1;
	}

	.sidebar.admin-sidebar .footer {
		display: flex;
		justify-content: flex-start;
		align-items: center;
		position: absolute;
		bottom: 0;
		left: 0;
		width: calc(100% - 45px);
		height: 45px;
		background-color: #333;
		color: #fff;
		padding: 10px;
		text-align: center;
		font-size: 13px;
	}
</style>

<header class="header admin-header">
	<div class="user"><?= $sessionUser['username'] ?></div>
</header>

<aside class="sidebar admin-sidebar <?= IS_MOBILE ? 'mobile-sidebar' : 'desktop-sidebar' ?> <?= IS_MOBILE ? 'sidebar-closed' : 'sidebar-opened' ?>">
	<div class="greeting">
		<h2>¡Hola, <?= $sessionUser['username'] ?>!</h2>
	</div>
	<nav class="sidebar-navigation">
		<ul class="sidebar-menu">
			<li><a href="<?= HOST ?>/admin">Dashboard</a></li>
			<li><a href="<?= HOST ?>/admin/users">Usuarios</a></li>
			<li><a href="<?= HOST ?>/admin/predictions">Predicciones</a></li>
			<li><a href="<?= HOST ?>/admin/ppvs">Eventos</a></li>
			<li><a href="<?= HOST ?>/admin/matches">Combates</a></li>
			<li><a href="<?= HOST ?>/admin/cookies">Cookies</a></li>
			<li class="logout"><a href="<?= HOST ?>/auth/logout">Cerrar sesión</a></li>
		</ul>
	</nav>

	<div class="footer">
		<p>© <?= date('Y') ?> Predicciones</p>
	</div>

	<div class="open-button">
		<button id="open-sidebar" class="toggle-sidebar-btn" type="button" onclick="toggleSidebar()">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>
</aside>