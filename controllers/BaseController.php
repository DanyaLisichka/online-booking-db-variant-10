<?php

class BaseController
{
    protected function render(
        string $view,
        array $data = []
    ): void {

        extract($data);

        require 'views/layouts/header.php';

        require "views/{$view}.php";

        require 'views/layouts/footer.php';
    }

    protected function redirect(
        string $url
    ): void {

        header("Location: {$url}");
        exit;
    }
}