<?php

class ClientRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "SELECT * FROM clients";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT * FROM clients
            WHERE client_id = :id
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
            INSERT INTO clients (
                full_name,
                email,
                phone,
                registration_date
            )
            VALUES (
                :full_name,
                :email,
                :phone,
                :registration_date
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':registration_date' => $data['registration_date']
        ]);
    }

    public function update(int $id, array $data)
    {
        $sql = "
            UPDATE clients
            SET
                full_name = :full_name,
                email = :email,
                phone = :phone,
                registration_date = :registration_date
            WHERE client_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':full_name' => $data['full_name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':registration_date' => $data['registration_date']
        ]);
    }

    public function delete(int $id)
    {
        $sql = "
            DELETE FROM clients
            WHERE client_id = :id
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
    string $sort = 'client_id',
    string $order = 'desc'
) {
    $allowedSortFields = [
        'client_id',
        'full_name',
        'email',
        'phone',
        'registration_date'
    ];

    if (!in_array($sort, $allowedSortFields)) {
        $sort = 'client_id';
    }

    $order = strtolower($order);

    if (!in_array($order, ['asc', 'desc'])) {
        $order = 'desc';
    }

    $sql = "
        SELECT *
        FROM clients
        WHERE
            full_name LIKE :search
            OR email LIKE :search
            OR phone LIKE :search
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
            FROM clients
            WHERE
                full_name LIKE :search
                OR email LIKE :search
                OR phone LIKE :search
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
        WHERE client_id = :id
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

public function hasRequests(int $id): bool
{
    $sql = "
        SELECT COUNT(*)
        FROM requests
        WHERE client_id = :id
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return (int) $stmt->fetchColumn() > 0;
}
}