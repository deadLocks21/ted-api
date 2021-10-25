DROP DATABASE IF EXISTS timothe_ted;
CREATE DATABASE timothe_ted;
USE timothe_ted;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(251) NOT NULL,
    password VARCHAR(251) NOT NULL
);

INSERT INTO users(login, password) VALUES ('admin', '$2y$10$YIjXT10VDws48QFw4VqUYOpT5YZvjgCc1Zu65YNSXIdkOVj1FpEHO');

CREATE TABLE todolists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(251) NOT NULL,
    user INT NOT NULL,
    FOREIGN KEY (user)
        REFERENCES users (id)
        ON DELETE CASCADE
);

INSERT INTO todolists(name, user) VALUES ('My first TodoList', 1);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(251) NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    todolist INT NOT NULL,
    FOREIGN KEY (todolist)
        REFERENCES todolists (id) ON DELETE CASCADE
);

INSERT INTO tasks(task, todolist) VALUES ('My first task', 1);
INSERT INTO tasks(task, completed, todolist) VALUES ('My second completed task', TRUE, 1);
INSERT INTO tasks(task, todolist) VALUES ('My third task', 1);