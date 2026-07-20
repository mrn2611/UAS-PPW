-- =============================================
-- DATABASE BIOSKOP
-- Nama Database: bioskop
-- =============================================

-- Hapus database jika sudah ada
DROP DATABASE IF EXISTS bioskop;

-- Buat database baru
CREATE DATABASE bioskop;
USE bioskop;

-- =============================================
-- TABEL FILMS (Data Film)
-- =============================================
CREATE TABLE films (
    id INT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    durasi VARCHAR(50) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    poster VARCHAR(255) DEFAULT 'default.jpg',
    deskripsi TEXT,
    sutradara VARCHAR(200),
    pemain TEXT,
    tahun VARCHAR(4),
    rating DECIMAL(3,1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- TABEL JADWAL TAYANG
-- =============================================
CREATE TABLE jadwal_tayang (
    id INT PRIMARY KEY AUTO_INCREMENT,
    film_id INT,
    hari VARCHAR(50),
    jam VARCHAR(100),
    studio VARCHAR(50),
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE
);

-- =============================================
-- TABEL RESERVASI (Pemesanan Tiket)
-- =============================================
CREATE TABLE reservasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_reservasi VARCHAR(50) UNIQUE NOT NULL,
    nama_pemesan VARCHAR(200) NOT NULL,
    nomor_telepon VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    film_id INT,
    jadwal_id INT,
    jumlah_tiket INT NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    tanggal_reservasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_tayang(id) ON DELETE CASCADE
);

-- =============================================
-- DATA FILM (6 Film)
-- =============================================
INSERT INTO films (judul, genre, durasi, harga, poster, deskripsi, sutradara, pemain, tahun, rating) VALUES
('Avatar: The Way of Water', 'Sci-Fi', '192 menit', 50000, 'avatar.jpg', 
'Film sekuel dari Avatar yang menceritakan petualangan Jake Sully dan keluarganya di planet Pandora. Mereka harus menghadapi ancaman baru dari manusia yang kembali untuk mengklaim sumber daya alam Pandora.', 
'James Cameron', 'Sam Worthington, Zoe Saldana, Sigourney Weaver, Stephen Lang', '2022', 8.5),

('The Batman', 'Action', '176 menit', 45000, 'batman.jpg',
'Film superhero gelap yang mengisahkan perjalanan Bruce Wayne menjadi Batman di tahun kedua karirnya. Ia harus menghadapi Riddler, seorang pembunuh berantai yang menargetkan koruptor di Gotham City.',
'Matt Reeves', 'Robert Pattinson, Zoë Kravitz, Paul Dano, Colin Farrell', '2022', 8.2),

('Dilan 1990', 'Romance', '120 menit', 40000, 'dilan.jpg',
'Kisah cinta Dilan dan Milea di masa SMA tahun 1990. Dilan, seorang siswa SMA yang terkenal dengan sikapnya yang cool dan geng motor, jatuh cinta pada Milea yang baru pindah ke Bandung.',
'Pidi Baiq', 'Iqbaal Ramadhan, Vanesha Prescilla, Bucek Depp', '2018', 7.8),

('Spider-Man: No Way Home', 'Action', '148 menit', 50000, 'spiderman.jpg',
'Peter Parker menghadapi konsekuensi dari identitasnya yang terbongkar. Ia meminta bantuan Doctor Strange untuk menyembunyikan identitasnya, namun mantra yang kacau membuka multiverse dan menghadirkan musuh-musuh dari dimensi lain.',
'Jon Watts', 'Tom Holland, Zendaya, Benedict Cumberbatch, Jacob Batalon', '2021', 8.7),

('Pengabdi Setan 2: Communion', 'Horror', '119 menit', 40000, 'pengabdi.jpg',
'Menyusul peristiwa mengerikan yang menimpa keluarga, Rini dan keluarganya pindah ke rumah susun. Namun, teror dari pengabdi setan terus mengintai dan mengancam keselamatan mereka.',
'Joko Anwar', 'Tara Basro, Bront Palarae, Endy Arfian, Nasar Annuz', '2022', 7.9),

('Top Gun: Maverick', 'Action', '131 menit', 50000, 'topgun.jpg',
'Pilot elite Pete "Maverick" Mitchell kembali melatih generasi baru pilot Top Gun untuk misi berbahaya. Ia harus menghadapi masa lalu dan mengatasi ketakutannya untuk menyelesaikan misi yang nyaris mustahil.',
'Joseph Kosinski', 'Tom Cruise, Miles Teller, Jennifer Connelly, Jon Hamm', '2022', 8.6);

-- =============================================
-- DATA JADWAL TAYANG
-- =============================================
INSERT INTO jadwal_tayang (film_id, hari, jam, studio) VALUES
(1, 'Senin - Jumat', '10:00, 13:00, 16:00, 19:00', 'Studio 1'),
(1, 'Sabtu - Minggu', '09:00, 12:00, 15:00, 18:00, 21:00', 'Studio 1'),
(2, 'Senin - Jumat', '11:00, 14:00, 17:00, 20:00', 'Studio 2'),
(2, 'Sabtu - Minggu', '10:00, 13:00, 16:00, 19:00, 22:00', 'Studio 2'),
(3, 'Senin - Jumat', '10:30, 13:30, 16:30, 19:30', 'Studio 3'),
(3, 'Sabtu - Minggu', '09:30, 12:30, 15:30, 18:30, 21:30', 'Studio 3'),
(4, 'Senin - Jumat', '11:30, 14:30, 17:30, 20:30', 'Studio 4'),
(4, 'Sabtu - Minggu', '10:30, 13:30, 16:30, 19:30, 22:30', 'Studio 4'),
(5, 'Senin - Jumat', '10:00, 13:00, 16:00, 19:00', 'Studio 5'),
(5, 'Sabtu - Minggu', '09:00, 12:00, 15:00, 18:00, 21:00', 'Studio 5'),
(6, 'Senin - Jumat', '12:00, 15:00, 18:00, 21:00', 'Studio 6'),
(6, 'Sabtu - Minggu', '11:00, 14:00, 17:00, 20:00, 23:00', 'Studio 6');