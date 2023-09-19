-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 19, 2023 at 02:36 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chewvy`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `DOB` date NOT NULL,
  `account_status` varchar(50) NOT NULL,
  `registration_date_and_time` datetime NOT NULL,
  `image` blob NOT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `username`, `email`, `password`, `first_name`, `last_name`, `gender`, `DOB`, `account_status`, `registration_date_and_time`, `image`) VALUES
(7, 'Chewvy', 'chewvy@gmail.com', '344414520fc726613a48089de400be0c', 'Chewvy', 'Lau', 'female', '2023-07-17', 'active', '2023-08-05 07:46:07', 0x70726f66696c652031362e706e67),
(8, 'Estella', 'estella05@gmail.com', '047ae7840ed2e3801807b8c7696daccd', 'Estella', 'Loh', 'female', '2023-08-03', 'active', '2023-08-05 07:53:10', 0x70726f66696c65372e6a7067),
(9, 'LilyTan', 'lilytan@gmail.com', '299fe8a4ea7bf0e6eb5cdb0d9cfe5640', 'Lulu', 'Lala', 'female', '2023-08-01', 'pending', '2023-08-05 07:58:00', 0x70726f66696c6531332e6a7067),
(10, 'Apples', 'apples@gmail.com', 'a602e19d49a36a8151086b5265792943', 'Apple', '034', 'female', '2023-08-01', 'pending', '2023-08-05 07:59:46', 0x313639333734373935355f70726f66696c6531302e6a7067),
(11, 'Chanhan', 'chanhan@gmail.com', '60360c1584e50b727103e80736e8a193', 'Han', 'Han', 'others', '2023-08-03', 'active', '2023-08-05 08:30:33', 0x64656661756c742e6a7067),
(12, 'Rabbylee', 'rabby@gmail.com', '54392917e7951151356b8b32727b952d', 'Rabby', 'Lee', 'female', '2023-08-23', 'pending', '2023-08-05 09:30:07', 0x64656661756c742e6a7067),
(13, 'Venessa Lee', 'blublu@gmail.com', '1d4645181603dc58ee71cf6afaf42d26', 'Vanessa', 'Lee', 'male', '2023-08-03', 'inactive', '2023-08-07 10:35:28', 0x64656661756c742e6a7067),
(14, 'Ivylau070126', 'lauivy@gmail.com', '8d8a967dc2e079d4a3265a62788635b7', 'Ivy', 'Lau', 'male', '2023-07-05', 'active', '2023-08-08 03:31:37', 0x64656661756c742e6a7067),
(17, 'Joker ', 'Xuezhiqian@yahoo.com', '7c5896ff801e861a2d8b4620e11e4550', 'Zhiqian', 'Xue', 'male', '1980-07-17', 'active', '2023-09-03 04:26:49', 0x64656661756c742e6a7067),
(19, 'Sharon', 'Sharonlee@gmail.com', '$2y$10$IeTPVNjVLTnunk/L1qX7bOMrm7QhyejaoUGFF05PRVzGV8FzmeFLG', 'Lee', 'Sharon', 'male', '2023-06-26', 'active', '2023-09-03 07:35:01', 0x64656661756c742e6a7067),
(20, 'Shelly', 'Shellyong@gmail.com', '$2y$10$q82pQo/eAuzjGzUuXLGgS.CHa0toaHjf4YRIGjWQKD4MrIyZyQubu', 'Shelly', 'Ong', 'female', '2022-07-29', 'active', '2023-09-03 08:19:20', 0x64656661756c742e6a7067),
(21, 'Kazamilau', 'Lkseng13@yahoo.com', '$2y$10$ZGZuwsNPpEqu5q6qDWYe6OlfA7JO4meCKGZjmoRMKBESZ8ZhSM6H2', 'Seng', 'Kazami', 'male', '2021-08-05', 'active', '2023-09-03 08:24:28', 0x64656661756c742e6a7067),
(23, 'Lucky07', 'Lucky@gmail.com', '$2y$10$pNFO07jPZZAMC6Kf9T4/bOYOx8wdMJR13.Kh7uJ7kMyaKrqdKfRfK', 'Lucky', 'Li', 'female', '2023-08-29', 'active', '2023-09-03 08:40:00', 0x64656661756c742e6a7067),
(24, 'Lay Zhang', 'Ranseti@gmail.com', '$2y$10$HYCK31Ng1EWjVlyNVdCqYuUwwuSaFP/ZepEHlSUzmtmdqIbea8C76', 'Lay', 'Zhang Yi Xing', 'male', '1995-02-14', 'active', '2023-09-03 08:41:22', 0x50524f46494c452031352e706e67),
(28, 'Louislow', 'Louislow@gmail.com', '$2y$10$cH/LVEjIYKm1RFQRY7e3qe.0rVtWDso2Tf0bfBoJhF1MW9LASxADa', 'Low', 'Louis', 'male', '1999-09-01', 'active', '2023-09-16 13:52:17', 0x70726f66696c65322e6a7067),
(29, 'Xiaomai', 'Wangxiaomai@gmail.com', '$2y$10$r4CqVIKJQ3gUtOGcoBk2J.ahtDTEUG1OfjB3nczR8KFba8PBy/FWu', 'Xiaomai', 'Wang', 'female', '2016-01-04', 'active', '2023-09-18 05:28:47', 0x64656661756c742e6a7067),
(31, 'Zhangzhang', 'zhangzhang@gmail.com', '$2y$10$s6Q3.uR/GQgM62eDKBw2nedUI4tGxTDiNUIm77.Znri/hmzNsKBES', 'Zhang', 'Zhang', 'male', '2019-01-08', 'active', '2023-09-18 06:07:19', 0x70726f66696c6531362e706e67);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`order_detail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=311 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`) VALUES
(2, 1, 5, 1),
(3, 1, 4, 1),
(5, 2, 6, 1),
(6, 2, 20, 1),
(310, 22, 30, 3),
(9, 3, 17, 1),
(309, 22, 16, 2),
(12, 4, 20, 1),
(308, 22, 23, 1),
(15, 5, 20, 1),
(307, 21, 21, 10),
(18, 6, 20, 1),
(306, 21, 20, 9),
(20, 7, 19, 1),
(21, 7, 20, 1),
(305, 21, 19, 8),
(23, 8, 19, 1),
(24, 8, 20, 1),
(304, 21, 16, 7),
(26, 9, 19, 1),
(27, 9, 20, 1),
(303, 21, 15, 6),
(302, 21, 14, 5),
(30, 8, 20, 1),
(301, 21, 7, 4),
(32, 3, 20, 1),
(33, 3, 18, 1),
(300, 21, 6, 3),
(299, 21, 5, 2),
(36, 5, 18, 1),
(298, 21, 4, 1),
(297, 20, 23, 5),
(39, 6, 20, 1),
(269, 16, 19, 1),
(85, 7, 6, 1),
(86, 7, 19, 1),
(88, 8, 6, 1),
(89, 8, 19, 1),
(289, 18, 30, 6),
(288, 18, 25, 5),
(287, 18, 5, 4),
(296, 20, 25, 4),
(295, 20, 26, 3),
(283, 17, 4, 9),
(282, 17, 21, 9),
(281, 17, 19, 8),
(280, 17, 5, 7),
(279, 17, 7, 6),
(278, 17, 14, 5),
(277, 17, 15, 4),
(276, 17, 20, 3),
(294, 20, 29, 2),
(274, 17, 6, 1),
(273, 16, 21, 5),
(271, 16, 15, 3),
(270, 16, 6, 2),
(268, 15, 15, 9),
(293, 20, 30, 1),
(252, 12, 14, 2),
(251, 12, 6, 1),
(292, 19, 26, 3),
(249, 11, 14, 2),
(248, 11, 6, 1),
(291, 19, 29, 2),
(246, 10, 14, 2),
(245, 10, 6, 1),
(267, 15, 14, 8),
(266, 15, 7, 7),
(265, 15, 6, 6),
(264, 15, 5, 5),
(263, 15, 4, 4),
(262, 15, 3, 3),
(290, 19, 30, 1),
(256, 13, 21, 3),
(255, 13, 20, 2),
(254, 13, 14, 1),
(259, 14, 20, 3),
(258, 14, 7, 2),
(257, 14, 21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_summary`
--

