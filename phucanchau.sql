-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 07, 2025 lúc 08:32 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phucanchau`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(86, 8, 2, 1, '2025-06-01 05:03:57'),
(132, NULL, 26, 1, '2025-06-27 08:11:02'),
(133, NULL, 26, 1, '2025-06-27 08:11:08'),
(135, 12, 6, 1, '2025-06-27 09:21:15'),
(169, NULL, 1, 1, '2025-06-30 08:46:32'),
(180, 1, 14, 1, '2025-07-06 13:59:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `slug`) VALUES
(1, 'Thuốc', 'Chữa bệnh', 'thuoc'),
(2, 'Thiết Bị Y Tế', 'Thiết bị y tế', 'y-te'),
(3, 'Mỹ phẩm', 'Chăm sóc da ', 'my-pham'),
(4, 'Thực Phẩm Chức Năng', 'Tăng sức khỏe', 'tpcn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sender_type` enum('user','pharmacist') DEFAULT 'user',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `receiver_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `user_id`, `message`, `sender_type`, `sent_at`, `is_read`, `receiver_id`) VALUES
(77, 4, 'Tôi cảm thấy chóng mặt và buồn nôn, nên dùng thuốc gì vậy dược sĩ.', 'user', '2025-07-04 09:03:18', 1, 1),
(78, 1, 'Tôi khuyên bạn nên dùng panadol để thuyên giảm, còn trong trường hợp đã sử dụng thuốc nhưng vẫn còn các triệu chứng trên, thì bạn nên đến trung tâm y tế gần nhất để kịp thời xử lý.', 'pharmacist', '2025-07-04 09:05:52', 0, 4),
(79, 4, 'Chào Dược sĩ', 'user', '2025-07-07 06:21:11', 1, 1),
(80, 1, 'Chào bạn, Tôi có thể giúp gì cho bạn!', 'pharmacist', '2025-07-07 06:21:56', 0, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `product_id`, `comment`, `parent_id`, `created_at`) VALUES
(25, 4, 1, 'Hà Nội còn thuốc này ở cơ sở nào không?', NULL, '2025-05-31 20:32:21'),
(26, 1, 1, 'Chào anh Tu,\r\nDạ sản phẩm còn hàng trên hệ thống ạ.\r\nDạ sẽ có tư vấn viên nhà thuốc Phúc An Châu liên hệ theo SĐT chị đã để lại ạ.\r\nThân mến!', 25, '2025-05-31 20:33:16'),
(27, 8, 1, 'mẫu này còn ko? giá bao nhiêu 1 hộp', NULL, '2025-05-31 20:34:47'),
(28, 1, 1, 'Chào Anh Trí,\r\nDạ sản phẩm có giá 45,000 ₫/ Hộp và còn hàng trên hệ thống ạ.\r\nDạ sẽ có tư vấn viên nhà thuốc Phúc An Châu liên hệ theo SĐT anh đã để lại ạ.\r\nThân mến!', 27, '2025-05-31 20:35:26'),
(29, 8, 1, 'có giao thuốc qua cồn phụng xã long hòa ,châu thành trà vinh k ?', NULL, '2025-05-31 20:42:30'),
(30, 1, 1, 'Chào anh,\r\nDạ sẽ có tư vấn viên nhà thuốc Phúc An Châu liên hệ theo SĐT chị đã để lại ạ.\r\nThân mến', 29, '2025-05-31 20:43:02'),
(31, 4, 1, 'Sản phẩm còn hàng không ạ?', NULL, '2025-05-31 22:06:47'),
(32, 1, 1, 'Chào anh Tu,\r\nSản phẩm hiện vẫ còn hàng ở nhà thuốc Phúc An Châu trên toàn quốc ạ.\r\nThân mến!', 31, '2025-05-31 22:08:12'),
(33, 4, 1, 'Nhà thuốc uy tín, giao hàng nhanh.', NULL, '2025-05-31 22:30:18'),
(34, 4, 11, 'Sản phẩm tốt', NULL, '2025-06-05 06:47:36'),
(35, 1, 11, 'Cảm ơn bạn đã tin dùng sản phẩm từ nha thuốc Phúc Ân Châu', 34, '2025-06-05 06:48:38'),
(36, 4, 12, 'Sản phẩm tốt', NULL, '2025-06-06 07:53:14'),
(37, 2, 2, 'c', NULL, '2025-06-28 16:57:23'),
(38, 2, 2, 'ok', 37, '2025-06-28 16:57:38'),
(39, 4, 1, 'Sản Phẩm Tốt', NULL, '2025-06-29 06:20:51'),
(40, 4, 12, 'Sản phẩm chất lượng', NULL, '2025-06-29 07:14:11'),
(41, 1, 1, 'Cảm ơn bạn đã tin dùng sản phẩm từ nhà thuốc Phúc Ân Châu', 39, '2025-06-30 01:50:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `comment_id`, `user_id`, `liked_at`) VALUES
(3, 4, 4, '2025-05-31 12:44:13'),
(4, 15, 2, '2025-05-31 15:16:29'),
(5, 8, 2, '2025-05-31 15:16:32'),
(6, 16, 2, '2025-05-31 15:16:37'),
(7, 21, 2, '2025-05-31 15:37:00'),
(8, 20, 2, '2025-05-31 15:37:03'),
(9, 18, 1, '2025-05-31 16:48:13'),
(10, 31, 1, '2025-06-01 05:29:14'),
(11, 29, 1, '2025-06-01 05:29:28'),
(12, 33, 1, '2025-06-01 05:30:29'),
(13, 34, 1, '2025-06-05 13:48:44'),
(14, 37, 2, '2025-06-28 23:57:44'),
(15, 38, 2, '2025-06-28 23:57:47'),
(16, 33, 4, '2025-06-29 13:04:10'),
(17, 39, 4, '2025-06-29 13:21:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('processing','completed') NOT NULL DEFAULT 'processing',
  `payment_check_time` datetime DEFAULT NULL,
  `transaction_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `order_date`, `full_name`, `phone`, `email`, `address`, `payment_method`, `payment_status`, `payment_check_time`, `transaction_id`) VALUES
