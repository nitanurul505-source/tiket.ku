-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Jul 2026 pada 12.44
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
-- Database: `tiketku`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `event_date` datetime NOT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `location_detail` varchar(300) DEFAULT NULL,
  `capacity` int(11) DEFAULT 0,
  `price` decimal(10,2) DEFAULT NULL,
  `organizer_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `name`, `event_date`, `venue`, `description`, `image_url`, `location_detail`, `capacity`, `price`, `organizer_id`, `created_at`) VALUES
(1, 'Tech Conference 2026', '2026-08-15 09:00:00', 'Auditorium Utama', 'Sebuah acara seminar nasional yang menghadirkan para pakar teknologi informasi dan kecerdasan buatan dari seluruh Indonesia. Diskusi mendalam tentang masa depan AI, machine learning, dan dampaknya terhadap industri.', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80', 'Auditorium Utama, Gedung A Lt. 2, Universitas Contoh, Jl. Pendidikan No. 1', 100, 50000.00, 1, '2026-04-13 09:23:45'),
(2, 'Pentas Seni Tahunan', '2026-10-20 18:00:00', 'Lapangan Terbuka', 'Pentas seni tahunan yang menampilkan berbagai pertunjukan seni budaya mahasiswa, mulai dari tari tradisional, musik kontemporer, teater, hingga pameran karya seni rupa.', 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&q=80', 'Lapangan Terbuka Kampus, Area Parkir Barat, Universitas Contoh', 200, 35000.00, 2, '2026-04-13 09:23:45'),
(3, 'Workshop UI/UX Design', '2026-07-05 10:00:00', 'Lab Komputer Lt. 3', 'Workshop intensif selama satu hari penuh tentang desain antarmuka dan pengalaman pengguna. Peserta akan belajar langsung menggunakan Figma, prinsip desain modern, dan studi kasus nyata.', 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=800&q=80', 'Lab Komputer Lt. 3, Gedung Teknik, Universitas Contoh', 40, 75000.00, 1, '2026-04-13 09:23:45'),
(4, 'Malam Keakraban Mahasiswa', '2026-09-01 19:00:00', 'Gedung Serbaguna', 'Malam keakraban mahasiswa baru bersama seluruh civitas akademika. Acara penuh kegembiraan dengan games, penampilan bakat, dan makan malam bersama.', 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&q=80', 'Gedung Serbaguna Lt. 1, Universitas Contoh', 300, 25000.00, 3, '2026-04-13 09:23:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `organizers`
--

CREATE TABLE `organizers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `organizers`
--

INSERT INTO `organizers` (`id`, `name`, `contact_email`) VALUES
(1, 'Himpunan Mahasiswa Ilmu Komputer', 'himakom@univ.edu'),
(2, 'Unit Kegiatan Mahasiswa Seni', 'ukm_seni@univ.edu'),
(3, 'BEM Fakultas Teknik', 'bemft@univ.edu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `method` enum('transfer','cash','card') DEFAULT 'transfer',
  `status` enum('unpaid','paid','failed') DEFAULT 'unpaid',
  `ticket_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `amount`, `payment_date`, `method`, `status`, `ticket_id`) VALUES
(11, 35000.00, '2026-04-15 15:28:43', 'transfer', 'paid', 11),
(12, 75000.00, '2026-04-15 15:30:47', 'card', 'failed', 12),
(13, 300000.00, '2026-04-16 13:39:19', 'transfer', 'paid', 13),
(15, 210000.00, '2026-04-16 14:59:02', 'transfer', 'paid', 15),
(16, 100000.00, '2026-04-16 15:16:04', 'cash', 'paid', 16),
(17, 150000.00, '2026-04-16 18:43:28', 'cash', 'failed', 17),
(18, 75000.00, '2026-04-19 05:37:54', 'transfer', 'paid', 18),
(19, 75000.00, '2026-04-19 21:38:26', 'card', 'unpaid', 19),
(20, 50000.00, '2026-07-23 17:39:36', 'transfer', 'paid', 20);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_code` varchar(50) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `qty` int(11) DEFAULT 1,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_code`, `purchase_date`, `status`, `qty`, `event_id`, `user_id`) VALUES
(11, 'TIX-20260415-DC12', '2026-04-15 15:28:43', 'confirmed', 1, 2, 1),
(12, 'TIX-20260415-C149', '2026-04-15 15:30:47', 'cancelled', 1, 3, 1),
(13, 'TIX-20260416-2D24', '2026-04-16 13:39:19', 'confirmed', 4, 3, 1),
(15, 'TIX-20260416-559C', '2026-04-16 14:59:02', 'confirmed', 6, 2, 1),
(16, 'TIX-20260416-BA0E', '2026-04-16 15:16:04', 'confirmed', 2, 1, 1),
(17, 'TIX-20260416-3A2B', '2026-04-16 18:43:28', 'cancelled', 3, 1, 1),
(18, 'TIX-20260419-8BB6', '2026-04-19 05:37:54', 'confirmed', 1, 3, 3),
(19, 'TIX-20260419-E0BF', '2026-04-19 21:38:26', 'cancelled', 1, 3, 1),
(20, 'TIX-20260723-3720', '2026-07-23 17:39:35', 'confirmed', 1, 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quota` int(11) DEFAULT 0,
  `sold` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ticket_categories`
--

INSERT INTO `ticket_categories` (`id`, `event_id`, `name`, `price`, `quota`, `sold`) VALUES
(1, 1, 'Early Bird', 35000.00, 30, 30),
(2, 1, 'Regular', 50000.00, 50, 20),
(3, 1, 'VIP', 100000.00, 20, 5),
(4, 2, 'Umum', 35000.00, 150, 80),
(5, 2, 'Tribun', 50000.00, 30, 10),
(6, 2, 'VVIP', 75000.00, 20, 3),
(7, 3, 'Peserta', 75000.00, 30, 15),
(8, 3, 'Peserta + Kit', 95000.00, 10, 4),
(9, 4, 'Reguler', 25000.00, 200, 50),
(10, 4, 'All-in', 40000.00, 50, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`) VALUES
(1, 'Budi Santoso', 'budi@example.com', '081234567890', 'budi123'),
(2, 'Siti Aminah', 'siti@example.com', '089876543210', 'siti123'),
(3, 'Rizky Pratama', 'rizky@example.com', '082345678901', 'rizky123'),
(5, 'Administrator', 'admin@tiketku.com', '000000000000', 'admin123');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indeks untuk tabel `organizers`
--
ALTER TABLE `organizers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_id` (`ticket_id`);

--
-- Indeks untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_code` (`ticket_code`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `organizers`
--
ALTER TABLE `organizers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD CONSTRAINT `ticket_categories_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
