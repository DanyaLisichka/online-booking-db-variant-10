<h1 class="mb-4">
    Добавить психолога
</h1>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(generateCsrfToken()) ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            ФИО психолога *
        </label>

        <input
            type="text"
            name="full_name"
            class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($data['full_name']) ?>"
            maxlength="100"
            required
        >

        <?php if (isset($errors['full_name'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['full_name']) ?>
            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Специализация
        </label>

        <input
            type="text"
            name="specialization"
            class="form-control <?= isset($errors['specialization']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($data['specialization']) ?>"
            maxlength="100"
        >

        <?php if (isset($errors['specialization'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['specialization']) ?>
            </div>

        <?php endif; ?>

    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Создать
    </button>

    <a
        href="index.php?entity=psychologist&action=list"
        class="btn btn-secondary"
    >
        Назад
    </a>

</form>