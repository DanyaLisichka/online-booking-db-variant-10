SELECT DISTINCT c.full_name
FROM clients c
JOIN appointments a
    ON c.client_id = a.client_id
GROUP BY c.client_id
HAVING COUNT(a.appointment_id) >
(
    SELECT AVG(cnt)
    FROM
    (
        SELECT COUNT(*) AS cnt
        FROM appointments
        GROUP BY client_id
    ) AS stats
);