(26, 4, 55000.00, '2025-06-03 14:09:32', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 07:19:32', NULL),
(27, 4, 38000.00, '2025-06-03 14:09:52', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 07:19:52', NULL),
(28, 5, 100000.00, '2025-06-03 14:13:06', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 07:23:06', NULL),
(29, 5, 14000.00, '2025-06-03 14:20:08', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 07:30:08', NULL),
(30, 5, 20460.00, '2025-06-03 14:23:30', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 07:33:30', NULL),
(31, 5, 36100.00, '2025-06-03 14:25:37', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 07:35:37', NULL),
(32, 5, 45000.00, '2025-06-03 14:52:57', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'completed', '2025-06-03 08:02:57', NULL),
(33, 5, 36100.00, '2025-06-03 15:01:09', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 08:11:09', NULL),
(34, 5, 55000.00, '2025-06-03 15:04:21', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 08:14:21', NULL),
(35, 5, 45000.00, '2025-06-03 15:37:35', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 08:47:35', NULL),
(36, 5, 45000.00, '2025-06-03 15:39:15', 'Tấn Tú', '0933856281', 'nguyentantu612@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 08:49:15', NULL),
(37, 10, 38000.00, '2025-06-03 15:53:13', 'vuongvuong', '0328417187', 'thanhvuong123sss@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-03 09:03:13', NULL),
(38, 2, 38000.00, '2025-06-03 15:54:03', 'vuongvuong', '0328417187', 'thanhvuong123sss@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-03 09:04:03', NULL),
(39, 2, 38000.00, '2025-06-03 15:54:08', 'vuongvuong', '0328417187', 'thanhvuong123sss@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-03 09:04:08', NULL),
(40, 2, 4048000.00, '2025-06-03 19:10:51', 'Dược sĩ Vương', '0328417187', 'thanhvuong123sss@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-03 12:20:51', NULL),
(41, 4, 36100.00, '2025-06-04 06:04:59', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'completed', '2025-06-03 23:14:59', NULL),
(42, 4, 36100.00, '2025-06-04 06:06:16', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'completed', '2025-06-03 23:16:16', NULL),
(43, 4, 13300.00, '2025-06-04 06:06:39', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-03 23:16:39', NULL),
(44, 4, 90000.00, '2025-06-05 16:49:15', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-05 09:59:15', NULL),
(45, 4, 1619200.00, '2025-06-06 18:10:47', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-06 11:20:47', NULL),
(46, 4, 48400.00, '2025-06-07 06:17:13', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-06 23:27:13', NULL),
(47, 2, 999600.00, '2025-06-11 20:20:17', 'Dược sĩ Vương', '0328417187', 'thanhvuong123sss@gmail.com', '181 Biên Cương', 'atm', 'completed', '2025-06-11 13:30:17', NULL),
(48, 2, 171100.00, '2025-06-12 05:37:18', 'Dược sĩ Vương', '0328417187', 'nguyentantubt@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-11 22:47:18', NULL),
(49, 12, 145040.00, '2025-06-27 12:20:49', 'Nguyễn Thanh Vương', '0328417187', 'thu@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-27 05:30:49', NULL),
(50, 2, 1121900.00, '2025-06-27 13:51:27', 'Dược sĩ Vương', '0328417187', 'nguyentantubt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 07:01:27', NULL),
(51, 2, 809600.00, '2025-06-27 13:52:49', 'Dược sĩ Vương', '0328417187', 'nguyentantubt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 07:02:49', NULL),
(52, 2, 45000.00, '2025-06-27 13:54:50', 'Dược sĩ Vương', '0328417187', 'nguyentantu2005bt@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-27 07:04:50', NULL),
(53, 2, 36100.00, '2025-06-27 14:05:16', 'Dược sĩ Vương', '0777783837', 'nguyentantubt@gmail.com', 'xnxjcjc', 'qrcode', 'completed', '2025-06-27 07:15:16', NULL),
(54, 1, 1772750.00, '2025-06-27 18:59:06', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 12:09:06', NULL),
(55, 1, 809600.00, '2025-06-27 19:00:03', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 12:10:03', NULL),
(56, 1, 809600.00, '2025-06-27 19:02:46', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 12:12:46', NULL),
(57, 1, 809600.00, '2025-06-27 19:04:08', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 12:14:08', NULL),
(58, 1, 809600.00, '2025-06-27 19:04:51', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 12:14:51', NULL),
(59, 1, 809600.00, '2025-06-27 19:05:21', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 12:15:21', NULL),
(60, 4, 1084600.00, '2025-06-28 05:46:37', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-27 22:56:37', NULL),
(61, 4, 899600.00, '2025-06-28 07:22:12', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-28 00:32:12', NULL),
(62, 2, 196650.00, '2025-06-29 02:59:14', 'Dược sĩ Vương', '0987678456', 'vuongnt1090@ut.edu.vn', '1233', 'qrcode', 'completed', '2025-06-28 20:09:14', NULL),
(63, 2, 3573900.00, '2025-06-29 12:55:22', 'Dược sĩ Vương', '0328417187', 'thanhvuong123sss@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-29 06:05:22', NULL),
(64, 2, 80150400.00, '2025-06-29 12:58:01', 'Dược sĩ Vương', '0328417187', 'nhue6995@gmail.com', '181 Biên Cương', 'qrcode', 'completed', '2025-06-29 06:08:01', NULL),
(65, 4, 45000.00, '2025-06-29 15:48:03', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-29 08:58:03', NULL),
(66, 4, 45000.00, '2025-06-29 15:48:44', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-29 08:58:44', NULL),
(67, 4, 45000.00, '2025-06-29 16:04:53', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'processing', '2025-06-29 09:14:53', NULL),
(68, 4, 19000.00, '2025-06-30 10:46:04', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-30 03:56:04', NULL),
(69, 1, 64000.00, '2025-06-30 10:59:44', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-30 04:09:44', NULL),
(70, 1, 64000.00, '2025-06-30 11:02:51', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-30 04:12:51', NULL),
(71, 1, 64000.00, '2025-06-30 11:03:14', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-30 04:13:14', NULL),
(72, 1, 809600.00, '2025-06-30 11:03:53', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-30 04:13:53', NULL),
(73, 1, 809600.00, '2025-06-30 11:04:55', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'processing', '2025-06-30 04:14:55', NULL),
(74, 1, 809600.00, '2025-06-30 11:05:25', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'processing', '2025-06-30 04:15:25', NULL),
(75, 1, 809600.00, '2025-06-30 11:05:53', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-30 04:15:53', NULL),
(76, 1, 13300.00, '2025-06-30 11:09:05', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'atm', 'processing', '2025-06-30 04:19:05', NULL),
(77, 1, 13300.00, '2025-06-30 11:11:38', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'money', 'processing', '2025-06-30 04:21:38', NULL),
(78, 1, 19000.00, '2025-06-30 11:24:53', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'money', 'processing', '2025-06-30 04:34:53', NULL),
(79, 1, 19000.00, '2025-06-30 11:25:29', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'money', 'processing', '2025-06-30 04:35:29', NULL),
(80, 1, 45000.00, '2025-06-30 11:25:50', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'money', 'processing', '2025-06-30 04:35:50', NULL),
(81, 1, 45000.00, '2025-06-30 11:27:46', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'money', 'processing', '2025-06-30 04:37:46', NULL),
(82, 1, 9000.00, '2025-06-30 11:28:07', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'money', 'processing', '2025-06-30 04:38:07', NULL),
(83, 1, 45000.00, '2025-06-30 11:28:39', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-06-30 04:38:39', NULL),
(84, 1, 13300.00, '2025-06-30 11:30:44', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'cash', 'processing', '2025-06-30 04:40:44', NULL),
(85, 1, 13300.00, '2025-06-30 11:32:08', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'cash', 'processing', '2025-06-30 04:42:08', NULL),
(86, 1, 809600.00, '2025-06-30 11:33:43', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'cash', 'processing', '2025-06-30 04:43:43', NULL),
(87, 1, 36100.00, '2025-06-30 11:34:24', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'cash', 'processing', '2025-06-30 04:44:24', NULL),
(88, 1, 36100.00, '2025-06-30 11:35:31', 'Dược sĩ Tú', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'cash', 'processing', '2025-06-30 04:45:31', NULL),
(89, 4, 183000.00, '2025-07-04 10:30:04', 'Tu', '0328400600', 'nguyentantu2005bt@gmail.com', '123aaa', 'qrcode', 'completed', '2025-07-04 03:40:04', NULL),
(90, 4, 183000.00, '2025-07-04 10:30:34', 'Tu', '0328400600', 'nguyentantu2005bt@gmail.com', '123aaa', 'qrcode', 'completed', '2025-07-04 03:40:34', NULL),
(91, 4, 90000.00, '2025-07-04 10:33:23', 'Tu', '0328400699', 'nguyentantu2005bt@gmail.com', '633dfueged', 'qrcode', 'completed', '2025-07-04 03:43:23', NULL),
(92, 4, 90000.00, '2025-07-04 10:34:31', 'Tu', '0328400699', 'nguyentantu2005bt@gmail.com', '633dfueged', 'qrcode', 'processing', '2025-07-04 03:44:31', NULL),
(93, 4, 45000.00, '2025-07-04 10:36:28', 'Tu', '0328400600', 'nguyentantu2005bt@gmail.com', 'jj', 'qrcode', 'completed', '2025-07-04 03:46:28', NULL),
(94, 4, 45000.00, '2025-07-04 10:39:31', 'Tu', '0335863294', 'nguyentantu2005bt@gmail.com', '111', 'qrcode', 'completed', '2025-07-04 03:49:31', NULL),
(95, 4, 45000.00, '2025-07-04 10:54:03', 'Tu', '0335863294', 'nguyentantu2005bt@gmail.com', '111', 'qrcode', 'completed', '2025-07-04 04:04:03', NULL),
(96, 4, 1016500.00, '2025-07-07 01:19:00', 'Tu', '0933856281', 'nguyentantu2005bt@gmail.com', 'TRUONG DAI HOC GTVT TP HCM', 'qrcode', 'completed', '2025-07-07 08:29:00', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 4, 1, 65550.00),
(2, 2, 3, 1, 55000.00),
(3, 3, 27, 1, 114000.00),
(4, 4, 2, 1, 36100.00),
(5, 5, 7, 1, 12000.00),
(6, 5, 3, 1, 55000.00),
(7, 6, 7, 1, 12000.00),
(8, 6, 3, 1, 55000.00),
(9, 7, 3, 1, 55000.00),
(10, 8, 3, 1, 55000.00),
(11, 9, 3, 1, 55000.00),
(12, 10, 4, 1, 65550.00),
(13, 11, 1, 1, 4500.00),
(14, 12, 1, 1, 4500.00),
(15, 13, 2, 1, 36100.00),
(16, 14, 3, 1, 55000.00),
(17, 15, 3, 1, 55000.00),
(18, 16, 4, 1, 65550.00),
(19, 17, 1, 1, 4500.00),
(20, 18, 2, 1, 36100.00),
(21, 19, 3, 1, 55000.00),
(22, 19, 1, 2, 45000.00),
(23, 20, 3, 1, 55000.00),
(24, 21, 1, 1, 45000.00),
(25, 23, 2, 1, 36100.00),
(26, 23, 38, 1, 288000.00),
(27, 24, 1, 1, 45000.00),
(28, 25, 1, 1, 45000.00),
(29, 25, 4, 1, 65550.00),
(30, 26, 3, 1, 55000.00),
(31, 27, 2, 1, 38000.00),
(32, 28, 1, 2, 50000.00),
(33, 29, 10, 1, 14000.00),
(34, 30, 9, 1, 20460.00),
(35, 31, 2, 1, 36100.00),
(36, 32, 1, 1, 45000.00),
(37, 33, 2, 1, 36100.00),
(38, 34, 3, 1, 55000.00),
(39, 35, 1, 1, 45000.00),
(40, 36, 1, 1, 45000.00),
(41, 37, 6, 2, 19000.00),
(42, 38, 6, 2, 19000.00),
(43, 39, 6, 2, 19000.00),
(44, 40, 26, 5, 809600.00),
(45, 41, 2, 1, 36100.00),
(46, 42, 2, 1, 36100.00),
(47, 43, 10, 1, 13300.00),
(48, 44, 11, 2, 45000.00),
(49, 45, 26, 2, 809600.00),
(50, 46, 3, 1, 48400.00),
(51, 47, 14, 1, 9500.00),
(52, 47, 2, 5, 36100.00),
(53, 47, 26, 1, 809600.00),
(54, 48, 2, 1, 36100.00),
(55, 48, 1, 3, 45000.00),
(56, 49, 1, 1, 45000.00),
(57, 49, 2, 2, 36100.00),
(58, 49, 16, 1, 27840.00),
(59, 50, 5, 1, 69000.00),
(60, 50, 2, 3, 36100.00),
(61, 50, 1, 3, 45000.00),
(62, 50, 26, 1, 809600.00),
(63, 51, 26, 1, 809600.00),
(64, 52, 1, 1, 45000.00),
(65, 53, 2, 1, 36100.00),
(66, 54, 6, 1, 19000.00),
(67, 54, 4, 1, 65550.00),
(68, 54, 26, 2, 809600.00),
(69, 54, 5, 1, 69000.00),
(70, 55, 26, 1, 809600.00),
(71, 56, 26, 1, 809600.00),
(72, 57, 26, 1, 809600.00),
(73, 58, 26, 1, 809600.00),
(74, 59, 26, 1, 809600.00),
(75, 60, 6, 5, 19000.00),
(76, 60, 11, 3, 45000.00),
(77, 60, 26, 1, 809600.00),
(78, 60, 1, 1, 45000.00),
(79, 61, 1, 2, 45000.00),
(80, 61, 26, 1, 809600.00),
(81, 62, 4, 3, 65550.00),
(82, 63, 2, 99, 36100.00),
(83, 64, 26, 99, 809600.00),
(84, 65, 1, 1, 45000.00),
(85, 66, 1, 1, 45000.00),
(86, 67, 1, 1, 45000.00),
(87, 68, 6, 1, 19000.00),
(88, 69, 1, 1, 45000.00),
(89, 69, 6, 1, 19000.00),
(90, 70, 1, 1, 45000.00),
(91, 70, 6, 1, 19000.00),
(92, 71, 1, 1, 45000.00),
(93, 71, 6, 1, 19000.00),
(94, 72, 26, 1, 809600.00),
(95, 73, 26, 1, 809600.00),
(96, 74, 26, 1, 809600.00),
(97, 75, 26, 1, 809600.00),
(98, 76, 10, 1, 13300.00),
(99, 77, 10, 1, 13300.00),
(100, 78, 6, 1, 19000.00),
(101, 79, 6, 1, 19000.00),
(102, 80, 1, 1, 45000.00),
(103, 81, 1, 1, 45000.00),
(104, 82, 7, 1, 9000.00),
(105, 83, 11, 1, 45000.00),
(106, 84, 10, 1, 13300.00),
(107, 85, 10, 1, 13300.00),
(108, 86, 26, 1, 809600.00),
(109, 87, 2, 1, 36100.00),
(110, 88, 2, 1, 36100.00),
(111, 89, 1, 1, 45000.00),
(112, 89, 5, 2, 69000.00),
(113, 90, 1, 1, 45000.00),
(114, 90, 5, 2, 69000.00),
(115, 91, 1, 2, 45000.00),
(116, 92, 1, 2, 45000.00),
(117, 93, 1, 1, 45000.00),
(118, 94, 1, 1, 45000.00),
(119, 95, 1, 1, 45000.00),
(120, 96, 26, 1, 809600.00),
(121, 96, 37, 1, 69000.00),
(122, 96, 35, 1, 37800.00),
(123, 96, 1, 1, 45000.00),
(124, 96, 2, 1, 36100.00),
(125, 96, 6, 1, 19000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sold` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `brand` varchar(100) DEFAULT NULL,
  `ingredient` text DEFAULT NULL,
  `uses` text DEFAULT NULL,
  `usage` text DEFAULT NULL,
  `side_effects` text DEFAULT NULL,
  `warning` text DEFAULT NULL,
  `storage` text DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `final_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `description`, `price`, `image`, `quantity`, `created_at`, `sold`, `views`, `brand`, `ingredient`, `uses`, `usage`, `side_effects`, `warning`, `storage`, `discount`, `final_price`) VALUES
(1, 'Paracetamol giảm đau hạ sốt 500 mg', 1, 'Paracetamol 500mg Quapharco có quy cách đóng gói gồm hộp 10 vỉ x 10 viên nén chứa paracetamol 500 mg do công ty cổ phần dược phẩm Quảng Bình sản xuất. Paracetamol 500 mg Quảng Bình được dùng để điều trị triệu chứng hạ sốt, giảm đau nhẹ và vừa trong các trường hợp: Sốt, nhức đầu, đau nửa đầu, cảm lạnh, cảm cúm, đau họng, đau răng, đau nhức xương, đau do hành kinh, đau do viêm khớp.', 50000.00, 'Paracetamol.jpg', 100, '2025-05-21 04:23:12', 50, 267, 'Dược Hậu Giang', 'Công dụng của Thuốc Paracetamol 500mg\r\nChỉ định\r\nThuốc Paracetamol 500 mg được chỉ định dùng trong các trường hợp sau:\r\n\r\nÐiều trị triệu chứng hạ sốt, giảm đau nhẹ và vừa trong các trường hợp: Sốt, nhức đầu, đau nửa đầu, cảm lạnh, cảm cúm, đau họng, đau răng, đau nhức xương, đau do hành kinh, đau do viêm khớp.\r\n\r\nDược lực học\r\nParacetamol (acetaminophen hay N - acetyl - p - aminophenol) là chất chuyển hóa có hoạt tính của phenacetin, là thuốc giảm đau - hạ sốt hữu hiệu có thể thay thế aspirin. Tuy vậy, khác với aspirin, paracetamol không có hiệu quả điều trị viêm.\r\n\r\nVới liều ngang nhau tính theo gam, paracetamol có tác dụng giảm đau và hạ sốt tương tự như aspirin. Paracetamol làm giảm thân nhiệt ở người bệnh sốt, nhưng hiếm khi làm giảm thân nhiệt ở người bình thường. Thuốc tác động lên vùng dưới đồi gây hạ nhiệt, tỏa nhiệt tăng do giãn mạch và tăng lưu lượng máu ngoại biên.\r\n\r\nParacetamol, với liều điều trị, ít tác động đến hệ tim mạch và hô hấp, không làm thay đổi cân bằng acid - base, không gây kích ứng, xước hoặc chảy máu dạ dày như khi dùng salicylat, vì paracetamol không tác dụng trên cyclooxygenase toàn thân, chỉ tác động đến cyclooxygenase/prostaglandin của hệ thần kinh trung ương.\r\n\r\nParacetamol không có tác dụng trên tiểu cầu hoặc thời gian chảy máu. Khi dùng quá liều paracetamol một chất chuyển hóa là N - acetyl - benzoquinonimin gây độc nặng cho gan.\r\n\r\nDược động học\r\nHấp thu\r\n\r\nParacetamol được hấp thu nhanh chóng và hầu như hoàn toàn qua đường tiêu hóa. Thức ăn có thể làm viên nén giải phóng kéo dài paracetamol chậm được hấp thu một phần và thức ăn giàu carbohydrate làm giảm tỷ lệ hấp thu của paracetamol. Nồng độ đỉnh trong huyết tương đạt trong vòng 30 đến 60 phút sau khi uống với liều điều trị.\r\n\r\nPhân bố\r\n\r\nParacetamol phân bố nhanh và đồng đều trong phần lớn các mô của cơ thể. Khoảng 25% paracetamol trong máu kết hợp với protein huyết tương.\r\n\r\nChuyển hóa và thải trừ\r\n\r\nNửa đời huyết tương của paracetamol là 1,25 - 3 giờ, có thể kéo dài với liều gây độc hoặc ở người bệnh có thương tổn gan. Sau liều điều trị, có thể tìm thấy 90 đến 100% thuốc trong nước tiểu trong ngày thứ nhất, chủ yếu sau khi liên hợp trong gan với acid glucuronic (khoảng 60%), acid sulfuric (khoảng 35%) hoặc cystein (khoảng 3%); cũng phát hiện thấy một lượng nhỏ những chất chuyển hóa hydroxyl - hoá và khử acetyl. Trẻ nhỏ ít khả năng glucuro liên hợp với thuốc hơn so với người lớn.\r\n\r\nParacetamol bị N - hydroxyl hóa bởi cytochrom P để tạo nên N - acetyl - benzoquinonimin, một chất trung gian có tính phản ứng cao. Chất chuyển hóa này bình thường phản ứng với các nhóm sulfhydryl trong glutathion và bị khử hoạt tính.\r\n\r\nTuy nhiên, nếu uống liều cao paracetamol, chất chuyển hóa này được tạo thành với lượng đủ để làm cạn kiệt glutathion của gan; trong tình trạng đó, phản ứng của nó với nhóm sulfhydry của protein gan tăng lên, có thể dẫn đến hoại tử gan.', 'Công dụng của Thuốc Paracetamol 500mg\r\nChỉ định\r\nThuốc Paracetamol 500 mg được chỉ định dùng trong các trường hợp sau:\r\n\r\nÐiều trị triệu chứng hạ sốt, giảm đau nhẹ và vừa trong các trường hợp: Sốt, nhức đầu, đau nửa đầu, cảm lạnh, cảm cúm, đau họng, đau răng, đau nhức xương, đau do hành kinh, đau do viêm khớp.\r\n\r\nDược lực học\r\nParacetamol (acetaminophen hay N - acetyl - p - aminophenol) là chất chuyển hóa có hoạt tính của phenacetin, là thuốc giảm đau - hạ sốt hữu hiệu có thể thay thế aspirin. Tuy vậy, khác với aspirin, paracetamol không có hiệu quả điều trị viêm.\r\n\r\nVới liều ngang nhau tính theo gam, paracetamol có tác dụng giảm đau và hạ sốt tương tự như aspirin. Paracetamol làm giảm thân nhiệt ở người bệnh sốt, nhưng hiếm khi làm giảm thân nhiệt ở người bình thường. Thuốc tác động lên vùng dưới đồi gây hạ nhiệt, tỏa nhiệt tăng do giãn mạch và tăng lưu lượng máu ngoại biên.\r\n\r\nParacetamol, với liều điều trị, ít tác động đến hệ tim mạch và hô hấp, không làm thay đổi cân bằng acid - base, không gây kích ứng, xước hoặc chảy máu dạ dày như khi dùng salicylat, vì paracetamol không tác dụng trên cyclooxygenase toàn thân, chỉ tác động đến cyclooxygenase/prostaglandin của hệ thần kinh trung ương.\r\n\r\nParacetamol không có tác dụng trên tiểu cầu hoặc thời gian chảy máu. Khi dùng quá liều paracetamol một chất chuyển hóa là N - acetyl - benzoquinonimin gây độc nặng cho gan.\r\n\r\nDược động học\r\nHấp thu\r\n\r\nParacetamol được hấp thu nhanh chóng và hầu như hoàn toàn qua đường tiêu hóa. Thức ăn có thể làm viên nén giải phóng kéo dài paracetamol chậm được hấp thu một phần và thức ăn giàu carbohydrate làm giảm tỷ lệ hấp thu của paracetamol. Nồng độ đỉnh trong huyết tương đạt trong vòng 30 đến 60 phút sau khi uống với liều điều trị.\r\n\r\nPhân bố\r\n\r\nParacetamol phân bố nhanh và đồng đều trong phần lớn các mô của cơ thể. Khoảng 25% paracetamol trong máu kết hợp với protein huyết tương.\r\n\r\nChuyển hóa và thải trừ\r\n\r\nNửa đời huyết tương của paracetamol là 1,25 - 3 giờ, có thể kéo dài với liều gây độc hoặc ở người bệnh có thương tổn gan. Sau liều điều trị, có thể tìm thấy 90 đến 100% thuốc trong nước tiểu trong ngày thứ nhất, chủ yếu sau khi liên hợp trong gan với acid glucuronic (khoảng 60%), acid sulfuric (khoảng 35%) hoặc cystein (khoảng 3%); cũng phát hiện thấy một lượng nhỏ những chất chuyển hóa hydroxyl - hoá và khử acetyl. Trẻ nhỏ ít khả năng glucuro liên hợp với thuốc hơn so với người lớn.\r\n\r\nParacetamol bị N - hydroxyl hóa bởi cytochrom P để tạo nên N - acetyl - benzoquinonimin, một chất trung gian có tính phản ứng cao. Chất chuyển hóa này bình thường phản ứng với các nhóm sulfhydryl trong glutathion và bị khử hoạt tính.\r\n\r\nTuy nhiên, nếu uống liều cao paracetamol, chất chuyển hóa này được tạo thành với lượng đủ để làm cạn kiệt glutathion của gan; trong tình trạng đó, phản ứng của nó với nhóm sulfhydry của protein gan tăng lên, có thể dẫn đến hoại tử gan.', 'Cách dùng Thuốc Paracetamol 500mg\r\nCách dùng\r\nDùng đường uống.\r\n\r\nLiều dùng\r\nNgười lớn và trẻ em trên 12 tuổi: Uống mỗi lần 1 - 2 viên, khoảng thời gian đưa thuốc 4 - 6 giờ một lần. Uống tối đa 1 g/lần, 4 g/24 giờ.\r\n\r\nTrẻ em dưới 12 tuổi: Không khuyên dùng do dạng bào chế và hàm lượng của chế phẩm không phù hợp với liều lượng của trẻ em dưới 12 tuổi.\r\n\r\nLưu ý: Liều dùng trên chỉ mang tính chất tham khảo. Liều dùng cụ thể tùy thuộc vào thể trạng và mức độ diễn tiến của bệnh. Để có liều dùng phù hợp, bạn cần tham khảo ý kiến bác sĩ hoặc chuyên viên y tế.\r\n\r\nLàm gì khi dùng quá liều?\r\nRửa dạ dày tốt nhất trong vòng 4 giờ sau khi uống. Sử dụng N - acetylcystein uống hoặc tiêm tĩnh mạch, nếu không có N - acetyl cystein có thể dùng Methin thể dùng than hoạt hoặc thuốc tẩy muối, chúng làm giảm hấp thụ paracetamol.\r\n\r\nLàm gì khi quên 1 liều?\r\nBổ sung liều ngay khi nhớ ra. Tuy nhiên, nếu thời gian giãn cách với liều tiếp theo quá ngắn thì bỏ qua liều đã quên và tiếp tục lịch dùng thuốc. Không dùng liều gấp đôi để bù cho liều đã bị bỏ lỡ.', 'Tác dụng phụ\r\nKhi sử dụng thuốc Paracetamol 500mg Quapharco, bạn có thể gặp các tác dụng không mong muốn (ADR).\r\n\r\nBan da và những phản ứng dị ứng khác thỉnh thoảng xảy ra. Thường là ban đỏ hoặc mày đay, nhưng đôi khi nặng hơn và có thể kèm theo sốt do thuốc và thương tổn niêm mạc. Trong một số ít trường hợp riêng lẻ, paracetamol đã gây giảm bạch cầu trung tính, giảm tiểu cầu và giảm toàn thể huyết cầu.\r\n\r\nÍt gặp, 1/1000 < ADR < 1/100\r\n\r\nBan, buồn nôn, nôn, loạn tạo máu, thiếu máu, bệnh thận, độc tính lên thận khi lạm dùng nhiều ngày.\r\n\r\nHướng dẫn cách xử trí ADR\r\n\r\nKhi gặp tác dụng phụ của thuốc, cần ngưng sử dụng và thông báo cho bác sĩ hoặc đến cơ sở y tế gần nhất để được xử trí kịp thời.', 'Lưu ý\r\nTrước khi sử dụng thuốc bạn cần đọc kỹ hướng dẫn sử dụng và tham khảo thông tin bên dưới.\r\n\r\nChống chỉ định\r\nThuốc Paracetamol 500mg Quapharco chống chỉ định trong các trường hợp sau:\r\n\r\nNgười bệnh nhiều lần thiếu máu hoặc có bệnh tim, phổi, thận, hoặc gan.\r\n\r\nNgười bệnh quá mẫn với paracetamol.\r\n\r\nNgười bệnh thiếu hụt G6PD (Glucose - 6 - Phosphat Dehydrogenase).\r\n\r\nThận trọng khi sử dụng\r\nNgười bị suy thận nặng, người bị bệnh thiếu máu.\r\n\r\nTránh hoặc hạn chế uống rượu khi sử dụng thuốc.\r\n\r\nKhả năng lái xe và vận hành máy móc\r\nKhông ảnh hưởng.\r\n\r\nThời kỳ mang thai\r\nChỉ nên dùng Paracetamol ở người mang thai khi thật cần thiết.\r\n\r\nThời kỳ cho con bú\r\nKhông thấy có tác dụng không mong muốn ở trẻ nhỏ bú mẹ khi người mẹ dùng paracetamol. Tuy nhiên chỉ nên dùng paracetamol ở phụ nữ cho con bú khi thật cần thiết.\r\n\r\nTương tác thuốc\r\nUống dài ngày liều cao làm tăng nhẹ tác dụng chống đông của coumarin và dẫn chất indandion.\r\n\r\nCần phải chú ý đến khả năng gây hạ sốt nghiêm trọng ở người bệnh dùng đồng thời phenothiazin và liệu pháp hạ nhiệt.\r\n\r\nUống rượu nhiều, dùng thuốc chống co giật (phenytoin, barbiturat, carbamazepin), izoniazid làm tăng nguy cơ paracetamol gây độc cho gan.', 'Bảo quản\r\nNơi khô ráo, tránh ánh sáng, nhiệt độ không quá 30oC.\r\n\r\n', 10.00, 45000.00),
(2, 'Thuốc Vitamin C 500mg SPHARM', 4, 'Thuốc Vitamin C 500mg là sản phẩm của S.PHARM, thành phần chính Acid Ascorbic. Thuốc dùng để điều trị bệnh do thiếu Vitamin C (Scorbut); hỗ trợ các dấu hiệu về tàn nhang, đốm, nám do phát ban cháy nắng; phòng ngừa chảy máu như chảy máu từ nướu răng, chảy máu mũi, bổ sung vitamin C trong các trường hợp như mệt mỏi, trong thời kỳ mang thai và cho con bú, suy giảm thể lực sau ốm, người cao tuổi.', 38000.00, 'Vitamin C.jpg', 200, '2025-05-21 04:23:12', 138, 180, 'Dược Phẩm ABC', 'Acid Ascorbic\r\n\r\n500mg\r\n\r\n', 'Công dụng của Thuốc Vitamin C 500mg\r\nChỉ định\r\nThuốc Vitamin C 500mg được chỉ định dùng trong các trường hợp sau:\r\n\r\nĐiều trị bệnh do thiếu Vitamin C (Scorbut).\r\nHỗ trợ các dấu hiệu về tàn nhang, đốm, nám do phát ban cháy nắng.\r\nPhòng ngừa chảy máu như chảy máu từ nướu răng, chảy máu mũi.\r\nBổ sung vitamin C trong các trường hợp như: Mệt mỏi, trong thời kỳ mang thai và cho con bú, suy giảm thể lực sau ốm, người cao tuổi.\r\nDược lực học\r\nNhóm dược lý: Vitamin C.\r\n\r\nVitamin C cần cho sự tạo thành colagen, tu sửa mô trong cơ thể và tham gia trong một số phản ứng oxy hóa - khử. Vitamin C tham gia trong chuyển hóa phenylalanin, tyrosin, acid folic, norepinephrin, histamin, sắt, và một số hệ thống enzym chuyển hóa thuốc, trong sử dụng carbohydrat, trong tổng hợp lipid và protein, trong chức năng miễn dịch, trong đề kháng với nhiễm khuẩn, trong giữ gìn sự toàn vẹn của mạch máu và trong hô hấp tế bào.\r\n\r\nDược động học\r\nHấp thu:\r\n\r\nVitamin C được hấp thu dễ dàng sau khi uống; tuy vậy, hấp thu là một quá trình tích cực và có thể bị hạn chế sau những liều rất lớn. Trong nghiên cứu trên người bình thường, chỉ có 50% của một liều uống 1,5 g vitamin C được hấp thu. Hấp thu vitamin C ở dạ dày - ruột có thể giảm ở người ỉa chảy hoặc có bệnh về dạ dày - ruột.\r\n\r\nNồng độ vitamin C bình thường trong huyết tương ở khoảng 10 - 20 microgam/ml. Dự trữ toàn bộ vitamin C trong cơ thể ước tính khoảng 1,5 g với khoảng 30 - 45 mg được luân chuyển hàng ngày. Dấu hiệu lâm sàng của bệnh Scorbut thường trở nên rõ ràng sau 3 - 5 tháng thiếu hụt vitamin C.\r\n\r\nPhân bố:\r\n\r\nVitamin C phân bố rộng rãi trong các mô cơ thể. Khoảng 25% vitamin C trong huyết tương kết hợp với protein.\r\n\r\nChuyển hóa và thải trừ:\r\n\r\nVitamin C oxy - hóa thuận nghịch thành acid dehydroascorbic. Một ít vitamin C chuyển hóa thành những hợp chất không có hoạt tính gồm ascorbic acid-2-sulfat và acid oxalic được bài tiết trong nước tiểu. Lượng vitamin C vượt quá nhu cầu của cơ thể cũng được nhanh chóng đào thải ra nước tiểu dưới dạng không biến đổi. Điều này thường xảy ra khi lượng vitamin C nhập hàng ngày vượt quá 200 mg.', 'Cách dùng Thuốc Vitamin C 500mg\r\nCách dùng\r\nThuốc dùng đường uống, nên uống nguyên viên, uống với nhiều nước.\r\n\r\nLiều dùng\r\nNgười cao tuổi: Uống 1 viên mỗi ngày.\r\n\r\nNgười lớn và thanh thiếu niên từ 15 tuổi trở lên: Uống 2 viên/ngày, chia làm 2 lần.\r\n\r\nTrẻ em từ 7 đến 15 tuổi: Uống 1 viên/lần/ngày.\r\n\r\nTrẻ em dưới 7 tuổi: Không khuyến cáo sử dụng do dạng bào chế viên nang cứng không thích hợp cho các đối tượng này.\r\n\r\nLưu ý: Liều dùng trên chỉ mang tính chất tham khảo. Liều dùng cụ thể tùy thuộc vào thể trạng và mức độ diễn tiến của bệnh. Để có liều dùng phù hợp, bạn cần tham khảo ý kiến bác sĩ hoặc chuyên viên y tế.\r\n\r\nLàm gì khi dùng quá liều?\r\nTriệu chứng quá liều: Những triệu chứng quá liều gồm sỏi thận, buồn nôn, viêm dạ dày và tiêu chảy.\r\n\r\nXử trí: Trong trường hợp quá liều, bệnh nhân nên ngưng dùng thuốc và thông báo ngay cho bác sỹ để được theo dõi và điều trị triệu chứng. Gây lợi tiểu bằng truyền dịch có thể có tác dụng sau khi uống liều lớn.\r\n\r\nTrong trường hợp khẩn cấp, hãy gọi ngay cho Trung tâm cấp cứu 115 hoặc đến trạm Y tế địa phương gần nhất.\r\n\r\nLàm gì khi quên 1 liều?\r\nBổ sung liều ngay khi nhớ ra. Tuy nhiên, nếu thời gian giãn cách với liều tiếp theo quá ngắn thì bỏ qua liều đã quên và tiếp tục lịch dùng thuốc. Không dùng liều gấp đôi để bù cho liều đã bị bỏ lỡ.', 'Tác dụng phụ\r\nKhi sử dụng thuốc thường gặp các tác dụng không mong muốn (ADR) như:\r\n\r\nTăng oxalat - niệu, buồn nôn, nôn, ợ nóng, co cứng cơ bụng, mệt mỏi, đỏ bừng, nhức đầu, mất ngủ, và tình trạng buồn ngủ đã xảy ra. Sau khi uống liều 1g hàng ngày hoặc lớn hơn, có thể xảy ra ỉa chảy.\r\n\r\nThường gặp, ADR>1/100:\r\n\r\nThận: Tăng oxalat niệu.\r\nÍt gặp, 1/1000<ADR<1/100:\r\n\r\nMáu: Thiếu máu tan máu.\r\nTim mạch: Bừng đỏ, suy tim.\r\nThần kinh trung ương: Xỉu, chóng mặt, nhức đầu, mệt mỏi.\r\nDạ dày - ruột: Buồn nôn, nôn, ợ nóng, ỉa chảy.\r\nThần kinh - cơ và xương: Đau cạnh sườn.\r\nHướng dẫn cách xử trí ADR:\r\n\r\nThông báo cho thầy thuốc các tác dụng không mong muốn gặp phải khi sử dụng thuốc.', 'Lưu ý\r\nTrước khi sử dụng thuốc bạn cần đọc kỹ hướng dẫn sử dụng và tham khảo thông tin bên dưới.\r\n\r\nChống chỉ định\r\nThuốc Vitamin C 500mg chống chỉ định trong các trường hợp sau:\r\n\r\nQuá mẫn với vitamin C hoặc bất kỳ thành phần nào của thuốc.\r\nNgười bị thiếu hụt glucose-6-phosphat dehydrogenase (G6PD) (nguy cơ thiếu máu huyết tán).\r\nNgười có tiền sử sỏi thận, tăng oxalat niệu và loạn chuyển hóa oxalat (tăng nguy cơ sỏi thận), bị bệnh thalassemia (tăng nguy cơ hấp thu sắt).\r\nThận trọng khi sử dụng\r\nCác tình trạng cần thận trọng:\r\n\r\nDùng kéo dài có thể dẫn đến hiện tượng nhờn thuốc, do đó khi giảm liều sẽ dẫn đến thiếu hụt vitamin C.\r\nDo thuốc chứa hàm lượng vitamin C cao nên khi uống thuốc có thể gây tăng oxalat niệu, acid - hóa nước tiểu, đôi khi dẫn đến kết tủa urat hoặc cystin, hoặc sỏi oxalat, hoặc thuốc trong đường tiết niệu.\r\nNgười bệnh thiếu hụt glucose-6-phosphat dehydrogenase khi dùng thuốc có thể bị chứng tan máu. Có thể xảy ra huyết khối tĩnh mạch sâu. Do đó, chống chỉ định sử dụng thuốc cho các bệnh nhân này.\r\nKhuyến cáo về tá dược:\r\n\r\nThuốc có chứa tá dược là tinh bột. Bệnh nhân dị ứng với tinh bột mì thì không nên dùng thuốc này. Nhưng sử dụng được cho người bị bệnh dị ứng với gluten (thành phần nhỏ có trong tinh bột mì).\r\n\r\nẢnh hưởng của thuốc lên khả năng lái xe và vận hành máy móc\r\nChưa có thông tin về ảnh hưởng của thuốc đến khả năng lái xe và vận hành máy móc, tuy nhiên cần lưu ý tác dụng phụ gây nhức đầu, buồn ngủ của thuốc.\r\n\r\nSử dụng thuốc cho phụ nữ trong thời kỳ mang thai và cho con bú\r\nSử dụng thuốc cho phụ nữ có thai\r\n\r\nVitamin C đi qua nhau thai. Chưa có các nghiên cứu cả trên súc vật và trên người mang thai, và nếu dùng vitamin C theo nhu cầu bình thường hàng ngày thì chưa thấy xảy ra vấn đề gì trên người.\r\n\r\nTuy nhiên, uống thuốc này trong khi mang thai có thể làm tăng nhu cầu về vitamin C và dẫn đến bệnh Scorbut ở trẻ sơ sinh.\r\n\r\nSử dụng thuốc cho phụ nữ cho con bú\r\n\r\nVitamin C phân bố trong sữa mẹ. Người cho con bú dùng vitamin C theo nhu cầu bình thường, chưa thấy có vấn đề gì xảy ra đối với trẻ sơ sinh.\r\n\r\nTương tác thuốc\r\nDùng đồng thời theo tỷ lệ trên 200 mg vitamin C với 30 mg sắt nguyên tố làm tăng hấp thu sắt qua đường dạ dày - ruột; tuy vậy, đa số người bệnh đều có khả năng hấp thu sắt uống vào một cách đầy đủ mà không phải dùng đồng thời vitamin C.\r\n\r\nDùng đồng thời vitamin C với aspirin làm tăng bài tiết vitamin C và giảm bài tiết aspirin trong nước tiểu.\r\n\r\nDùng đồng thời vitamin C và fluphenazin dẫn đến giảm nồng độ fluphenazin huyết tương. Sự acid - hóa nước tiểu sau khi dùng vitamin C có thể làm thay đổi sự bài tiết của các thuốc khác.\r\n\r\nVitamin C liều cao có thể phá hủy vitamin B12; cần khuyên người bệnh tránh uống thuốc này trong vòng một giờ trước hoặc sau khi uống vitamin B12.\r\n\r\nVì vitamin C là một chất khử mạnh, nên ảnh hưởng đến nhiều xét nghiệm dựa trên phản ứng oxy hóa - khử. Sự có mặt vitamin C trong nước tiểu làm tăng giả tạo lượng glucose nếu định lượng bằng thuốc thử đồng (II) sulfat và giảm giả tạo lượng glucose nếu định lượng bằng phương pháp glucose oxydase. Với các xét nghiệm khác, cần phải tham khảo tài liệu chuyên biệt về ảnh hưởng của vitamin C.', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C.', 5.00, 36100.00),
(3, 'Khẩu trang y tế Thiên Thủy 4 lớp ( 50 cái )', 2, 'Khẩu trang y tế Thiên Thủy 4 lớp hỗ trợ ngăn ngừa bụi, vi khuẩn và các bệnh lây qua đường hô hấp, giúp giảm tác động của ánh sáng mặt trời lên da.', 55000.00, 'mask.jpg', 0, '2025-05-21 04:23:12', 3, 78, 'Thương hiệu Khẩu Trang Việt', 'Vải không dệt PP\r\n\r\nLớp vi lọc thấu khí PP\r\n\r\nThanh nẹp mũi bằng nhựa\r\n\r\nDây đeo có tính đàn hồi', 'Công dụng của Khẩu trang y tế 4 lớp xanh\r\nKhẩu trang y tế Thiên Thủy 4 lớp hỗ trợ ngăn ngừa: Bụi, vi khuẩn và các bệnh lây qua đường hô hấp.\r\n\r\nGiảm tác động của ánh sáng mặt trời lên da.', 'Cách dùng Khẩu trang y tế 4 lớp xanh\r\nKéo hai dây khẩu trang vòng qua hai bên vành tay.\r\n\r\nĐiều chỉnh nẹp mũi ôm vừa khít sống mũi.\r\n\r\nKéo mép dưới khẩu trang qua cằm.', 'Chưa có thông tin về tác dụng phụ của sản phẩm.', 'Lưu ý\r\nSản phẩm chỉ sử dụng một lần.\r\n\r\nTránh xa khu vực có lửa hoặc nguy cơ bắt lửa.\r\n\r\nTránh xa tầm tay trẻ em.\r\n\r\nSau khi sử dụng, bỏ chất thải đúng nơi quy định.', 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp và nhiệt độ cao.', 12.00, 48400.00),
(4, 'Nước súc miệng Listerine Green Tea bảo vệ răng miệng', 2, 'Ngăn ngừa vi khuẩn, bảo vệ răng miệng.', 69000.00, 'listerine.jpg', 0, '2025-05-21 04:23:12', 5, 45, 'Listerine', 'Menthol, Eucalyptol, Methyl Salicylate, Thymol', 'Giúp ngăn ngừa mảng bám, làm sạch khoang miệng, khử mùi hôi và bảo vệ răng lợi.', 'Súc miệng 20ml trong 30 giây sau khi đánh răng, ngày 2 lần, không nuốt.', 'Có thể gây cảm giác nóng rát nhẹ trong miệng, không sử dụng cho trẻ em dưới 12 tuổi.', 'Không nuốt, tránh tiếp xúc với mắt. Ngưng sử dụng nếu bị kích ứng.', 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp, nhiệt độ dưới 30°C.', 5.00, 65550.00),
(5, 'Dầu gội  Nazorel Shampoo  trị liệu các bệnh da liễu', 3, 'Điều trị nấm da đầu và gàu.', 75000.00, 'Nazorel.jpg', 0, '2025-05-21 04:23:12', 11, 4, 'Nazorel', 'Ketoconazole 1%', 'Dùng để điều trị các bệnh nấm da đầu như gàu, viêm da tiết bã.', 'Thoa dầu gội lên tóc ướt, massage nhẹ nhàng, để khoảng 3-5 phút rồi xả sạch. Dùng 2-3 lần mỗi tuần.', 'Có thể gây kích ứng da đầu nhẹ, ngứa hoặc đỏ da.', 'Tránh tiếp xúc với mắt. Ngưng sử dụng nếu xuất hiện kích ứng nặng.', 'Bảo quản nơi khô ráo, tránh ánh sáng trực tiếp, nhiệt độ dưới 30°C.', 8.00, 69000.00),
(6, 'Gel rửa tay Natural Hand Sanitizer Diệt khuẩn', 2, 'Dung tích 100ml, diệt khuẩn 99%.', 20000.00, 'gel.jpg', 0, '2025-05-21 04:23:12', 19, 36, 'Natural', 'Alcohol 70%, Glycerin, Aloe Vera Extract', 'Diệt khuẩn, làm sạch tay nhanh chóng mà không cần rửa với nước.', 'Lấy một lượng vừa đủ gel, xoa đều khắp tay cho đến khi khô.', 'Có thể gây khô da nếu sử dụng nhiều lần trong ngày.', 'Tránh tiếp xúc với mắt và vết thương hở. Không dùng cho trẻ sơ sinh.', 'Bảo quản nơi khô ráo, thoáng mát, tránh xa nguồn lửa.', 5.00, 19000.00),
(7, 'Siro Tiffy điều trị nghẹt mũi, hạ sốt', 1, '\r\nSiro Tiffy được sản xuất bởi công ty TNHH Thai Nakorn Patana (Việt Nam), được chỉ định dùng trong trường hợp làm giảm các triệu chứng cảm thông thường: nghẹt mũi, hạ sốt, giảm đau và viêm mũi dị ứng.\r\n\r\nTiffy syrup là dung dịch siro màu đỏ cam với hương cam, đóng gói trong chai thủy tinh màu hổ phách thể tích 30 ml và 60 ml được đậy kín bằng nắp nhôm và đóng gói trong hộp riêng, ép phim 12 hộp.', 12000.00, 'tiffy.jpg', 0, '2025-05-21 16:14:13', 1, 33, 'Tiffy', 'Thành phần chính chưa rõ', 'Dùng để điều trị bệnh theo chỉ định của bác sĩ', 'Uống 1 viên/lần, mỗi ngày 2 lần sau ăn', 'Có thể gây dị ứng, buồn nôn', 'Không dùng cho người mẫn cảm với thành phần thuốc', 'Bảo quản nơi khô ráo, tránh ánh sáng', 25.00, 9000.00),
(8, 'Decolgen Forte giảm đau hạ sốt nghẹt mũi', 1, 'Giảm triệu chứng cảm lạnh', 18000.00, 'Decolgen.jpg', 0, '2025-05-21 16:14:13', 0, 7, 'Decolgen', 'Paracetamol 500mg, Phenylephrine HCl 5mg, Chlorpheniramine Maleate 2mg', 'Giảm đau, hạ sốt, giảm nghẹt mũi trong các trường hợp cảm cúm, cảm lạnh', 'Người lớn và trẻ em trên 12 tuổi: 1 viên/lần, mỗi 4-6 giờ nếu cần, không quá 4 viên/ngày', 'Buồn ngủ, chóng mặt, khô miệng, hiếm khi dị ứng da', 'Không dùng quá liều, thận trọng với người cao huyết áp, phụ nữ có thai và cho con bú', 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp, nhiệt độ dưới 30°C', 5.00, 17100.00),
(9, 'Panadol Extra tê chân nhức mỏi vai gáy', 1, 'Hạ sốt, giảm đau hiệu quả', 22000.00, 'panadol_extra.jpg', 0, '2025-05-21 16:14:13', 1, 31, 'Panadol', 'Paracetamol 500mg, Caffeine 65mg', 'Giảm đau, hạ sốt, giảm đau đầu, đau răng, đau cơ, đau lưng, đau bụng kinh', 'Người lớn và trẻ em trên 12 tuổi: 1-2 viên mỗi 4-6 giờ nếu cần. Không dùng quá 8 viên trong 24 giờ.', 'Buồn nôn, dị ứng da, kích ứng dạ dày, hiếm khi phản ứng nghiêm trọng', 'Không dùng quá liều, thận trọng với người bệnh gan hoặc đang dùng thuốc khác có Paracetamol.', 'Bảo quản nơi khô ráo, tránh ánh sáng trực tiếp, nhiệt độ dưới 30°C', 7.00, 20460.00),
(10, 'Coldacmin Điều trị cảm , ho, sổ mũi', 1, 'Điều trị cảm cúm, đau đầu', 14000.00, 'coldacmin.jpg', 0, '2025-05-21 16:14:13', 6, 63, 'Dafaco', 'Paracetamol 500mg, Chlorpheniramine maleate 2mg, Phenylephrine HCl 10mg', 'Giảm đau, hạ sốt, giảm nghẹt mũi, sổ mũi do cảm cúm', 'Người lớn và trẻ em trên 12 tuổi: 1 viên mỗi 4-6 giờ khi cần. Không dùng quá 6 viên trong 24 giờ.', 'Buồn ngủ, khô miệng, chóng mặt, buồn nôn', 'Không dùng cho người bị cao huyết áp, bệnh tim, hoặc đang dùng thuốc chống trầm cảm. Thận trọng khi lái xe.', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 5.00, 13300.00),
(11, 'Alpha Choay Điều trị chấn thương, chống viêm ', 1, 'Kháng viêm, chống phù nề', 50000.00, 'alpha_choay.jpg', 0, '2025-05-21 16:14:13', 6, 20, 'Sanofi', 'Alpha chymotrypsin 4.2 mg (tương đương 21 microkatals)', 'Chống viêm, chống phù nề do chấn thương hoặc sau phẫu thuật; hỗ trợ điều trị viêm xoang', 'Người lớn: 2 viên x 3-4 lần/ngày. Uống xa bữa ăn hoặc ngậm dưới lưỡi.', 'Rối loạn tiêu hóa nhẹ, dị ứng, thay đổi men gan (hiếm)', 'Không dùng cho người bị rối loạn đông máu, dị ứng protein hoặc đang dùng thuốc kháng đông. Không dùng cho trẻ dưới 18 tuổi nếu không có chỉ định bác sĩ.', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ không quá 30°C', 10.00, 45000.00),
(12, 'Viên nang cứng Omeprazol TVP 20mg', 1, '\r\nOmeprazol TVP 20mg của Công ty Cổ phần Dược phẩm TV.PHARM, thành phần chính omeprazol 20mg, là thuốc dùng để điều trị và ngăn ngừa tái phát loét dạ dày, tá tràng, điều trị và ngăn ngừa loét dạ dày tá tràng do NSAID, điều trị trào ngược dạ dày, thực quản, kết hợp với kháng sinh thích hợp điều trị bệnh loét dạ dày, tá tràng do Helicobacter pylori, hội chứng Zollinger-Ellision.', 25000.00, 'efferalgan.jpg', 0, '2025-05-21 16:14:13', 0, 25, 'UPSA', 'Paracetamol 500mg', 'Giảm đau, hạ sốt trong các trường hợp như đau đầu, đau răng, đau cơ, đau lưng, sốt do cảm cúm', 'Người lớn và trẻ em trên 12 tuổi: 1-2 viên mỗi 4-6 giờ khi cần. Không dùng quá 8 viên trong 24 giờ.', 'Buồn nôn, phát ban, dị ứng, tổn thương gan nếu dùng quá liều', 'Không dùng quá liều, thận trọng với người bệnh gan hoặc nghiện rượu. Không dùng cùng thuốc khác có chứa Paracetamol.', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 5.00, 23750.00),
(13, 'Viên nang cứng Omeprazol TVP 20mg', 1, 'Điều trị đau dạ dày', 18000.00, 'omeprazol.jpg', 0, '2025-05-21 16:14:13', 0, 2, 'Stada', 'Omeprazol 20mg', 'Điều trị viêm loét dạ dày tá tràng, trào ngược dạ dày thực quản, hội chứng Zollinger-Ellison', 'Uống trước bữa ăn sáng, 1 viên/ngày. Trong một số trường hợp, liều có thể tăng theo chỉ định bác sĩ.', 'Đau đầu, buồn nôn, tiêu chảy, táo bón, đầy hơi. Hiếm gặp: giảm magie máu, loãng xương khi dùng dài hạn', 'Không dùng cho người dị ứng với Omeprazol hoặc dẫn xuất benzimidazol. Thận trọng khi dùng kéo dài.', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 8.00, 16560.00),
(14, 'Thuốc Clorpheniramin 4mg Khapharco ', 1, 'Chống dị ứng, mẩn ngứa', 10000.00, 'clorpheniramin.jpg', 0, '2025-05-21 16:14:13', 1, 8, 'Dược Hậu Giang', 'Clorpheniramin maleat 4mg', 'Điều trị dị ứng, viêm mũi dị ứng, sổ mũi, nổi mề đay, mẩn ngứa, viêm da dị ứng', 'Người lớn: 1 viên/lần, 3-4 lần mỗi ngày. Trẻ em: theo chỉ định bác sĩ.', 'Buồn ngủ, khô miệng, chóng mặt, rối loạn tiêu hóa nhẹ', 'Không dùng cho người đang lái xe, vận hành máy móc. Tránh dùng chung với rượu và thuốc ức chế thần kinh trung ương.', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 5.00, 9500.00),
(15, 'Viên nén Myonal 50mg Eisai cải thiện tăng trương lực cơ', 1, 'Thuốc giãn cơ, giảm đau cơ xương', 38000.00, 'myonal.jpg', 0, '2025-05-21 16:14:13', 0, 0, 'Eisai', 'Eperisone hydrochloride 50mg', 'Điều trị co cứng cơ trong các bệnh lý thần kinh như thoái hóa cột sống, đau cột sống thắt lưng, liệt co cứng', 'Người lớn: 1 viên x 3 lần/ngày sau bữa ăn. Liều dùng theo chỉ định bác sĩ.', 'Buồn nôn, chóng mặt, mệt mỏi, dị ứng da nhẹ. Hiếm gặp: phản ứng quá mẫn', 'Không dùng cho phụ nữ có thai hoặc đang cho con bú, người có tiền sử dị ứng với Eperisone', 'Bảo quản nơi khô ráo, tránh ánh sáng trực tiếp, nhiệt độ dưới 30°C', 6.00, 35720.00),
(16, 'Thuốc Vitamin E 400IU OPC hỗ trợ trị thiếu Vitamin E', 3, 'Chống lão hóa da, tăng sinh collagen', 29000.00, 'vitamin_e.jpg', 0, '2025-05-21 16:14:13', 1, 3, 'Mekophar', 'Vitamin E (dl-alpha-tocopheryl acetate) 400 IU', 'Bổ sung vitamin E, giúp chống oxy hóa, làm đẹp da, hỗ trợ điều trị các bệnh do thiếu vitamin E', 'Uống 1 viên/ngày sau bữa ăn, theo chỉ dẫn của bác sĩ nếu dùng dài hạn', 'Hiếm gặp: buồn nôn, tiêu chảy, đau bụng, mệt mỏi nếu dùng liều cao kéo dài', 'Không nên tự ý dùng liều cao trong thời gian dài. Thận trọng với người rối loạn đông máu hoặc đang dùng thuốc chống đông', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 4.00, 27840.00),
(17, 'Thuốc Berberin 100mg Bidiphar điều trị tiêu chảy', 1, 'Tiêu chảy, rối loạn tiêu hóa', 8000.00, 'berberin.jpg', 0, '2025-05-21 16:14:13', 0, 5, 'Traphaco', 'Berberin clorid 10mg', 'Điều trị tiêu chảy, lỵ trực khuẩn, viêm ruột, rối loạn tiêu hóa do nhiễm khuẩn', 'Người lớn: 2 viên/lần x 2-3 lần/ngày. Trẻ em: theo chỉ định của bác sĩ.', 'Buồn nôn, táo bón, vàng da nhẹ (hiếm), kích ứng dạ dày', 'Không dùng cho phụ nữ mang thai, trẻ sơ sinh, người bị vàng da do tắc mật. Không dùng kéo dài', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 3.00, 7760.00),
(18, 'Thuốc Loperamide điều trị tiêu chảy do bệnh viêm ruột', 1, 'Cầm tiêu chảy nhanh chóng', 10000.00, 'loperamide.jpg', 0, '2025-05-21 16:14:13', 0, 1, 'Imexpharm', 'Loperamide hydrochloride 2mg', 'Điều trị tiêu chảy cấp và mãn tính không do nhiễm khuẩn; làm giảm tần suất đi ngoài sau phẫu thuật ruột', 'Người lớn: Liều khởi đầu 2 viên, sau mỗi lần đi ngoài thêm 1 viên, không quá 8 viên/ngày. Trẻ em: theo chỉ định bác sĩ.', 'Buồn ngủ, chóng mặt, táo bón, khô miệng, buồn nôn', 'Không dùng trong tiêu chảy do nhiễm khuẩn, sốt cao, phân có máu hoặc người dưới 12 tuổi nếu không có chỉ định bác sĩ', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 5.00, 9500.00),
(19, 'Viên uống B Complex Vitamin Royal Care ', 4, 'Bổ sung vitamin nhóm B', 15000.00, 'B Complex.jpg', 0, '2025-05-21 16:14:13', 0, 3, 'Stada', 'Vitamin B1 2mg, B2 2mg, B6 2mg, B12 2mcg, Nicotinamide 15mg, Acid folic 0.2mg, Calci pantothenate 3mg', 'Bổ sung vitamin nhóm B, hỗ trợ điều trị thiếu vitamin B, mệt mỏi, stress, rối loạn thần kinh, đau nhức cơ', 'Người lớn: 1-2 viên/ngày sau ăn. Trẻ em: theo chỉ định bác sĩ.', 'Hiếm gặp: buồn nôn, tiêu chảy nhẹ, dị ứng da', 'Không dùng quá liều. Thận trọng với người suy thận nặng hoặc có tiền sử dị ứng với bất kỳ thành phần nào', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 4.00, 14400.00),
(20, 'Siro Ginkid ZinC bổ sung kẽm, hỗ trợ tăng đề kháng', 4, 'Bổ sung kẽm, tăng miễn dịch', 25000.00, 'zinc.jpg', 0, '2025-05-21 16:14:13', 0, 0, 'Nature’s Way', 'Zinc gluconate (tương đương kẽm nguyên tố 10–15mg)', 'Bổ sung kẽm, hỗ trợ tăng cường miễn dịch, giảm rối loạn tiêu hóa, hỗ trợ phát triển chiều cao, trị mụn', 'Người lớn: 1 viên/ngày sau ăn. Trẻ em: theo chỉ định của bác sĩ hoặc dược sĩ', 'Buồn nôn, đau bụng, khó chịu dạ dày nếu uống khi đói', 'Không dùng quá liều khuyến cáo. Thận trọng với người bị rối loạn hấp thu kẽm hoặc đang dùng thuốc khác có chứa kẽm', 'Bảo quản nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp, nhiệt độ dưới 30°C', 5.00, 23750.00),
(21, 'Thuốc Calcium Corbiere Kids Extra bổ sung canxi', 4, 'Bổ sung canxi và vitamin D', 55000.00, 'calcium_corbiere.jpg', 0, '2025-05-21 16:14:13', 0, 2, 'Sanofi', 'Calcium glucoheptonate 1.125g, Vitamin C 100mg, Vitamin B6 2mg', 'Bổ sung canxi và vitamin cho cơ thể, hỗ trợ phát triển xương, phòng ngừa loãng xương, thiếu hụt vitamin C và B6', 'Người lớn: 1-2 ống/ngày, sau ăn. Trẻ em: theo chỉ định bác sĩ. Dạng uống trực tiếp hoặc pha loãng với nước.', 'Buồn nôn, đầy bụng, táo bón nhẹ, hiếm gặp dị ứng hoặc tăng canxi máu nếu dùng quá liều', 'Không dùng cho người bị sỏi thận, tăng canxi huyết, hoặc mẫn cảm với bất kỳ thành phần nào của thuốc', 'Bảo quản nơi khô ráo, nhiệt độ dưới 30°C, tránh ánh sáng', 6.00, 51700.00),
(22, 'Thuốc Cebraton Traphaco hoạt huyết dưỡng não', 4, 'Tăng tuần hoàn máu não', 65000.00, 'cebraton.jpg', 0, '2025-05-21 16:14:13', 0, 4, 'Traphaco', 'Cao Bacopa monnieri (Rau đắng biển), Cao Ginkgo biloba (Bạch quả), Taurin, Vitamin nhóm B (B1, B6, B12)', 'Hỗ trợ tăng cường tuần hoàn não, cải thiện trí nhớ, giảm căng thẳng thần kinh, hỗ trợ điều trị thiểu năng tuần hoàn não', 'Người lớn: 1 viên x 2 lần/ngày sau ăn. Dùng liên tục ít nhất 4 tuần để thấy hiệu quả rõ rệt.', 'Hiếm gặp: đau đầu nhẹ, rối loạn tiêu hóa, mất ngủ nếu dùng buổi tối', 'Không dùng cho phụ nữ mang thai, cho con bú hoặc người mẫn cảm với bất kỳ thành phần nào', 'Bảo quản nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp, nhiệt độ dưới 30°C', 5.00, 61750.00),
(23, 'Viên uống Omexxel Ginkgo 120 Excelife', 4, 'Hỗ trợ trí nhớ, tuần hoàn não', 72000.00, 'ginkgo.jpg', 0, '2025-05-21 16:14:13', 0, 10, 'Stada', 'Ginkgo biloba extract 40mg', 'Hỗ trợ tăng cường tuần hoàn máu não, cải thiện trí nhớ, giảm chóng mặt, ù tai, đau đầu do thiểu năng tuần hoàn não', 'Người lớn: 1 viên x 2-3 lần/ngày sau ăn. Dùng tối thiểu 4 tuần để có hiệu quả rõ rệt.', 'Rối loạn tiêu hóa nhẹ, đau đầu, dị ứng da (hiếm gặp)', 'Không dùng cho người rối loạn đông máu, phụ nữ có thai, đang dùng thuốc chống đông hoặc sắp phẫu thuật', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 5.00, 68400.00),
(24, 'Cao dán Salonpas Hisamitsu giảm đau vai, đau lưng', 2, 'Miếng dán giảm đau', 30000.00, 'salonpas.jpg', 0, '2025-05-21 16:14:13', 0, 42, 'Hisamitsu', 'Methyl salicylate 6.29%, Menthol 5.71%, Camphor 1.24%, Tocopherol acetate (Vitamin E) 2.00%', 'Giảm đau nhức cơ, đau lưng, đau vai gáy, bong gân, đau khớp nhẹ, viêm khớp dạng nhẹ', 'Dán trực tiếp lên vùng đau 3–4 lần/ngày. Không dán lên vết thương hở, vùng da nhạy cảm hoặc bị kích ứng.', 'Kích ứng da nhẹ, ngứa, đỏ da tại vùng dán (hiếm gặp)', 'Không dùng cho trẻ em dưới 12 tuổi nếu không có chỉ định bác sĩ. Không dùng cùng lúc với sản phẩm chứa salicylat khác.', 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp, nhiệt độ dưới 30°C', 3.00, 29100.00),
(25, 'Máy đo huyết áp bắp tay Omron HEM-7121', 2, 'Máy đo huyết áp tự động, dễ sử dụng, công nghệ Intellisense cho kết quả chính xác.', 950000.00, 'omrom.jpg', 50, '2025-05-30 08:48:49', 0, 0, 'Omron', 'Thiết bị điện tử đo huyết áp tự động, sử dụng công nghệ Intellisense', 'Đo huyết áp tâm thu, huyết áp tâm trương và nhịp tim. Giúp theo dõi, kiểm soát huyết áp tại nhà.', 'Quấn vòng bít vào tay trái, ngồi thư giãn 5 phút rồi nhấn nút Start. Đọc kết quả sau vài giây.', 'Không có tác dụng phụ y tế. Sử dụng sai tư thế có thể gây sai lệch kết quả.', 'Không sử dụng khi vòng bít bị rách hoặc hỏng. Đọc kỹ hướng dẫn sử dụng trước khi dùng.', 'Bảo quản nơi khô ráo, tránh nước, tránh va đập mạnh, tháo pin nếu không dùng lâu ngày', 10.00, 855000.00),
(26, 'Máy đo huyết áp bắp tay MediUSA – Model UB-351', 2, 'Máy đo huyết áp tự động MediUSA UB-351 với công nghệ đo thông minh, màn hình lớn dễ đọc, lưu được nhiều kết quả đo.', 880000.00, 'mediusa-ub351.jpg', 40, '2025-05-30 08:51:00', 129, 58, 'MediUSA', 'Thiết bị điện tử đo huyết áp tự động, công nghệ đo thông minh Fuzzy Logic', 'Giúp đo huyết áp và nhịp tim chính xác tại nhà, hỗ trợ người cao huyết áp kiểm soát sức khỏe tim mạch', 'Đeo vòng bít lên bắp tay, ngồi thẳng và thư giãn 5 phút, bấm nút Start để tiến hành đo. Giữ yên trong suốt quá trình đo.', 'Không có tác dụng phụ nếu sử dụng đúng cách. Sử dụng sai tư thế có thể gây sai lệch kết quả.', 'Không dùng nếu vòng bít bị rách hoặc máy báo lỗi liên tục. Tránh dùng cho người có nhịp tim không đều nếu không có tư vấn bác sĩ.', 'Bảo quản nơi khô ráo, tránh ẩm, không để máy dính nước hoặc va đập mạnh. Tháo pin nếu không sử dụng lâu.', 8.00, 809600.00),
(27, 'Nano Sea – Viên uống tăng sức đề kháng', 4, 'Nano Sea là thực phẩm bảo vệ sức khỏe, chứa kẽm, vitamin C và các vi chất giúp tăng cường hệ miễn dịch, giảm nguy cơ mắc bệnh hô hấp.', 120000.00, 'nano-sea.jpg', 100, '2025-05-30 08:52:12', 0, 1, 'Nano Sea', 'Kẽm gluconat, vitamin C, selen, chiết xuất cam thảo, rutin, nano curcumin', 'Tăng cường sức đề kháng, hỗ trợ chống oxy hóa, giảm nguy cơ viêm họng, cảm cúm, mệt mỏi do thiếu vi chất.', 'Người lớn: uống 1–2 viên/ngày sau ăn. Trẻ em từ 6 tuổi trở lên: uống 1 viên/ngày.', 'Hiếm gặp: rối loạn tiêu hóa nhẹ, buồn nôn nếu dùng lúc đói', 'Không dùng cho người mẫn cảm với bất kỳ thành phần nào. Không dùng quá liều khuyến nghị.', 'Bảo quản nơi khô ráo, thoáng mát, tránh ánh nắng, nhiệt độ dưới 30°C.', 5.00, 114000.00),
(28, 'Miếng dán mụn Derma Angel Ultra Thin (Original) ', 3, 'Miếng dán mụn Derma Angel Ultra Thin dạng trong suốt, siêu mỏng, phù hợp dùng cả ban ngày và ban đêm, giúp bảo vệ và hỗ trợ làm lành mụn nhanh chóng.', 68000.00, 'derma-angel-ultrathin.jpg', 120, '2025-05-30 09:00:04', 0, 1, 'Derma Angel', 'Hydrocolloid siêu mỏng', 'Giúp hút mủ, giảm viêm, bảo vệ mụn khỏi vi khuẩn và tác động bên ngoài; hỗ trợ làm lành mụn nhanh chóng.', 'Làm sạch da, lau khô vùng bị mụn. Dán trực tiếp lên nốt mụn. Thay miếng dán sau 6-8 giờ hoặc khi miếng dán chuyển màu trắng đục.', 'Hiếm khi gây kích ứng. Ngưng sử dụng nếu có dấu hiệu mẩn đỏ, ngứa, hoặc kích ứng da.', 'Không sử dụng cho vùng da bị tổn thương nặng, chảy máu. Tránh dán trên nhiều lớp mỹ phẩm.', 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp, đậy kín sau khi sử dụng.', 10.00, 61200.00),
(29, 'Mặt nạ JMsolution Marine Luminous Pearl Moisture Mask Plus', 0, 'Mặt nạ dưỡng da JMsolution Marine Luminous Pearl Moisture Mask Plus với chiết xuất ngọc trai biển sâu giúp cung cấp độ ẩm, làm sáng và phục hồi làn da mệt mỏi.', 32000.00, 'jmsolution-pearl-mask.jpg', 100, '2025-05-30 09:00:41', 0, 0, 'JMsolution', 'Chiết xuất ngọc trai, nước biển sâu, collagen thủy phân, hyaluronic acid', 'Dưỡng ẩm sâu, cải thiện độ sáng của da, hỗ trợ phục hồi da xỉn màu, thiếu sức sống.', 'Sau khi làm sạch da, sử dụng sản phẩm theo thứ tự Step 1 → Step 2 → Step 3. Dán mặt nạ trong 10–20 phút, sau đó tháo ra và vỗ nhẹ để tinh chất thẩm thấu.', 'Không sử dụng nếu da bị kích ứng hoặc có vết thương hở. Có thể gây dị ứng nhẹ với da nhạy cảm.', 'Ngưng sử dụng nếu xuất hiện mẩn đỏ hoặc ngứa. Tránh tiếp xúc trực tiếp với mắt.', 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp và nhiệt độ cao.', 15.00, 27200.00),
(30, 'Serum Garnier Bright Anti-Acne Booster 4% (30ml)', 3, 'Serum Garnier Bright Anti-Acne Booster với công thức chứa 4% phức hợp Vitamin C, Salicylic Acid, Niacinamide và AHA giúp giảm mụn, mờ thâm và dưỡng sáng da.', 198000.00, 'facewash.jpg', 75, '2025-05-30 09:04:06', 0, 1, 'Garnier', 'Vitamin C, Salicylic Acid, Niacinamide, AHA', 'Giúp giảm mụn, kháng viêm, ngăn ngừa vết thâm và cải thiện độ sáng cho da dầu, da mụn.', 'Dùng sau bước làm sạch và toner. Lấy 3–4 giọt thoa đều lên mặt, vỗ nhẹ đến khi thẩm thấu. Dùng sáng và tối.', 'Có thể gây châm chích nhẹ ở da nhạy cảm hoặc khi dùng lần đầu. Khuyến khích thử trước trên một vùng da nhỏ.', 'Không sử dụng trên vùng da có vết thương hở. Tránh để sản phẩm tiếp xúc trực tiếp với mắt.', 'Đậy kín sau khi dùng. Bảo quản nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp.', 12.00, 174240.00),
(31, 'Xoang Hải Đằng – Hỗ trợ giảm viêm xoang, viêm mũi ', 4, 'Xoang Hải Đằng là thực phẩm bảo vệ sức khỏe hỗ trợ giảm viêm xoang, viêm mũi dị ứng nhờ thành phần dược liệu tự nhiên. Dùng an toàn lâu dài.', 165000.00, 'xoang-hai-dang.jpg', 80, '2025-05-30 08:52:57', 0, 1, 'Hải Đằng', 'Tân di hoa, ké đầu ngựa, bạch chỉ, phòng phong, cam thảo, tía tô, hoàng cầm', 'Hỗ trợ giảm triệu chứng nghẹt mũi, chảy nước mũi, đau nhức xoang, viêm mũi dị ứng, tăng sức đề kháng mũi xoang.', 'Người lớn: uống 2 viên/lần, ngày 2 lần sau ăn. Nên dùng ít nhất 1–2 tháng để thấy hiệu quả rõ rệt.', 'Rất hiếm gặp: rối loạn tiêu hóa nhẹ hoặc mẩn ngứa do dị ứng thảo dược', 'Không dùng cho người mẫn cảm với bất kỳ thành phần nào của sản phẩm. Tham khảo ý kiến bác sĩ nếu đang mang thai hoặc cho con bú.', 'Bảo quản nơi khô ráo, tránh ánh nắng, tránh ẩm mốc, nhiệt độ dưới 30°C.', 5.00, 156750.00),
(32, 'Sữa rửa mặt Rosette Acne Clear 120g', 3, 'Sữa rửa mặt Rosette Acne Clear với thành phần lưu huỳnh và chiết xuất thực vật giúp làm sạch sâu, hỗ trợ điều trị mụn và ngăn ngừa mụn quay trở lại, phù hợp cho da dầu mụn.', 95000.00, 'rosette-acne-clear.jpg', 90, '2025-05-30 09:05:23', 0, 0, 'Rosette', 'Lưu huỳnh, chiết xuất thực vật thiên nhiên, acid stearic', 'Làm sạch bụi bẩn và bã nhờn, kháng khuẩn, hỗ trợ điều trị mụn và giảm viêm da.', 'Làm ướt mặt, lấy một lượng vừa đủ, tạo bọt rồi massage nhẹ nhàng lên mặt, rửa lại bằng nước sạch. Dùng 2 lần/ngày.', 'Có thể gây khô da nhẹ nếu dùng quá thường xuyên. Không dùng cho da quá nhạy cảm với lưu huỳnh.', 'Tránh tiếp xúc với mắt. Nếu có kích ứng, ngưng sử dụng và hỏi ý kiến bác sĩ da liễu.', 'Bảo quản nơi thoáng mát, tránh ánh nắng trực tiếp và nơi có độ ẩm cao.', 5.00, 90250.00),
(34, 'Bao cao su Safefit 003 Super Thin ', 2, 'Bao cao su Safefit 003 được làm từ latex cao cấp với độ dày chỉ 0.03mm, mang lại cảm giác chân thật và thoải mái khi quan hệ. Thiết kế siêu mỏng, không mùi, có gel bôi trơn.', 21000.00, 'safefit-003.jpg', 120, '2025-05-30 09:06:38', 0, 2, 'Safefit', 'Latex tự nhiên, chất bôi trơn gốc nước', 'Ngừa thai, phòng tránh các bệnh lây truyền qua đường tình dục (STD), tăng khoái cảm nhờ thiết kế siêu mỏng.', 'Xé bao theo đường viền, đeo bao vào dương vật khi cương cứng trước khi quan hệ. Chỉ sử dụng một lần.', 'Không sử dụng nếu dị ứng với latex. Sử dụng không đúng cách có thể giảm hiệu quả tránh thai và phòng bệnh.', 'Tránh dùng với dầu massage hoặc gel gốc dầu (làm rách bao). Không tái sử dụng.', 'Bảo quản nơi khô ráo, thoáng mát, tránh nhiệt độ cao và ánh sáng trực tiếp.', 10.00, 18900.00),
(35, 'Bao cao su Durex Invisible Extra Thin Extra Sensitive ', 2, 'Durex Invisible là dòng bao cao su siêu mỏng nhất của Durex, giúp tăng độ nhạy cảm và mang lại trải nghiệm chân thật khi quan hệ. Có chất bôi trơn, không mùi, chất liệu cao su tự nhiên.', 42000.00, 'durex-invisible.jpg', 100, '2025-05-30 09:07:19', 1, 1, 'Durex', 'Cao su latex tự nhiên, chất bôi trơn gốc nước', 'Ngừa thai, phòng chống lây nhiễm các bệnh qua đường tình dục, hỗ trợ cảm giác chân thật khi quan hệ.', 'Xé bao nhẹ nhàng, đeo vào khi dương vật cương cứng, đảm bảo bao không bị rách và ôm khít. Dùng một lần.', 'Có thể gây kích ứng nếu dị ứng với latex. Sử dụng sai cách làm giảm hiệu quả tránh thai.', 'Không tái sử dụng. Không dùng cùng chất bôi trơn gốc dầu.', 'Để nơi thoáng mát, tránh nhiệt độ cao và ánh nắng trực tiếp.', 10.00, 37800.00),
(36, 'Bao cao su Durex Performa (Hộp 3 chiếc)', 2, 'Durex Performa là bao cao su chứa chất gây tê nhẹ (Benzocaine) giúp kéo dài thời gian quan hệ. Thiết kế siêu mỏng, có gel bôi trơn, không mùi, chất liệu cao su tự nhiên.', 49000.00, 'durex-performa.jpg', 85, '2025-05-30 09:08:00', 0, 0, 'Durex', 'Cao su latex tự nhiên, Benzocaine 5%, chất bôi trơn gốc nước', 'Ngừa thai, phòng tránh bệnh lây qua đường tình dục, hỗ trợ kéo dài thời gian quan hệ.', 'Xé bao nhẹ nhàng, đeo vào khi dương vật cương cứng. Chờ 1-2 phút cho chất gây tê phát huy tác dụng.', 'Có thể gây ngứa nhẹ hoặc dị ứng nếu mẫn cảm với Benzocaine hoặc latex.', 'Không dùng nếu có tiền sử dị ứng với thuốc gây tê tại chỗ. Không sử dụng quá thường xuyên.', 'Bảo quản nơi khô ráo, tránh ánh nắng và nhiệt độ cao. Không để sản phẩm trong ví hoặc túi quần lâu ngày.', 10.00, 44100.00),
(37, 'Siro Bổ Phế Lábebé (Chai 125ml)', 4, 'Siro Bổ Phế Lábebé giúp giảm ho, giảm đờm, giảm đau họng, hỗ trợ tăng sức đề kháng và phòng ngừa viêm phế quản. Sản phẩm phù hợp cho trẻ nhỏ.', 69000.00, 'bo-phe-labebe.jpg', 100, '2025-05-30 09:10:17', 1, 1, 'Lábebé', 'Tinh chất lá thường xuân, mật ong, đường phèn, cam thảo, tần dày lá, gừng, húng chanh,...', 'Giảm ho, tiêu đờm, hỗ trợ điều trị viêm họng, cảm lạnh, viêm phế quản ở trẻ em.', 'Lắc kỹ trước khi dùng. Trẻ dưới 1 tuổi: theo chỉ dẫn bác sĩ. Trẻ 1-6 tuổi: 5ml x 2-3 lần/ngày. Trẻ trên 6 tuổi: 10ml x 2-3 lần/ngày.', 'Hiếm gặp: tiêu chảy nhẹ, đầy bụng. Ngưng dùng nếu có biểu hiện dị ứng.', 'Không dùng cho trẻ dị ứng với bất kỳ thành phần nào của sản phẩm. Tham khảo ý kiến bác sĩ nếu triệu chứng không cải thiện sau 5 ngày.', 'Bảo quản nơi khô ráo, thoáng mát, tránh ánh nắng trực tiếp. Đậy kín nắp sau khi sử dụng.', 0.00, 69000.00),
(38, 'Quấn chân thể thao BonBone Knee Support', 4, 'Quấn gối BonBone giúp cố định khớp gối, hỗ trợ người bị đau khớp, viêm khớp hoặc vận động viên trong quá trình luyện tập và thi đấu.', 320000.00, 'bonbone-knee-support.jpg', 50, '2025-05-30 08:54:16', 0, 2, 'BonBone', 'Vải co giãn tổng hợp, Neoprene, Nylon, Polyester', 'Hỗ trợ cố định khớp gối, giảm đau trong viêm khớp, bong gân, chấn thương dây chằng nhẹ. Tăng cường ổn định cho đầu gối khi vận động.', 'Đeo trực tiếp vào đầu gối, căn chỉnh vị trí vừa vặn. Dùng khi vận động thể thao hoặc khi có chỉ định bác sĩ.', 'Có thể gây khó chịu, bí bách nếu đeo lâu, đặc biệt trong thời tiết nóng. Không nên đeo khi ngủ.', 'Không dùng khi có vết thương hở hoặc viêm da tại vùng đầu gối. Giặt tay, không dùng máy giặt.', 'Bảo quản nơi khô ráo, thoáng mát. Giặt nhẹ bằng tay với xà phòng dịu nhẹ, không phơi trực tiếp dưới ánh nắng.', 10.00, 288000.00),
(10229, 'Thuốc Loperamide Stella Pharm điều trị tiêu chảy ', 1, 'Cầm tiêu chảy nhanh chóng', 10000.00, 'loperamide.jpg', 0, '2025-05-21 16:14:13', 0, 1, 'Imexpharm', 'Loperamide hydrochloride 2mg', 'Điều trị tiêu chảy cấp và mãn tính không do nhiễm khuẩn; làm giảm tần suất đi ngoài sau phẫu thuật ruột', 'Người lớn: Liều khởi đầu 2 viên, sau mỗi lần đi ngoài thêm 1 viên, không quá 8 viên/ngày. Trẻ em: theo chỉ định bác sĩ.', 'Buồn ngủ, chóng mặt, táo bón, khô miệng, buồn nôn', 'Không dùng trong tiêu chảy do nhiễm khuẩn, sốt cao, phân có máu hoặc người dưới 12 tuổi nếu không có chỉ định bác sĩ', 'Bảo quản nơi khô ráo, tránh ánh sáng, nhiệt độ dưới 30°C', 5.00, 9500.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.jpg',
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `avatar`, `role`, `created_at`) VALUES
(1, 'Dược sĩ Tú', '$2y$10$QvGeo41XOe8b6pHZilkDWuAc.pSWn4MOnLz5hqk.vR09.AyBoEqVm', 'nguyentantu2005bt@gmail.com', 'Tu.jpg', 'admin', '2025-05-21 04:21:38'),
(2, 'Dược sĩ Vương', '$2y$10$TQyyccXUh.zCVVau9lYs8uc8NFl1WwVnFZtW3TP6EyLCF8as6ov3G', 'nguyentantubt@gmail.com', 'anhvuong.jpg', 'admin', '2025-05-21 06:29:43'),
(3, 'Dược sĩ Trọng', '$2y$10$HjXCDIK59VnGBNTVu5gUA.c9Rx7BEALZWw4H1oTROzl80L9M4bHsa', 'trong@gmai.com', 'avt.jpg', 'admin', '2025-05-23 00:19:37'),
(4, 'Tu', '$2y$10$03ho9buMUtRLojBeO8Mwoe7xP7ToyigvXEjp22MKzran4zijues/C', 'nguyentantu2005bt@gmail.com', 'tantu.jpg', 'user', '2025-05-27 04:39:53'),
(5, 'Tấn Tú', '$2y$10$xVhq3wIz/.cx6VQQHVDWtOWX16ZvPudEHPoScNH9DCEk9y0Q/..E2', 'nguyentantu612@gmail.com', 'default-avatar.jpg', 'user', '2025-05-30 15:32:12'),
(7, 'vuong', '$2y$10$noo6gtFzZovxbfmyXnH3Z.lDKke.9w1qVKhe5DuOQ3ClpVoFT2W3G', 'thanhvuong123sss@gmail.com', 'default-avatar.jpg', 'user', '2025-05-31 14:19:57'),
(8, 'Nguyễn Tấn Trí', '$2y$10$dzd990pT/nM4VjOAoIkdBewCGFC/gmMFh8malFi44bnxfJihkds5W', 'tri@gmail.com', 'default-avatar.jpg', 'user', '2025-06-01 03:34:24'),
(9, 'tubigay', '$2y$10$jkL0fW0i7oj/ttDEskcFdea.JRMkIfsPMBaj6z17IMo1bnVxET4RS', 'tuananh2006aa@gmail.com', 'default-avatar.jpg', 'user', '2025-06-01 05:55:38'),
(10, 'vuongvuong', '$2y$10$0Ey9LARwT2m5HJJyOZzUze/I1fJ3W562eqNa1PEqOkPiilcrFRFw2', 'thanhvuong123sss@gmail.com', 'default-avatar.jpg', 'user', '2025-06-02 14:47:40'),
(11, 'tranvi', '$2y$10$QNnpvq4L1.U0nM4ks1b2POsx53cJqNAWcVoOX0Rvss.SKEzVXd4Sy', 'tvi59013@gmail.com', 'default-avatar.jpg', 'user', '2025-06-04 12:46:25'),
(12, 'thu', '$2y$10$G7KgHe9t8EsEkJhn732Q/eTFVzZICZmcpJEtdHLZ6wDP5eUAJ04M6', 'thu@gmail.com', 'default-avatar.jpg', 'user', '2025-06-11 16:54:05'),
(13, 'zizi', '$2y$10$4diprMf/vV34Jv1FJOr6ReTATHzHhYbDR5WTltO8OTuS/KvvEpH7W', 'vitt1727@ut.edu.vn', 'default-avatar.jpg', 'user', '2025-06-23 14:58:32'),
(14, 'test', '$2y$10$XaN1Tu8E5YPN.pvJSfnnNOrVdChfvrVPfot5Aus.yELqnRjuAfDZe', 'phuoctrongh@gmail.com', 'default-avatar.jpg', 'user', '2025-07-04 06:41:36');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comment_id` (`comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10230;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
