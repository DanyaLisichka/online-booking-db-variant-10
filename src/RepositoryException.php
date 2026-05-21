<?php

class RepositoryException extends Exception
{
    public function __construct(
        string $message = "Ошибка репозитория",
        int $code = 0
    ) {
        parent::__construct($message, $code);
    }
}