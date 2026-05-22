<?php

class ClientController extends BaseController
{
    private ClientRepository $repository;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->repository = new ClientRepository($pdo);
    }

    public function list(): void
{
    $search = isset($_GET['search'])
        ? trim($_GET['search'])
        : '';

    $page = isset($_GET['page'])
        ? max(1, (int) $_GET['page'])
        : 1;

    $limit = 10;
    $offset = ($page - 1) * $limit;

    /*
     * SORTING
     */

    $allowedSortFields = [
        'client_id',
        'full_name',
        'email',
        'phone',
        'registration_date'
    ];

    $sort = $_GET['sort'] ?? 'client_id';

    if (!in_array($sort, $allowedSortFields)) {
        $sort = 'client_id';
    }

    $order = $_GET['order'] ?? 'desc';

    if (!in_array($order, ['asc', 'desc'])) {
        $order = 'desc';
    }

    $clients = $this->repository->findPaginated(
        $search,
        $limit,
        $offset,
        $sort,
        $order
    );

    $total = $this->repository->countAll($search);

    $pages = (int) ceil($total / $limit);

    $this->render(
        'client/list',
        [
            'clients' => $clients,
            'search' => $search,
            'page' => $page,
            'pages' => $pages,
            'sort' => $sort,
            'order' => $order
        ]
    );
}

    public function create(): void
    {
        $errors = [];

        $data = [
            'full_name' => '',
            'email' => '',
            'phone' => '',
            'registration_date' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
    !checkCsrfToken(
        $_POST['csrf_token'] ?? null
    )
) {
    $_SESSION['error'] =
        'Ошибка безопасности. Повторите отправку формы.';

    $this->redirect(
        'index.php?entity=client&action=create'
    );
}

            $data = [
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'registration_date' => $_POST['registration_date']
            ];

            $errors = validateClient($data);

            if (empty($errors)) {
                $this->repository->create($data);

                $_SESSION['success'] = 'Клиент успешно создан';

                $this->redirect(
                    'index.php?entity=client&action=list'
                );
            }
        }

        $this->render(
            'client/create',
            [
                'errors' => $errors,
                'data' => $data
            ]
        );
    }

    public function edit(): void
    {
        $id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'Некорректный ID';

            $this->redirect(
                'index.php?entity=client&action=list'
            );
        }

        $client = $this->repository->findById($id);

        if (!$client) {
            $_SESSION['error'] = 'Клиент не найден';

            $this->redirect(
                'index.php?entity=client&action=list'
            );
        }

        $errors = [];
        $data = $client;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
    !checkCsrfToken(
        $_POST['csrf_token'] ?? null
    )
) {
    $_SESSION['error'] =
        'Ошибка безопасности. Повторите отправку формы.';

    $this->redirect(
        'index.php?entity=client&action=edit&id=' . $id
    );
}
            $data = [
                'full_name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'registration_date' => $_POST['registration_date']
            ];

            $errors = validateClient($data);

            if (empty($errors)) {
                $this->repository->update($id, $data);

                $_SESSION['success'] = 'Клиент успешно обновлён';

                $this->redirect(
                    'index.php?entity=client&action=list'
                );
            }
        }

        $this->render(
            'client/edit',
            [
                'errors' => $errors,
                'data' => $data,
                'id' => $id
            ]
        );
    }

    public function delete(): void
    {
        $id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'Некорректный ID';

            $this->redirect(
                'index.php?entity=client&action=list'
            );
        }

        $client = $this->repository->findById($id);

        if (!$client) {
            $_SESSION['error'] = 'Клиент не найден';

            $this->redirect(
                'index.php?entity=client&action=list'
            );
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
    !checkCsrfToken(
        $_POST['csrf_token'] ?? null
    )
) {
    $_SESSION['error'] =
        'Ошибка безопасности. Повторите удаление.';

    $this->redirect(
        'index.php?entity=client&action=delete&id=' . $id
    );
}
if ($this->repository->hasAppointments($id)) {
    $_SESSION['error'] =
        'Нельзя удалить клиента, так как у него есть записи на консультации. Сначала удалите или отмените связанные записи.';

    $this->redirect(
        'index.php?entity=client&action=list'
    );
}

if ($this->repository->hasRequests($id)) {
    $_SESSION['error'] =
        'Нельзя удалить клиента, так как у него есть заявки. Сначала удалите связанные заявки.';

    $this->redirect(
        'index.php?entity=client&action=list'
    );
}
            $this->repository->delete($id);

            $_SESSION['success'] = 'Клиент удалён';

            $this->redirect(
                'index.php?entity=client&action=list'
            );
        }

        $this->render(
            'client/delete',
            [
                'client' => $client,
                'id' => $id
            ]
        );
    }
}