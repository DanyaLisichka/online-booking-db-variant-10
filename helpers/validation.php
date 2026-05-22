<?php

function validateClient(array $data): array
{
    $errors = [];

    /*
     * FULL NAME
     */

    if (
        empty(trim($data['full_name']))
    ) {
        $errors['full_name'] =
            'Введите ФИО';
    }

    /*
     * EMAIL
     */

    if (
        empty(trim($data['email']))
    ) {

        $errors['email'] =
            'Введите email';

    } elseif (
        !filter_var(
            $data['email'],
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $errors['email'] =
            'Некорректный email';
    }

    /*
     * PHONE
     */

    if (
        empty(trim($data['phone']))
    ) {

        $errors['phone'] =
            'Введите телефон';

    } elseif (
        !preg_match(
            '/^\+?[0-9\s\-()]{7,20}$/',
            $data['phone']
        )
    ) {

        $errors['phone'] =
            'Некорректный телефон';
    }

    /*
     * DATE
     */

    if (
        empty($data['registration_date'])
    ) {

        $errors['registration_date'] =
            'Введите дату';

    } elseif (
        strtotime(
            $data['registration_date']
        ) > time()
    ) {

        $errors['registration_date'] =
            'Дата не может быть в будущем';
    }

    return $errors;
}

function validateMethod(array $data): array
{
    $errors = [];

    if (empty(trim($data['method_name'] ?? ''))) {
        $errors['method_name'] =
            'Введите название методики';
    } elseif (mb_strlen(trim($data['method_name'])) > 100) {
        $errors['method_name'] =
            'Название методики не должно быть длиннее 100 символов';
    }

    return $errors;
}

function validatePsychologist(array $data): array
{
    $errors = [];

    if (empty(trim($data['full_name'] ?? ''))) {
        $errors['full_name'] = 'Введите ФИО психолога';
    } elseif (mb_strlen(trim($data['full_name'])) > 100) {
        $errors['full_name'] = 'ФИО не должно быть длиннее 100 символов';
    }

    if (!empty($data['specialization']) && mb_strlen(trim($data['specialization'])) > 100) {
        $errors['specialization'] = 'Специализация не должна быть длиннее 100 символов';
    }

    return $errors;
}

function validateAppointment(array $data): array
{
    $errors = [];

    if (empty($data['client_id']) || (int) $data['client_id'] <= 0) {
        $errors['client_id'] = 'Выберите клиента';
    }

    if (empty($data['psychologist_id']) || (int) $data['psychologist_id'] <= 0) {
        $errors['psychologist_id'] = 'Выберите психолога';
    }

    if (empty($data['appointment_date'])) {
        $errors['appointment_date'] = 'Выберите дату консультации';
    }

    if (
        empty($data['consultation_type'])
        || !in_array($data['consultation_type'], ['offline', 'online'])
    ) {
        $errors['consultation_type'] = 'Выберите тип консультации';
    }

    return $errors;
}