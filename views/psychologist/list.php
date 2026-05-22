<?php

function psychologistSortLink(
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

    $url = 'index.php?entity=psychologist&action=list'
        . '&sort=' . urlencode($column)
        . '&order=' . urlencode($newOrder)
        . '&search=' . urlencode($search);

    return '<a href="' . $url . '">' .
        htmlspecialchars($title . $arrow) .
        '</a>';
}

?>

<h1 class="mb-4">Психологи</h1>

<a
    href="index.php?entity=psychologist&action=create"
    class="btn btn-success mb-3"
>
    Добавить психолога
</a>

<form method="GET" class="row g-2 mb-3">

    <input type="hidden" name="entity" value="psychologist">
    <input type="hidden" name="action" value="list">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">

    <div class="col-md-4">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Поиск по ФИО или специализации"
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
            href="index.php?entity=psychologist&action=list"
            class="btn btn-secondary"
        >
            Сбросить
        </a>
    </div>

</form>

<?php if (empty($psychologists)): ?>

    <div class="alert alert-info">
        Психологи не найдены
    </div>

<?php else: ?>

    <div class="table-responsive mt-3">

        <table class="table table-bordered table-striped align-middle">

            <thead>
                <tr>
                    <th>
                        <?= psychologistSortLink(
                            'psychologist_id',
                            'ID',
                            $sort,
                            $order,
                            $search
                        ) ?>
                    </th>

                    <th>
                        <?= psychologistSortLink(
                            'full_name',
                            'ФИО',
                            $sort,
                            $order,
                            $search
                        ) ?>
                    </th>

                    <th>
                        <?= psychologistSortLink(
                            'specialization',
                            'Специализация',
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

                <?php foreach ($psychologists as $psychologist): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($psychologist['psychologist_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($psychologist['full_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($psychologist['specialization'] ?? '') ?>
                        </td>

                        <td>
                            <a
                                href="index.php?entity=psychologist&action=edit&id=<?= htmlspecialchars($psychologist['psychologist_id']) ?>"
                                class="btn btn-warning btn-sm"
                            >
                                Изменить
                            </a>

                            <a
                                href="index.php?entity=psychologist&action=delete&id=<?= htmlspecialchars($psychologist['psychologist_id']) ?>"
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
                            href="index.php?entity=psychologist&action=list&page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= urlencode($sort) ?>&order=<?= urlencode($order) ?>"
                        >
                            <?= $i ?>
                        </a>
                    </li>

                <?php endfor; ?>

            </ul>
        </nav>

    <?php endif; ?>

<?php endif; ?>