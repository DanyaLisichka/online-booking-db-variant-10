<?php

abstract class AbstractRepository
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract public function findAll();

    abstract public function findById(int $id);

    abstract public function create(array $data);

    abstract public function update(
        int $id,
        array $data
    );

    abstract public function delete(int $id);
}