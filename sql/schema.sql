-- Создание базы данных
CREATE DATABASE psychology_center;
USE psychology_center;


CREATE TABLE clients (
    client_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    registration_date DATE NOT NULL
);

-- Таблица психологов


CREATE TABLE psychologists (
    psychologist_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100)
);


-- Таблица методик


CREATE TABLE methods (
    method_id INT PRIMARY KEY AUTO_INCREMENT,
    method_name VARCHAR(100) NOT NULL
);


-- Связь психологов и методик


CREATE TABLE psychologist_methods (
    psychologist_id INT,
    method_id INT,
    PRIMARY KEY (psychologist_id, method_id),
    FOREIGN KEY (psychologist_id)
        REFERENCES psychologists(psychologist_id),
    FOREIGN KEY (method_id)
        REFERENCES methods(method_id)
);


-- Таблица заявок
-- anonymous_name  для анонимных заявок


CREATE TABLE requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NULL,
    anonymous_name VARCHAR(100),
    request_text TEXT,
    request_date DATE NOT NULL,
    FOREIGN KEY (client_id)
        REFERENCES clients(client_id)
);


-- Таблица записей на консультацию


CREATE TABLE appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    psychologist_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    consultation_type ENUM('offline', 'online') NOT NULL,
    
    FOREIGN KEY (client_id)
        REFERENCES clients(client_id),
        
    FOREIGN KEY (psychologist_id)
        REFERENCES psychologists(psychologist_id)
);


-- Таблица заметок психолога
-- Доступ только владельцу записи


CREATE TABLE psychologist_notes (
    note_id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    psychologist_id INT NOT NULL,
    note_text TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (appointment_id)
        REFERENCES appointments(appointment_id),
        
    FOREIGN KEY (psychologist_id)
        REFERENCES psychologists(psychologist_id)
);


-- Триггер: Клиент не может записаться к одному психологу чаще 1 раза в 3 дня


DELIMITER //

CREATE TRIGGER check_appointment_interval
BEFORE INSERT ON appointments
FOR EACH ROW
BEGIN
    DECLARE appointment_count INT;

    SELECT COUNT(*)
    INTO appointment_count
    FROM appointments
    WHERE client_id = NEW.client_id
      AND psychologist_id = NEW.psychologist_id
      AND ABS(DATEDIFF(appointment_date, NEW.appointment_date)) < 3;

    IF appointment_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 
        'Запись к этому психологу возможна не чаще одного раза в 3 дня';
    END IF;
END //

DELIMITER ;


-- Пример данных

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


