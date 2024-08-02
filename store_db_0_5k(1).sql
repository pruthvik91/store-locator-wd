-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2020 at 07:13 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store_db_0_5k`
--

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE IF NOT EXISTS `store` (
  `store_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_detail_id` varchar(255) DEFAULT NULL,
  `store_category_id` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `store_description` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `store_featured_image` varchar(255) DEFAULT NULL,
  `store_images` varchar(255) DEFAULT NULL,
  `store_video` varchar(255) DEFAULT NULL,
  `store_opening_hours` varchar(255) DEFAULT NULL,
  `open_always` varchar(255) DEFAULT NULL,
  `store_phone` varchar(255) DEFAULT NULL,
  `store_email` varchar(255) DEFAULT NULL,
  `store_website` varchar(255) DEFAULT NULL,
  `store_address_line1` varchar(255) DEFAULT NULL,
  `store_address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `marker_type` varchar(255) DEFAULT NULL,
  `marker_icon` varchar(255) DEFAULT NULL,
  `marker_color` varchar(255) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `marker_size` varchar(255) DEFAULT NULL,
  `featured` varchar(10) DEFAULT NULL,
  `is_active` varchar(10) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  PRIMARY KEY (`store_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `user_detail_id`, `store_category_id`, `store_name`, `store_description`, `tags`, `store_featured_image`, `store_images`, `store_video`, `store_opening_hours`, `open_always`, `store_phone`, `store_email`, `store_website`, `store_address_line1`, `store_address_line2`, `city`, `state`, `country`, `zip`, `latitude`, `longitude`, `marker_type`, `marker_icon`, `marker_color`, `marker_image`, `marker_size`, `featured`, `is_active`, `create_date`, `modify_date`) VALUES
(4, '3', '1', 'ifox solutions', '', 'harshit,vaishnav,ifox,solutions', '', 'Array', '', '', NULL, '', '', '', 'Ifox solutions', '', 'Ahmedabad', 'Gujarat', 'India', '382415', '', '', 'image', '', '#DF3C3C', 'OWT-white-2.jpg', '', '0', '1', '2020-02-28 06:35:18', '2020-03-07 04:39:39'),
(3, '3', '8', 'shree ram', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '1', '2020-02-28 06:33:44', '2020-02-28 12:45:33'),
(5, '3', '8', 'ifox web design', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '1', '2020-02-28 06:57:51', NULL),
(6, '3', '', 'ifox ifox', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '1', '2020-02-28 07:17:01', '2020-02-28 12:33:04'),
(7, '3', '8', 'store lcoator', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '1', '2020-02-28 12:44:13', NULL),
(9, '3', '', 'asd', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '1', '2020-02-29 06:49:36', NULL),
(10, '3', '', 'asdf', '', NULL, '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '1', '2020-02-29 06:50:45', NULL),
(38, '3', '', 'image testing 1', '', NULL, '', '[{\"name\":\"march-best-buy1-5.jpg\",\"type\":\"image\"},{\"name\":\"WiBSTpdkFVE\",\"type\":\"video-youtube\"},{\"name\":\"lm3-2.jpg\",\"type\":\"image\"},{\"name\":\"2205_Moleski-kitchen1.jpg\",\"type\":\"image\"}]', 'WiBSTpdkFVE', '', NULL, '', '', '', '', '', '', '', '', '', '', '', 'image', '', '', '[{\"name\":\"lm31-1.jpg\",\"type\":\"image\"}]', '', '0', '1', '2020-03-03 11:21:24', '2020-03-05 09:57:18'),
(37, '3', '', 'image testing 1', '', NULL, NULL, NULL, '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', 'image', '', '', 'OWT-Grey-3-24.jpg', '', '0', '1', '2020-03-03 11:19:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `store_category`
--

DROP TABLE IF EXISTS `store_category`;
CREATE TABLE IF NOT EXISTS `store_category` (
  `store_category_id` int(255) NOT NULL AUTO_INCREMENT,
  `user_detail_id` int(255) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `category_detail` varchar(255) DEFAULT NULL,
  `marker_type` varchar(20) DEFAULT NULL,
  `marker_icon` varchar(255) DEFAULT NULL,
  `marker_color` varchar(20) DEFAULT NULL,
  `marker_size` varchar(255) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `featured` varchar(10) DEFAULT NULL,
  `is_active` varchar(10) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  PRIMARY KEY (`store_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store_category`
--

INSERT INTO `store_category` (`store_category_id`, `user_detail_id`, `category_name`, `category_detail`, `marker_type`, `marker_icon`, `marker_color`, `marker_size`, `marker_image`, `featured`, `is_active`, `create_date`, `modify_date`) VALUES
(1, 3, 'hellow cateogry', 'details', 'icon', '', '#8A5252', '', '', '0', '1', '2020-02-15 09:58:59', '2020-02-29 07:35:17'),
(3, 3, 'cateogry one', 'this is category details,', 'icon', 'icon-marker', '#4F050B', '10', '', '1', '1', '2020-02-15 09:59:58', '2020-02-29 06:23:10'),
(23, 3, 'one one', '', '', '', '#000000', '', '', '0', '1', '2020-02-28 13:23:17', NULL),
(8, 3, 'location one', '', '', '', '#000000', '', '', '0', '1', '2020-02-19 13:05:03', '2020-02-28 12:18:56'),
(9, 3, 'asdasd', '', '', '', '#000000', '', '', '0', '1', '2020-02-26 10:12:26', '2020-02-28 12:11:35'),
(11, 3, 'invoice details', '', '', '', '#000000', '', '', '0', '1', '2020-02-26 10:12:39', '2020-02-28 12:11:20');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
