
---------------------------------------------------
-- Пример данных
---------------------------------------------------

INSERT INTO clients (full_name, phone, email, registration_date)
VALUES
('Анна Иванова', '+79990001111', 'anna@mail.ru', '2026-01-10'),
('Игорь Смирнов', '+79990002222', 'igor@mail.ru', '2026-01-12');

INSERT INTO psychologists (full_name, specialization)
VALUES
('Елена Петрова', 'Когнитивная терапия'),
('Мария Соколова', 'Семейная психология');

INSERT INTO methods (method_name)
VALUES
('КПТ'),
('Гештальт-терапия'),
('Арт-терапия');

INSERT INTO psychologist_methods
VALUES
(1, 1),
(1, 3),
(2, 2);

INSERT INTO requests 
(client_id, anonymous_name, request_text, request_date)
VALUES
(NULL, 'Аноним', 'Хочу получить консультацию', '2026-04-01'),
(1, NULL, 'Проблемы со стрессом', '2026-04-03');

INSERT INTO appointments
(client_id, psychologist_id, appointment_date, consultation_type)
VALUES
(1, 1, '2026-04-10', 'online'),
(2, 1, '2026-04-11', 'offline'),
(1, 2, '2026-04-15', 'online');

INSERT INTO psychologist_notes
(appointment_id, psychologist_id, note_text)
VALUES
(1, 1, 'Клиент испытывает тревожность'),
(2, 1, 'Рекомендованы дыхательные практики');

---------------------------------------------------
-- Запрос:
-- Самые востребованные психологи за квартал
---------------------------------------------------

SELECT
    p.full_name AS "Психолог",
    COUNT(a.appointment_id) AS "Количество консультаций"
FROM psychologists p
JOIN appointments a
    ON p.psychologist_id = a.psychologist_id
WHERE QUARTER(a.appointment_date) = QUARTER(CURDATE())
AND YEAR(a.appointment_date) = YEAR(CURDATE())
GROUP BY p.psychologist_id, p.full_name
ORDER BY COUNT(a.appointment_id) DESC;