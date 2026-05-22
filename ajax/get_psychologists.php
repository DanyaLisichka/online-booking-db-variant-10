<?php

session_start();

require_once '../config/config.php';
require_once '../autoload.php';

header('Content-Type: application/json; charset=utf-8');

$serviceId = isset($_GET['service_id'])
    ? (int) $_GET['service_id']
    : 0;

if ($serviceId <= 0) {
    echo json_encode([]);
    exit;
}

$pdo = Database::getConnection();

$serviceRepository = new ServiceRepository($pdo);

$psychologists = $serviceRepository
    ->getPsychologistsByService($serviceId);

echo json_encode(
    $psychologists,
    JSON_UNESCAPED_UNICODE
);