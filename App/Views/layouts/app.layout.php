<?php

/** @var string $content */
?>
<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Aplicació', ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <header>
            <h2>Aplicació de productes</h2>
            <nav class="d-flex align-items-center gap-3 mb-3" aria-label="Navegació interna">
                <a href="/profile">Profile</a>
                <a href="/products">Products</a>
                <form action="/logout" method="post" class="m-0">
                    <button type="submit" class="btn btn-link p-0">Logout</button>
                </form>
            </nav>
        </header>
        <hr>
        <main><?= $content ?></main>
        <hr>
        <footer class="text-center">PHP · Autenticació amb Google</footer>
    </div>
</body>
</html>
