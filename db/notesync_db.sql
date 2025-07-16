-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 03:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notesync_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `title`, `description`, `price`, `file_path`, `uploaded_by`, `created_at`) VALUES
(3, 'csc510', '', 8.00, '../uploads/687284565ea2e_CSC510 - Lecture 8 (1).pdf', 4, '2025-07-12 15:50:46'),
(4, 'CSC510 - Lecture 6', '', 3.00, '../uploads/687289fe852c1_CSC510 - Lecture 6.pdf', 4, '2025-07-12 16:14:54'),
(5, 'CSC510', 'Lecture 8', 2.00, '../uploads/68729f153ea6b_CSC510 - Lecture 8 (1).pdf', 4, '2025-07-12 17:44:53'),
(6, 'Set & Operation', 'example', 3.00, '../uploads/68729fb2b09d4_CSC510 - Set and Set Operations_ Example Question.pdf', 4, '2025-07-12 17:47:30'),
(7, 'CSC510', 'Lecture 10', 2.50, '../uploads/6872a0d140bd9_CSC510 - Lecture 10.pptx', 4, '2025-07-12 17:52:17'),
(8, 'csc510', 'upload many times', 1.00, '../uploads/6872a19aea1ae_CSC510 - Lecture 10 - DFSA (1).ppt', 4, '2025-07-12 17:55:38'),
(9, 'Buku baru', '', 12.00, '../uploads/6872b51231d6b_Assessment 3 - Practical Assignment.pdf', 1, '2025-07-12 19:18:42'),
(10, 'Set & Function', 'pptx', 12.00, '../uploads/6872c445e688e_687283b090819_CSC510 - Lecture 8 - SET AND FUNCTIONS (1).pptx', 1, '2025-07-12 20:23:33'),
(11, 'Note of Responsive', 'Study for your test. Happy reading', 18.00, '../uploads/68743716d1e4c_CSC578 - Topic 8 - Introduction to Server.pptx', 4, '2025-07-13 22:45:42'),
(12, 'Future Trends', 'Web Development', 20.00, '../uploads/6874373ad5ac1_CSC578 - Topic 12 - Future Trends in Web Development.pptx', 4, '2025-07-13 22:46:18'),
(13, 'Satu', 'nak tengok id je', 1.00, '../uploads/68743fc41f5b6_Screenshot 2025-07-05 130444.png', 10, '2025-07-13 23:22:44'),
(14, 'Digital Computed Radiography', 'Radiography', 5.00, '../uploads/6874f9a8461db_Digital Computed Radiography.pdf', 22, '2025-07-14 12:35:52'),
(15, 'The Digestive System', 'Part 1', 6.50, '../uploads/6874f9e56783c_Unit 8.1 The Digestive System Part 1.pdf', 22, '2025-07-14 12:36:53'),
(16, 'The Digestive System', 'Part 2', 6.50, '../uploads/6874fa0b9db05_Unit 8.1 The Digestive System Part 2.pdf', 22, '2025-07-14 12:37:31'),
(17, 'The Digestive System', 'Part 3', 6.50, '../uploads/6874fa933ee61_Unit 8.1 The Digestive System Part 3.pdf', 22, '2025-07-14 12:39:47'),
(18, 'Life Insurance', 'Part 1', 30.00, '../uploads/6874fb6ba6e1e_3.1 Life Insurance (Part 1) (1).pdf', 18, '2025-07-14 12:43:23'),
(19, 'Life Insurance', 'Part 2', 16.00, '../uploads/6875024120afb_3.2 Life Insurance (Part 2) (1).pdf', 18, '2025-07-14 13:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `note_id`, `purchase_date`) VALUES
(9, 6, 10, '2025-07-13 17:53:40'),
(10, 2, 3, '2025-07-13 19:15:31'),
(11, 2, 10, '2025-07-13 22:25:39'),
(12, 11, 12, '2025-07-14 00:04:20'),
(13, 11, 9, '2025-07-14 00:04:46'),
(14, 12, 13, '2025-07-14 00:07:50'),
(15, 17, 13, '2025-07-14 08:14:09'),
(16, 17, 12, '2025-07-14 08:14:32'),
(17, 2, 4, '2025-07-14 10:42:39'),
(18, 2, 11, '2025-07-14 10:52:37'),
(19, 18, 3, '2025-07-14 11:58:42'),
(20, 19, 7, '2025-07-14 11:59:27'),
(21, 19, 8, '2025-07-14 12:00:16'),
(22, 20, 13, '2025-07-14 12:04:22'),
(23, 18, 4, '2025-07-14 12:04:57'),
(24, 21, 3, '2025-07-14 12:07:15'),
(25, 23, 3, '2025-07-14 12:24:12'),
(26, 27, 18, '2025-07-14 12:56:14'),
(27, 28, 3, '2025-07-16 01:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `google_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `user_role`, `password`, `role`, `google_id`, `created_at`, `last_login`, `profile_picture`) VALUES
(1, 'Shaf', 'akmalshafiqah@gmail.com', '', '$2y$10$ebjbDgDwS7Zgodsn6X8AT.h0vxmREPJgjAhNTX/WMMWYziIbfiTAS', 'admin', NULL, '2025-07-10 09:00:17', '2025-07-12 08:03:30', NULL),
(2, 'Fiffy', 'fiffy@gmail.com', '', '$2y$10$CslnGQYN/KpolXPJK.HMeu.TLkB9wysUc45UX2vSqt6GKRayOQ4R6', 'user', NULL, '2025-07-10 09:17:09', '2025-07-12 08:03:30', '/NOTESYNC/uploads/profile_pictures/6872e426e5b21.jpg'),
(4, 'NURUL AKMAL SHAFIQAH', 'akmalfiqah24@gmail.com', '', '$2y$10$JHncBVBFRjvt2nf2OvvlvuZPtrWRn98sdemI/HiFbI4/vE0DuwE22', 'admin', '113330920879339386070', '2025-07-12 12:23:21', '2025-07-14 12:01:47', '/NOTESYNC/uploads/profile_pictures/6874fc40a61fb.jpg'),
(5, 'Qurratu', 'qur@gmail.com', '', '$2y$10$1vZ//D7FuNpIREXG2J288u2hM6Z6/9PFacnlYV2Pyt16QCpSFJ1DW', 'admin', NULL, '2025-07-12 21:21:17', '2025-07-12 21:21:17', NULL),
(6, 'Aleesya', 'aleesya@gmail.com', '', '$2y$10$Y5ZB3Zzy3nXZnc0EJJ97euCU5omU.I2aOWxdRtqOJVVdgwE4KR/cC', 'user', NULL, '2025-07-12 21:21:47', '2025-07-12 21:21:47', NULL),
(7, 'Izatul', 'izatul@gmail.com', '', '$2y$10$OjrB17K924hrxMdgBxJ5ZuVCOF8rLAwnszQJ5yym8jRPGcf/evmnW', 'admin', NULL, '2025-07-12 21:22:25', '2025-07-12 21:22:25', NULL),
(8, 'Nadhirah', 'nadhirah@gmail.com', '', '$2y$10$wvAMkn333t0XoWdldjSWO.CbsridVgecYrxI6cKVRiL22UjdOVTtS', 'admin', NULL, '2025-07-12 21:22:56', '2025-07-12 21:22:56', NULL),
(9, 'NURUL AKMAL SHAFIQAH MOHD ZAIDI', '2023637684@student.uitm.edu.my', '', '$2y$10$eyfr9Oz2O9ViWV.w2VKyk.AgPbj/.g7LApjCD79dLDbB7hj2U2.w.', 'user', '114532519296436769210', '2025-07-13 17:41:37', '2025-07-14 12:18:57', 'https://lh3.googleusercontent.com/a/ACg8ocJ863v_mmH9xKGtYMyokTZor4ruA4Z-vpbk9PB7PoW2qVs5WA=s96-c'),
(10, 'lalalala', 'tryjedulu@gmail.com', '', '$2y$10$oau0u3vwzwOtB88myDfDV.jFg81gyjphvw8QeMJbN6L0AsiwlJZzC', 'admin', NULL, '2025-07-13 23:20:16', '2025-07-13 23:20:16', NULL),
(11, 'yaya', 'yaya@gmail.com', '', '$2y$10$SCqH03Ln3eny.puCmC6.TOF6WN6xg3xxqQslHUZ1e9kELuRfTntMS', 'user', NULL, '2025-07-14 00:03:58', '2025-07-14 00:03:58', NULL),
(12, 'Zedz', 'dgitaz@gmail.com', '', '$2y$10$B4m1ioCF/NbNMCC5t.XXHuI9.KXVlNfGckAO1DFfBEtNfJfRYYNfi', 'user', NULL, '2025-07-14 00:05:54', '2025-07-14 12:14:16', '/NOTESYNC/uploads/profile_pictures/profile_6874fced735c24.19818657.jpg'),
(13, 'alishamz', 'sweetyaya13@gmail.com', '', '$2y$10$vx28Yz8aJ03ogjtwIazT..a3DoSAR9FDdG4puwfvFD1qydDoEuRRW', 'user', NULL, '2025-07-14 00:43:12', '2025-07-16 01:05:44', '/NOTESYNC/uploads/profile_pictures/profile_6874fe7d7658b1.58734263.jpg'),
(14, 'Syahmized', 'azimsyahmi285@gmail.com', '', '$2y$10$aaC7qVEzbqZhDgP3oXKbaOsuxpCi9H41KRTLaE3k2eGnZx7S.Ef5K', 'user', NULL, '2025-07-14 01:46:50', '2025-07-14 01:46:50', '/NOTESYNC/uploads/profile_pictures/profile_6874fcccab4ea3.46239263.jpg'),
(15, 'Naza Jusoh', 'nasdya13@gmail.com', '', '$2y$10$qRfW8r3f2idzTV6UJdRsfuNBLQF9kqJG6mC3MZbW5UBk9rENXuU9S', 'user', NULL, '2025-07-14 02:25:12', '2025-07-14 02:25:12', '/NOTESYNC/uploads/profile_pictures/profile_6874fe6eece7a1.05941247.jpg'),
(16, 'HunterX', 'obbyzaidi@gmail.com', '', '$2y$10$246re7ySQoTpKHaekH7n6Oxx.NGpEM8cjzoydevAHGtduzgu3S/YK', 'user', NULL, '2025-07-14 08:10:05', '2025-07-14 08:10:05', '/NOTESYNC/uploads/profile_pictures/profile_6874fc98083947.05050529.jpg'),
(17, 'Hunter', 'obyone160911@gmail.com', '', '$2y$10$5wp3pW6gJ2KHm8dtYE5ReO2Vr84klXVKob8MC30MhegIWmKoCQFUe', 'user', NULL, '2025-07-14 08:11:59', '2025-07-14 08:11:59', NULL),
(18, 'qaqa', 'syaf@gmail.com', '', '$2y$10$K0ypZtihl/rdjewoYweGbed11wfIYXbp8bDnBql0gD.P8ql/tK/S.', 'admin', NULL, '2025-07-14 11:56:27', '2025-07-14 11:56:27', '/NOTESYNC/uploads/profile_pictures/6874fcb375d15.jpg'),
(19, 'NaZa Channel', 'nazababies@gmail.com', '', '$2y$10$TsRc/E1h/hxSfU9gWIj2XOki5Q49O0x.DRV.ccI5Sv47croGUOcAO', 'user', '107255445841091783618', '2025-07-14 11:58:30', '2025-07-14 11:58:30', 'https://lh3.googleusercontent.com/a/ACg8ocIcCtXGZBLYfFV_voEny8kadPL1MvcmiZ4vdXa_kLxTjibNKw=s96-c'),
(20, 'Izatul28', 'izzatulfitrahhroslan@gmail.com', '', '$2y$10$.DNGGlVUfpLOSCCFy9peweLsYNd0vH3.LrgkSnCh3OhGHysIa3j3a', 'admin', NULL, '2025-07-14 12:01:57', '2025-07-14 12:01:57', '/NOTESYNC/uploads/profile_pictures/6874fdfc1c6c3.jpg'),
(21, 'loveloverobinchwan', '2023663362@student.uitm.edu.my', '', '$2y$10$B4uEvGQBIlOL/YDohC6jUOrTk84TgwtWLf90VzzXK59p3LXaPKwmu', 'user', NULL, '2025-07-14 12:06:05', '2025-07-14 12:06:05', NULL),
(22, 'DevSquad', 'devsquad@gmail.com', '', '$2y$10$hOES3QOrD.OAiX7Y2qvRwOTj1OWy5KzDt/yLxdxvWZVewIQSKxDWS', 'admin', NULL, '2025-07-14 12:17:29', '2025-07-14 12:17:29', '/NOTESYNC/uploads/profile_pictures/6874fdb366262.jpg'),
(23, 'SYAFIQAH NADHIRAH SHAWAL', '2023435818@student.uitm.edu.my', '', '$2y$10$CDVLcVymoxaYfrYsya8cFuqJmhhtyxThUOxKaB.x1rUH2cAk7Yp6K', 'user', '104737534716781948505', '2025-07-14 12:21:35', '2025-07-16 01:02:07', 'https://lh3.googleusercontent.com/a/ACg8ocK-ZF6bT4xJ11C267vzI63wStVauhDEj6IX9JsnVMWaShKqXQ=s96-c'),
(24, 'Notesync', 'werj@gmail.com', '', '$2y$10$zA5jKAB2e3WV3pGDoNTkIO1gySd.UWENQhtPP4.ExAS2dNTwEP2he', 'user', NULL, '2025-07-14 12:22:10', '2025-07-14 12:22:10', NULL),
(25, 'dee kay', 'qurratuadilah@gmail.com', '', '$2y$10$Ffh7rFKDMV9jiIu/cIoTbeiSa9JNowL7zF8/ti1o1D.5lYvgn1OBK', 'admin', '113720794960697228565', '2025-07-14 12:25:25', '2025-07-14 12:25:25', 'https://lh3.googleusercontent.com/a/ACg8ocKNdf-UbvX1_BT-PAGNckaZ-l7tgl_TvN0JGotNQZkjpqK3uk1D=s96-c'),
(26, 'Syafiqah Asri', 'syafiqahpd@gmail.com', '', '$2y$10$19LtA7pPfVhubAPvFed9xeq0R7CgpfLVezqjE8fAytv5Z02an7Pw.', 'user', '117718255741631823442', '2025-07-14 12:27:19', '2025-07-14 12:27:19', 'https://lh3.googleusercontent.com/a/ACg8ocKkQ_4i6HzGmldyoEa2xuBWgjfcf3_TC-rfxZBFUayFP0c2CA=s96-c'),
(27, 'Syafiqah Nadhirah', 'nadhirahsyafiqah71@gmail.com', '', '$2y$10$Sqfs67Dpreu/CBEy5jJ8WOLXMj/m23s7/7HqogWSmumfMiA2CDVNW', 'user', '118328385903197719970', '2025-07-14 12:28:02', '2025-07-16 01:39:47', 'https://lh3.googleusercontent.com/a/ACg8ocKMfCmjoyIEGQ8BI_qXUqMPNLvfxpKekXhH630VvMarLqY0tw=s96-c'),
(28, 'frhnn mlik', 'farhanamalik199@gmail.com', '', '$2y$10$XWoozsJgc22C1jbi/J/vnuP3xvrB5ZeaFTuzYqP5lTlPP/rk4n/ym', 'user', '102535995683206768439', '2025-07-16 01:10:53', '2025-07-16 01:10:53', 'https://lh3.googleusercontent.com/a/ACg8ocJigBv1d_vkaBy2C3Y76XujeJIJEbaYa3kDkISL4bwgRR-OTQ8=s96-c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`note_id`),
  ADD KEY `fk_note_id` (`note_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `fk_note_id` FOREIGN KEY (`note_id`) REFERENCES `notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
