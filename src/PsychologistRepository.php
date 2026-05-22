<?php

class PsychologistRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "SELECT * FROM psychologists ORDER BY psychologist_id DESC";

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

    public function create(array $data)
    {
        $sql = "
            INSERT INTO psychologists (
                full_name,
                specialization
            )
            VALUES (
                :full_name,
                :specialization
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':full_name' => $data['full_name'],
            ':specialization' => $data['specialization']
        ]);
    }

    public function update(int $id, array $data)
    {
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
            ':full_name' => $data['full_name'],
            ':specialization' => $data['specialization']
        ]);
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

    public function findPaginated(
        string $search,
        int $limit,
        int $offset,
        string $sort = 'psychologist_id',
        string $order = 'desc'
    ) {
        $allowedSortFields = [
            'psychologist_id',
            'full_name',
            'specialization'
        ];

        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'psychologist_id';
        }

        $order = strtolower($order);

        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $sql = "
            SELECT *
            FROM psychologists
            WHERE
                full_name LIKE :search
                OR specialization LIKE :search
            ORDER BY $sort $order
            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':search', "%{$search}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(string $search)
    {
        $sql = "
            SELECT COUNT(*)
            FROM psychologists
            WHERE
                full_name LIKE :search
                OR specialization LIKE :search
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':search' => "%{$search}%"
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function hasAppointments(int $id): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM appointments
            WHERE psychologist_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function hasNotes(int $id): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM psychologist_notes
            WHERE psychologist_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }
}