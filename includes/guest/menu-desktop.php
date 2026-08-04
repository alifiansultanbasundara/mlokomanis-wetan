<?php foreach ($menus as $menu): ?>

    <?php if (isset($menu['children'])): ?>

        <!-- Dropdown -->

    <?php else: ?>

        <a href="<?= $menu['url'] ?>">
            <?= $menu['title'] ?>
        </a>

    <?php endif; ?>

<?php endforeach; ?>