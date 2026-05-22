<?php

class MethodRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "SELECT * FROM methods ORDER BY method_id DESC";

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

    public function create(array $data)
    {
        $sql = "
            INSERT INTO methods (
                method_name
            )
            VALUES (
                :method_name
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':method_name' => $data['method_name']
        ]);
    }

    public function update(int $id, array $data)
    {
        $sql = "
            UPDATE methods
            SET method_name = :method_name
            WHERE method_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':method_name' => $data['method_name']
        ]);
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

    public function findPaginated(
        string $search,
        int $limit,
        int $offset,
        string $sort = 'method_id',
        string $order = 'desc'
    ) {
        $allowedSortFields = [
            'method_id',
            'method_name'
        ];

        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'method_id';
        }

        $order = strtolower($order);

        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $sql = "
            SELECT *
            FROM methods
            WHERE method_name LIKE :search
            ORDER BY $sort $order
            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':search',
            "%{$search}%",
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(string $search)
    {
        $sql = "
            SELECT COUNT(*)
            FROM methods
            WHERE method_name LIKE :search
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':search' => "%{$search}%"
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function hasPsychologists(int $id): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM psychologist_methods
            WHERE method_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }
}