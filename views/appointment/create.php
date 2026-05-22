<h1 class="mb-4">
    Добавить запись на консультацию
</h1>

<?php if (isset($errors['common'])): ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($errors['common']) ?>
    </div>

<?php endif; ?>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(generateCsrfToken()) ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            Клиент *
        </label>

        <select
            name="client_id"
            class="form-select <?= isset($errors['client_id']) ? 'is-invalid' : '' ?>"
            required
        >
            <option value="">Выберите клиента</option>

            <?php foreach ($clients as $client): ?>

                <option
                    value="<?= htmlspecialchars($client['client_id']) ?>"
                    <?= (int) $data['client_id'] === (int) $client['client_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($client['full_name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <?php if (isset($errors['client_id'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['client_id']) ?>
            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Психолог *
        </label>

        <select
            name="psychologist_id"
            class="form-select <?= isset($errors['psychologist_id']) ? 'is-invalid' : '' ?>"
            required
        >
            <option value="">Выберите психолога</option>

            <?php foreach ($psychologists as $psychologist): ?>

                <option
                    value="<?= htmlspecialchars($psychologist['psychologist_id']) ?>"
                    <?= (int) $data['psychologist_id'] === (int) $psychologist['psychologist_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($psychologist['full_name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <?php if (isset($errors['psychologist_id'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['psychologist_id']) ?>
            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Дата консультации *
        </label>

        <input
            type="date"
            name="appointment_date"
            class="form-control <?= isset($errors['appointment_date']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($data['appointment_date']) ?>"
            required
        >

        <?php if (isset($errors['appointment_date'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['appointment_date']) ?>
            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Тип консультации *
        </label>

        <select
            name="consultation_type"
            class="form-select <?= isset($errors['consultation_type']) ? 'is-invalid' : '' ?>"
            required
        >
            <option value="">Выберите тип</option>

            <option
                value="offline"
                <?= $data['consultation_type'] === 'offline' ? 'selected' : '' ?>
            >
                Очно
            </option>

            <option
                value="online"
                <?= $data['consultation_type'] === 'online' ? 'selected' : '' ?>
            >
                Онлайн
            </option>
        </select>

        <?php if (isset($errors['consultation_type'])): ?>

            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['consultation_type']) ?>
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
        href="index.php?entity=appointment&action=list"
        class="btn btn-secondary"
    >
        Назад
    </a>

</form>