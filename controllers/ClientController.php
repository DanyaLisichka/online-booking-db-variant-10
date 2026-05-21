<?php

class ClientController extends BaseController

{
    private ClientRepository $repository;

    

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->repository =
            new ClientRepository($pdo);
    }

    /*
     * LIST
     */

    public function list(): void
    {
        $clients =
            $this->repository->findAll();

        $this->render(
            'client/list',
            [
                'clients' => $clients
            ]
        );
    }

    /*
     * CREATE
     */

    public function create(): void
    {
        $errors = [];

        $data = [
            'full_name' => '',
            'email' => '',
            'phone' => '',
            'registration_date' => ''
        ];

        /*
         * FORM SUBMIT
         */

        if ($_SERVER['REQUEST_METHOD']
            === 'POST'
        ) {

            $data = [
                'full_name' =>
                    trim($_POST['full_name']),
                'email' =>
                    trim($_POST['email']),
                'phone' =>
                    trim($_POST['phone']),
                'registration_date' =>
                    $_POST['registration_date']
            ];

            /*
             * VALIDATION
             */

            $errors =
                validateClient($data);

            /*
             * SAVE
             */

            if (empty($errors)) {

                $this->repository
                    ->create($data);

                /*
                 * FLASH MESSAGE
                 */

                $_SESSION['success'] =
                    'Клиент успешно создан';

                /*
                 * REDIRECT
                 */

                $this->redirect(
                    'index.php?entity=client&action=list'
                );
            }
        }

        /*
         * SHOW FORM
         */

        $this->render(
            'client/create',
            [
                'errors' => $errors,
                'data' => $data
            ]
        );
    }

    public function delete(): void
{
    $id = isset($_GET['id'])
        ? (int) $_GET['id']
        : 0;

    if ($id <= 0) {

        $_SESSION['error'] =
            'Некорректный ID';

        $this->redirect(
            'index.php?entity=client&action=list'
        );
    }

    /*
     * LOAD CLIENT
     */

    $client =
        $this->repository->findById($id);

    if (!$client) {

        $_SESSION['error'] =
            'Клиент не найден';

        $this->redirect(
            'index.php?entity=client&action=list'
        );
    }

    /*
     * CONFIRM DELETE (GET -> form submit)
     */

    if ($_SERVER['REQUEST_METHOD']
        === 'POST'
    ) {

        $this->repository->delete($id);

        $_SESSION['success'] =
            'Клиент удалён';

        $this->redirect(
            'index.php?entity=client&action=list'
        );
    }

    /*
     * SHOW CONFIRM PAGE
     */

    $this->render(
        'client/delete',
        [
            'client' => $client,
            'id' => $id
        ]
    );
}
public function edit(): void
{
    /*
     * VALIDATE ID
     */

    $id = isset($_GET['id'])
        ? (int) $_GET['id']
        : 0;

    if ($id <= 0) {

        $_SESSION['error'] =
            'Некорректный ID';

        $this->redirect(
            'index.php?entity=client&action=list'
        );
    }

    /*
     * LOAD CLIENT
     */

    $client =
        $this->repository->findById($id);

    if (!$client) {

        $_SESSION['error'] =
            'Клиент не найден';

        $this->redirect(
            'index.php?entity=client&action=list'
        );
    }

    $errors = [];

    $data = $client;

    /*
     * FORM SUBMIT
     */

    if ($_SERVER['REQUEST_METHOD']
        === 'POST'
    ) {

        $data = [
            'full_name' =>
                trim($_POST['full_name']),
            'email' =>
                trim($_POST['email']),
            'phone' =>
                trim($_POST['phone']),
            'registration_date' =>
                $_POST['registration_date']
        ];

        /*
         * VALIDATION
         */

        $errors =
            validateClient($data);

        /*
         * UPDATE
         */

        if (empty($errors)) {

            $this->repository
                ->update($id, $data);

            $_SESSION['success'] =
                'Клиент успешно обновлён';

            $this->redirect(
                'index.php?entity=client&action=list'
            );
        }
    }

    /*
     * SHOW FORM
     */

    $this->render(
        'client/edit',
        [
            'errors' => $errors,
            'data' => $data,
            'id' => $id
        ]
    );
}

}


