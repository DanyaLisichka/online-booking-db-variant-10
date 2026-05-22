<?php

class ServiceRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "
            SELECT *
            FROM services
            ORDER BY service_name ASC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT *
            FROM services
            WHERE service_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $sql = "
            INSERT INTO services (
                service_name,
                duration_minutes,
                price
            )
            VALUES (
                :service_name,
                :duration_minutes,
                :price
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':service_name' => $data['service_name'],
            ':duration_minutes' => $data['duration_minutes'],
            ':price' => $data['price']
        ]);
    }

    public function update(int $id, array $data)
    {
        $sql = "
            UPDATE services
            SET
                service_name = :service_name,
                duration_minutes = :duration_minutes,
                price = :price
            WHERE service_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':service_name' => $data['service_name'],
            ':duration_minutes' => $data['duration_minutes'],
            ':price' => $data['price']
        ]);
    }

    public function delete(int $id)
    {
        $sql = "
            DELETE FROM services
            WHERE service_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function getPsychologistsByService(int $serviceId): array
    {
        $sql = "
            SELECT
                p.psychologist_id,
                p.full_name,
                p.specialization
            FROM psychologists p
            INNER JOIN psychologist_services ps
                ON p.psychologist_id = ps.psychologist_id
            WHERE ps.service_id = :service_id
            ORDER BY p.full_name ASC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':service_id' => $serviceId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}