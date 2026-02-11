CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('superadmin','admincfi','chefdps','equipier','visiteur') DEFAULT 'visiteur',
    email_validated BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE email_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiration DATETIME NOT NULL
);

CREATE TABLE equipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    telephone VARCHAR(30),
    latitude DOUBLE DEFAULT NULL,
    longitude DOUBLE DEFAULT NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE push_tokens (
    token VARCHAR(255) PRIMARY KEY,
    user_id INT DEFAULT NULL,
    equipe_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bilans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dps_id INT DEFAULT NULL,
    equipe_id INT DEFAULT NULL,
    symptomes TEXT,
    actions TEXT,
    constantes JSON,
    formulaire JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    date_start DATETIME,
    date_end DATETIME,
    lieu VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE inscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dps_id INT,
    user_id INT,
    equipe_id INT DEFAULT NULL,
    statut ENUM('inscrit','present','absent') DEFAULT 'inscrit',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    start DATETIME,
    end DATETIME,
    location VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE poles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    description TEXT
);

CREATE TABLE pole_docs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pole_id INT,
    filename VARCHAR(255),
    filepath VARCHAR(255),
    uploaded_by INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    role VARCHAR(150),
    telephone VARCHAR(50),
    email VARCHAR(200),
    notes TEXT
);
