<?php


require_once 'config/config.php';
require_once 'src/Database.php';
require_once 'src/AbstractRepository.php';
require_once 'src/ClientRepository.php';


require_once 'src/PsychologistRepository.php';
require_once 'src/AppointmentRepository.php';
require_once 'src/RequestRepository.php';
require_once 'src/MethodRepository.php';

try {

    // Подключение к БД
    $pdo = Database::getConnection();

    echo "<h2>Подключение успешно</h2>";

    // Создание репозиториев
    $clientRepo = new ClientRepository($pdo);

    $psychologistRepo =
        new PsychologistRepository($pdo);

    $appointmentRepo =
        new AppointmentRepository($pdo);

    // Получение всех клиентов
    echo "<h3>Список клиентов:</h3>";

    echo "<pre>";

    print_r(
        $clientRepo->findAll()
    );

    echo "</pre>";

    // Получение психолога по ID
    echo "<h3>Психолог с ID = 1</h3>";

    echo "<pre>";

    print_r(
        $psychologistRepo->findById(1)
    );

    echo "</pre>";

    // Добавление записи
    echo "<h3>Создание консультации</h3>";

    $appointmentRepo->createAppointment(
        1,
        1,
        '2026-06-01',
        'online'
    );

    echo "Запись успешно создана";

} catch (PDOException $e) {

    echo "Ошибка PDO: "
         . $e->getMessage();

} catch (Exception $e) {

    echo "Ошибка: "
         . $e->getMessage();
}
