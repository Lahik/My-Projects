-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2024 at 06:10 PM
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
-- Database: `restaurantdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(6, 'icbt', '$2y$10$MlNdoWPTa2d/KwolKepIbOv6cfyvbSOOPD.Mp.NYX6gYq66qaPbjS');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`name`, `email`) VALUES
('lahik', 'lahik65@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `name` varchar(50) NOT NULL,
  `number` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `orders` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date_time`, `name`, `number`, `address`, `orders`, `total`, `payment_method`, `payment_status`) VALUES
(9, 3, '2023-12-28 23:16:50', 'lahik', 760494181, '142/5, bulugohetenne, akurana, kandy, central province - 20850', 'cheese pizza x (1)', 2800, 'card', 1),
(10, 3, '2023-12-29 00:29:25', 'lahik', 760494181, '142/5, bulugohetenne, akurana, kandy, central province - 20850', 'zinger burger x (1), strawberry milkshake x (1), burger x (2)', 3200, 'cash on delivery', 1),
(11, 3, '2024-01-01 15:24:04', 'lahik', 760494181, '142/5, bulugohetenne, akurana, kandy, central province - 20850', 'whole chicken x (2)', 4400, 'take away', 0),
(12, 3, '2024-01-02 01:57:16', 'lahik', 760494181, '142/5, bulugohetenne, akurana, kandy, central province - 20850', 'whole chicken x (1)', 2200, 'take away', 0),
(13, 3, '2024-01-03 15:40:53', 'lahik', 760494181, '142/5, bulugohetenne, akurana, kandy, central province - 20850', 'double decker burger x (1), mushroom pizza x (1), lemon drink x (1)', 4150, 'cash on delivery', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `cuisine` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(50) NOT NULL,
  `hero_slider` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `cuisine`, `category`, `name`, `price`, `image`, `hero_slider`) VALUES
(12, 'french', 'fast food', 'burger', 800, 'fast_food_1.png', 0),
(13, '', 'fast food', 'zinger burger', 1200, 'fast_food_2.png', 0),
(14, '', 'drinks', 'strawberry milkshake', 400, 'drinks_1.png', 0),
(15, '', 'desserts', 'brownie', 250, 'desserts_1.png', 0),
(16, '', 'drinks', 'chocolate milkshake', 450, 'drinks_2.png', 0),
(17, '', 'desserts', 'cupcake', 250, 'desserts_2.png', 0),
(18, '', 'desserts', 'strawberry icecream', 500, 'desserts_3.png', 0),
(19, '', 'desserts', 'pudding', 200, 'desserts_4.png', 0),
(20, 'italian', 'main dish', 'noodles', 800, 'main_dish_1.png', 0),
(21, '', 'main dish', 'pasta', 500, 'main_dish_2.png', 0),
(22, '', 'fast food', 'kothu', 800, 'fast_food_3.png', 0),
(23, '', 'main dish', 'beef steak', 1500, 'main_dish_3.png', 0),
(24, '', 'drinks', 'lemon drink', 250, 'drinks_3.png', 0),
(25, '', 'drinks', 'Ice coffee', 500, 'drinks_4.png', 0),
(26, '', 'drinks', 'mojito', 350, 'drinks_5.png', 1),
(27, '', 'drinks', 'watermelon drink', 300, 'drinks_6.png', 0),
(28, '', 'drinks', 'strawberry mojito', 600, 'drinks_7.png', 0),
(29, '', 'fast food', 'pepporoni pizza', 2200, 'fast_food_4.png', 0),
(30, '', 'fast food', 'vegetable pizza', 1700, 'fast_food_5.png', 0),
(31, 'french', 'fast food', 'sausage pizza', 2000, 'fast_food_6.png', 0),
(32, 'chinese', 'fast food', 'cheese pizza', 2800, 'fast_food_7.png', 0),
(33, 'chinese', 'fast food', 'mushroom pizza', 2100, 'fast_food_8.png', 1),
(35, '', 'fast food', 'double decker burger', 1800, 'fast_food_10.png', 1),
(36, '', 'main dish', 'whole chicken', 2200, 'main_dish_4.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `adults` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `number` int(11) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `date_time`, `adults`, `children`, `comments`, `name`, `number`, `status`) VALUES
(17, 3, '2023-12-31 15:53:00', 2, 2, '', 'lahik', 760494181, 'rejected'),
(18, 6, '2024-01-04 14:00:00', 5, 2, 'We hope that we get our reservation approved! thank you.', 'icbt', 777123456, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `password`) VALUES
(1, 'icbt', '$2y$10$9t8qexUKV7YvkePxGFzz9egg12frJEe8FKx79jaxlYoKiPMLA0MRa'),
(2, 'lahik', '$2y$10$yjnwMPX8niymiVYxB2RIMO6yhHhI14MWOT9/qnYBqwwRgleQ4jnX.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(11) NOT NULL,
  `password` char(255) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `reg_date`, `address`) VALUES
(3, 'lahik', 'lahik65@gmail.com', '0760494181', '521ea2961471c083641fe8125f27e72053e7f309', '2023-12-22 18:34:10', '142/5, bulugohetenne, akurana, kandy, central province - 20850'),
(6, 'icbt', 'icbt@gmail.com', '0777123456', 'fff67b65cda981ce86e547e9f251ca1ac3c34772', '2024-01-03 17:28:52', '');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `user_id` varchar(50) NOT NULL,
  `food_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`user_id`, `food_id`) VALUES
('3', 35),
('3', 33),
('3', 36),
('3', 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
