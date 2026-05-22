<h1 class="mb-4">
    Отчёты
</h1>

<form method="GET" class="row g-2 mb-4">

    <input type="hidden" name="entity" value="report">
    <input type="hidden" name="action" value="index">

    <div class="col-md-3">
        <label class="form-label">
            Дата от
        </label>

        <input
            type="date"
            name="date_from"
            class="form-control"
            value="<?= htmlspecialchars($dateFrom) ?>"
        >
    </div>

    <div class="col-md-3">
        <label class="form-label">
            Дата до
        </label>

        <input
            type="date"
            name="date_to"
            class="form-control"
            value="<?= htmlspecialchars($dateTo) ?>"
        >
    </div>

    <div class="col-md-auto align-self-end">
        <button
            type="submit"
            class="btn btn-primary"
        >
            Сформировать
        </button>
    </div>

    <div class="col-md-auto align-self-end">
        <a
            href="index.php?entity=report&action=exportCsv&date_from=<?= urlencode($dateFrom) ?>&date_to=<?= urlencode($dateTo) ?>"
            class="btn btn-success"
        >
            Экспорт CSV
        </a>
    </div>

</form>

<h2 class="h4 mt-4">
    Записи и выручка по дням
</h2>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Количество записей</th>
            <th>Выручка</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($appointmentsByDay as $row): ?>

            <tr>
                <td>
                    <?= htmlspecialchars($row['appointment_date']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['appointment_count']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['revenue'] ?? 0) ?> ₽
                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="h4 mt-5">
    Загруженность психологов
</h2>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Психолог</th>
            <th>Количество записей</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($psychologistLoad as $row): ?>

            <tr>
                <td>
                    <?= htmlspecialchars($row['psychologist_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['appointment_count']) ?>
                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="h4 mt-5">
    Выручка по услугам
</h2>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Услуга</th>
            <th>Количество записей</th>
            <th>Выручка</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($revenueByService as $row): ?>

            <tr>
                <td>
                    <?= htmlspecialchars($row['service_name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['appointment_count']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['revenue'] ?? 0) ?> ₽
                </td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>