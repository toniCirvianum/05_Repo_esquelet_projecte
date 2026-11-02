<?php

/**
 * @var string $title
 * @var array $products
 */
?>
<h3><?= htmlspecialchars($title) ?></h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Preu</th>
            <th>Categoria</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><a href="/products/<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a></td>
                <td><?= number_format($product['price'], 2, ',', '.') ?> €</td>
                <td><?= htmlspecialchars($product['category']) ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/products">Veure productes</a>