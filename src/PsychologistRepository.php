<?php

require_once 'AbstractRepository.php';

class PsychologistRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "SELECT * FROM psychologists";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT *
            FROM psychologists
            WHERE psychologist_id = :id
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
            DELETE FROM psychologists
            WHERE psychologist_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function create(
        string $fullName,
        string $specialization
    ) {
        $sql = "
            INSERT INTO psychologists
            (
                full_name,
                specialization
            )
            VALUES
            (
                :full_name,
                :specialization
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':full_name' => $fullName,
            ':specialization' => $specialization
        ]);
    }

    public function update(
        int $id,
        string $fullName,
        string $specialization
    ) {
        $sql = "
            UPDATE psychologists
            SET
                full_name = :full_name,
                specialization = :specialization
            WHERE psychologist_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':full_name' => $fullName,
            ':specialization' => $specialization
        ]);
    }

    public function findBySpecialization(
        string $specialization
    ) {
        $sql = "
            SELECT *
            FROM psychologists
            WHERE specialization = :specialization
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':specialization' => $specialization
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}