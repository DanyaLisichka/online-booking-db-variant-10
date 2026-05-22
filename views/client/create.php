<h1 class="mb-4">
    Добавить клиента
</h1>

<form method="POST">

<input
    type="hidden"
    name="csrf_token"
    value="<?= htmlspecialchars(generateCsrfToken()) ?>"
>

    <!-- FULL NAME -->

    <div class="mb-3">

        <label class="form-label">
            ФИО *
        </label>

        <input
            type="text"
            name="full_name"
            class="form-control
            <?= isset($errors['full_name'])
                ? 'is-invalid'
                : '' ?>"

            value="<?= htmlspecialchars(
                $data['full_name']
            ) ?>"

            required
        >

        <?php if (
            isset($errors['full_name'])
        ): ?>

            <div class="invalid-feedback">
                <?= $errors['full_name'] ?>
            </div>

        <?php endif; ?>

    </div>

    <!-- EMAIL -->

    <div class="mb-3">

        <label class="form-label">
            Email *
        </label>

        <input
            type="email"
            name="email"
            class="form-control
            <?= isset($errors['email'])
                ? 'is-invalid'
                : '' ?>"

            value="<?= htmlspecialchars(
                $data['email']
            ) ?>"

            required
        >

        <?php if (
            isset($errors['email'])
        ): ?>

            <div class="invalid-feedback">
                <?= $errors['email'] ?>
            </div>

        <?php endif; ?>

    </div>

    <!-- PHONE -->

    <div class="mb-3">

        <label class="form-label">
            Телефон *
        </label>

        <input
            type="text"
            name="phone"
            class="form-control
            <?= isset($errors['phone'])
                ? 'is-invalid'
                : '' ?>"

            value="<?= htmlspecialchars(
                $data['phone']
            ) ?>"

            required
        >

        <?php if (
            isset($errors['phone'])
        ): ?>

            <div class="invalid-feedback">
                <?= $errors['phone'] ?>
            </div>

        <?php endif; ?>

    </div>

    <!-- DATE -->

    <div class="mb-3">

        <label class="form-label">
            Дата регистрации *
        </label>

        <input
            type="date"
            name="registration_date"
            class="form-control
            <?= isset(
                $errors['registration_date']
            )
                ? 'is-invalid'
                : '' ?>"

            value="<?= htmlspecialchars(
                $data['registration_date']
            ) ?>"

            required
        >

        <?php if (
            isset(
                $errors['registration_date']
            )
        ): ?>

            <div class="invalid-feedback">
                <?= $errors['registration_date'] ?>
            </div>

        <?php endif; ?>

    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Создать
    </button>

</form>