use psychology_center

SELECT
    c.full_name,
    p.full_name,
    a.appointment_date,
    a.consultation_type
FROM appointments a
JOIN clients c
    ON a.client_id = c.client_id
JOIN psychologists p
    ON a.psychologist_id = p.psychologist_id;