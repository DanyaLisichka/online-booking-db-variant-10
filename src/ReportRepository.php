<?php

class ReportRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function appointmentsByDay(
        string $dateFrom,
        string $dateTo
    ): array {
        $sql = "
            SELECT
                a.appointment_date,
                COUNT(*) AS appointment_count,
                SUM(COALESCE(s.price, 0)) AS revenue
            FROM appointments a
            LEFT JOIN services s ON a.service_id = s.service_id
            WHERE a.appointment_date BETWEEN :date_from AND :date_to
              AND a.status != 'cancelled'
            GROUP BY a.appointment_date
            ORDER BY a.appointment_date ASC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':date_from' => $dateFrom,
            ':date_to' => $dateTo
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function psychologistLoad(
        string $dateFrom,
        string $dateTo
    ): array {
        $sql = "
            SELECT
                p.full_name AS psychologist_name,
                COUNT(a.appointment_id) AS appointment_count
            FROM psychologists p
            LEFT JOIN appointments a
                ON p.psychologist_id = a.psychologist_id
                AND a.appointment_date BETWEEN :date_from AND :date_to
                AND a.status != 'cancelled'
            GROUP BY p.psychologist_id, p.full_name
            ORDER BY appointment_count DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':date_from' => $dateFrom,
            ':date_to' => $dateTo
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function revenueByService(
        string $dateFrom,
        string $dateTo
    ): array {
        $sql = "
            SELECT
                s.service_name,
                COUNT(a.appointment_id) AS appointment_count,
                SUM(COALESCE(s.price, 0)) AS revenue
            FROM services s
            LEFT JOIN appointments a
                ON s.service_id = a.service_id
                AND a.appointment_date BETWEEN :date_from AND :date_to
                AND a.status != 'cancelled'
            GROUP BY s.service_id, s.service_name
            ORDER BY revenue DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':date_from' => $dateFrom,
            ':date_to' => $dateTo
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}