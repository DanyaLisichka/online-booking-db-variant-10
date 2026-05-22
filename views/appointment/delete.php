<h1 class="mb-4 text-danger">
    Удаление записи
</h1>

<div class="alert alert-warning">
    Вы уверены, что хотите удалить запись №
    <strong>
        <?= htmlspecialchars($appointment['appointment_id']) ?>
    </strong>?
</div>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(generateCsrfToken()) ?>"
    >

    <button
        type="submit"
        class="btn btn-danger"
    >
        Да, удалить
    </button>

    <a
        href="index.php?entity=appointment&action=list"
        class="btn btn-secondary"
    >
        Отмена
    </a>

</form>