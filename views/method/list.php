<?php

function methodSortLink(
    string $column,
    string $title,
    string $currentSort,
    string $currentOrder,
    string $search
): string {
    $newOrder = 'asc';

    if ($currentSort === $column && $currentOrder === 'asc') {
        $newOrder = 'desc';
    }

    $arrow = '';

    if ($currentSort === $column) {
        $arrow = $currentOrder === 'asc' ? ' ↑' : ' ↓';
    }

    $url = 'index.php?entity=method&action=list'
        . '&sort=' . urlencode($column)
        . '&order=' . urlencode($newOrder)
        . '&search=' . urlencode($search);

    return '<a href="' . $url . '">' .
        htmlspecialchars($title . $arrow) .
        '</a>';
}

?>

<h1 class="mb-4">Методики</h1>

<a
    href="index.php?entity=method&action=create"
    class="btn btn-success mb-3"
>
    Добавить методику
</a>

<form method="GET" class="row g-2 mb-3">

    <input type="hidden" name="entity" value="method">
    <input type="hidden" name="action" value="list">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">

    <div class="col-md-4">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Поиск по названию методики"
            value="<?= htmlspecialchars($search) ?>"
        >
    </div>

    <div class="col-md-auto">
        <button type="submit" class="btn btn-primary">
            Поиск
        </button>
    </div>

    <div class="col-md-auto">
        <a
            href="index.php?entity=method&action=list"
            class="btn btn-secondary"
        >
            Сбросить
        </a>
    </div>

</form>

<?php if (empty($methods)): ?>

    <div class="alert alert-info">
        Методики не найдены
    </div>

<?php else: ?>

    <div class="table-responsive mt-3">

        <table class="table table-bordered table-striped align-middle">

            <thead>
                <tr>
                    <th>
                        <?= methodSortLink(
                            'method_id',
                            'ID',
                            $sort,
                            $order,
                            $search
                        ) ?>
                    </th>

                    <th>
                        <?= methodSortLink(
                            'method_name',
                            'Название методики',
                            $sort,
                            $order,
                            $search
                        ) ?>
                    </th>

                    <th>
                        Действия
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($methods as $method): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($method['method_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($method['method_name']) ?>
                        </td>

                        <td>
                            <a
                                href="index.php?entity=method&action=edit&id=<?= htmlspecialchars($method['method_id']) ?>"
                                class="btn btn-warning btn-sm"
                            >
                                Изменить
                            </a>

                            <a
                                href="index.php?entity=method&action=delete&id=<?= htmlspecialchars($method['method_id']) ?>"
                                class="btn btn-danger btn-sm"
                            >
                                Удалить
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

    <?php if ($pages > 1): ?>

        <nav class="mt-4">
            <ul class="pagination">

                <?php for ($i = 1; $i <= $pages; $i++): ?>

                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a
                            class="page-link"
                            href="index.php?entity=method&action=list&page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= urlencode($sort) ?>&order=<?= urlencode($order) ?>"
                        >
                            <?= $i ?>
                        </a>
                    </li>

                <?php endfor; ?>

            </ul>
        </nav>

    <?php endif; ?>

<?php endif; ?>