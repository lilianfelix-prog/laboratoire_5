USE tch056_labo_forum;

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    date_soumission TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    message VARCHAR(50),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES usagers(user_id)
);


INSERT INTO messages (message, user_id) VALUES ('Bonjour', 1);
INSERT INTO messages (message, user_id) VALUES ('Salut', 2);
INSERT INTO messages (message, user_id) VALUES ('Allo', 2);
INSERT INTO messages (message, user_id) VALUES ('Bonsoir', 3);
INSERT INTO messages (message, user_id) VALUES ('Comment ça va', 4);
INSERT INTO messages (message, user_id) VALUES ('Bonne journée !', 5);
