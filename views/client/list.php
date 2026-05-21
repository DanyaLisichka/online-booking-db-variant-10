<h1 class="mb-4">
    Клиенты
</h1>

<a
    href="index.php?entity=client&action=create"
    class="btn btn-success mb-3"
>
    Добавить клиента
</a>

<table class="table table-bordered">

    <thead>

        <tr>

            <?php foreach (
                array_keys($clients[0]) as $column
            ): ?>

                <th>
                    <?= htmlspecialchars($column) ?>
                </th>

            <?php endforeach; ?>

            <th>
                Действия
            </th>

        </tr>

    </thead>

    <tbody>

    <?php foreach ($clients as $client): ?>

        <tr>

            <?php foreach ($client as $value): ?>

                <td>
                    <?= htmlspecialchars($value) ?>
                </td>

            <?php endforeach; ?>

            <td>

                <a
                    href="index.php?entity=client&action=edit&id=<?= $client['client_id'] ?>"
                    class="btn btn-warning btn-sm"
                >
                    Изменить
                </a>

                <a
                    href="index.php?entity=client&action=delete&id=<?= $client['client_id'] ?>"
                    class="btn btn-danger btn-sm"
                >
                    Удалить
                </a>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>