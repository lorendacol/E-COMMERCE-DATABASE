-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 05:16 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-com`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'group6@admin.com', 'ecommers');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `logo_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `email`, `password`, `logo_image`) VALUES
(1, 'nike', 'admin@nike.com', 'nike123', 'https://i.pinimg.com/736x/d4/20/46/d4204662d48e847dbf4dff048863546c.jpg'),
(2, 'adidas', 'admin@adidas.com', 'adidas123', 'https://e7.pngegg.com/pngimages/61/526/png-clipart-adidas-logo-adidas-puma-logo-shoe-sportswear-adidas-angle-text.png'),
(3, 'puma', 'admin@puma.com', 'puma123', 'https://cdn.icon-icons.com/icons2/2845/PNG/512/puma_logo_icon_181343.png');

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE `failed_logins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `failed_logins`
--

INSERT INTO `failed_logins` (`id`, `email`, `ip_address`, `created_at`) VALUES
(1, 'dacol@gmail.com', '::1', '2025-02-27 04:14:36'),
(2, 'dacol@gmail.com', '::1', '2025-02-27 04:14:38'),
(3, 'dacol@gmail.com', '::1', '2025-02-27 04:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `action` text NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `company_name`, `action`, `product_name`, `details`, `timestamp`) VALUES
(1, 'Adidas', 'Stock Added', 'Adidas Yeezy Boost 350', 'Added 1 stocks', '2024-12-11 04:07:17'),
(2, 'Adidas', 'Price Changed', 'fsd', 'Changed price from ₱343.00 to ₱434.00', '2024-12-11 04:08:55'),
(3, 'Adidas', 'Product Added', 'fgdfg', 'Added product with price ₱455.00 and stock quantity of 14', '2024-12-11 04:11:26'),
(4, 'Adidas', 'Stock Added', 'fgdfg', 'Added 2 stocks', '2024-12-11 05:08:49'),
(5, 'Nike', 'Stock Added', 'Nike Zoom Pegasus', 'Added 3 stocks', '2024-12-11 05:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `company_name`, `name`, `description`, `price`, `image`, `stock`) VALUES
(1, 'Puma', 'Puma RS-X3', 'Bold running shoes with futuristic design.', 120.00, 'https://m.media-amazon.com/images/I/61XBZGSXcmL._AC_UL1000_.jpg', 4),
(2, 'Puma', 'Puma Cali Sport', 'Classic sneakers with a modern twist.', 45.00, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_450,h_450/global/383157/04/sv01/fnd/PHL/fmt/png/Cali-Dream-Lth-Women\'s-Trainers', 1),
(3, 'Puma', 'Puma Future Rider', 'Retro-style shoes for everyday comfort.', 100.00, 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_2000,h_2000/global/391927/05/sv01/fnd/PHL/fmt/png/Future-Rider-New-Core-Sneakers', 1),
(4, 'Puma', 'Puma Suede Classic', 'Iconic suede sneakers for casual wear.', 90.00, 'https://www.capital.com.ph/cdn/shop/files/399781-01_grande.jpg?v=1721967271', 5),
(5, 'Puma', 'Puma Ignite Blaze', 'Performance running shoes with great cushioning.', 130.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-jb8hf0QooVIToy0ryX02EiQE-xmZHF5K5g&s', 5),
(6, 'Nike', 'Nike Air Force 1', 'Timeless basketball sneakers with premium comfort.', 6667.00, 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/777c9d18-2c2e-4b72-8244-70dce5177b1f/AIR+FORCE+1+SP.png', 38),
(7, 'Nike', 'Nike Air Max 270', 'Stylish sneakers with responsive cushioning.', 160.00, 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/10747498-d6bb-403b-8698-635e2dd652c5/NIKE+AIR+MAX+270+%28GS%29.png', 7),
(8, 'Nike', 'Nike React Infinity', 'Running shoes designed for injury prevention.', 170.00, 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/a2ad85fe-51cc-462a-9b33-76add33004a4/W+NIKE+REACT+INFINITY+RUN+FK+3.png', 5),
(9, 'Nike', 'Nike Blazer Mid', 'Retro high-top sneakers for street style.', 140.00, 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/f77841e0-11de-4b1f-86e8-997df2a6deff/W+BLAZER+MID+%2777+NEXT+NATURE.png', 4),
(10, 'Nike', 'Nike Zoom Pegasus', 'Performance running shoes for all distances.', 130.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSn6UoXaPEBmQLpWL6-3s7WaWhfRkgOGCTTXg&s', 8),
(11, 'Adidas', 'Adidas Ultraboost', 'High-performance running shoes with energy return.', 23.00, 'https://m.media-amazon.com/images/I/71uFwrSUIyL._AC_SL1500_.jpg', 8),
(12, 'Adidas', 'Adidas Superstar', 'Classic sneakers with shell-toe design.', 3.00, 'https://m.media-amazon.com/images/I/716Inzq-uoL._AC_SL1500_.jpg', 6),
(13, 'Adidas', 'Adidas NMD_R1', 'Innovative sneakers for urban explorers.', 140.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQFIBpbJlWiiW1FdiqeHsAXOTfH9S9z4jq_Qg&s', 5),
(14, 'Adidas', 'Adidas Stan Smith', 'Minimalist sneakers with timeless appeal.', 100.00, 'https://www.urbanathletics.com.ph/cdn/shop/files/IE0458-D_2048x.jpg?v=1716433612', 5),
(15, 'Adidas', 'Adidas Yeezy Boost 350', 'Exclusive sneakers with unique design.', 3434.00, 'https://m.media-amazon.com/images/I/71wQhBkg9AL._AC_SL1500_.jpg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `product_feedback`
--

CREATE TABLE `product_feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_feedback`
--

INSERT INTO `product_feedback` (`id`, `user_id`, `product_id`, `feedback`, `created_at`) VALUES
(1, 4, 2, 'erro', '2024-12-11 06:27:11'),
(19, 4, 2, 'shit\r\n', '2024-12-11 06:54:35'),
(20, 4, 3, 'dfsd', '2024-12-11 06:55:56'),
(21, 4, 2, 'hi keyeytdtc', '2024-12-11 10:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp(),
  `amount_paid` decimal(10,2) NOT NULL,
  `change_due` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `payment_method`, `purchase_date`, `amount_paid`, `change_due`) VALUES
(1, 4, 2, 2, 220.00, 'Cash on Delivery', '2024-12-11 03:18:07', 0.00, 0.00),
(2, 4, 3, 1, 100.00, 'Cash on Delivery', '2024-12-11 03:18:07', 0.00, 0.00),
(3, 4, 1, 1, 120.00, 'Cash on Delivery', '2024-12-11 03:25:41', 344.00, 114.00),
(4, 4, 2, 1, 110.00, 'Cash on Delivery', '2024-12-11 03:25:41', 344.00, 114.00),
(5, 4, 2, 2, 220.00, 'Cash on Delivery', '2024-12-11 03:46:40', 234.00, 14.00),
(6, 4, 3, 1, 100.00, 'Cash on Delivery', '2024-12-11 03:47:26', 455.00, 245.00),
(7, 4, 2, 1, 110.00, 'Cash on Delivery', '2024-12-11 03:47:26', 455.00, 245.00),
(8, 4, 3, 1, 100.00, 'Cash on Delivery', '2024-12-11 04:04:01', 444.00, 344.00),
(9, 4, 6, 5, 33335.00, 'Cash on Delivery', '2024-12-11 11:16:13', 344434.00, 311099.00),
(10, 4, 11, 1, 23.00, 'Cash on Delivery', '2024-12-11 13:35:05', 34.00, 11.00),
(11, 4, 9, 1, 140.00, 'Gcash', '2024-12-11 18:45:18', 800.00, 495.00),
(12, 4, 2, 1, 45.00, 'Gcash', '2024-12-11 18:45:18', 800.00, 495.00),
(13, 4, 1, 1, 120.00, 'Gcash', '2024-12-11 18:45:18', 800.00, 495.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `cellphone_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `country` enum('Philippines','America','China','Brazil','Thailand','Japan','India','Australia','Canada','Germany') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_attempt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `cellphone_number`, `address`, `birthday`, `gender`, `country`, `created_at`, `updated_at`, `failed_attempts`, `last_failed_attempt`) VALUES
(0, 'lorai@gmail.com', '$2y$10$f61LFhTo/K.ijTvJAi18a.docwA6UlDeg1w7VBye3LwbhpA/JVmtm', 'red', '32436', 'vv', '2005-02-02', 'Male', 'Philippines', '2025-02-27 03:57:49', '2025-02-27 04:14:40', 3, '2025-02-27 04:14:40'),
(0, 'dacol@gmail.com', '$2y$10$xK0C9Nb8vtTPx4bG05P9hedc7THN1s3HKw3jnsrEGDN6WK5PDXwGK', 'red', '32436', 'vv', '2005-02-02', 'Male', 'Philippines', '2025-02-27 04:14:11', '2025-02-27 04:14:40', 3, '2025-02-27 04:14:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`ip_address`,`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
