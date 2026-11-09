<?php

/** @var array $user */
?>
<h3>Perfil de Google</h3>
<dl class="row">
    <?php foreach ($user as $key => $value): ?>
        <dt class="col-sm-3"><?= htmlspecialchars( $key) ?></dt>
        <dd class="col-sm-9 text-break"><?= htmlspecialchars( $value) ?></dd>
    <?php endforeach; ?>
</dl>

<?php if (!empty($user['picture'])): ?>
    <img
        src="<?= htmlspecialchars($user['picture'], ENT_QUOTES, 'UTF-8') ?>"
        alt="Foto del perfil de Google"
        class="rounded img-thumbnail"
        width="150"
        height="150"
        referrerpolicy="no-referrer"
    >
<?php endif; ?>
