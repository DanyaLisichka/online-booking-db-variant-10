<?php

class MethodController extends BaseController
{
    private MethodRepository $repository;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->repository = new MethodRepository($pdo);
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

        $allowedSortFields = [
            'method_id',
            'method_name'
        ];

        $sort = $_GET['sort'] ?? 'method_id';

        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'method_id';
        }

        $order = $_GET['order'] ?? 'desc';

        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $methods = $this->repository->findPaginated(
            $search,
            $limit,
            $offset,
            $sort,
            $order
        );

        $total = $this->repository->countAll($search);

        $pages = (int) ceil($total / $limit);

        $this->render(
            'method/list',
            [
                'methods' => $methods,
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
            'method_name' => ''
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
                    'index.php?entity=method&action=create'
                );
            }

            $data = [
                'method_name' => trim($_POST['method_name'])
            ];

            $errors = validateMethod($data);

            if (empty($errors)) {
                $this->repository->create($data);

                $_SESSION['success'] =
                    'Методика успешно создана';

                $this->redirect(
                    'index.php?entity=method&action=list'
                );
            }
        }

        $this->render(
            'method/create',
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
                'index.php?entity=method&action=list'
            );
        }

        $method = $this->repository->findById($id);

        if (!$method) {
            $_SESSION['error'] = 'Методика не найдена';

            $this->redirect(
                'index.php?entity=method&action=list'
            );
        }

        $errors = [];
        $data = $method;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                !checkCsrfToken(
                    $_POST['csrf_token'] ?? null
                )
            ) {
                $_SESSION['error'] =
                    'Ошибка безопасности. Повторите отправку формы.';

                $this->redirect(
                    'index.php?entity=method&action=edit&id=' . $id
                );
            }

            $data = [
                'method_name' => trim($_POST['method_name'])
            ];

            $errors = validateMethod($data);

            if (empty($errors)) {
                $this->repository->update($id, $data);

                $_SESSION['success'] =
                    'Методика успешно обновлена';

                $this->redirect(
                    'index.php?entity=method&action=list'
                );
            }
        }

        $this->render(
            'method/edit',
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
                'index.php?entity=method&action=list'
            );
        }

        $method = $this->repository->findById($id);

        if (!$method) {
            $_SESSION['error'] = 'Методика не найдена';

            $this->redirect(
                'index.php?entity=method&action=list'
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
                    'index.php?entity=method&action=delete&id=' . $id
                );
            }

            if ($this->repository->hasPsychologists($id)) {
                $_SESSION['error'] =
                    'Нельзя удалить методику, так как она связана с психологами.';

                $this->redirect(
                    'index.php?entity=method&action=list'
                );
            }

            $this->repository->delete($id);

            $_SESSION['success'] =
                'Методика удалена';

            $this->redirect(
                'index.php?entity=method&action=list'
            );
        }

        $this->render(
            'method/delete',
            [
                'method' => $method,
                'id' => $id
            ]
        );
    }
}