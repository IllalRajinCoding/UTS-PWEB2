CREATE DATABASE umkm;

USE umkm;

CREATE TABLE provinsi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(45) NOT NULL,
    ibukota VARCHAR(45) NOT NULL,
    latitude DOUBLE,
    longitude DOUBLE
);

CREATE TABLE kabkota (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    latitude DOUBLE,
    longitude DOUBLE,
    provinsi_id INT NOT NULL,
    FOREIGN KEY (provinsi_id) REFERENCES provinsi(id)
);

CREATE TABLE kategori_umkm (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(45) NOT NULL
);

CREATE TABLE pembina (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(50) NOT NULL,
    gender CHAR(1),
    tgl_lahir DATE,
    tmp_lahir VARCHAR(30),
    keahlian VARCHAR(200)
);

CREATE TABLE umkm (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    modal DOUBLE,
    pemilik VARCHAR(45) NOT NULL,
    alamat VARCHAR(100) NOT NULL,
    website VARCHAR(45),
    email VARCHAR(45),
    kabkota_id INT NOT NULL,
    rating INT,
    kategori_umkm_id INT NOT NULL,
    pembina_id INT,
    FOREIGN KEY (kabkota_id) REFERENCES kabkota(id),
    FOREIGN KEY (kategori_umkm_id) REFERENCES kategori_umkm(id),
    FOREIGN KEY (pembina_id) REFERENCES pembina(id)
);