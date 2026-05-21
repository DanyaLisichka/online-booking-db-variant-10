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
            ':registration_date' =>
                $data['registration_date']
        ]);
    }

    public function update(
        int $id,
        array $data
    ) {

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
            ':registration_date' =>
                $data['registration_date']
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
}