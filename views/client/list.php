<?php

function sortLink(
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

    $url = 'index.php?entity=client&action=list'
        . '&sort=' . urlencode($column)
        . '&order=' . urlencode($newOrder)
        . '&search=' . urlencode($search);

    return '<a href="' . $url . '">' .
        htmlspecialchars($title . $arrow) .
        '</a>';
}

?>

<h1 class="mb-4">Клиенты</h1>


<a

    href="index.php?entity=client&action=create"

    class="btn btn-success mb-3"

>

    Добавить клиента

</a>

<form method="GET" class="row g-2 mb-3">

    <input type="hidden" name="entity" value="client">

    <input type="hidden" name="action" value="list">

    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">

    <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">

    <div class="col-md-4">

        <input

            type="text"

            name="search"

            class="form-control"

            placeholder="Поиск по ФИО, email или телефону"

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

            href="index.php?entity=client&action=list"

            class="btn btn-secondary"

        >

            Сбросить

        </a>

    </div>

</form>

<?php if (!empty($clients)): ?>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th><?= sortLink('client_id', 'ID', $sort, $order, $search) ?></th>
                    <th><?= sortLink('full_name', 'ФИО', $sort, $order, $search) ?></th>
                    <th><?= sortLink('phone', 'Телефон', $sort, $order, $search) ?></th>
                    <th><?= sortLink('email', 'Email', $sort, $order, $search) ?></th>
                    <th><?= sortLink('registration_date', 'Дата регистрации', $sort, $order, $search) ?></th>
                    <th>Действия</th>
                
                </tr>
            </thead>

            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['client_id']) ?></td>
                        <td><?= htmlspecialchars($client['full_name']) ?></td>
                        <td><?= htmlspecialchars($client['phone']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['registration_date']) ?></td>
                        <td>
                            <a
                                href="index.php?entity=client&action=edit&id=<?= htmlspecialchars($client['client_id']) ?>"
                                class="btn btn-warning btn-sm">
                                Изменить
                            </a>

                            <a
                                href="index.php?entity=client&action=delete&id=<?= htmlspecialchars($client['client_id']) ?>"
                                class="btn btn-danger btn-sm">
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>

    <div class="alert alert-info mt-3">
        Клиенты не найдены
    </div>

<?php endif; ?>