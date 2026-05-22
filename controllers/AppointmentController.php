<?php

class AppointmentController extends BaseController
{
    private AppointmentRepository $repository;
    private ClientRepository $clientRepository;
    private PsychologistRepository $psychologistRepository;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->repository = new AppointmentRepository($pdo);
        $this->clientRepository = new ClientRepository($pdo);
        $this->psychologistRepository = new PsychologistRepository($pdo);
    }

    public function list(): void
{
    $filters = [
        'search' => isset($_GET['search'])
            ? trim($_GET['search'])
            : '',

        'status' => $_GET['status'] ?? '',

        'psychologist_id' => isset($_GET['psychologist_id'])
            ? (int) $_GET['psychologist_id']
            : 0,

        'date_from' => $_GET['date_from'] ?? '',

        'date_to' => $_GET['date_to'] ?? ''
    ];

    $page = isset($_GET['page'])
        ? max(1, (int) $_GET['page'])
        : 1;

    $limit = 20;
    $offset = ($page - 1) * $limit;

    $allowedSortFields = [
        'appointment_id',
        'client_name',
        'psychologist_name',
        'appointment_date',
        'appointment_time',
        'consultation_type',
        'status'
    ];

    $sort = $_GET['sort'] ?? 'appointment_id';

    if (!in_array($sort, $allowedSortFields)) {
        $sort = 'appointment_id';
    }

    $order = $_GET['order'] ?? 'desc';

    if (!in_array($order, ['asc', 'desc'])) {
        $order = 'desc';
    }

    $appointments = $this->repository->findPaginated(
        $filters,
        $limit,
        $offset,
        $sort,
        $order
    );

    $total = $this->repository->countAll($filters);

    $pages = (int) ceil($total / $limit);

    $psychologists =
        $this->psychologistRepository->findAll();

    $this->render(
        'appointment/list',
        [
            'appointments' => $appointments,
            'filters' => $filters,
            'page' => $page,
            'pages' => $pages,
            'sort' => $sort,
            'order' => $order,
            'psychologists' => $psychologists
        ]
    );
}

    public function create(): void
    {
        $errors = [];

        $data = [
            'client_id' => '',
            'psychologist_id' => '',
            'appointment_date' => '',
            'consultation_type' => ''
        ];

        $clients = $this->clientRepository->findAll();
        $psychologists = $this->psychologistRepository->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = 'Ошибка безопасности. Повторите отправку формы.';

                $this->redirect(
                    'index.php?entity=appointment&action=create'
                );
            }

            $data = [
                'client_id' => (int) $_POST['client_id'],
                'psychologist_id' => (int) $_POST['psychologist_id'],
                'appointment_date' => $_POST['appointment_date'],
                'consultation_type' => $_POST['consultation_type']
            ];

            $errors = validateAppointment($data);

            if (empty($errors)) {
                try {
                    $this->repository->create($data);

                    $_SESSION['success'] = 'Запись на консультацию успешно создана';

                    $this->redirect(
                        'index.php?entity=appointment&action=list'
                    );
                } catch (PDOException $e) {
                    $errors['common'] = $e->getMessage();
                }
            }
        }

        $this->render(
            'appointment/create',
            [
                'errors' => $errors,
                'data' => $data,
                'clients' => $clients,
                'psychologists' => $psychologists
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
                'index.php?entity=appointment&action=list'
            );
        }

        $appointment = $this->repository->findById($id);

        if (!$appointment) {
            $_SESSION['error'] = 'Запись не найдена';

            $this->redirect(
                'index.php?entity=appointment&action=list'
            );
        }

        $errors = [];
        $data = $appointment;

        $clients = $this->clientRepository->findAll();
        $psychologists = $this->psychologistRepository->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = 'Ошибка безопасности. Повторите отправку формы.';

                $this->redirect(
                    'index.php?entity=appointment&action=edit&id=' . $id
                );
            }

            $data = [
                'client_id' => (int) $_POST['client_id'],
                'psychologist_id' => (int) $_POST['psychologist_id'],
                'appointment_date' => $_POST['appointment_date'],
                'consultation_type' => $_POST['consultation_type']
            ];

            $errors = validateAppointment($data);

            if (empty($errors)) {
                try {
                    $this->repository->update($id, $data);

                    $_SESSION['success'] = 'Запись успешно обновлена';

                    $this->redirect(
                        'index.php?entity=appointment&action=list'
                    );
                } catch (PDOException $e) {
                    $errors['common'] = $e->getMessage();
                }
            }
        }

        $this->render(
            'appointment/edit',
            [
                'errors' => $errors,
                'data' => $data,
                'id' => $id,
                'clients' => $clients,
                'psychologists' => $psychologists
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
                'index.php?entity=appointment&action=list'
            );
        }

        $appointment = $this->repository->findById($id);

        if (!$appointment) {
            $_SESSION['error'] = 'Запись не найдена';

            $this->redirect(
                'index.php?entity=appointment&action=list'
            );
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = 'Ошибка безопасности. Повторите удаление.';

                $this->redirect(
                    'index.php?entity=appointment&action=delete&id=' . $id
                );
            }

            if ($this->repository->hasNotes($id)) {
                $_SESSION['error'] =
                    'Нельзя удалить запись, так как к ней привязаны заметки психолога.';

                $this->redirect(
                    'index.php?entity=appointment&action=list'
                );
            }
        }
            $this->repository->delete($id);

            $_SESSION['success'] = 'Запись удалена';

            $this->redirect(
                'index.php?entity=appointment&action=list'
            );
        
        $this->render(
            'appointment/delete',
            [
                'appointment' => $appointment,
                'id' => $id
            ]
        );
    }

public function changeStatus(): void
{
    $id = isset($_GET['id'])
        ? (int) $_GET['id']
        : 0;

    $status = $_GET['status'] ?? '';

    if ($id <= 0) {
        $_SESSION['error'] = 'Некорректный ID записи';

        $this->redirect(
            'index.php?entity=appointment&action=list'
        );
    }

    try {
        $this->repository->changeStatus(
            $id,
            $status
        );

        $_SESSION['success'] =
            'Статус записи успешно изменён';
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $this->redirect(
        'index.php?entity=appointment&action=list'
    );
}
}