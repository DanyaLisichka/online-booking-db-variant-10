<?php

class AppointmentRepository extends AbstractRepository
{
    public function findAll()
    {
        $sql = "
            SELECT
                a.*,
                c.full_name AS client_name,
                p.full_name AS psychologist_name
            FROM appointments a
            JOIN clients c ON a.client_id = c.client_id
            JOIN psychologists p ON a.psychologist_id = p.psychologist_id
            ORDER BY a.appointment_id DESC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "
            SELECT *
            FROM appointments
            WHERE appointment_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $sql = "
            INSERT INTO appointments (
                client_id,
                psychologist_id,
                appointment_date,
                consultation_type
            )
            VALUES (
                :client_id,
                :psychologist_id,
                :appointment_date,
                :consultation_type
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':client_id' => $data['client_id'],
            ':psychologist_id' => $data['psychologist_id'],
            ':appointment_date' => $data['appointment_date'],
            ':consultation_type' => $data['consultation_type']
        ]);
    }

    public function update(int $id, array $data)
    {
        $sql = "
            UPDATE appointments
            SET
                client_id = :client_id,
                psychologist_id = :psychologist_id,
                appointment_date = :appointment_date,
                consultation_type = :consultation_type
            WHERE appointment_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':client_id' => $data['client_id'],
            ':psychologist_id' => $data['psychologist_id'],
            ':appointment_date' => $data['appointment_date'],
            ':consultation_type' => $data['consultation_type']
        ]);
    }

    public function delete(int $id)
    {
        $sql = "
            DELETE FROM appointments
            WHERE appointment_id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function findPaginated(
    array $filters,
    int $limit,
    int $offset,
    string $sort = 'appointment_id',
    string $order = 'desc'
) {
    $allowedSortFields = [
        'appointment_id',
        'client_name',
        'psychologist_name',
        'service_name',
        'appointment_date',
        'appointment_time',
        'consultation_type',
        'status'
    ];

    if (!in_array($sort, $allowedSortFields)) {
        $sort = 'appointment_id';
    }

    $order = strtolower($order);

    if (!in_array($order, ['asc', 'desc'])) {
        $order = 'desc';
    }

    $where = [];
    $params = [];

    if (!empty($filters['search'])) {
        $where[] = "
            (
                c.full_name LIKE :search
                OR p.full_name LIKE :search
                OR a.consultation_type LIKE :search
            )
        ";

        $params[':search'] = '%' . $filters['search'] . '%';
    }

    if (!empty($filters['status'])) {
        $where[] = "a.status = :status";
        $params[':status'] = $filters['status'];
    }

    if (!empty($filters['psychologist_id'])) {
        $where[] = "a.psychologist_id = :psychologist_id";
        $params[':psychologist_id'] =
            (int) $filters['psychologist_id'];
    }

    if (!empty($filters['date_from'])) {
        $where[] = "a.appointment_date >= :date_from";
        $params[':date_from'] = $filters['date_from'];
    }

    if (!empty($filters['date_to'])) {
        $where[] = "a.appointment_date <= :date_to";
        $params[':date_to'] = $filters['date_to'];
    }

    $whereSql = '';

    if (!empty($where)) {
        $whereSql = 'WHERE ' . implode(' AND ', $where);
    }

    $sql = "
        SELECT
            a.appointment_id,
            a.client_id,
            a.psychologist_id,
            a.service_id,
            a.appointment_date,
            a.appointment_time,
            a.consultation_type,
            a.status,
            c.full_name AS client_name,
            p.full_name AS psychologist_name,
            s.service_name
        FROM appointments a
        JOIN clients c ON a.client_id = c.client_id
        JOIN psychologists p ON a.psychologist_id = p.psychologist_id
        LEFT JOIN services s ON a.service_id = s.service_id
        $whereSql
        ORDER BY $sort $order
        LIMIT :limit
        OFFSET :offset
    ";

    $stmt = $this->pdo->prepare($sql);

    foreach ($params as $key => $value) {
        if ($key === ':psychologist_id') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function countAll(array $filters)
{
    $where = [];
    $params = [];

    if (!empty($filters['search'])) {
        $where[] = "
            (
                c.full_name LIKE :search
                OR p.full_name LIKE :search
                OR a.consultation_type LIKE :search
            )
        ";

        $params[':search'] = '%' . $filters['search'] . '%';
    }

    if (!empty($filters['status'])) {
        $where[] = "a.status = :status";
        $params[':status'] = $filters['status'];
    }

    if (!empty($filters['psychologist_id'])) {
        $where[] = "a.psychologist_id = :psychologist_id";
        $params[':psychologist_id'] =
            (int) $filters['psychologist_id'];
    }

    if (!empty($filters['date_from'])) {
        $where[] = "a.appointment_date >= :date_from";
        $params[':date_from'] = $filters['date_from'];
    }

    if (!empty($filters['date_to'])) {
        $where[] = "a.appointment_date <= :date_to";
        $params[':date_to'] = $filters['date_to'];
    }

    $whereSql = '';

    if (!empty($where)) {
        $whereSql = 'WHERE ' . implode(' AND ', $where);
    }

    $sql = "
        SELECT COUNT(*)
        FROM appointments a
        JOIN clients c ON a.client_id = c.client_id
        JOIN psychologists p ON a.psychologist_id = p.psychologist_id
        LEFT JOIN services s ON a.service_id = s.service_id
        $whereSql
    ";

    $stmt = $this->pdo->prepare($sql);

    foreach ($params as $key => $value) {
        if ($key === ':psychologist_id') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }

    $stmt->execute();

    return (int) $stmt->fetchColumn();
}

    public function getAvailableSlots(
        int $psychologistId,
        string $date,
        int $durationMinutes
    ): array {


    $workStart = '09:00';
    $workEnd = '18:00';
    $lunchStart = '13:00';
    $lunchEnd = '14:00';

    $sql = "
        SELECT appointment_time
        FROM appointments
        WHERE psychologist_id = :psychologist_id
          AND appointment_date = :appointment_date
          AND status != 'cancelled'
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':psychologist_id' => $psychologistId,
        ':appointment_date' => $date
    ]);

    $busyTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $slots = [];

    $current = strtotime($date . ' ' . $workStart);
    $end = strtotime($date . ' ' . $workEnd);

    while ($current + ($durationMinutes * 60) <= $end) {
        $time = date('H:i:s', $current);
        $shortTime = date('H:i', $current);

        $isLunch = (
            $shortTime >= $lunchStart
            && $shortTime < $lunchEnd
        );

        if (
            !$isLunch
            && !in_array($time, $busyTimes)
        ) {
            $slots[] = $shortTime;
        }

        $current += 30 * 60;
    }

    return $slots;
}

public function isSlotAvailable(
    int $psychologistId,
    string $date,
    string $time
): bool {
    $sql = "
        SELECT COUNT(*)
        FROM appointments
        WHERE psychologist_id = :psychologist_id
          AND appointment_date = :appointment_date
          AND appointment_time = :appointment_time
          AND status != 'cancelled'
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':psychologist_id' => $psychologistId,
        ':appointment_date' => $date,
        ':appointment_time' => $time
    ]);

    return (int) $stmt->fetchColumn() === 0;
}

