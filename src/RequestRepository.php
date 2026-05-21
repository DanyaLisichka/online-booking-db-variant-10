<?php

require_once 'AbstractRepository.php';

class RequestRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "SELECT * FROM requests";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT *
            FROM requests
            WHERE request_id = :id
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
            DELETE FROM requests
            WHERE request_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function createRequest(
        ?int $clientId,
        ?string $anonymousName,
        string $requestText,
        string $requestDate
    ) {
        $sql = "
            INSERT INTO requests
            (
                client_id,
                anonymous_name,
                request_text,
                request_date
            )
            VALUES
            (
                :client_id,
                :anonymous_name,
                :request_text,
                :request_date
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':client_id' => $clientId,
            ':anonymous_name' => $anonymousName,
            ':request_text' => $requestText,
            ':request_date' => $requestDate
        ]);
    }
}