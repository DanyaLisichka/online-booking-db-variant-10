<?php

function appointmentSortLink(
    string $column,
    string $title,
    string $currentSort,
    string $currentOrder,
    array $filters
): string {
    $newOrder = 'asc';

    if ($currentSort === $column && $currentOrder === 'asc') {
        $newOrder = 'desc';
    }

    $arrow = '';

    if ($currentSort === $column) {
        $arrow = $currentOrder === 'asc' ? ' ↑' : ' ↓';
    }

    $query = array_merge(
        [
            'entity' => 'appointment',
            'action' => 'list',
            'sort' => $column,
            'order' => $newOrder
        ],
        $filters
    );

    $url = 'index.php?' . http_build_query($query);

    return '<a href="' . htmlspecialchars($url) . '">' .
        htmlspecialchars($title . $arrow) .
        '</a>';
}

?>

<h1 class="mb-4">Записи на консультацию</h1>

<a
    href="index.php?entity=appointment&action=create"
    class="btn btn-success mb-3"
>
    Добавить запись
</a>

<a
    href="index.php?entity=booking&action=create"
    class="btn btn-primary mb-3"
>
    Бронирование
</a>

<form method="GET" class="row g-2 mb-3">

    <input type="hidden" name="entity" value="appointment">
    <input type="hidden" name="action" value="list">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">

    <div class="col-md-3">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Клиент, психолог или тип"
            value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
        >
    </div>

    <div class="col-md-2">
        <select
            name="status"
            class="form-select"
        >
            <option value="">Все статусы</option>

            <option
                value="pending"
                <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>
            >
                Ожидает
            </option>

            <option
                value="confirmed"
                <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>
            >
                Подтверждена
            </option>

            <option
                value="cancelled"
                <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>
            >
                Отменена
            </option>

            <option
                value="completed"
                <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>
            >
                Завершена
            </option>
        </select>
    </div>

    <div class="col-md-3">
        <select
            name="psychologist_id"
            class="form-select"
        >
            <option value="0">Все психологи</option>

            <?php foreach ($psychologists as $psychologist): ?>

                <option
                    value="<?= htmlspecialchars($psychologist['psychologist_id']) ?>"
                    <?= (int) ($filters['psychologist_id'] ?? 0) === (int) $psychologist['psychologist_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($psychologist['full_name']) ?>
                </option>

            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-2">
        <input
            type="date"
            name="date_from"
            class="form-control"
            value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
        >
    </div>

    <div class="col-md-2">
        <input
            type="date"
            name="date_to"
            class="form-control"
            value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
        >
    </div>

    <div class="col-md-auto">
        <button
            type="submit"
            class="btn btn-primary"
        >
            Фильтровать
        </button>
    </div>

    <div class="col-md-auto">
        <a
            href="index.php?entity=appointment&action=list"
            class="btn btn-secondary"
        >
            Сбросить
        </a>
    </div>

</form>

<?php if (empty($appointments)): ?>

    <div class="alert alert-info">
        Записи не найдены
    </div>

<?php else: ?>

    <div class="table-responsive mt-3">

        <table class="table table-bordered table-striped align-middle">

            <thead>
                <tr>
                    <th>
                        <?= appointmentSortLink(
                            'appointment_id',
                            'ID',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'client_name',
                            'Клиент',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'service_name',
                            'Услуга',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'psychologist_name',
                            'Психолог',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'appointment_date',
                            'Дата',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'appointment_time',
                            'Время',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'consultation_type',
                            'Тип',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        <?= appointmentSortLink(
                            'status',
                            'Статус',
                            $sort,
                            $order,
                            $filters
                        ) ?>
                    </th>

                    <th>
                        Действия
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($appointments as $appointment): ?>

                    <?php
                        $statusClasses = [
                            'pending' => 'bg-warning text-dark',
                            'confirmed' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            'completed' => 'bg-secondary'
                        ];

                        $statusNames = [
                            'pending' => 'Ожидает',
                            'confirmed' => 'Подтверждена',
                            'cancelled' => 'Отменена',
                            'completed' => 'Завершена'
                        ];

                        $status = $appointment['status'] ?? 'pending';
                    ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($appointment['appointment_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['client_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['service_name'] ?? '—') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['psychologist_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['appointment_date']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['appointment_time'] ?? '—') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($appointment['consultation_type']) ?>
                        </td>

                        <td>
                            <span class="badge <?= htmlspecialchars($statusClasses[$status] ?? 'bg-secondary') ?>">
                                <?= htmlspecialchars($statusNames[$status] ?? $status) ?>
                            </span>
                        </td>

                        <td>
                            <a
                                href="index.php?entity=appointment&action=edit&id=<?= htmlspecialchars($appointment['appointment_id']) ?>"
                                class="btn btn-warning btn-sm mb-1"
                            >
                                Изменить
                            </a>

                            <a
                                href="index.php?entity=appointment&action=delete&id=<?= htmlspecialchars($appointment['appointment_id']) ?>"
                                class="btn btn-danger btn-sm mb-1"
                            >
                                Удалить
                            </a>

                            <?php if ($status === 'pending'): ?>

                                <a
                                    href="index.php?entity=appointment&action=changeStatus&id=<?= htmlspecialchars($appointment['appointment_id']) ?>&status=confirmed"
                                    class="btn btn-success btn-sm mb-1"
                                >
                                    Подтвердить
                                </a>

                            <?php endif; ?>

                            <?php if (in_array($status, ['pending', 'confirmed'])): ?>

                                <a
                                    href="index.php?entity=appointment&action=changeStatus&id=<?= htmlspecialchars($appointment['appointment_id']) ?>&status=cancelled"
                                    class="btn btn-outline-danger btn-sm mb-1"
                                    onclick="return confirm('Отменить запись?')"
                                >
                                    Отменить
                                </a>

                            <?php endif; ?>

                            <?php if ($status === 'confirmed'): ?>

                                <a
                                    href="index.php?entity=appointment&action=changeStatus&id=<?= htmlspecialchars($appointment['appointment_id']) ?>&status=completed"
                                    class="btn btn-secondary btn-sm mb-1"
                                >
                                    Завершить
                                </a>

                            <?php endif; ?>
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

                    <?php
                        $paginationQuery = array_merge(
                            [
                                'entity' => 'appointment',
                                'action' => 'list',
                                'page' => $i,
                                'sort' => $sort,
                                'order' => $order
                            ],
                            $filters
                        );
                    ?>

                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a
                            class="page-link"
                            href="index.php?<?= htmlspecialchars(http_build_query($paginationQuery)) ?>"
                        >
                            <?= $i ?>
                        </a>
                    </li>

                <?php endfor; ?>

            </ul>
        </nav>

    <?php endif; ?>

<?php endif; ?>