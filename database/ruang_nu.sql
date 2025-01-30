-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ruang_nu
DROP DATABASE IF EXISTS `ruang_nu`;
CREATE DATABASE IF NOT EXISTS `ruang_nu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ruang_nu`;

-- Dumping structure for table ruang_nu.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nim` varchar(100) NOT NULL,
  `img_prfl` varchar(100) NOT NULL DEFAULT 'profile.jpg',
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','mahasiswa') DEFAULT 'mahasiswa',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ruang_nu.users: ~3 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `nim`, `img_prfl`, `password`, `role`, `created_at`, `updated_at`) VALUES
	(1, 'ilham', 'ilhamkurniawanjateng@gmail.com', '231111016', 'profile.jpg', '$2y$10$kE.xSJQuJE6l7JL4gAcfFOfTow.jImnHArNycGrU.RLjnO2mi1Gn6', 'superadmin', '2025-01-25 07:11:23', '2025-01-25 14:14:50'),
	(3, 'mahasiswa', 'mahasiswa@gmail.com', '2311313', 'profile.jpg', '$2y$10$cnw47ol5phSdEWMS4hr9xee9bMSkNi62YQekYOXS4DAPmTqWNieIG', 'mahasiswa', '2025-01-25 20:48:24', '2025-01-25 20:57:51'),
	(4, 'admin', 'admin@gmail.com', '2333333', 'profile.jpg', '$2y$10$tOqxwu3gHdRrPCrLBKWRh.BZM2.zIHl8c8dnEVdlT12ckPe9Zt9PK', 'admin', '2025-01-26 08:03:30', '2025-01-26 08:03:30');

-- Dumping structure for table ruang_nu.rooms
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) NOT NULL,
  `capacity` int NOT NULL,
  `img_room` varchar(100) NOT NULL DEFAULT 'room.jpg',
  `is_available` enum('available','occupied','maintenance') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `img_room` (`img_room`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ruang_nu.rooms: ~9 rows (approximately)
DELETE FROM `rooms`;
INSERT INTO `rooms` (`id`, `name`, `location`, `capacity`, `img_room`, `is_available`, `created_at`, `updated_at`) VALUES
	(1, 'Ruang Kelas', 'Gedung 1 lantai 3', 35, 'ruang_kelas.jpg', 'available', '2025-01-25 21:12:54', '2025-01-25 21:17:39'),
	(2, 'Ruang Sidang Utama', 'Gedung Rektorat Lantai 2', 100, 'ruang_sidang.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06'),
	(3, 'Ruang Seminar A', 'Gedung Kuliah Barat Lantai 3', 50, 'seminar_a.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06'),
	(4, 'Laboratorium Komputer', 'Gedung Teknik Informatika', 40, 'lab_komputer.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06'),
	(5, 'Aula Serbaguna', 'Gedung Pusat Lantai 1', 200, 'aula_serbaguna.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06'),
	(6, 'Ruang Rapat Senat', 'Gedung Rektorat Lantai 3', 30, 'ruang_rapat_senat.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06'),
	(7, 'Studio Multimedia', 'Gedung Komunikasi Lantai 2', 25, 'studio_multimedia.jpg', 'available', '2025-01-25 23:05:06', '2025-01-26 08:04:28'),
	(8, 'Ruang Workshop', 'Gedung Teknik Lantai 1', 35, 'workshop_room.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06'),
	(9, 'Ruang Diskusi', 'Perpustakaan Lantai 2', 20, 'diskusi_room.jpg', 'available', '2025-01-25 23:05:06', '2025-01-25 23:05:06');

-- Dumping structure for table ruang_nu.activity_logs
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `activity` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ruang_nu.activity_logs: ~0 rows (approximately)
DELETE FROM `activity_logs`;

-- Dumping structure for table ruang_nu.bookings
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `purpose` text NOT NULL,
  `agency` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ruang_nu.bookings: ~6 rows (approximately)
DELETE FROM `bookings`;
INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `start_time`, `end_time`, `status`, `purpose`, `agency`, `created_at`, `updated_at`) VALUES
	(13, 3, 1, '2024-02-15 09:00:00', '2024-02-15 11:00:00', 'completed', 'Rapat koordinasi himpunan', 'Himpunan Mahasiswa Teknik Informatika', '2025-01-25 23:07:15', '2025-01-26 08:31:59'),
	(14, 3, 3, '2024-02-20 14:00:00', '2024-02-20 16:00:00', 'completed', 'Seminar kewirausahaan', 'UKM Entrepreneur', '2025-01-25 23:07:15', '2025-01-26 08:02:37'),
	(15, 3, 2, '2024-03-01 10:00:00', '2024-03-01 12:00:00', 'rejected', 'Workshop pengembangan diri', 'Bimbingan Konseling Mahasiswa', '2025-01-25 23:07:15', '2025-01-25 23:07:15'),
	(16, 3, 4, '2024-03-10 13:00:00', '2024-03-10 15:00:00', 'completed', 'Pelatihan kepemimpinan', 'Badan Eksekutif Mahasiswa', '2025-01-25 23:07:15', '2025-01-25 23:07:15'),
	(17, 3, 1, '2025-01-27 08:00:00', '2025-01-27 16:00:00', 'approved', 'rapat himatika', 'hmp informatika', '2025-01-26 08:28:00', '2025-01-26 08:28:28'),
	(18, 3, 1, '2025-01-26 08:00:00', '2025-01-26 16:00:00', 'approved', 'testing', 'testing', '2025-01-26 08:29:43', '2025-01-26 08:30:09');

-- Dumping structure for table ruang_nu.booking_logs
DROP TABLE IF EXISTS `booking_logs`;
CREATE TABLE IF NOT EXISTS `booking_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `action` enum('created','updated','approved','rejected','completed','canceled') NOT NULL,
  `log_message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `booking_logs_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ruang_nu.booking_logs: ~8 rows (approximately)
DELETE FROM `booking_logs`;
INSERT INTO `booking_logs` (`id`, `booking_id`, `action`, `log_message`, `created_at`) VALUES
	(1, 14, 'updated', 'Status berubah menjadi completed', '2025-01-26 08:02:37'),
	(2, 17, 'created', 'Booking baru dibuat', '2025-01-26 08:28:01'),
	(3, 13, 'approved', 'Status berubah menjadi approved', '2025-01-26 08:28:26'),
	(4, 17, 'approved', 'Status berubah menjadi approved', '2025-01-26 08:28:28'),
	(5, 18, 'created', 'Booking baru dibuat', '2025-01-26 08:29:43'),
	(6, 18, 'rejected', 'Status berubah menjadi rejected', '2025-01-26 08:30:01'),
	(7, 18, 'approved', 'Status berubah menjadi approved', '2025-01-26 08:30:09'),
	(8, 13, 'updated', 'Status berubah menjadi completed', '2025-01-26 08:31:59');


-- Dumping structure for table ruang_nu.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ruang_nu.settings: ~0 rows (approximately)
DELETE FROM `settings`;


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
