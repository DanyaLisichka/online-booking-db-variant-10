<?php

require_once 'AbstractRepository.php';

class MethodRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "
            SELECT *
            FROM methods
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT *
            FROM methods
            WHERE method_id = :id
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
            DELETE FROM methods
            WHERE method_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function createMethod(
        string $methodName
    ) {
        $sql = "
            INSERT INTO methods
            (
                method_name
            )
            VALUES
            (
                :method_name
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':method_name' => $methodName
        ]);
    }

    public function findByName(
        string $methodName
    ) {
        $sql = "
            SELECT *
            FROM methods
            WHERE method_name LIKE :method_name
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':method_name' => "%$methodName%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}