<?php

class BookingController extends BaseController
{
    private AppointmentRepository $appointmentRepository;
    private ServiceRepository $serviceRepository;
    private ClientRepository $clientRepository;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->appointmentRepository =
            new AppointmentRepository($pdo);

        $this->serviceRepository =
            new ServiceRepository($pdo);

        $this->clientRepository =
            new ClientRepository($pdo);
    }

    public function create(): void
    {
        $errors = [];

        $data = [
            'service_id' => '',
            'psychologist_id' => '',
            'appointment_date' => '',
            'appointment_time' => '',
            'client_id' => '',
            'consultation_type' => ''
        ];

        $services = $this->serviceRepository->findAll();
        $clients = $this->clientRepository->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                !checkCsrfToken(
                    $_POST['csrf_token'] ?? null
                )
            ) {
                $_SESSION['error'] =
                    'Ошибка безопасности. Повторите отправку формы.';

                $this->redirect(
                    'index.php?entity=booking&action=create'
                );
            }

            $data = [
                'service_id' => (int) $_POST['service_id'],
                'psychologist_id' => (int) $_POST['psychologist_id'],
                'appointment_date' => $_POST['appointment_date'],
                'appointment_time' => $_POST['appointment_time'],
                'client_id' => (int) $_POST['client_id'],
                'consultation_type' => $_POST['consultation_type']
            ];

            $errors = $this->validateBooking($data);

            if (empty($errors)) {
                try {
                    $appointmentId =
                        $this->appointmentRepository
                            ->createAppointment($data);

                    $_SESSION['success'] =
                        'Бронирование успешно создано. Номер записи: '
                        . $appointmentId;

                    $this->redirect(
                        'index.php?entity=appointment&action=list'
                    );
                } catch (Exception $e) {
                    $errors['common'] = $e->getMessage();
                }
            }
        }

        $this->render(
            'booking/create',
            [
                'errors' => $errors,
                'data' => $data,
                'services' => $services,
                'clients' => $clients
            ]
        );
    }

    private function validateBooking(array $data): array
    {
        $errors = [];

        if (empty($data['service_id'])) {
            $errors['service_id'] =
                'Выберите услугу';
        }

        if (empty($data['psychologist_id'])) {
            $errors['psychologist_id'] =
                'Выберите психолога';
        }

        if (empty($data['client_id'])) {
            $errors['client_id'] =
                'Выберите клиента';
        }

        if (empty($data['appointment_date'])) {
            $errors['appointment_date'] =
                'Выберите дату';
        } elseif (
            strtotime($data['appointment_date'])
            < strtotime(date('Y-m-d'))
        ) {
            $errors['appointment_date'] =
                'Дата не может быть в прошлом';
        }

        if (empty($data['appointment_time'])) {
            $errors['appointment_time'] =
                'Выберите время';
        }

        if (
            empty($data['consultation_type'])
            || !in_array(
                $data['consultation_type'],
                ['offline', 'online']
            )
        ) {
            $errors['consultation_type'] =
                'Выберите тип консультации';
        }

        return $errors;
    }
}