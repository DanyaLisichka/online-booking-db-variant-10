<?php

class PsychologistController extends BaseController
{
    private PsychologistRepository $repository;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->repository = new PsychologistRepository($pdo);
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
            'psychologist_id',
            'full_name',
            'specialization'
        ];

        $sort = $_GET['sort'] ?? 'psychologist_id';

        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'psychologist_id';
        }

        $order = $_GET['order'] ?? 'desc';

        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $psychologists = $this->repository->findPaginated(
            $search,
            $limit,
            $offset,
            $sort,
            $order
        );

        $total = $this->repository->countAll($search);

        $pages = (int) ceil($total / $limit);

        $this->render(
            'psychologist/list',
            [
                'psychologists' => $psychologists,
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
            'specialization' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = 'Ошибка безопасности. Повторите отправку формы.';

                $this->redirect(
                    'index.php?entity=psychologist&action=create'
                );
            }

            $data = [
                'full_name' => trim($_POST['full_name']),
                'specialization' => trim($_POST['specialization'])
            ];

            $errors = validatePsychologist($data);

            if (empty($errors)) {
                $this->repository->create($data);

                $_SESSION['success'] = 'Психолог успешно создан';

                $this->redirect(
                    'index.php?entity=psychologist&action=list'
                );
            }
        }

        $this->render(
            'psychologist/create',
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
                'index.php?entity=psychologist&action=list'
            );
        }

        $psychologist = $this->repository->findById($id);

        if (!$psychologist) {
            $_SESSION['error'] = 'Психолог не найден';

            $this->redirect(
                'index.php?entity=psychologist&action=list'
            );
        }

        $errors = [];
        $data = $psychologist;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = 'Ошибка безопасности. Повторите отправку формы.';

                $this->redirect(
                    'index.php?entity=psychologist&action=edit&id=' . $id
                );
            }

            $data = [
                'full_name' => trim($_POST['full_name']),
                'specialization' => trim($_POST['specialization'])
            ];

            $errors = validatePsychologist($data);

            if (empty($errors)) {
                $this->repository->update($id, $data);

                $_SESSION['success'] = 'Психолог успешно обновлён';

                $this->redirect(
                    'index.php?entity=psychologist&action=list'
                );
            }
        }

        $this->render(
            'psychologist/edit',
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
                'index.php?entity=psychologist&action=list'
            );
        }

        $psychologist = $this->repository->findById($id);

        if (!$psychologist) {
            $_SESSION['error'] = 'Психолог не найден';

            $this->redirect(
                'index.php?entity=psychologist&action=list'
            );
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = 'Ошибка безопасности. Повторите удаление.';

                $this->redirect(
                    'index.php?entity=psychologist&action=delete&id=' . $id
                );
            }

            if ($this->repository->hasAppointments($id)) {
                $_SESSION['error'] =
                    'Нельзя удалить психолога, так как у него есть записи на консультации.';

                $this->redirect(
                    'index.php?entity=psychologist&action=list'
                );
            }

            if ($this->repository->hasNotes($id)) {
                $_SESSION['error'] =
                    'Нельзя удалить психолога, так как у него есть заметки.';

                $this->redirect(
                    'index.php?entity=psychologist&action=list'
                );
            }

            $this->repository->delete($id);

            $_SESSION['success'] = 'Психолог удалён';

            $this->redirect(
                'index.php?entity=psychologist&action=list'
            );
        }

        $this->render(
            'psychologist/delete',
            [
                'psychologist' => $psychologist,
                'id' => $id
            ]
        );
    }
}