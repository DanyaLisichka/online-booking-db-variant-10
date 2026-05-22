<?php

class ReportController extends BaseController
{
    private ReportRepository $repository;

    public function __construct()
    {
        $pdo = Database::getConnection();

        $this->repository = new ReportRepository($pdo);
    }

    public function index(): void
    {
        $dateFrom = $_GET['date_from']
            ?? date('Y-m-01');

        $dateTo = $_GET['date_to']
            ?? date('Y-m-t');

        $appointmentsByDay =
            $this->repository->appointmentsByDay(
                $dateFrom,
                $dateTo
            );

        $psychologistLoad =
            $this->repository->psychologistLoad(
                $dateFrom,
                $dateTo
            );

        $revenueByService =
            $this->repository->revenueByService(
                $dateFrom,
                $dateTo
            );

        $this->render(
            'report/index',
            [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'appointmentsByDay' => $appointmentsByDay,
                'psychologistLoad' => $psychologistLoad,
                'revenueByService' => $revenueByService
            ]
        );
    }

    public function exportCsv(): void
    {
        $dateFrom = $_GET['date_from']
            ?? date('Y-m-01');

        $dateTo = $_GET['date_to']
            ?? date('Y-m-t');

        $rows = $this->repository->appointmentsByDay(
            $dateFrom,
            $dateTo
        );

        header('Content-Type: text/csv; charset=utf-8');
        header(
            'Content-Disposition: attachment; filename="appointments_report.csv"'
        );

        $output = fopen('php://output', 'w');

        fputcsv(
            $output,
            [
                'Дата',
                'Количество записей',
                'Выручка'
            ],
            ';'
        );

        foreach ($rows as $row) {
            fputcsv(
                $output,
                [
                    $row['appointment_date'],
                    $row['appointment_count'],
                    $row['revenue']
                ],
                ';'
            );
        }

        fclose($output);
        exit;
    }
}