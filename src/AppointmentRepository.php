<?php

require_once 'AbstractRepository.php';

class AppointmentRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "
            SELECT *
            FROM appointments
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT *
            FROM appointments
            WHERE appointment_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id)
    {
        $sql = "
            DELETE FROM appointments
            WHERE appointment_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function createAppointment(
        int $clientId,
        int $psychologistId,
        string $appointmentDate,
        string $consultationType
    ) {
        $sql = "
            INSERT INTO appointments
            (
                client_id,
                psychologist_id,
                appointment_date,
                consultation_type
            )
            VALUES
            (
                :client_id,
                :psychologist_id,
                :appointment_date,
                :consultation_type
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':client_id' => $clientId,
            ':psychologist_id' => $psychologistId,
            ':appointment_date' => $appointmentDate,
            ':consultation_type' => $consultationType
        ]);
    }

    public function getAppointmentsByDate(
        string $date
    ) {
        $sql = "
            SELECT *
            FROM appointments
            WHERE DATE(appointment_date) = :date
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':date' => $date
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPsychologistAppointments(
        int $psychologistId
    ) {
        $sql = "
            SELECT *
            FROM appointments
            WHERE psychologist_id = :psychologist_id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':psychologist_id' => $psychologistId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}