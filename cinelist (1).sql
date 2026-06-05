-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2026 at 10:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinelist`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_05_31_053916_create_movies-table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cast` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'English',
  `release_year` year(4) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 0.0,
  `votes` int(11) NOT NULL DEFAULT 0,
  `poster` varchar(255) DEFAULT NULL,
  `backdrop` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `director`, `description`, `cast`, `genre`, `language`, `release_year`, `duration`, `rating`, `votes`, `poster`, `backdrop`, `is_featured`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'Call Me By Your Name', 'Luca Guadagnino', 'In the summer of 1983, seventeen-year-old Elio spends his days in his family\'s villa in Northern Italy.', 'Timothée Chalamet, Armie Hammer, Michael Stuhlbarg', 'Romance, Drama', 'English', '2017', '2h 12m', 8.2, 1245, 'posters/hBWEaet9FprGHPOfuoVS91jo6XEF2jxyRCcgLtXq.jpg', NULL, 1, 1, '2026-05-30 21:45:50', '2026-05-31 01:40:23'),
(4, 'hehe', 'haha', 'humsd', 'ako, vhinze, and kids', 'romance', 'English', '2024', '1', 10.0, 10, 'posters/efxZTc2besGxD2xjwm8ohYE0NQDKyuKnfTCx5OOY.png', NULL, 0, 2, '2026-05-31 00:04:09', '2026-05-31 00:04:09'),
(5, 'wandaVision', 'marvel', 'asasasa', 'wanda. vision', 'scific', 'English', '2020', '2', 10.0, 10, 'posters/aIFcTnLOaVuXkPgRosuGfU5NqIbaGNq1FiCMW6f7.jpg', NULL, 0, 3, '2026-05-31 00:12:59', '2026-05-31 00:12:59'),
(6, 'about time', 'uhm', 'adadadadadadaddadadd ajahajahjaha, aagshash hahsghags', 'shaja, shahsh, ajshjas,', 'romance', 'English', '2015', '1', 10.0, 1, 'posters/NL8ShQLFiAMt2MFhrw9CWGzMJtyXHzL5IIJnnXVC.jpg', NULL, 0, 4, '2026-05-31 00:38:01', '2026-05-31 00:38:01'),
(7, 'About Time', 'Luca Guadagnino', 'When Tim Lake (Domhnall Gleeson) is 21, his father (Bill Nighy) tells him a secret: The men in their family can travel through time. Although he can\'t change history, Tim resolves to improve his life by getting a girlfriend. He meets Mary (Rachel McAdams), falls in love and finally wins her heart', 'Timothée el Stuhlbarg', 'drama', 'English', '2020', '1h', 10.0, 0, 'posters/8t9pu9re2HW7AYIstWYYo2SlqfgiZlwt3LXAbGeP.jpg', NULL, 1, 5, '2026-05-31 02:00:24', '2026-05-31 02:00:24'),
(8, 'Her', 'Spike Jonze', 'A Spike Jonze love story.\r\nIn the not so distant future, Theodore, a lonely writer, purchases a newly developed operating system designed to meet the user’s every need. To Theodore’s surprise, a romantic relationship develops between him and his operating system. This unconventional love story blends science fiction and romance in a sweet tale that explores the nature of love and the ways that technology isolates and connects us all.', 'joaquin Phoenix, Scarlett Johansson, Lynn Adrianna, Lisa Renee Pitts', 'Scsi-fi, Romance', 'English', '2013', '1h', 7.0, 0, 'posters/YFpvjDPx2w750RdnAGoALsjoW4RClcPxY7iJw9PL.jpg', NULL, 1, 5, '2026-05-31 03:12:39', '2026-06-01 22:38:21'),
(9, '500 Days of Summer', 'Marc Webb', 'Tom, greeting-card writer and hopeless romantic, is caught completely off-guard when his girlfriend, Summer, suddenly dumps him. He reflects on their 500 days together to try to figure out where their love affair went sour, and in doing so, Tom rediscovers his true passions in life.', 'Joseph Gordon-Levitt, Zoeey Deschnanel, Geoffrey Arend', 'Romance, comedy', 'English', '2009', '1h', 8.0, 0, 'posters/2VbSy24U9dua5MDa4Jr93qSiPViBl3BdGlRcOpuA.jpg', NULL, 0, 5, '2026-05-31 03:16:57', '2026-05-31 03:16:57'),
(10, 'Waves', 'Trey Edward Shults', 'aray mo', 'Kelvin Harrison Jr., Taylor Russell, Renee Elise Goldsberry, Sterling K. Brown', 'Drama', 'English', '2019', '2h 25 minutes', 9.0, 0, 'posters/3qweGV9iDVyv94GJvDdn0Ch0KVI69grpFoH3DnqA.jpg', NULL, 0, 5, '2026-05-31 03:22:30', '2026-05-31 03:22:30'),
(11, 'One Day', 'Lone Scherfig', 'THE BEST! will rewatch this soon...', 'Anne Hathway, Jim Strurgess, Tom Mison', 'Romance, drama', 'English', '2011', '1h', 10.0, 0, 'posters/qPACGHoSmEF8jQrFwS6ehLk2OpWungGuuQfMpVGV.jpg', NULL, 0, 5, '2026-05-31 03:25:32', '2026-05-31 03:25:55'),
(12, 'minari', 'minari', NULL, 'minari, minari, minari', 'drama', 'English', '2000', '1', 9.0, 0, NULL, NULL, 0, 1, '2026-06-01 22:36:42', '2026-06-01 22:36:42'),
(13, 'salawahan', 'jasmine luki', 'sobrang ganda gaiz may bakbakan', 'basta kahit sino', 'lampungan', 'English', '2026', '3hrs', 10.0, 0, NULL, NULL, 0, 8, '2026-06-01 23:07:47', '2026-06-01 23:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_image`, `bio`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'JASMINE OMNES', 'admin@gmail.com', '$2y$12$lcZdUMGPSDNiTqEx2rH11erjiVg7lzbIwRrq8Ilg.iEJEr/SmYIhO', 'admin', 'avatars/px4FTvUOdn9pJfQtwdqp0OVxEPuAo6UWtOiksP17.jpg', NULL, NULL, '2026-05-30 21:45:50', '2026-05-31 00:15:32'),
(2, 'angel', 'angel@gmail.com', '$2y$12$nbOYiEhdEzks41YvgXq9jumC5gAyemlhf0lE2P0ifanPi/tV1tN1q', 'user', NULL, NULL, NULL, '2026-05-31 00:03:22', '2026-05-31 00:03:22'),
(3, 'jasmin', 'jasmin@gmail.com', '$2y$12$SHXCFZgzn7tb6vau5eWJh.Gpk0UTEsKI9O3J9287eTvM2d3DJj87G', 'user', NULL, NULL, NULL, '2026-05-31 00:11:32', '2026-05-31 00:11:32'),
(4, 'jass', 'jass@gmail.com', '$2y$12$HYP/RQswtLUi7EBJjZBiduP9RQdhsT.S4jHUzGHS9W33Pg4FD7pq.', 'user', NULL, NULL, NULL, '2026-05-31 00:34:21', '2026-05-31 00:34:21'),
(5, 'milo', 'milo@gmail.com', '$2y$12$vqJlv5x8.63kyPVSmCnA2O128q2ickIXkBotr0jJGwWtsun2ROp0u', 'user', 'avatars/e7TADoF9Fn15D4nlpDt8WMfFovBQHUBVbQhPTn7t.jpg', 'hi hehe', NULL, '2026-05-31 01:56:05', '2026-05-31 01:57:59'),
(6, 'choco mani', 'choco@gmail.com', '$2y$12$2TmIS8YCyaBBj2u/rk7xq.khjJKQq0uJ5EpZ2QlY.8ZUZfXEcw7uq', 'user', NULL, NULL, NULL, '2026-05-31 04:07:38', '2026-05-31 04:07:38'),
(8, 'vhinze pogi ventura', 'vhinze@gmail.com', '$2y$12$AKCut26IUWD2vQlOf/7/6useK9l1OtTZasQqA1aP3FbK.kL1LUpJy', 'user', NULL, NULL, NULL, '2026-06-01 23:06:47', '2026-06-01 23:06:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movies_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
