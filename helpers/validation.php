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