-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Nov 2025 pada 14.37
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sibio_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `habitat`
--

CREATE TABLE `habitat` (
  `id` int(11) NOT NULL,
  `nama_habitat` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `iklim` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `habitat`
--

INSERT INTO `habitat` (`id`, `nama_habitat`, `lokasi`, `iklim`, `deskripsi`) VALUES
(1, 'Hutan Hujan', 'Kalimantan', 'Tropis', 'Hutan tropis dengan keanekaragaman tinggi'),
(2, 'Padang Rumput', 'Sumatera', 'Tropis', 'Area terbuka');

-- --------------------------------------------------------

--
-- Struktur dari tabel `observasi`
--

CREATE TABLE `observasi` (
  `id` int(11) NOT NULL,
  `spesies_id` int(11) DEFAULT NULL,
  `peneliti_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `observasi`
--

INSERT INTO `observasi` (`id`, `spesies_id`, `peneliti_id`, `tanggal`, `catatan`) VALUES
(1, 1, 1, '2025-10-01', 'Terlihat jejak kaki besar dan bulu'),
(2, 2, 2, '2025-09-15', 'Ditemukan 1 individu di pinggir hutan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peneliti`
--

CREATE TABLE `peneliti` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `institusi` varchar(100) DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peneliti`
--

INSERT INTO `peneliti` (`id`, `nama`, `institusi`, `kontak`) VALUES
(1, 'Dr. Ani', 'Universitas X', 'ani@univx.edu'),
(2, 'Budi', 'Balai Konservasi', '081234567890');

-- --------------------------------------------------------

--
-- Struktur dari tabel `spesies`
--

CREATE TABLE `spesies` (
  `id` int(11) NOT NULL,
  `nama_latin` varchar(100) DEFAULT NULL,
  `nama_umum` varchar(100) DEFAULT NULL,
  `kingdom` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `habitat_id` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `spesies`
--

INSERT INTO `spesies` (`id`, `nama_latin`, `nama_umum`, `kingdom`, `deskripsi`, `habitat_id`, `foto`) VALUES
(1, 'Panthera tigris', 'Harimau', 'Animalia', 'Kucing besar pemangsa', 1, NULL),
(2, 'Rafflesia arnoldii', 'Bunga Bangkai', 'Plantae', 'Bunga terbesar di dunia', 1, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(4, 'admin', 'admin123', 'admin'),
(5, 'tes1', '123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `habitat`
--
ALTER TABLE `habitat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `observasi`
--
ALTER TABLE `observasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spesies_id` (`spesies_id`),
  ADD KEY `peneliti_id` (`peneliti_id`);

--
-- Indeks untuk tabel `peneliti`
--
ALTER TABLE `peneliti`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `spesies`
--
ALTER TABLE `spesies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitat_id` (`habitat_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `habitat`
--
ALTER TABLE `habitat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `observasi`
--
ALTER TABLE `observasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `peneliti`
--
ALTER TABLE `peneliti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `spesies`
--
ALTER TABLE `spesies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `observasi`
--
ALTER TABLE `observasi`
  ADD CONSTRAINT `observasi_ibfk_1` FOREIGN KEY (`spesies_id`) REFERENCES `spesies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `observasi_ibfk_2` FOREIGN KEY (`peneliti_id`) REFERENCES `peneliti` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `spesies`
--
ALTER TABLE `spesies`
  ADD CONSTRAINT `spesies_ibfk_1` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
