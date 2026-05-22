<h1 class="mb-4">
    Редактировать методику
</h1>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(generateCsrfToken()) ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            Название методики *
        </label>

        <input
            type="text"
            name="method_name"
            class="form-control <?= isset($errors['method_name']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($data['method_name']) ?>"
            maxlength="100"
            required
        >

        <?php if (isset($errors['method_name'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['method_name']) ?>
            </div>

        <?php endif; ?>

    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Сохранить изменения
    </button>

    <a
        href="index.php?entity=method&action=list"
        class="btn btn-secondary"
    >
        Назад
    </a>

</form>