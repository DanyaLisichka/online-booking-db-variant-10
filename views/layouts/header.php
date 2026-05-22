<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Справочник консультаций</title>
<link rel="stylesheet" href="assets/css/style.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        
    >
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
    <div class="container">

        <a
            class="navbar-brand"
            href="index.php"
        >
            Психологический центр
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="index.php?entity=client&action=list"
                    >
                        Клиенты
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="index.php?entity=method&action=list"
                    >
                        Методики
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="index.php?entity=psychologist&action=list"
                    >
                        Психологи
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="index.php?entity=appointment&action=list"
                    >
                        Записи
                    </a>
                </li>

                <li class="nav-item">
    <a
        class="nav-link"
        href="index.php?entity=booking&action=create"
    >
        Бронирование
    </a>
</li>
<li class="nav-item">
    <a
        class="nav-link"
        href="index.php?entity=report&action=index"
    >
        Отчёты
    </a>
</li>

            </ul>
        </div>

    </div>
</nav>

<div class="container mt-4">

    <?php if (isset($_SESSION['success'])): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>