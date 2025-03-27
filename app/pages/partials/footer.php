<ul class="alert-messages">
    <?php foreach (array_slice(Notifications::get(), 0, 3) as $alert): ?>
        <li class="alert alert-<?= $alert['type'] ?>">
            <?= $alert['message'] ?>
        </li>
    <?php endforeach; ?>
</ul>
<footer class="main-app-footer"></footer>
<script src="<?= URL_BASE ?>/assets/js/script.js" defer></script>