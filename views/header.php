<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>CRM</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
</head>
<body>

<div class="container mt-4">

<?php if (isset($_SESSION['success'])): ?>

    <div class="alert alert-success alert-dismissible fade show">

        <?= htmlspecialchars(
            $_SESSION['success']
        ) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>

    <div class="alert alert-danger alert-dismissible fade show">

        <?= htmlspecialchars(
            $_SESSION['error']
        ) ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>