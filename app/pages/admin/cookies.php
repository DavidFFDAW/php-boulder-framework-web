<?php $cookies = [
	'predictions-session-token' => Cookie::getExpiration('predictions-session-token'),
	'refresh_token' => Cookie::getExpiration('refresh_token'),
]; 
?>

<div class="title">
	<h1 class="uppercase">Cookies</h1>
	<small>Las cookies se almacenan en el navegador del usuario</small>
</div>

<div class="panel down">
	<?php if (!empty($cookies)): ?>
		<div class="w1 cookies-listing flex column gap-small">
			<?php foreach ($cookies as $name => $cookie): ?>
				<div class="w1 flex acenter total cookie">
					<div class="cookie-name"><?= $name ?></div>
					<div class="cookie-expiration"><?= date('d-m-Y H:i:s', strtotime($cookie['expiration'])) ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<p>No hay cookies almacenadas</p>
	<?php endif; ?>
</div>