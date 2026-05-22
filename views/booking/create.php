<h1 class="mb-4">
    Бронирование консультации
</h1>

<?php if (isset($errors['common'])): ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($errors['common']) ?>
    </div>

<?php endif; ?>

<form method="POST" id="bookingForm">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(generateCsrfToken()) ?>"
    >

    <div class="mb-3">
        <label class="form-label">
            Услуга *
        </label>

        <select
            name="service_id"
            id="service_id"
            class="form-select <?= isset($errors['service_id']) ? 'is-invalid' : '' ?>"
            required
        >
            <option value="">Выберите услугу</option>

            <?php foreach ($services as $service): ?>

                <option
                    value="<?= htmlspecialchars($service['service_id']) ?>"
                    data-duration="<?= htmlspecialchars($service['duration_minutes']) ?>"
                    <?= (int) $data['service_id'] === (int) $service['service_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($service['service_name']) ?>
                    —
                    <?= htmlspecialchars($service['duration_minutes']) ?> мин.
                    —
                    <?= htmlspecialchars($service['price']) ?> ₽
                </option>

            <?php endforeach; ?>

        </select>

        <?php if (isset($errors['service_id'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['service_id']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">
            Психолог *
        </label>

        <select
            name="psychologist_id"
            id="psychologist_id"
            class="form-select <?= isset($errors['psychologist_id']) ? 'is-invalid' : '' ?>"
            required
        >
            <option value="">Сначала выберите услугу</option>
        </select>

        <?php if (isset($errors['psychologist_id'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['psychologist_id']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">
            Дата *
        </label>

        <input
            type="date"
            name="appointment_date"
            id="appointment_date"
            class="form-control <?= isset($errors['appointment_date']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($data['appointment_date']) ?>"
            min="<?= date('Y-m-d') ?>"
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
            Доступное время *
        </label>

        <div
            id="slotsContainer"
            class="border rounded p-3"
        >
            Выберите услугу, психолога и дату
        </div>

        <input
            type="hidden"
            name="appointment_time"
            id="appointment_time"
            value="<?= htmlspecialchars($data['appointment_time']) ?>"
        >

        <?php if (isset($errors['appointment_time'])): ?>
            <div class="text-danger mt-1">
                <?= htmlspecialchars($errors['appointment_time']) ?>
            </div>
        <?php endif; ?>
    </div>

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
        Забронировать
    </button>

    <a
        href="index.php?entity=appointment&action=list"
        class="btn btn-secondary"
    >
        Назад
    </a>

</form>

<script>
const serviceSelect = document.getElementById('service_id');
const psychologistSelect = document.getElementById('psychologist_id');
const dateInput = document.getElementById('appointment_date');
const slotsContainer = document.getElementById('slotsContainer');
const timeInput = document.getElementById('appointment_time');

serviceSelect.addEventListener('change', loadPsychologists);
psychologistSelect.addEventListener('change', loadSlots);
dateInput.addEventListener('change', loadSlots);

function loadPsychologists() {
    const serviceId = serviceSelect.value;

    psychologistSelect.innerHTML =
        '<option value="">Загрузка...</option>';

    slotsContainer.innerHTML =
        'Выберите психолога и дату';

    timeInput.value = '';

    if (!serviceId) {
        psychologistSelect.innerHTML =
            '<option value="">Сначала выберите услугу</option>';
        return;
    }

    fetch('ajax/get_psychologists.php?service_id=' + serviceId)
        .then(response => response.json())
        .then(data => {
            psychologistSelect.innerHTML =
                '<option value="">Выберите психолога</option>';

            if (data.length === 0) {
                psychologistSelect.innerHTML =
                    '<option value="">Нет доступных психологов</option>';
                return;
            }

            data.forEach(psychologist => {
                const option = document.createElement('option');

                option.value = psychologist.psychologist_id;
                option.textContent =
                    psychologist.full_name + ' — ' +
                    (psychologist.specialization ?? '');

                psychologistSelect.appendChild(option);
            });
        });
}

function loadSlots() {
    const serviceId = serviceSelect.value;
    const psychologistId = psychologistSelect.value;
    const date = dateInput.value;

    timeInput.value = '';

    if (!serviceId || !psychologistId || !date) {
        slotsContainer.innerHTML =
            'Выберите услугу, психолога и дату';
        return;
    }

    slotsContainer.innerHTML =
        '<div class="spinner-border spinner-border-sm"></div> Загрузка слотов...';

    const url =
        'ajax/get_available_slots.php'
        + '?service_id=' + serviceId
        + '&psychologist_id=' + psychologistId
        + '&date=' + date;

    fetch(url)
        .then(response => response.json())
        .then(slots => {
            slotsContainer.innerHTML = '';

            if (slots.length === 0) {
                slotsContainer.innerHTML =
                    '<div class="text-muted">Нет свободного времени на выбранную дату</div>';
                return;
            }

            slots.forEach(slot => {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'btn btn-outline-primary btn-sm me-2 mb-2';
                button.textContent = slot;

                button.addEventListener('click', function () {
                    document
                        .querySelectorAll('#slotsContainer button')
                        .forEach(btn => {
                            btn.classList.remove('btn-primary');
                            btn.classList.add('btn-outline-primary');
                        });

                    button.classList.remove('btn-outline-primary');
                    button.classList.add('btn-primary');

                    timeInput.value = slot;
                });

                slotsContainer.appendChild(button);
            });
        });
}
</script>