DROP TABLE IF EXISTS `order_summary`;
CREATE TABLE IF NOT EXISTS `order_summary` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_summary`
--

INSERT INTO `order_summary` (`order_id`, `customer_id`, `order_date`) VALUES
(22, 13, '2023-09-18 10:18:26'),
(21, 13, '2023-09-17 13:55:54'),
(4, 7, '2023-08-19 10:28:02'),
(5, 7, '2023-08-19 10:30:40'),
(6, 7, '2023-08-19 10:32:06'),
(7, 7, '2023-08-20 06:40:09'),
(8, 10, '2023-08-20 06:50:52'),
(9, 10, '2023-08-27 12:39:20'),
(10, 12, '2023-09-02 15:31:33'),
(11, 12, '2023-09-02 15:34:12'),
(12, 12, '2023-09-02 15:35:30'),
(14, 10, '2023-09-02 15:38:24'),
(15, 13, '2023-09-02 15:46:53'),
(16, 9, '2023-09-02 15:53:38'),
(17, 14, '2023-09-03 04:32:31'),
(18, 8, '2023-09-04 05:50:01'),
(19, 17, '2023-09-09 04:48:18'),
(20, 17, '2023-09-09 04:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `categoryID` int(1) NOT NULL,
  `price` varchar(11) NOT NULL,
  `Promotion_price` varchar(11) NOT NULL,
  `image` blob NOT NULL,
  `manufacture_date` date NOT NULL,
  `expire_date` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `categoryID`, `price`, `Promotion_price`, `image`, `manufacture_date`, `expire_date`, `created`, `modified`) VALUES
(4, 'Trash Can', 'It will help you maintain cleanliness.', 5, '4.00', '2.00', 0x50726f64756374436f6d696e67536f6f6e2e6a7067, '2023-08-01', NULL, '2015-08-02 12:16:08', '2023-08-29 01:20:59'),
(5, 'Mouse', 'Very useful if you love your computer.', 6, '11', '0.00', 0x6d6f7573652e6a7067, '2023-08-01', '2023-08-31', '2015-08-02 12:17:58', '2023-08-27 05:00:25'),
(6, 'Earphone', 'You need this one if you love music.', 6, '7.00', '6.00', 0x50726f64756374436f6d696e67536f6f6e2e6a7067, '2023-08-01', '2023-08-30', '2015-08-02 12:18:21', '2023-08-27 05:00:25'),
(7, 'Pillow', 'Sleeping well is important.', 5, '9', '0.00', 0x50494c4c4f572e706e67, '2023-08-01', '2023-08-30', '2015-08-02 12:18:56', '2023-08-27 05:00:25'),
(14, 'Ninja Sticker', 'best sticker in the world', 7, '15', '14.00', 0x6e696e6a6120737469636b65722e6a7067, '2023-07-25', '2023-08-01', '2023-08-01 02:53:08', '2023-08-27 05:00:25'),
(15, 'Pen', 'use to make notes', 7, '25', '24.00', 0x70656e2e706e67, '2023-08-02', '2023-08-24', '2023-08-05 07:29:15', '2023-08-27 05:00:25'),
(16, 'Iphone X', 'greatest mobile in the world', 6, '5999', '4999.00', 0x6970686f6e65582e706e67, '2023-08-01', '2023-08-29', '2023-08-05 08:25:04', '2023-08-27 05:00:25'),
(19, 'Yoyo', 'Cutest pet in the world', 5, '15', '14.00', 0x796f796f2e706e67, '2023-08-01', '2023-08-20', '2023-08-05 08:31:38', '2023-08-27 05:00:25'),
(20, 'PS4', 'Gaming equipment', 6, '5999', '4999.00', 0x7073342e706e67, '2023-08-03', '2023-08-24', '2023-08-06 13:41:12', '2023-08-27 05:00:25'),
(21, 'shirt', 'best quality', 9, '15', '14.00', 0x73686972742e706e67, '2023-08-02', '2023-09-05', '2023-08-13 08:44:45', '2023-08-27 05:00:25'),
(23, 'Oversized sweatshirt', 'Oversized top in sweatshirt fabric made from a cotton blend with a soft brushed inside. Low dropped shoulders, long sleeves and ribbing around the neckline, cuffs and hem.', 9, '94.95', '4.00', 0x737765617473686972742e706e67, '2023-08-30', '2023-09-26', '2023-09-03 05:34:25', '2023-09-03 05:34:25'),
(25, 'NEXT Cardigan', 'Perfect for everyday layering, this cardigan is designed in a classic style with a front button fastening and pocket..', 9, '55', '0.00', 0x50726f64756374436f6d696e67536f6f6e2e6a7067, '2023-08-29', NULL, '2023-09-03 05:46:16', '2023-09-03 05:46:16'),
(26, 'Character Hooded Baby Cardigan', 'Add a pop fun to their wardrobe with our character cardigan this season. Perfect for layering over every style..', 9, '99', '0.00', 0x303238663139663137623034663530356530613238396631386537366665643934656436383363302d686f6f6469652e6a7067, '2023-08-30', '2023-09-28', '2023-09-03 05:52:39', '2023-09-03 05:52:39'),
(29, 'Zip Pocket Short Sleeve T-Shirt', 'Update their wardrobe with the relaxed fit utility zip pocket T-shirt, short sleeve and machine washable', 9, '61', 'RM 0.00', 0x50726f64756374436f6d696e67536f6f6e2e6a7067, '2023-08-29', '2023-10-07', '2023-09-03 07:06:54', '2023-09-03 07:06:54'),
(30, 'Oversized Fit Printed hoodie', 'Oversized hoodie in sweatshirt fabric made from a cotton blend with a soft brushed inside and a print motif. Double-layered wrapover hood, low dropped shoulders, long sleeves, a kangaroo pocket and ribbing at the cuffs and hem.', 9, '129.95', '0.00', 0x50726f64756374436f6d696e67536f6f6e2e6a7067, '2023-08-31', '2023-10-05', '2023-09-03 08:50:38', '2023-09-03 08:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
CREATE TABLE IF NOT EXISTS `product_category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`categoryID`, `category_name`, `description`) VALUES
(5, 'Games', 'Football, Basketball, Tennis'),
(4, 'Food', 'Bread, Biscuits, Rice'),
(3, 'Drinks', 'soft drink,mineral'),
(6, 'Gadgets', 'Mouse, Keyboard, Monitor, Cable'),
(7, 'Stationary', 'Sticker, Books, Pen'),
(9, 'Clothing', 'Polo, T-shirt, Hoodies, Jackets'),
(10, 'Shoes', 'Boots, Slippers, Sport Shoes');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
