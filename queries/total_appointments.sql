SELECT
    p.full_name,
    COUNT(a.appointment_id) AS total_appointments
FROM psychologists p
JOIN appointments a
    ON p.psychologist_id = a.psychologist_id
GROUP BY p.psychologist_id
HAVING total_appointments > 0;
