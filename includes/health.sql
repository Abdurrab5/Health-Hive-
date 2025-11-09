-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 06:23 AM
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
-- Database: `health`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `account_title` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `cvv` varchar(10) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `account_type` enum('user','lab','pharmacy','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `user_id`, `account_title`, `account_number`, `card_number`, `cvv`, `balance`, `account_type`) VALUES
(1, 1, 'Admin Account', '123456', '11223344', '123', 103.00, 'admin'),
(2, 5, 'lab account', '446677', '9999999', '987', 0.00, 'lab'),
(3, 3, 'samad', '789789', '98765', '123', 197970.00, 'user'),
(4, 4, 'Pharmacy ', '666666666', '4444444', '1234', 27.00, 'pharmacy');

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','in progress','resolved') DEFAULT 'pending',
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `subject`, `message`, `status`, `response`, `created_at`, `updated_at`) VALUES
(1, 3, 'regarding net', 'sxsaxiasxoi ascias chchc i hih uih jdh idhc', 'in progress', 'sdcsdcdcd', '2025-05-06 04:13:09', '2025-05-06 04:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `id` int(11) NOT NULL,
  `lab_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`id`, `lab_id`, `test_name`, `image`, `description`, `price`, `created_at`) VALUES
(1, 5, 'CBC', 'uploads/6818e9ec7d28d_istockphoto-1354172647-612x612.jpg', 'sdyavdasvdhashb', 500.00, '2025-05-05 09:45:56');

-- --------------------------------------------------------

--
-- Table structure for table `lab_test_reports`
--

CREATE TABLE `lab_test_reports` (
  `id` int(11) NOT NULL,
  `test_request_id` int(11) NOT NULL,
  `report_file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_test_requests`
--

CREATE TABLE `lab_test_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lab_test_id` int(11) NOT NULL,
  `lab_id` int(11) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid',
  `payment_method` enum('cash','card') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_test_requests`
--

INSERT INTO `lab_test_requests` (`id`, `user_id`, `lab_test_id`, `lab_id`, `appointment_date`, `appointment_time`, `status`, `payment_status`, `payment_method`, `created_at`) VALUES
(4, 3, 1, 5, '2025-05-08', '08:52:00', 'rejected', 'paid', 'card', '2025-05-06 01:27:15'),
(5, 3, 1, 5, '2025-05-07', '09:31:00', 'pending', 'paid', 'card', '2025-05-06 03:22:02'),
(6, 3, 1, 5, '2025-05-29', '09:31:00', 'pending', 'paid', 'card', '2025-05-06 03:22:02');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `pharmacy_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `generic` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `stock` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `pharmacy_id`, `name`, `brand`, `generic`, `category`, `price`, `discount`, `stock`, `description`, `image`, `status`, `is_available`, `created_at`) VALUES
(3, 4, 'corona', 'galaxy', 'virus', 'genetics', 5.00, 0.00, 500, 'medicine for corona virus', 'uploads/coronavirus-4932607_640.jpg', 1, 1, '2025-05-05 08:10:39'),
(4, 4, 'Multi Vitamin', 'XYZ', 'Vitmin A,Vitamin C', 'Health', 20.00, 0.00, 120, 'gvvvv  fggfbgb', 'uploads/6818e67d83168_istockphoto-914503810-612x612.jpg', 1, 1, '2025-05-05 15:12:36'),
(6, 4, 'panadol', 'galaxy', 'paracetamol', 'temprature', 5.00, 0.00, 120, '120', 'uploads/6818e25346196_medications-257336_1280.jpg', 1, 1, '2025-05-05 16:07:47'),
(8, 4, 'Augmentine', 'galaxy', 'Antibiotic', 'Antibiotic', 20.00, 0.00, 120, '', 'uploads/6818e3d4d4793_istockphoto-2190041588-1024x1024.jpg', 1, 1, '2025-05-05 16:14:12'),
(10, 4, 'Amocile', 'galaxy', 'Antibiotic', 'Antibiotic', 20.00, 0.00, 120, 'asacascd d csdcdc', 'uploads/6818e452a3c5d_istockphoto-486639888-612x612.jpg', 1, 1, '2025-05-05 16:16:18');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_orders`
--

CREATE TABLE `medicine_orders` (
  `id` int(11) NOT NULL,
  `pharmacy_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','cancelled') DEFAULT 'pending',
  `order_date` datetime DEFAULT current_timestamp(),
  `delivery_address` text DEFAULT NULL,
  `payment_method` enum('card','cash','delivery') DEFAULT 'cash',
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `transaction_id` int(11) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_orders`
--

INSERT INTO `medicine_orders` (`id`, `pharmacy_id`, `user_id`, `medicine_id`, `quantity`, `total_amount`, `status`, `order_date`, `delivery_address`, `payment_method`, `payment_status`, `transaction_id`, `admin_note`, `created_at`, `updated_at`) VALUES
(3, 4, 3, 3, 3, 15.00, 'pending', '2025-05-06 04:56:28', 'lhore', 'card', 'completed', 3, NULL, '2025-05-06 07:56:28', '2025-05-06 08:12:25'),
(4, 4, 3, 3, 1, 5.00, 'cancelled', '2025-05-06 05:02:53', 'dcsdsc', 'cash', 'pending', NULL, NULL, '2025-05-06 08:02:53', '2025-05-06 08:12:32'),
(5, 4, 3, 3, 1, 5.00, 'pending', '2025-05-06 05:04:55', 'sdcdcdc', '', 'pending', NULL, NULL, '2025-05-06 08:04:55', '2025-05-06 08:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `transaction_type` enum('payment','credit') DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `recipient_id`, `amount`, `transaction_date`, `transaction_type`, `description`) VALUES
(2, 3, 3, 200000.00, '2025-05-06 06:12:59', 'credit', 'Added balance'),
(3, 3, 4, 15.00, '2025-05-06 07:56:28', '', 'Medicine Order Payment'),
(4, 3, 1, 1.50, '2025-05-06 07:56:28', '', 'Admin 10% Commission for Order'),
(5, 3, 5, 500.00, '2025-05-06 08:22:02', '', 'Lab Test Payment: CBC');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `license_expiry_date` date DEFAULT NULL,
  `role` enum('user','lab','pharmacy','admin') NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `store_name`, `logo_path`, `phone`, `address`, `license_number`, `license_expiry_date`, `role`, `status`, `created_at`) VALUES
(1, 'Admin', 'admin@healthhive.com', '$2y$10$qjHJi9wRhoPijxAi/xU.WOSSx.8HJrdH0Z3mvr6t.XBLIi32/F8l2', NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 'approved', '2025-05-05 04:05:23'),
(3, 'samad', 'samad@gmail.com', '$2y$10$92ZPEpwWH2WpOtbfYqxLSuaboM8qEAD2xCPvZIhNcFU1Fcf4eFxFC', NULL, NULL, '1212121212', 'swabi', NULL, NULL, 'user', 'approved', '2025-05-05 04:38:52'),
(4, 'axyz', 'xyz@healthhive.com', '$2y$10$6.ISbGyozLs3MTRs.K7sqe0N6ST08M3EAC3PuUrAdmAfRU1tHhkLy', 'xyz', 'uploads/istockphoto-943974286-612x612.jpg', '230293983', 'mardan', 'we234242424', '2025-05-29', 'pharmacy', 'approved', '2025-05-05 04:39:49'),
(5, 'lab', 'lab@gmail.com', '$2y$10$fSIFP2PYAKhqpfLOC1VrU.e028iSKm4iZaLtJdEuCvlhr4TFoIgv6', 'star lab', 'uploads/68188853d5448_th (1).jpeg', '230293983', 'lahore', 'lab12133', '2025-05-30', 'lab', 'approved', '2025-05-05 09:43:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_accounts_user` (`user_id`);

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_id` (`lab_id`);

--
-- Indexes for table `lab_test_reports`
--
ALTER TABLE `lab_test_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_request_id` (`test_request_id`);

--
-- Indexes for table `lab_test_requests`
--
ALTER TABLE `lab_test_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lab_test_id` (`lab_test_id`),
  ADD KEY `lab_id` (`lab_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacy_id` (`pharmacy_id`);

--
-- Indexes for table `medicine_orders`
--
ALTER TABLE `medicine_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pharmacy_id` (`pharmacy_id`),
  ADD KEY `medicine_id` (`medicine_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transactions_user` (`user_id`),
  ADD KEY `fk_transactions_recipient` (`recipient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_tests`
--
ALTER TABLE `lab_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_test_reports`
--
ALTER TABLE `lab_test_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_test_requests`
--
ALTER TABLE `lab_test_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medicine_orders`
--
ALTER TABLE `medicine_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_accounts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD CONSTRAINT `lab_tests_ibfk_1` FOREIGN KEY (`lab_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `lab_test_reports`
--
ALTER TABLE `lab_test_reports`
  ADD CONSTRAINT `lab_test_reports_ibfk_1` FOREIGN KEY (`test_request_id`) REFERENCES `lab_test_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_test_requests`
--
ALTER TABLE `lab_test_requests`
  ADD CONSTRAINT `lab_test_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lab_test_requests_ibfk_2` FOREIGN KEY (`lab_test_id`) REFERENCES `lab_tests` (`id`),
  ADD CONSTRAINT `lab_test_requests_ibfk_3` FOREIGN KEY (`lab_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`pharmacy_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medicine_orders`
--
ALTER TABLE `medicine_orders`
  ADD CONSTRAINT `medicine_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `medicine_orders_ibfk_2` FOREIGN KEY (`pharmacy_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `medicine_orders_ibfk_3` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`),
  ADD CONSTRAINT `medicine_orders_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_recipient` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
