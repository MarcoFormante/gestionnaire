
    CREATE DATABASE  IF NOT EXISTS gestionnaire


    CREATE TABLE IF NOT EXISTS contact (
    id int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name varchar(50),
    email varchar(255),
    phone_number varchar(10)
    )
  

    INSERT INTO contact(name, email, phone_number) VALUES
    ('Mario Rossi', 'mario.rossi@example.com', '3201234567'),
    ('Giulia Bianchi', 'giulia.bianchi@example.com', '3897654321'),
    ('Luca Verdi', 'luca.verdi@example.com', '3339876543');


    ALTER TABLE contact MODIFY name varchar(30) NOT NULL