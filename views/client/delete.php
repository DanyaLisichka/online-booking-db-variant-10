<h1 class="mb-4 text-danger">
    Удаление клиента
</h1>

<div class="alert alert-warning">
    Вы уверены, что хотите удалить клиента:
    <strong>
        <?= htmlspecialchars($client['full_name']) ?>
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
        href="index.php?entity=client&action=list"
        class="btn btn-secondary"
    >
        Отмена
    </a>

</form>