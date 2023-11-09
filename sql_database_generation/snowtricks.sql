DROP SCHEMA IF EXISTS snowTriks;
CREATE SCHEMA snowTriks;

USE snowTriks;

-- Création de la table 'user'
CREATE TABLE `user` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    valide BOOLEAN NOT NULL DEFAULT FALSE
);

-- Création de la table 'group'
CREATE TABLE `groupe` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Création de la table 'trick'
CREATE TABLE `trick` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    author INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    groupe_id INT NOT NULL,
    slug VARCHAR(255) NOT NULL,
    FOREIGN KEY (author) REFERENCES user(id),
    FOREIGN KEY (groupe_id) REFERENCES groupe(id),
    UNIQUE (name)
);

-- Création de la table 'comment'
CREATE TABLE `comment` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    content TEXT,
    author INT,
    trick_id INT,
    FOREIGN KEY (author) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (trick_id) REFERENCES trick(id) ON DELETE CASCADE
);

-- Création de la table 'file'
CREATE TABLE `trick_file` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    path TEXT NOT NULL,
    type_file VARCHAR(255) NOT NULL,
    trick_id INT NOT NULL,
    featured_image BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (trick_id) REFERENCES trick(id) ON DELETE CASCADE
);

CREATE TABLE `user_file` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    path VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
