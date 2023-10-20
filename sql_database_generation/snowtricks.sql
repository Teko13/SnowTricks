DROP SCHEMA IF EXISTS SnowTriks;
CREATE SCHEMA SnowTriks;

USE SnowTriks;

-- Création de la table 'user'
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    valide BOOLEAN NOT NULL DEFAULT FALSE
);

-- Création de la table 'group'
CREATE TABLE groupes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Création de la table 'trick'
CREATE TABLE trick (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    author INT NOT NULL,
    group_id INT NOT NULL,
    FOREIGN KEY (author) REFERENCES user(id),
    FOREIGN KEY (group_id) REFERENCES groupes(id),
    UNIQUE (name)
);

-- Création de la table 'comment'
CREATE TABLE comment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    content TEXT,
    author INT,
    trick_id INT,
    FOREIGN KEY (author) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (trick_id) REFERENCES trick(id) ON DELETE CASCADE
);

-- Création de la table 'file'
CREATE TABLE trick_file (
    id INT PRIMARY KEY AUTO_INCREMENT,
    path VARCHAR(255) NOT NULL,
    type_file VARCHAR(255) NOT NULL,
    trick_id INT NOT NULL,
    FOREIGN KEY (trick_id) REFERENCES trick(id) ON DELETE CASCADE
);

CREATE TABLE user_file (
    id INT PRIMARY KEY AUTO_INCREMENT,
    path VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);




-- Insertion des données

-- Insérer des groupes
INSERT INTO groupes (name)
VALUES 
    ('Groupe 1'),
    ('Groupe 2'),
    ('Groupe 3'),
    ('Groupe 4'),
    ('Groupe 5');



-- Insérer un utilisateur
INSERT INTO user (user_name, email, password, valide)
VALUES ('nom_utilisateur', 'email@example.com', 'mot_de_passe', TRUE);

-- Récupérer l'ID de l'utilisateur que vous venez d'insérer
SET @user_id = LAST_INSERT_ID();

-- Insérer 10 tricks
INSERT INTO trick (name, description, author, group_id)
VALUES 
    ('Trick 1', 'Description du Trick 1', @user_id, 1),
    ('Trick 2', 'Description du Trick 2', @user_id, 1),
    ('Trick 3', 'Description du Trick 3', @user_id, 2),
    ('Trick 4', 'Description du Trick 4', @user_id, 2),
    ('Trick 5', 'Description du Trick 5', @user_id, 3),
    ('Trick 6', 'Description du Trick 6', @user_id, 3),
    ('Trick 7', 'Description du Trick 7', @user_id, 4),
    ('Trick 8', 'Description du Trick 8', @user_id, 4),
    ('Trick 9', 'Description du Trick 9', @user_id, 5),
    ('Trick 10', 'Description du Trick 10', @user_id, 5);

-- Insérer des images pour les tricks
INSERT INTO trick_file (path, type_file, trick_id)
VALUES 
    ('chemin_image1.jpg', 'image', 1),
    ('chemin_image2.jpg', 'image', 2),
    ('chemin_image3.jpg', 'image', 3),
    ('chemin_image4.jpg', 'image', 4),
    ('chemin_image5.jpg', 'image', 5),
    ('chemin_image6.jpg', 'image', 6),
    ('chemin_image7.jpg', 'image', 7),
    ('chemin_image8.jpg', 'image', 8),
    ('chemin_image9.jpg', 'image', 9),
    ('chemin_image10.jpg', 'image', 10);

-- Insérer des images pour l'utilisateur
INSERT INTO user_file (path, user_id)
VALUES 
    ('chemin_image_utilisateur.jpg', @user_id);
