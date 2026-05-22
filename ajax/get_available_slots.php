<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../config/config.php';
require_once '../autoload.php';

header('Content-Type: application/json; charset=utf-8');

$psychologistId = isset($_GET['psychologist_id'])
    ? (int) $_GET['psychologist_id']
    : 0;

$serviceId = isset($_GET['service_id'])
    ? (int) $_GET['service_id']
    : 0;

$date = $_GET['date'] ?? '';

if (
    $psychologistId <= 0
    || $serviceId <= 0
    || empty($date)
) {
    echo json_encode([]);
    exit;
}

$pdo = Database::getConnection();

$serviceRepository = new ServiceRepository($pdo);
$appointmentRepository = new AppointmentRepository($pdo);

$service = $serviceRepository->findById($serviceId);

if (!$service) {
    echo json_encode([]);
    exit;
}

$slots = $appointmentRepository->getAvailableSlots(
    $psychologistId,
    $date,
    (int) $service['duration_minutes']
);


echo json_encode(
    $slots,
    JSON_UNESCAPED_UNICODE
);