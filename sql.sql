CREATE DATABASE IF NOT EXISTS biggym;
USE biggym;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    subtitulo VARCHAR(100),
    preco DECIMAL(10,2) NOT NULL,
    desconto INT DEFAULT 0,
    preco_antigo DECIMAL(10,2),
    imagem VARCHAR(255),
    disponivel TINYINT(1) DEFAULT 1
);