public function createAppointment(array $data): int
{
    if (
        !$this->isSlotAvailable(
            (int) $data['psychologist_id'],
            $data['appointment_date'],
            $data['appointment_time']
        )
    ) {
        throw new Exception(
            'К сожалению, выбранное время только что занято другим клиентом. Пожалуйста, выберите другое время.'
        );
    }

    $sql = "
        INSERT INTO appointments (
            client_id,
            psychologist_id,
            service_id,
            appointment_date,
            appointment_time,
            consultation_type,
            status,
            booking_code
        )
        VALUES (
            :client_id,
            :psychologist_id,
            :service_id,
            :appointment_date,
            :appointment_time,
            :consultation_type,
            :status,
            :booking_code
        )
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':client_id' => $data['client_id'],
        ':psychologist_id' => $data['psychologist_id'],
        ':service_id' => $data['service_id'],
        ':appointment_date' => $data['appointment_date'],
        ':appointment_time' => $data['appointment_time'],
        ':consultation_type' => $data['consultation_type'],
        ':status' => 'pending',
        ':booking_code' => ''
    ]);

    $appointmentId = (int) $this->pdo->lastInsertId();

    $bookingCode = hash(
        'sha256',
        'booking_' . $appointmentId . '_' . time()
    );

    $updateSql = "
        UPDATE appointments
        SET booking_code = :booking_code
        WHERE appointment_id = :appointment_id
    ";

    $updateStmt = $this->pdo->prepare($updateSql);

    $updateStmt->execute([
        ':booking_code' => $bookingCode,
        ':appointment_id' => $appointmentId
    ]);

    return $appointmentId;
}

public function changeStatus(
    int $appointmentId,
    string $newStatus
): bool {
    $allowedStatuses = [
        'pending',
        'confirmed',
        'cancelled',
        'completed'
    ];

    if (!in_array($newStatus, $allowedStatuses)) {
        throw new Exception('Недопустимый статус записи');
    }

    $appointment = $this->findById($appointmentId);

    if (!$appointment) {
        throw new Exception('Запись не найдена');
    }

    if (
        $appointment['status'] === 'completed'
        && $newStatus !== 'completed'
    ) {
        throw new Exception(
            'Нельзя изменить статус завершённой записи'
        );
    }

    $sql = "
        UPDATE appointments
        SET status = :status
        WHERE appointment_id = :appointment_id
    ";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ':status' => $newStatus,
        ':appointment_id' => $appointmentId
    ]);
}
}