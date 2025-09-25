-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 29, 2025 at 02:32 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cauca`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_action_logs`
--

CREATE TABLE `admin_action_logs` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `target_user_id` int NOT NULL,
  `field_name` varchar(50) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_action_logs`
--

INSERT INTO `admin_action_logs` (`id`, `admin_id`, `target_user_id`, `field_name`, `old_value`, `new_value`, `created_at`) VALUES
(1, 10, 17, 'phone', '0911111112', '0911111112', '2025-06-25 23:57:52'),
(2, 10, 17, 'review_status', 'no', 'yes', '2025-06-25 23:57:57'),
(3, 10, 17, 'bank_account', '', '00710053442616', '2025-06-25 23:58:38'),
(4, 10, 17, 'bank_name', '', 'vietcombank', '2025-06-25 23:58:45'),
(5, 10, 17, 'full_name', 'Nguyễn Thanh', 'Nguyễn Thanh A', '2025-06-25 23:58:50'),
(6, 10, 17, 'CCCD_number', '', '0101020020', '2025-06-25 23:59:00'),
(7, 10, 17, 'user_note', 'Ghi chú user...', 'user giàu có', '2025-06-25 23:59:42'),
(8, 10, 10, 'phone', '0999999999', '0935192079', '2025-06-26 00:01:49'),
(9, 10, 10, 'ref_code', NULL, '0935192079', '2025-06-26 00:07:55'),
(10, 10, 10, 'bank_account', '12345677890', '5512345678', '2025-06-26 00:09:21'),
(11, 10, 10, 'bank_name', 'nguyen thanh', 'Nguyen Ngoc Tan Thanh', '2025-06-26 00:09:34'),
(12, 10, 10, 'review_status', 'no', 'yes', '2025-06-26 00:09:38'),
(13, 10, 10, 'bank_name', 'Nguyen Ngoc Tan Thanh', 'ACB', '2025-06-26 00:09:44'),
(14, 10, 10, 'full_name', 'Nguyễn Thanh 10', 'Nguyen Ngoc Tan Thanh', '2025-06-26 00:09:53'),
(15, 10, 10, 'CCCD_number', '12323123334', '024217481', '2025-06-26 00:10:00'),
(16, 10, 10, 'user_note', '', 'Đài Sư Tập Sự', '2025-06-26 00:10:34'),
(17, 10, 10, 'nickname', 'admin_master', 'Đài Sư Tập Sự', '2025-06-26 00:10:43'),
(18, 10, 10, 'user_exp', '10', '10000', '2025-06-26 00:11:05'),
(19, 10, 11, 'nickname', 'mod_quanly', 'MOD - Quản Lý', '2025-06-26 00:11:32'),
(20, 10, 11, 'bank_account', NULL, '5512345678', '2025-06-26 00:11:38'),
(21, 10, 11, 'bank_name', NULL, 'ACB', '2025-06-26 00:11:48'),
(22, 10, 11, 'full_name', 'Điều phối viên MOD', 'Nguyễn MOD', '2025-06-26 00:11:58'),
(23, 10, 11, 'CCCD_number', NULL, '0101020020', '2025-06-26 00:12:03'),
(24, 10, 11, 'ref_code', NULL, '0935192079', '2025-06-26 00:12:06'),
(25, 10, 11, 'user_note', NULL, 'Quản lý', '2025-06-26 00:12:14'),
(26, 10, 18, 'ref_code', '093873737239', '0935192079', '2025-06-26 00:16:52'),
(27, 10, 18, 'CCCD_number', '0010029388282', '0101020020', '2025-06-26 00:17:09'),
(28, 10, 17, 'nickname', 'Hồ Bảo Ngân Edit', 'Hồ Câu Hoàng Hải', '2025-06-26 00:23:42'),
(29, 10, 17, 'ref_code', NULL, '0935192079', '2025-06-26 00:23:55'),
(30, 10, 17, 'status', 'Chưa xác minh', 'Đã xác minh', '2025-06-26 00:23:59');

-- --------------------------------------------------------

--
-- Table structure for table `admin_config_keys`
--

CREATE TABLE `admin_config_keys` (
  `id` int NOT NULL,
  `config_key` varchar(100) NOT NULL,
  `config_value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'mô tả...'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_config_keys`
--

INSERT INTO `admin_config_keys` (`id`, `config_key`, `config_value`, `description`) VALUES
(1, 'ref_percent', '5', 'Phần trăm hoa hồng mặc định cho mỗi booking'),
(2, 'xp_game', '5', 'EXP cho game mới'),
(3, 'xp_booking_new', '5', 'EXP cho Mỗi booking mới'),
(4, 'xp_booking_new_ho', '5', 'EXP cho Booking tại hồ chưa từng đến'),
(5, 'xp_booking_new_xa', '10', 'EXP cho Booking tại xã mới'),
(6, 'xp_booking_new_tinh', '30', 'EXP cho Booking tại tỉnh mới'),
(7, 'booking_hold_amount', '50000', 'Cần Thủ: Số tiền giữ lại khi cần thủ đặt booking'),
(8, 'booking_fee_amount', '10000', 'Cần Thủ: Phí đặt booking'),
(9, 'booking_vat_percent', '10', 'Cần Thủ: Phần trăm VAT áp dụng cho mỗi booking'),
(10, 'service_vat_percent', '10', 'Thuế VAT cho dịch vụ'),
(11, 'booking_day_limit', '30', 'Cần Thủ: Giới hạn số ngày có thể đặt booking trước'),
(12, 'game_day_limit', '30', 'Cần thủ + chủ hồ: Giới hạn số ngày có thể đặt game trước'),
(13, 'user_game_limit', '10', 'Giới hạn số lượng game trạng thái \"chờ xác nhận\"'),
(14, 'game_vat_percent', '10', 'Cần thủ + chủ hồ: Thuế VAT cho game'),
(15, 'game_fee_user', '5000', 'Cần thủ + chủ hồ: Phí tạo game dành cho 1 người tham gia, người tạo game trả'),
(16, 'game_time_basic', '60', 'Là thời gian chuẩn cho 1 hiệp đấu game'),
(17, 'game_online_discount', '40000', 'Giảm giá khi thanh toán online, qua hệ thống'),
(18, 'giai_fee_user', '5000', 'Cần Thủ: Phí người tạo giải - tính cho 1 user'),
(19, 'giai_vat_percent', '10', 'Cần Thủ: Thuế người  tạo giải, tính trên tổng phí giải'),
(20, 'giai_hold_amount', '100000', 'Cần Thủ: Số tiến giữ lại khi user join giải'),
(21, 'giai_fee_amount', '10000', 'Cần Thủ: số tiền user phải trả cho hệ thống khi join booking'),
(22, 'giai_limit', '10', 'Giới hạn số lượng giải \"dang_cho_xac_nhan\"'),
(23, 'xp_giai', '20', 'EXP cho mỗi giải'),
(24, 'giai_time_basic', '60', 'Là thời gian chuẩn cho 1 hiệp đấu giải'),
(25, 'tao_giai_percent', '80', 'Số lượng % cần thủ ít nhất để có thể kích hoạt giải đấu!');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `nguoi_tao_id` int NOT NULL,
  `can_thu_id` int DEFAULT NULL,
  `chu_ho_id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `gia_id` int NOT NULL,
  `booking_where` enum('POS','online') NOT NULL DEFAULT 'POS',
  `ten_nguoi_cau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `nick_name` varchar(100) DEFAULT NULL,
  `vi_tri` varchar(255) DEFAULT NULL,
  `booking_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `booking_start_time` datetime DEFAULT NULL,
  `real_start_time` datetime DEFAULT NULL,
  `real_end_time` datetime DEFAULT NULL,
  `fish_weight` float DEFAULT '0',
  `fish_return_amount` int DEFAULT '0',
  `ref_by_user_id` int DEFAULT NULL,
  `fish_sell_weight` float DEFAULT '0',
  `fish_sell_amount` int DEFAULT '0',
  `real_tong_thoi_luong` int DEFAULT NULL,
  `real_so_suat` int DEFAULT '0',
  `real_gio_them` int DEFAULT '0',
  `payment_status` enum('Chưa thanh toán','Đã thanh toán') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Chưa thanh toán',
  `payment_method` enum('Tiền mặt','Balance','Chuyển khoản') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Chuyển khoản',
  `booking_status` enum('Đang chạy','Hoàn thành','Đã huỷ','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Đang chạy',
  `service_amount` decimal(12,0) DEFAULT NULL,
  `award_amount` decimal(12,0) DEFAULT NULL,
  `booking_amount` decimal(12,0) DEFAULT NULL,
  `total_amount` decimal(12,0) DEFAULT NULL,
  `qr_image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `nguoi_tao_id`, `can_thu_id`, `chu_ho_id`, `ho_cau_id`, `gia_id`, `booking_where`, `ten_nguoi_cau`, `nick_name`, `vi_tri`, `booking_time`, `booking_start_time`, `real_start_time`, `real_end_time`, `fish_weight`, `fish_return_amount`, `ref_by_user_id`, `fish_sell_weight`, `fish_sell_amount`, `real_tong_thoi_luong`, `real_so_suat`, `real_gio_them`, `payment_status`, `payment_method`, `booking_status`, `service_amount`, `award_amount`, `booking_amount`, `total_amount`, `qr_image_url`) VALUES
(32, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-09 21:27:11', '2025-06-11 06:00:00', '2025-06-11 06:00:00', '2025-06-11 15:00:00', 0, 0, NULL, 0, 0, 540, 2, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 540000, 60000, 540000, 0, NULL),
(33, 1, 1, 2, 36, 55, 'online', '', NULL, NULL, '2025-06-09 21:27:25', '2025-06-12 06:00:00', '2025-06-12 06:00:00', '2025-06-12 14:00:00', 0, 0, NULL, 0, 0, 480, 2, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 420000, 60000, 660000, 420000, NULL),
(34, 1, 1, 2, 35, 52, 'online', '', NULL, NULL, '2025-06-09 21:28:44', '2025-06-11 06:00:00', '2025-06-11 06:00:00', '2025-06-11 14:00:00', 20, 300000, NULL, 2, 60000, 480, 2, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 540000, 60000, 480000, 300000, NULL),
(35, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-10 10:42:00', '2025-06-11 06:00:00', '2025-06-11 06:00:00', '2025-06-11 15:00:00', 0, 0, NULL, 0, 0, 540, 2, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 540000, 60000, 540000, 0, NULL),
(36, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-10 10:58:50', '2025-06-12 06:00:00', '2025-06-12 06:00:00', '2025-06-12 16:00:00', 0, 0, NULL, 0, 0, 600, 2, 120, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 660000, 60000, 660000, 0, NULL),
(37, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-10 11:05:40', '2025-06-13 06:00:00', '2025-06-13 06:00:00', '2025-06-13 15:00:00', 0, 0, NULL, 0, 0, 540, 2, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 540000, 60000, 540000, 0, NULL),
(38, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-10 11:08:24', '2025-06-11 06:00:00', '2025-06-11 06:00:00', '2025-06-11 16:00:00', 0, 0, NULL, 0, 0, 600, 2, 120, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 660000, 60000, 660000, 0, NULL),
(40, 1, 1, 2, 35, 52, 'online', '', NULL, NULL, '2025-06-13 19:52:28', '2025-06-14 06:00:00', '2025-06-14 06:00:00', '2025-06-14 11:00:00', 30, 450000, NULL, 0, 0, 300, 1, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 360000, 0, 360000, -90000, NULL),
(47, 1, 1, 2, 36, 55, 'online', '', NULL, NULL, '2025-06-14 22:18:05', '2025-06-15 06:00:00', '2025-06-15 06:00:00', '2025-06-15 14:00:00', 50, 1250000, NULL, 0, 0, 480, 2, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 420000, 0, 600000, -830000, NULL),
(62, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-14 23:32:23', '2025-06-15 06:00:00', '2025-06-15 06:00:00', '2025-06-15 11:00:00', 0, 0, NULL, 0, 0, 300, 1, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 360000, 0, 360000, 0, NULL),
(74, 1, 1, 2, 35, 52, 'online', '', NULL, NULL, '2025-06-15 16:34:49', '2025-06-17 06:00:00', '2025-06-17 06:00:00', '2025-06-17 10:00:00', 15, 225000, NULL, 0, 0, 240, 1, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 300000, 0, 300000, 75000, NULL),
(87, 1, 1, 2, 35, 52, 'online', '', NULL, NULL, '2025-06-15 18:37:58', '2025-06-17 06:00:00', '2025-06-17 06:00:00', '2025-06-17 10:00:00', 0, 0, NULL, 0, 0, 240, 1, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 300000, 0, 300000, 0, NULL),
(95, 1, 1, 2, 36, 55, 'online', '', NULL, NULL, '2025-06-15 19:22:38', '2025-06-16 06:00:00', '2025-06-16 06:00:00', '2025-06-16 12:00:00', 10, 250000, NULL, 0, 0, 360, 1, 120, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 480000, 0, 480000, 230000, NULL),
(96, 1, 1, 2, 35, 52, 'online', '', NULL, NULL, '2025-06-15 19:27:01', '2025-06-17 06:00:00', '2025-06-17 06:00:00', '2025-06-17 10:00:00', 20, 300000, NULL, 2, 60000, 240, 1, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 300000, 0, 300000, 60000, NULL),
(97, 1, 1, 17, 37, 58, 'online', '', NULL, NULL, '2025-06-15 19:30:59', '2025-06-16 06:00:00', '2025-06-16 06:00:00', '2025-06-16 10:00:00', 0, 0, NULL, 0, 0, 240, 1, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 240000, 0, 240000, 0, NULL),
(98, 1, 1, 2, 35, 52, 'online', '', NULL, NULL, '2025-06-15 19:32:26', '2025-06-17 06:00:00', '2025-06-17 06:00:00', '2025-06-17 15:00:00', 15, 225000, NULL, 0, 0, 540, 2, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 600000, 0, 300000, 375000, NULL),
(109, 1, 1, 2, 45, 67, 'online', '', NULL, NULL, '2025-07-01 19:06:54', '2025-07-02 06:00:00', '2025-07-02 06:00:00', '2025-07-02 11:00:00', 0, 0, NULL, 0, 0, 300, 1, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 360000, 0, 360000, 0, NULL),
(112, 2, 1, 2, 50, 91, 'POS', 'nguyen van A', NULL, NULL, '2025-08-23 22:50:32', NULL, '2025-08-23 22:50:32', NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(113, 2, 18, 2, 50, 91, 'POS', '', NULL, '24', '2025-08-23 22:57:54', NULL, '2025-08-23 07:45:00', '2025-08-23 23:39:00', 20, 0, NULL, 0, 0, 954, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(114, 2, 145, 2, 35, 52, 'POS', '', NULL, '15', '2025-08-24 08:51:14', NULL, '2025-08-24 09:33:00', '2025-08-24 16:37:00', 20, 300000, NULL, 5, 150000, 424, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(115, 2, 2, 2, 50, 91, 'POS', '', NULL, '3', '2025-08-24 18:22:25', NULL, '2025-08-24 23:01:00', '2025-08-25 17:02:00', 12, 240000, NULL, 0, 0, 1081, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(116, 2, 145, 2, 50, 91, 'POS', '', NULL, '24', '2025-08-24 18:23:56', NULL, '2025-08-24 06:23:00', '2025-08-24 19:00:00', 22, 440000, NULL, 0, 0, 757, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(117, 2, 145, 2, 50, 91, 'POS', '', NULL, '10', '2025-08-25 10:17:34', NULL, '2025-08-27 17:39:00', '2025-08-28 05:38:00', 10, 200000, NULL, 0, 0, 719, 2, 239, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(118, 2, 178, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, NULL, '2025-08-25 14:34:02', NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(119, 2, 178, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, NULL, '2025-08-25 14:34:42', NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(120, 2, 179, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, NULL, '2025-08-25 14:35:01', NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(121, 2, 180, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, NULL, '2025-08-25 14:35:12', NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(122, 2, 181, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, NULL, '2025-08-25 14:35:54', NULL, NULL, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(123, 2, 176, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, '10', '2025-08-25 14:56:09', NULL, '2025-08-27 13:08:00', '2025-08-27 22:41:00', 12, 240000, NULL, 0, 0, 573, 2, 93, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(124, 2, 18, 2, 50, 91, 'POS', 'nguyen thanh', NULL, '10', '2025-08-27 13:51:12', NULL, '2025-08-27 17:42:00', '2025-08-27 20:54:00', 0, 0, NULL, 0, 0, 192, 1, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, 0, 0, NULL),
(125, 2, 167, 2, 50, 91, 'POS', 'Huynh Thi 434', NULL, NULL, '2025-08-27 22:57:21', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', NULL, NULL, NULL, NULL, NULL),
(126, 2, 168, 2, 50, 91, 'POS', 'Huynh Thi 120', NULL, NULL, '2025-08-27 23:15:36', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', NULL, NULL, NULL, NULL, NULL),
(127, 2, 182, 2, 50, 91, 'POS', 'Huynh Thi 140', NULL, NULL, '2025-08-27 23:18:20', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', NULL, NULL, NULL, NULL, NULL),
(128, 2, 184, 2, 50, 91, 'POS', 'Huynh Thi 143', 'NickPOS_1143_631', NULL, '2025-08-27 23:38:03', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', NULL, NULL, NULL, NULL, NULL),
(129, 2, 185, 2, 50, 91, 'POS', 'Huynh Thi 145', 'POS-1145_223', NULL, '2025-08-27 23:39:41', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', NULL, NULL, NULL, NULL, NULL),
(130, 2, 186, 2, 50, 91, 'POS', 'Huynh Thi 147', 'POS-1147-765', '7', '2025-08-27 23:40:14', NULL, '2025-08-27 23:41:00', '2025-08-28 10:41:00', 20, 400000, NULL, 3, 180000, 660, 2, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', NULL, NULL, NULL, NULL, NULL),
(131, 2, 185, 2, 50, 91, 'POS', 'Huynh Thi 145', 'POS-1145_223', NULL, '2025-08-28 07:52:03', NULL, '2025-08-28 03:51:00', '2025-08-28 14:51:00', 20, 400000, NULL, 0, 0, 660, 2, 60, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 115000, 100000, NULL, 316000, NULL),
(132, 2, 185, 2, 50, 91, 'POS', 'Huynh Thi 145', 'POS-1145_223', '28', '2025-08-28 09:41:56', NULL, '2025-08-28 11:27:00', '2025-08-28 18:27:00', 0, 0, NULL, 0, 0, 420, 1, 180, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, NULL, 481000, NULL),
(133, 2, 163, 2, 35, 54, 'POS', 'Bui Van 115', 'nickname_171835', NULL, '2025-08-28 11:43:51', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, NULL, 30000, NULL),
(135, 2, 145, 2, 34, 49, 'POS', 'nguyen thanh', 'nickname_695551', '15', '2025-08-28 16:26:06', NULL, '2025-08-28 09:40:00', '2025-08-28 16:31:00', 0, 0, NULL, 0, 0, 411, 1, 171, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, NULL, 431000, NULL),
(136, 2, 167, 2, 57, 112, 'POS', 'Bui Van 119', 'nickname_953455', '23', '2025-08-28 16:34:46', NULL, NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, 'Chưa thanh toán', 'Tiền mặt', 'Đang chạy', 0, 0, NULL, 0, NULL),
(137, 2, 187, 2, 56, 109, 'POS', '0922222299', 'POS-2299-871', '23', '2025-08-28 17:10:51', NULL, '2025-08-28 07:11:00', '2025-08-28 17:11:00', 0, 0, NULL, 0, 0, 600, 2, 120, 'Chưa thanh toán', 'Tiền mặt', 'Hoàn thành', 0, 0, NULL, 580000, NULL),
(138, 2, 188, 2, 57, 112, 'POS', 'Le van tam', 'POS-1149-605', '17', '2025-08-28 17:25:36', NULL, '2025-08-28 07:25:00', '2025-08-28 17:25:00', 20, 0, NULL, 0, 0, 600, 2, 120, 'Chưa thanh toán', 'Tiền mặt', 'Hoàn thành', 30000, 50000, NULL, 560000, NULL),
(139, 2, 145, 2, 34, 49, 'POS', 'nguyen thanh', 'nickname_695551', '13', '2025-08-28 17:27:24', NULL, '2025-08-29 09:30:00', '2025-08-29 21:30:00', 12, 300000, NULL, 0, 0, 720, 3, 0, 'Chưa thanh toán', 'Chuyển khoản', 'Đang chạy', 12000, 200000, NULL, 212000, NULL),
(140, 2, 1, 2, 34, 49, 'POS', 'Nguyễn Văn A', 'Chim Sẽ Già', '18', '2025-08-29 16:55:26', NULL, '2025-08-29 09:55:00', '2025-08-29 16:55:00', 12, 300000, NULL, 0, 0, 420, 1, 180, 'Đã thanh toán', 'Balance', 'Hoàn thành', 50000, 50000, NULL, 140000, NULL),
(141, 2, 167, 2, 57, 112, 'POS', 'Bui Van 119', 'nickname_953455', '21', '2025-08-29 16:58:51', NULL, '2025-08-29 07:59:00', '2025-08-29 16:59:00', 10, 250000, NULL, 0, 0, 540, 2, 60, 'Đã thanh toán', 'Chuyển khoản', 'Hoàn thành', 0, 0, NULL, 270000, NULL),
(142, 2, 1, 2, 34, 49, 'POS', 'Nguyễn Văn A', 'Chim Sẽ Già', '12', '2025-08-29 20:15:04', NULL, '2025-08-29 10:34:00', '2025-08-29 20:34:00', 10, 250000, NULL, 2, 110000, 600, 2, 120, 'Đã thanh toán', 'Chuyển khoản', 'Hoàn thành', 0, 50000, NULL, 410000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_logs`
--

CREATE TABLE `booking_logs` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `action` varchar(50) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_logs`
--

INSERT INTO `booking_logs` (`id`, `booking_id`, `user_id`, `action`, `note`, `created_at`) VALUES
(53, 34, 2, 'update', 'THAY ĐỔI: thời gian 540 phút || suất 2 || giờ thêm 60 || giảm giá 60000 || trước giảm 540000 || sau giảm 480000 || cá về 0 kg || trả 0 kg || cần thủ đã chuyển: 480000 || tổng cần thanh toán: 0', '2025-06-10 14:52:39'),
(54, 34, 2, 'update', 'THAY ĐỔI: thời gian 0 phút || suất 0 || giờ thêm 0 || giảm giá 0 || trước giảm 0 || sau giảm 0 || cá về 0 kg || trả 40 kg || cần thủ đã chuyển: 480000 || tổng cần thanh toán: -1480000', '2025-06-10 14:53:46'),
(55, 34, 2, 'update', 'THAY ĐỔI: thời gian 0 phút || suất 0 || giờ thêm 0 || giảm giá 0 || trước giảm 0 || sau giảm 0 || cá về 0 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: -500000', '2025-06-10 14:54:29'),
(56, 34, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 420000 || sau giảm 420000 || cá về 0 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: -80000', '2025-06-10 14:54:49'),
(57, 34, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 420000 || sau giảm 420000 || cá về 0 kg || trả 10 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 170000', '2025-06-10 15:23:34'),
(58, 34, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 420000 || sau giảm 420000 || cá về 0 kg || trả 10 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 170000', '2025-06-10 15:24:18'),
(59, 34, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 420000 || sau giảm 420000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 45000', '2025-06-10 15:30:54'),
(60, 34, 2, 'update', 'THAY ĐỔI: thời gian 427 phút || suất 1 || giờ thêm 187 || giảm giá 0 || trước giảm 427000 || sau giảm 427000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 52000', '2025-06-10 15:32:16'),
(61, 34, 2, 'update', 'THAY ĐỔI: thời gian 427 phút || suất 1 || giờ thêm 187 || giảm giá 0 || trước giảm 427000 || sau giảm 427000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 52000', '2025-06-10 15:35:08'),
(62, 34, 2, 'update', 'THAY ĐỔI: thời gian 487 phút || suất 2 || giờ thêm 7 || giảm giá 60000 || trước giảm 487000 || sau giảm 427000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 52000', '2025-06-10 15:35:23'),
(63, 34, 2, 'update', 'THAY ĐỔI: thời gian 487 phút || suất 2 || giờ thêm 7 || giảm giá 60000 || trước giảm 607000 || sau giảm 547000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 322000', '2025-06-10 15:42:21'),
(64, 34, 2, 'update', 'THAY ĐỔI: thời gian 487 phút || suất 2 || giờ thêm 7 || giảm giá 60000 || trước giảm 607000 || sau giảm 547000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 480000 || tổng cần thanh toán: -158000', '2025-06-13 19:36:59'),
(65, 34, 2, 'update', 'THAY ĐỔI: thời gian 487 phút || suất 2 || giờ thêm 7 || giảm giá 60000 || trước giảm 607000 || sau giảm 547000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 322000', '2025-06-13 19:37:33'),
(66, 40, 1, 'Khởi tạo booking', 'booking: 5 tiếng || Số suất: 1 || Giờ thêm: 1 || Tổng chưa giảm: 360.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 360.000 đ', '2025-06-13 19:52:28'),
(67, 40, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 480000 || sau giảm 480000 || cá về 0 kg || trả 0 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 480000', '2025-06-13 19:56:11'),
(68, 40, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 0 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 540000', '2025-06-13 20:07:33'),
(69, 40, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 375000', '2025-06-13 20:08:22'),
(70, 40, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 360000 || tổng cần thanh toán: -45000', '2025-06-13 22:50:26'),
(71, 40, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-13 22:50:43'),
(72, 40, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-13 22:50:48'),
(73, 34, 2, 'update', 'THAY ĐỔI: thời gian 547 phút || suất 2 || giờ thêm 67 || giảm giá 60000 || trước giảm 667000 || sau giảm 607000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 382000', '2025-06-14 17:44:01'),
(74, 33, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 480000 || sau giảm 420000 || cá về 0 kg || trả 0 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 420000', '2025-06-14 17:46:14'),
(75, 34, 2, 'update', 'THAY ĐỔI: thời gian 547 phút || suất 2 || giờ thêm 67 || giảm giá 60000 || trước giảm 667000 || sau giảm 607000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 382000', '2025-06-14 18:17:00'),
(76, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-14 18:18:31'),
(77, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-14 18:28:47'),
(78, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-14 18:29:03'),
(79, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-14 18:36:24'),
(80, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-14 18:36:28'),
(81, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:36:42'),
(82, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:36:59'),
(83, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:38:28'),
(84, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:38:33'),
(85, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:38:39'),
(86, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:38:51'),
(87, 34, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 300000', '2025-06-14 18:39:35'),
(88, 40, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 600000 || sau giảm 540000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 315000', '2025-06-14 18:41:33'),
(89, 40, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 480000 || sau giảm 480000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 255000', '2025-06-14 18:42:02'),
(90, 40, 2, 'update', 'THAY ĐỔI: thời gian 360 phút || suất 1 || giờ thêm 120 || giảm giá 0 || trước giảm 420000 || sau giảm 420000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 195000', '2025-06-14 18:47:28'),
(91, 40, 2, 'update', 'THAY ĐỔI: thời gian 960 phút || suất 4 || giờ thêm 0 || giảm giá 180000 || trước giảm 1200000 || sau giảm 1020000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 795000', '2025-06-14 18:47:56'),
(92, 40, 2, 'update', 'THAY ĐỔI: thời gian 240 phút || suất 1 || giờ thêm 0 || giảm giá 0 || trước giảm 300000 || sau giảm 300000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển: 0 || tổng cần thanh toán: 75000', '2025-06-14 18:48:16'),
(93, 40, 2, 'update', 'THAY ĐỔI: thời gian 300 phút || suất 1 || giờ thêm 60 || giảm giá 0 || trước giảm 360000 || sau giảm 360000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 135000', '2025-06-14 18:54:57'),
(120, 40, 2, 'update', 'THAY ĐỔI: thời gian 300 phút || suất 1 || giờ thêm 60 || giảm giá 0 || trước giảm 360000 || sau giảm 360000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 135000', '2025-06-14 21:53:20'),
(121, 40, 2, 'update', 'THAY ĐỔI: thời gian 300 phút || suất 1 || giờ thêm 60 || giảm giá 0 || trước giảm 360000 || sau giảm 360000 || cá về 0 kg || trả 30 kg || cần thủ đã chuyển:  || tổng cần thanh toán: -90000', '2025-06-14 21:53:30'),
(122, 40, 2, 'hoan_thanh', 'Booking hoàn thành, phương thức: Số dư user', '2025-06-14 21:53:36'),
(126, 47, 1, 'Khởi tạo booking', 'booking: 7 tiếng || Số suất: 1 || Giờ thêm: 3 || Tổng chưa giảm: 600.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 600.000 đ', '2025-06-14 22:18:05'),
(127, 47, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 480000 || sau giảm 420000 || cá về 0 kg || trả 50 kg || cần thủ đã chuyển:  || tổng cần thanh toán: -830000', '2025-06-14 22:19:14'),
(128, 47, 2, 'update', 'THAY ĐỔI: thời gian 480 phút || suất 2 || giờ thêm 0 || giảm giá 60000 || trước giảm 480000 || sau giảm 420000 || cá về 0 kg || trả 50 kg || cần thủ đã chuyển:  || tổng cần thanh toán: -830000', '2025-06-14 22:19:32'),
(129, 47, 2, 'hoan_thanh', 'Booking hoàn thành, phương thức: Số dư user', '2025-06-14 22:19:38'),
(146, 62, 1, 'Khởi tạo booking', 'booking: 5 tiếng || Số suất: 1 || Giờ thêm: 1 || Tổng chưa giảm: 360.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 360.000 đ', '2025-06-14 23:32:23'),
(185, 74, 1, 'Khởi tạo booking', 'booking: 4 tiếng || Số suất: 1 || Giờ thêm: 0 || Tổng chưa giảm: 300.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 300.000 đ', '2025-06-15 16:34:49'),
(191, 74, 2, 'update', 'THAY ĐỔI: thời gian 240 phút || suất 1 || giờ thêm 0 || giảm giá 0 || trước giảm 300000 || sau giảm 300000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 75000', '2025-06-15 16:45:54'),
(192, 74, 2, 'update', 'THAY ĐỔI: thời gian 240 phút || suất 1 || giờ thêm 0 || giảm giá 0 || trước giảm 300000 || sau giảm 300000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 75000', '2025-06-15 16:45:58'),
(193, 74, 2, 'hoan_thanh', 'Booking hoàn thành, phương thức: Số dư user', '2025-06-15 16:46:01'),
(232, 87, 1, 'Khởi tạo booking', 'booking: 4 tiếng || Số suất: 1 || Giờ thêm: 0 || Tổng chưa giảm: 300.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 300.000 đ', '2025-06-15 18:37:58'),
(233, 87, 2, 'cancel', 'Chủ hồ huỷ booking do quá hạn', '2025-06-15 18:38:33'),
(255, 95, 1, 'Khởi tạo booking', 'booking: 6 tiếng || Số suất: 1 || Giờ thêm: 2 || Tổng chưa giảm: 480.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 480.000 đ', '2025-06-15 19:22:38'),
(256, 95, 2, 'update', 'THAY ĐỔI: thời gian 360 phút || suất 1 || giờ thêm 120 || giảm giá 0 || trước giảm 480000 || sau giảm 480000 || cá về 0 kg || trả 10 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 230000', '2025-06-15 19:22:52'),
(257, 95, 2, 'hoan_thanh', 'Booking hoàn thành, phương thức: Số dư user', '2025-06-15 19:22:56'),
(258, 96, 1, 'Khởi tạo booking', 'booking: 4 tiếng || Số suất: 1 || Giờ thêm: 0 || Tổng chưa giảm: 300.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 300.000 đ', '2025-06-15 19:27:01'),
(259, 96, 2, 'update', 'THAY ĐỔI: thời gian 240 phút || suất 1 || giờ thêm 0 || giảm giá 0 || trước giảm 300000 || sau giảm 300000 || cá về 2 kg || trả 20 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 60000', '2025-06-15 19:27:18'),
(260, 96, 2, 'hoan_thanh', 'Booking hoàn thành, phương thức: Số dư user', '2025-06-15 19:27:29'),
(261, 97, 1, 'Khởi tạo booking', 'booking: 4 tiếng || Số suất: 1 || Giờ thêm: 0 || Tổng chưa giảm: 240.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 240.000 đ', '2025-06-15 19:30:59'),
(262, 98, 1, 'Khởi tạo booking', 'booking: 4 tiếng || Số suất: 1 || Giờ thêm: 0 || Tổng chưa giảm: 300.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 300.000 đ', '2025-06-15 19:32:26'),
(263, 98, 2, 'update', 'THAY ĐỔI: thời gian 240 phút || suất 1 || giờ thêm 0 || giảm giá 0 || trước giảm 300000 || sau giảm 300000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 75000', '2025-06-15 19:32:57'),
(264, 98, 2, 'update', 'THAY ĐỔI: thời gian 540 phút || suất 2 || giờ thêm 60 || giảm giá 60000 || trước giảm 660000 || sau giảm 600000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 375000', '2025-06-15 19:33:27'),
(265, 98, 2, 'update', 'THAY ĐỔI: thời gian 420 phút || suất 1 || giờ thêm 180 || giảm giá 0 || trước giảm 480000 || sau giảm 480000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 255000', '2025-06-15 19:34:09'),
(266, 98, 2, 'update', 'THAY ĐỔI: thời gian 540 phút || suất 2 || giờ thêm 60 || giảm giá 60000 || trước giảm 660000 || sau giảm 600000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 375000', '2025-06-15 19:34:57'),
(267, 98, 2, 'update', 'THAY ĐỔI: thời gian 540 phút || suất 2 || giờ thêm 60 || giảm giá 60000 || trước giảm 660000 || sau giảm 600000 || cá về 0 kg || trả 15 kg || cần thủ đã chuyển:  || tổng cần thanh toán: 375000', '2025-06-15 19:35:14'),
(268, 98, 2, 'hoan_thanh', 'Booking hoàn thành, phương thức: Số dư user', '2025-06-15 19:35:20'),
(295, 109, 1, 'Khởi tạo booking', 'booking: 5 tiếng || Số suất: 1 || Giờ thêm: 1 || Tổng chưa giảm: 360.000 đ || Giảm giá: - 0 đ ||  Tổng sau giảm: 360.000 đ', '2025-07-01 19:06:54'),
(296, 117, 2, 'delete_service', 'Xoá dịch vụ ID #1', '2025-08-25 10:57:07'),
(297, 117, 2, 'add_service', 'Thêm dịch vụ com, SL 1, ĐG 40000, TT 40000', '2025-08-25 11:06:45'),
(298, 117, 2, 'add_service', 'Thêm dịch vụ nuoc, SL 1, ĐG 20000, TT 20000', '2025-08-25 11:10:50'),
(299, 117, 2, 'delete_service', 'Xoá dịch vụ ID #2', '2025-08-25 11:11:06'),
(300, 117, 2, 'delete_service', 'Xoá dịch vụ ID #3', '2025-08-25 12:10:12'),
(301, 117, 2, 'add_service', 'Thêm dịch vụ do_an, SL 0.99, ĐG 20000, TT 19800', '2025-08-25 12:10:45'),
(302, 117, 2, 'add_service', 'Thêm dịch vụ com, SL 1, ĐG 34000, TT 34000', '2025-08-25 12:11:11'),
(303, 117, 2, 'delete_service', 'Xoá dịch vụ ID #4', '2025-08-25 12:24:35'),
(304, 117, 2, 'delete_service', 'Xoá dịch vụ ID #5', '2025-08-25 12:35:56'),
(305, 118, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922222242', '2025-08-25 14:34:02'),
(306, 119, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922222242', '2025-08-25 14:34:42'),
(307, 120, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922222252', '2025-08-25 14:35:01'),
(308, 121, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922233252', '2025-08-25 14:35:12'),
(309, 122, 2, 'create_pos', 'Tạo booking POS cho SĐT 0935192070', '2025-08-25 14:35:54'),
(310, 123, 2, 'create_pos', 'Tạo booking POS cho SĐT 0343808951', '2025-08-25 14:56:09'),
(311, 124, 2, 'create_pos', 'Tạo booking POS cho SĐT 0902222225', '2025-08-27 13:51:12'),
(312, 124, 2, 'add_service', 'Thêm dịch vụ thuoc, SL 1, ĐG 50000, TT 50000', '2025-08-27 13:57:56'),
(313, 124, 2, 'add_service', 'Thêm dịch vụ thuoc, SL 1, ĐG 23000, TT 23000', '2025-08-27 17:14:45'),
(314, 124, 2, 'delete_service', 'Xoá dịch vụ ID #7', '2025-08-27 17:18:23'),
(315, 124, 2, 'delete_service', 'Xoá dịch vụ ID #8', '2025-08-27 17:18:26'),
(316, 117, 2, 'delete_service', 'Xoá dịch vụ ID #6', '2025-08-27 17:19:12'),
(317, 117, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 24000, TT 24000', '2025-08-27 17:21:41'),
(318, 117, 2, 'add_service', 'Thêm dịch vụ Nước, SL 1, ĐG 10000, TT 10000', '2025-08-27 17:22:22'),
(319, 117, 2, 'add_service', 'Thêm dịch vụ Cơm, SL 1, ĐG 30000, TT 30000', '2025-08-27 17:22:29'),
(320, 117, 2, 'add_service', 'Thêm dịch vụ Mỳ, SL 1, ĐG 25000, TT 25000', '2025-08-27 17:22:37'),
(321, 117, 2, 'add_service', 'Thêm dịch vụ Mồi câu, SL 1, ĐG 55000, TT 55000', '2025-08-27 17:22:46'),
(322, 124, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 50000, TT 50000', '2025-08-27 18:02:01'),
(323, 124, 2, 'delete_service', 'Xoá dịch vụ ID #14', '2025-08-27 19:52:47'),
(324, 124, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 10000, TT 10000', '2025-08-27 20:09:32'),
(325, 124, 2, 'delete_service', 'Xoá dịch vụ ID #15', '2025-08-27 20:11:48'),
(326, 124, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 50000, TT 50000', '2025-08-27 20:53:44'),
(327, 124, 2, 'delete_service', 'Xoá dịch vụ ID #16', '2025-08-27 21:01:23'),
(328, 124, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 30000, TT 30000', '2025-08-27 21:07:05'),
(329, 125, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111119', '2025-08-27 22:57:21'),
(330, 126, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111120', '2025-08-27 23:15:36'),
(331, 127, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111140', '2025-08-27 23:18:20'),
(332, 128, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111143', '2025-08-27 23:38:03'),
(333, 129, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111145', '2025-08-27 23:39:41'),
(334, 130, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111147', '2025-08-27 23:40:14'),
(335, 130, 2, 'add_service', 'Thêm dịch vụ Mồi câu, SL 1, ĐG 100000, TT 100000', '2025-08-27 23:42:39'),
(336, 131, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111145', '2025-08-28 07:52:03'),
(337, 131, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 30000, TT 30000', '2025-08-28 08:10:03'),
(338, 131, 2, 'delete_service', 'Xoá dịch vụ ID #19', '2025-08-28 08:12:20'),
(339, 131, 2, 'add_service', 'Thêm dịch vụ Nước, SL 1, ĐG 15000, TT 15000', '2025-08-28 08:13:01'),
(340, 131, 2, 'add_service', 'Thêm dịch vụ Cơm, SL 1, ĐG 50000, TT 50000', '2025-08-28 09:03:02'),
(341, 131, 2, 'add_service', 'Thêm dịch vụ Cơm, SL 1, ĐG 50000, TT 50000', '2025-08-28 09:04:03'),
(342, 132, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111145', '2025-08-28 09:41:56'),
(343, 133, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111115', '2025-08-28 11:43:51'),
(345, 135, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111111', '2025-08-28 16:26:06'),
(346, 136, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111119', '2025-08-28 16:34:46'),
(347, 137, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922222299', '2025-08-28 17:10:51'),
(348, 138, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111149', '2025-08-28 17:25:36'),
(349, 138, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 30000, TT 30000', '2025-08-28 17:26:17'),
(350, 139, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111111', '2025-08-28 17:27:24'),
(351, 140, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922222222', '2025-08-29 16:55:26'),
(352, 140, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 50000, TT 50000', '2025-08-29 16:56:24'),
(353, 141, 2, 'create_pos', 'Tạo booking POS cho SĐT 0911111119', '2025-08-29 16:58:51'),
(354, 141, 2, 'finalize', 'Hoàn thành: thanh toán chuyển khoản', '2025-08-29 18:17:22'),
(355, 141, 2, 'finalize', 'Hoàn thành: thanh toán chuyển khoản', '2025-08-29 18:18:28'),
(356, 141, 2, 'finalize', 'Hoàn thành: thanh toán chuyển khoản', '2025-08-29 18:53:39'),
(357, 141, 2, 'finalize', 'Hoàn thành: thanh toán chuyển khoản', '2025-08-29 18:56:23'),
(358, 141, 2, 'finalize', 'Hoàn thành: thanh toán chuyển khoản', '2025-08-29 19:00:23'),
(359, 139, 2, 'add_service', 'Thêm dịch vụ Thuốc lá, SL 1, ĐG 12000, TT 12000', '2025-08-29 19:10:51'),
(360, 140, 2, 'finalize', 'Hoàn thành: thanh toán tiền mặt', '2025-08-29 19:57:59'),
(361, 142, 2, 'create_pos', 'Tạo booking POS cho SĐT 0922222222', '2025-08-29 20:15:04'),
(362, 142, 2, 'finalize', 'Hoàn thành: thanh toán chuyển khoản', '2025-08-29 20:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `booking_payment_logs`
--

CREATE TABLE `booking_payment_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `amount` int NOT NULL,
  `action` enum('hold','refund','received','sent') DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_payment_logs`
--

INSERT INTO `booking_payment_logs` (`id`, `user_id`, `booking_id`, `amount`, `action`, `note`, `created_at`) VALUES
(1, 1, 40, -50000, 'hold', 'Giữ tiền khi đặt booking', '2025-06-14 12:19:08'),
(2, 1, 40, 39000, 'refund', 'Hoàn lại sau khi trừ phí', '2025-06-14 12:20:23'),
(3, 1, 41, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:04:16'),
(4, 1, 42, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:15:53'),
(5, 1, 43, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:25:05'),
(6, 1, 44, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:26:22'),
(7, 1, 44, 115000, 'sent', 'Cần thủ thanh toán', '2025-06-14 21:26:22'),
(8, 2, 44, 115000, 'received', 'Chủ hồ nhận thanh toán', '2025-06-14 21:26:22'),
(9, 1, 45, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:38:08'),
(10, 1, 45, 115000, 'sent', 'Cần thủ thanh toán', '2025-06-14 21:38:08'),
(11, 2, 45, 115000, 'received', 'Chủ hồ nhận thanh toán', '2025-06-14 21:38:08'),
(12, 1, 44, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:45:27'),
(13, 1, 45, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:46:32'),
(14, 1, 40, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 21:53:36'),
(15, 1, 40, 90000, 'sent', 'Cần thủ thanh toán', '2025-06-14 21:53:37'),
(16, 2, 40, 90000, 'received', 'Chủ hồ nhận thanh toán', '2025-06-14 21:53:37'),
(17, 1, 46, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 22:06:28'),
(18, 2, 46, 10000, 'sent', 'Chủ hồ hoàn tiền cho cần thủ', '2025-06-14 22:06:28'),
(19, 1, 46, 10000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-14 22:06:28'),
(20, 1, 47, 35000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 22:19:38'),
(21, 2, 47, 830000, 'sent', 'Chủ hồ hoàn tiền cho cần thủ', '2025-06-14 22:19:38'),
(22, 1, 47, 830000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-14 22:19:38'),
(23, 1, 48, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 22:24:19'),
(24, 2, 48, -510000, 'sent', 'Chủ hồ hoàn tiền cho cần thủ', '2025-06-14 22:24:19'),
(25, 1, 48, 510000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-14 22:24:19'),
(26, 1, 49, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 22:30:03'),
(27, 1, 49, -240000, 'sent', 'Cần thủ thanh toán', '2025-06-14 22:30:03'),
(28, 2, 49, 240000, 'received', 'Chủ hồ nhận thanh toán', '2025-06-14 22:30:03'),
(29, 1, 50, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 22:32:42'),
(30, 2, 50, -450000, 'sent', 'Chủ hồ hoàn tiền cho cần thủ', '2025-06-14 22:32:42'),
(31, 1, 50, 450000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-14 22:32:42'),
(32, 1, 57, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:16:32'),
(33, 1, 58, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:17:28'),
(34, 1, 59, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:22:51'),
(35, 1, 60, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:27:22'),
(36, 1, 61, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:29:56'),
(37, 1, 60, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 23:31:46'),
(38, 1, 62, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:32:23'),
(39, 1, 63, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:36:39'),
(40, 1, 64, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:37:20'),
(41, 1, 61, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 23:40:47'),
(42, 1, 65, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:41:07'),
(43, 1, 65, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 23:41:49'),
(44, 2, 65, -325000, 'sent', 'Chủ hồ hoàn tiền cho cần thủ', '2025-06-14 23:41:49'),
(45, 1, 65, 325000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-14 23:41:49'),
(46, 1, 64, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 23:43:16'),
(47, 1, 64, -300000, 'sent', 'Cần thủ thanh toán', '2025-06-14 23:43:16'),
(48, 2, 64, 300000, 'received', 'Chủ hồ nhận thanh toán', '2025-06-14 23:43:16'),
(49, 1, 66, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-14 23:43:35'),
(50, 1, 66, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-14 23:43:53'),
(51, 1, 67, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 13:49:02'),
(52, 1, 67, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 14:22:42'),
(53, 1, 68, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 14:24:03'),
(54, 1, 68, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 14:26:22'),
(55, 1, 69, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 14:45:32'),
(56, 2, 69, -330000, 'sent', 'Chủ hồ hoàn tiền cho cần thủ', '2025-06-15 14:46:55'),
(57, 1, 69, 330000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-15 14:46:55'),
(58, 1, 69, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 14:46:55'),
(59, 1, 63, 50000, 'sent', 'Cần thủ thanh toán', '2025-06-15 16:07:12'),
(60, 2, 63, 50000, 'received', 'Chủ hồ nhận thanh toán', '2025-06-15 16:07:12'),
(61, 1, 63, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:07:12'),
(62, 1, 70, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:13:36'),
(63, 2, 70, -200000, 'sent', 'Chủ hồ hoàn tiền cá dư cho booking #70 user #1', '2025-06-15 16:14:04'),
(64, 1, 70, 200000, 'received', 'Cần thủ nhận hoàn tiền', '2025-06-15 16:14:04'),
(65, 1, 70, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:14:04'),
(66, 1, 71, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:22:23'),
(67, 1, 71, 115000, 'sent', 'Đã trừ tiền cá, thanh toán booking #71', '2025-06-15 16:23:02'),
(68, 2, 71, 115000, 'received', 'Nhận thanh toán từ booking #71', '2025-06-15 16:23:02'),
(69, 1, 71, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:23:02'),
(70, 1, 72, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:34:18'),
(71, 1, 73, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:34:36'),
(72, 1, 74, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:34:49'),
(73, 2, 72, -10000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu #72 user #1', '2025-06-15 16:35:34'),
(74, 1, 72, 10000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #72', '2025-06-15 16:35:34'),
(75, 1, 72, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:35:34'),
(76, 2, 73, -10000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu #73 user #1  4625000', '2025-06-15 16:41:22'),
(77, 1, 73, 10000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #73', '2025-06-15 16:41:22'),
(78, 1, 73, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:41:22'),
(79, 1, 74, -75000, 'sent', 'Đã trừ tiền cá, thanh toán booking #74', '2025-06-15 16:46:01'),
(80, 2, 74, 75000, 'received', 'Nhận thanh toán từ booking #74', '2025-06-15 16:46:01'),
(81, 1, 74, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:46:01'),
(82, 1, 75, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:46:47'),
(83, 2, 75, -135000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu #75 user #1  Số dư: 4555000', '2025-06-15 16:47:03'),
(84, 1, 75, 135000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #75', '2025-06-15 16:47:03'),
(85, 1, 75, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:47:03'),
(86, 1, 76, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:54:03'),
(87, 2, 76, -10000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #76 | user: #1 || Số dư: 4545000', '2025-06-15 16:54:27'),
(88, 1, 76, 10000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #76 | số dư: 455000', '2025-06-15 16:54:27'),
(89, 1, 76, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:54:27'),
(90, 1, 77, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:56:53'),
(91, 2, 77, -10000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #77 | user: #1 || Số dư: 4535000', '2025-06-15 16:57:27'),
(92, 1, 77, 10000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #77 | số dư: 513000', '2025-06-15 16:57:27'),
(93, 1, 77, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:57:27'),
(94, 1, 78, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 16:58:48'),
(95, 2, 78, -75000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #78 | user: #1 || Số dư: 4460000', '2025-06-15 16:59:08'),
(96, 1, 78, 75000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #78 | số dư: 567000', '2025-06-15 16:59:08'),
(97, 1, 78, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 16:59:08'),
(98, 1, 79, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 17:00:27'),
(99, 2, 79, -260000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #79 | user: #1 || Số dư: 4200000', '2025-06-15 17:00:44'),
(100, 1, 79, 260000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #79 | số dư: 816000', '2025-06-15 17:00:44'),
(101, 1, 79, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 17:00:44'),
(102, 1, 80, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 17:08:44'),
(103, 2, 80, -10000, 'sent', 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #80 | user: #1 || Số dư: 4190000', '2025-06-15 17:09:01'),
(104, 1, 80, 10000, 'received', 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #80 | số dư: 776000', '2025-06-15 17:09:01'),
(105, 1, 80, 39000, 'refund', 'Hoàn tiền giữ chỗ sau khi trừ phí & VAT', '2025-06-15 17:09:01'),
(106, 1, 81, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 17:59:03'),
(107, 2, 81, -10000, 'sent', 'Vé câu #81, Chủ hồ bị bẻ răng | Số dư cuối #81: 4180000', '2025-06-15 18:00:44'),
(108, 1, 81, 10000, 'received', 'Vé câu #81, nhận tiền bẻ răng chủ hồ | Số dư cuối #81: 775000', '2025-06-15 18:00:44'),
(109, 1, 81, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #81 sau khi trừ phí & VAT ||  Số dư: 814000', '2025-06-15 18:00:44'),
(110, 1, 82, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 18:03:56'),
(111, 1, 82, -115000, 'sent', 'Vé câu #82, bị chủ hồ bẻ răng | Số dư cuối: 649000', '2025-06-15 18:04:16'),
(112, 2, 82, 115000, 'received', 'Nhận thanh toán từ booking #82', '2025-06-15 18:04:16'),
(113, 1, 82, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #82 sau khi trừ phí & VAT ||  Số dư: 688000', '2025-06-15 18:04:16'),
(114, 1, 83, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 18:09:36'),
(115, 1, 83, -115000, 'sent', 'Vé câu #83, bị chủ hồ bẻ răng | Số dư cuối: 523000', '2025-06-15 18:09:54'),
(116, 2, 83, 115000, 'received', 'Vé câu #83, bẻ răng Cần thủ | Số dư cuối #83: 4410000', '2025-06-15 18:09:54'),
(117, 1, 83, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #83 sau khi trừ phí & VAT ||  Số dư: 562000', '2025-06-15 18:09:54'),
(118, 1, 84, -50000, 'hold', 'Trừ tiền giữ chỗ khi tạo booking', '2025-06-15 18:12:01'),
(119, 1, 84, -115000, 'sent', 'Vé câu #84, bị chủ hồ bẻ răng | Số dư cuối: 397000', '2025-06-15 18:12:17'),
(120, 2, 84, 115000, 'received', 'Vé câu #84, bẻ răng Cần thủ | Số dư cuối #84: 4525000', '2025-06-15 18:12:17'),
(121, 1, 84, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #84 sau khi trừ phí & VAT ||  Số dư: 436000', '2025-06-15 18:12:17'),
(122, 1, 85, -50000, 'hold', 'Vé câu #85, Đặt booking giữ chỗ | Số dư cuối: 436000', '2025-06-15 18:16:22'),
(123, 1, 86, -50000, 'hold', 'Vé câu #86, Đặt booking giữ chỗ | Số dư cuối: 386000', '2025-06-15 18:18:59'),
(124, 2, 85, -135000, 'sent', 'Vé câu #85: Chủ hồ bị bẻ răng | Số dư cuối #85: 4390000', '2025-06-15 18:22:25'),
(125, 1, 85, 135000, 'received', 'Vé câu #85: Nhận tiền bẻ răng chủ hồ | Số dư cuối #85: 471000', '2025-06-15 18:22:25'),
(126, 1, 85, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #85 sau khi trừ phí & VAT ||  Số dư: 510000', '2025-06-15 18:22:25'),
(127, 1, 86, 39000, 'refund', 'Vé câu #booking_id: Hoàn phí giữ chổ || Số dư cuối: 588000', '2025-06-15 18:35:18'),
(128, 1, 87, -50000, 'hold', 'Vé câu #87, Đặt booking giữ chỗ | Số dư cuối: 588000', '2025-06-15 18:37:58'),
(129, 1, 87, 39000, 'refund', 'Vé câu #booking_id: Hoàn phí giữ chổ || Số dư cuối: 577000', '2025-06-15 18:38:33'),
(130, 1, 88, -50000, 'hold', 'Vé câu #88, Đặt booking giữ chỗ | Số dư cuối: 577000', '2025-06-15 18:39:41'),
(131, 1, 88, 39000, 'refund', 'Vé câu #88: Hoàn phí giữ chổ || Số dư cuối: 566000', '2025-06-15 18:39:56'),
(132, 1, 89, -50000, 'hold', 'Vé câu #89, Đặt booking giữ chỗ | Số dư cuối: 566000', '2025-06-15 18:41:10'),
(133, 2, 89, -260000, 'sent', 'Vé câu #89: Chủ hồ bị bẻ răng | Số dư cuối #89: 4130000', '2025-06-15 18:41:37'),
(134, 1, 89, 260000, 'received', 'Vé câu #89: Nhận tiền bẻ răng chủ hồ | Số dư cuối #89: 776000', '2025-06-15 18:41:37'),
(135, 1, 89, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #89 sau khi trừ phí & VAT ||  Số dư: 815000', '2025-06-15 18:41:37'),
(136, 1, 90, -50000, 'hold', 'Vé câu #90, Đặt booking giữ chỗ | Số dư cuối: 815000', '2025-06-15 19:02:24'),
(137, 2, 90, -260000, 'sent', 'Vé câu #90: Chủ hồ bị bẻ răng | Số dư cuối #90: 3870000', '2025-06-15 19:03:03'),
(138, 1, 90, 260000, 'received', 'Vé câu #90: Nhận tiền bẻ răng chủ hồ | Số dư cuối #90: 1025000', '2025-06-15 19:03:03'),
(139, 2, 90, -260000, 'sent', 'Vé câu #90: Chủ hồ bị bẻ răng | Số dư cuối #90: 3610000', '2025-06-15 19:05:44'),
(140, 1, 90, 260000, 'received', 'Vé câu #90: Nhận tiền bẻ răng chủ hồ | Số dư cuối #90: 1285000', '2025-06-15 19:05:44'),
(141, 1, 90, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #90 sau khi trừ phí & VAT ||  Số dư: 1324000', '2025-06-15 19:05:44'),
(142, 1, 91, -50000, 'hold', 'Vé câu #91, Đặt booking giữ chỗ | Số dư cuối: 1324000', '2025-06-15 19:12:02'),
(143, 1, 91, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #91 sau khi trừ phí & VAT ||  Số dư: 1313000', '2025-06-15 19:12:26'),
(144, 1, 92, -50000, 'hold', 'Vé câu #92, Đặt booking giữ chỗ | Số dư cuối: 1313000', '2025-06-15 19:17:54'),
(145, 1, 92, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #92 sau khi trừ phí & VAT ||  Số dư: 1302000', '2025-06-15 19:18:14'),
(146, 1, 93, -50000, 'hold', 'Vé câu #93, Đặt booking giữ chỗ | Số dư cuối: 1302000', '2025-06-15 19:20:01'),
(147, 2, 93, -135000, 'sent', 'Vé câu #93: Chủ hồ bị bẻ răng | Số dư cuối #93: 3475000', '2025-06-15 19:20:17'),
(148, 1, 93, 135000, 'received', 'Vé câu #93: Nhận tiền bẻ răng chủ hồ | Số dư cuối #93: 1387000', '2025-06-15 19:20:17'),
(149, 1, 93, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #93 sau khi trừ phí & VAT ||  Số dư: 1426000', '2025-06-15 19:20:17'),
(150, 1, 94, -50000, 'hold', 'Vé câu #94, Đặt booking giữ chỗ | Số dư cuối: 1426000', '2025-06-15 19:21:53'),
(151, 1, 94, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #94 sau khi trừ phí & VAT ||  Số dư: 1415000', '2025-06-15 19:22:22'),
(152, 1, 95, -50000, 'hold', 'Vé câu #95, Đặt booking giữ chỗ | Số dư cuối: 1415000', '2025-06-15 19:22:38'),
(153, 1, 95, -230000, 'sent', 'Vé câu #95: Bị chủ hồ bẻ răng | Số dư cuối: 1135000', '2025-06-15 19:22:56'),
(154, 2, 95, 230000, 'received', 'Vé câu #95: Bẻ răng Cần thủ | Số dư cuối #95: 3705000', '2025-06-15 19:22:56'),
(155, 1, 95, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #95 sau khi trừ phí & VAT ||  Số dư: 1174000', '2025-06-15 19:22:56'),
(156, 1, 96, -50000, 'hold', 'Vé câu #96, Đặt booking giữ chỗ | Số dư cuối: 1174000 vnd', '2025-06-15 19:27:01'),
(157, 1, 96, -60000, 'sent', 'Vé câu #96: Bị chủ hồ bẻ răng | Số dư cuối: 1064000 vnd', '2025-06-15 19:27:29'),
(158, 2, 96, 60000, 'received', 'Vé câu #96: Bẻ răng Cần thủ | Số dư cuối #96: 3765000 vnd', '2025-06-15 19:27:29'),
(159, 1, 96, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #96 sau khi trừ phí & VAT ||  Số dư: 1103000 vnd', '2025-06-15 19:27:29'),
(160, 1, 97, -50000, 'hold', 'Vé câu #97, Đặt booking giữ chỗ | Số dư cuối: 1103000 vnd', '2025-06-15 19:30:59'),
(161, 1, 98, -50000, 'hold', 'Vé câu #98, Đặt booking giữ chỗ | Số dư cuối: 1053000 vnd', '2025-06-15 19:32:26'),
(162, 1, 98, -375000, 'sent', 'Vé câu #98: Bị chủ hồ bẻ răng | Số dư cuối: 628000 vnd', '2025-06-15 19:35:20'),
(163, 2, 98, 375000, 'received', 'Vé câu #98: Bẻ răng Cần thủ | Số dư cuối #98: 4140000 vnd', '2025-06-15 19:35:20'),
(164, 1, 98, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #98 sau khi trừ phí & VAT ||  Số dư: 667000 vnd', '2025-06-15 19:35:20'),
(165, 1, 99, -50000, 'hold', 'Vé câu #99, Đặt booking giữ chỗ | Số dư cuối: 667000 vnd', '2025-06-15 19:44:00'),
(166, 2, 99, -310000, 'sent', 'Vé câu #99: Chủ hồ bị bẻ răng | Số dư cuối #99: 3830000 vnd', '2025-06-15 19:44:29'),
(167, 1, 99, 310000, 'received', 'Vé câu #99: Nhận tiền bẻ răng chủ hồ | Số dư cuối #99: 927000 vnd', '2025-06-15 19:44:29'),
(168, 1, 99, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #99 sau khi trừ phí & VAT ||  Số dư: 966000 vnd', '2025-06-15 19:44:29'),
(169, 1, 100, -50000, 'hold', 'Vé câu #100, Đặt booking giữ chỗ | Số dư cuối: 966000 vnd', '2025-06-15 19:46:47'),
(170, 2, 100, -450000, 'sent', 'Vé câu #100: Bị cần thủ bẻ răng | Số dư cuối #100: 3380000 vnd', '2025-06-15 19:47:18'),
(171, 1, 100, 450000, 'received', 'Vé câu #100: Nhận tiền bẻ răng chủ hồ | Số dư cuối #100: 1366000 vnd', '2025-06-15 19:47:18'),
(172, 1, 100, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #100 sau khi trừ phí & VAT ||  Số dư: 1405000 vnd', '2025-06-15 19:47:18'),
(173, 1, 101, -50000, 'hold', 'Vé câu #101, Đặt booking giữ chỗ | Số dư cuối: 1405000 vnd', '2025-06-15 20:12:26'),
(174, 1, 101, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #101 sau khi trừ phí & VAT ||  Số dư: 1394000 vnd', '2025-06-15 20:14:56'),
(175, 1, 102, -50000, 'hold', 'Vé câu #102, Đặt booking giữ chỗ | Số dư cuối: 1394000 vnd', '2025-06-15 20:17:05'),
(176, 1, 103, -50000, 'hold', 'Vé câu #103, Đặt booking giữ chỗ | Số dư cuối: 1344000 vnd', '2025-06-15 20:17:14'),
(177, 1, 104, -50000, 'hold', 'Vé câu #104, Đặt booking giữ chỗ | Số dư cuối: 1294000 vnd', '2025-06-15 20:17:19'),
(178, 1, 102, 39000, 'refund', 'Vé câu #102: Hoàn phí giữ chổ || Số dư cuối: 1283000 vnd', '2025-06-15 20:21:25'),
(179, 1, 103, 39000, 'refund', 'Vé câu #103: Hoàn phí giữ chổ || Số dư cuối: 1322000 vnd', '2025-06-15 20:21:32'),
(180, 2, 104, -60000, 'sent', 'Vé câu #104: Chủ hồ bị bẻ răng | Số dư cuối #104: 3320000 vnd', '2025-06-15 20:21:49'),
(181, 1, 104, 60000, 'received', 'Vé câu #104: Nhận tiền bẻ răng chủ hồ | Số dư cuối #104: 1382000 vnd', '2025-06-15 20:21:49'),
(182, 1, 104, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #104 sau khi trừ phí & VAT ||  Số dư: 1421000 vnd', '2025-06-15 20:21:49'),
(183, 1, 105, -50000, 'hold', 'Vé câu #105, Đặt booking giữ chỗ | Số dư cuối: 1421000 vnd', '2025-06-17 21:48:26'),
(184, 1, 105, -45000, 'sent', 'Vé câu #105: Bị chủ hồ bẻ răng | Số dư cuối: 1326000 vnd', '2025-06-17 21:50:34'),
(185, 2, 105, 45000, 'received', 'Vé câu #105: Bẻ răng Cần thủ | Số dư cuối #105: 3365000 vnd', '2025-06-17 21:50:34'),
(186, 1, 105, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #105 sau khi trừ phí & VAT ||  Số dư: 1365000 vnd', '2025-06-17 21:50:34'),
(187, 1, 106, -50000, 'hold', 'Vé câu #106, Đặt booking giữ chỗ | Số dư cuối: 1365000 vnd', '2025-06-23 14:03:32'),
(188, 1, 107, -50000, 'hold', 'Vé câu #107, Đặt booking giữ chỗ | Số dư cuối: 839250 vnd', '2025-06-27 16:50:26'),
(189, 1, 108, -50000, 'hold', 'Vé câu #108, Đặt booking giữ chỗ | Số dư cuối: 789250 vnd', '2025-06-27 19:27:31'),
(190, 1, 108, -22000, 'sent', 'Vé câu #108: Bị chủ hồ bẻ răng | Số dư cuối: 717250 vnd', '2025-06-27 19:29:24'),
(191, 2, 108, 22000, 'received', 'Vé câu #108: Bẻ răng Cần thủ | Số dư cuối #108: 3387000 vnd', '2025-06-27 19:29:24'),
(192, 1, 108, 39000, 'refund', 'Hoàn tiền giữ chỗ booking #108 sau khi trừ phí & VAT ||  Số dư: 756250 vnd', '2025-06-27 19:29:24'),
(193, 1, 109, -50000, 'hold', 'Vé câu #109, Đặt booking giữ chỗ | Số dư cuối: 756250 vnd', '2025-07-01 19:06:54'),
(194, 1, 110, -50000, 'hold', 'Vé câu #110, Đặt booking giữ chỗ | Số dư cuối: 10093250 vnd', '2025-08-12 14:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking_prize_awards`
--

CREATE TABLE `booking_prize_awards` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `prize_type` enum('Thưởng heo','Thưởng xôi','Xẻ heo','Thưởng khoen') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `amount` int NOT NULL,
  `awarded_by` int NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_prize_awards`
--

INSERT INTO `booking_prize_awards` (`id`, `booking_id`, `ho_cau_id`, `prize_type`, `amount`, `awarded_by`, `note`, `created_at`) VALUES
(40, 117, 50, 'Thưởng xôi', 50000, 2, NULL, '2025-08-27 17:37:21'),
(41, 117, 50, 'Thưởng khoen', 100000, 2, NULL, '2025-08-27 17:37:45'),
(42, 117, 50, 'Xẻ heo', 150000, 2, NULL, '2025-08-27 17:37:52'),
(43, 117, 50, 'Thưởng heo', 200000, 2, NULL, '2025-08-27 17:38:01'),
(46, 124, 50, 'Thưởng xôi', 50000, 2, NULL, '2025-08-27 21:06:37'),
(47, 130, 50, 'Thưởng xôi', 50000, 2, 'xôi 1', '2025-08-27 23:42:30'),
(49, 131, 50, 'Thưởng xôi', 50000, 2, NULL, '2025-08-28 08:01:16'),
(50, 131, 50, 'Thưởng xôi', 50000, 2, NULL, '2025-08-28 08:09:08'),
(52, 138, 57, 'Thưởng xôi', 50000, 2, NULL, '2025-08-28 17:26:24'),
(54, 140, 34, 'Thưởng xôi', 50000, 2, NULL, '2025-08-29 16:55:50'),
(55, 142, 34, 'Thưởng xôi', 50000, 2, NULL, '2025-08-29 20:35:02'),
(59, 139, 34, 'Thưởng khoen', 100000, 2, 'A02', '2025-08-29 21:04:49'),
(60, 139, 34, 'Thưởng khoen', 100000, 2, 'A02', '2025-08-29 21:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `booking_service_fee`
--

CREATE TABLE `booking_service_fee` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `service_type` enum('Thuốc lá','Nước','Cơm','Mỳ','Đồ ăn','Mồi câu','Đồ câu') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT '1.00',
  `unit_price` int NOT NULL DEFAULT '0',
  `amount` int NOT NULL DEFAULT '0',
  `added_by` int NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_service_fee`
--

INSERT INTO `booking_service_fee` (`id`, `booking_id`, `ho_cau_id`, `service_type`, `qty`, `unit_price`, `amount`, `added_by`, `note`, `created_at`) VALUES
(9, 117, 50, 'Thuốc lá', 1.00, 24000, 24000, 2, '', '2025-08-27 17:21:41'),
(10, 117, 50, 'Nước', 1.00, 10000, 10000, 2, '', '2025-08-27 17:22:22'),
(11, 117, 50, 'Cơm', 1.00, 30000, 30000, 2, '', '2025-08-27 17:22:29'),
(12, 117, 50, 'Mỳ', 1.00, 25000, 25000, 2, '', '2025-08-27 17:22:37'),
(13, 117, 50, 'Mồi câu', 1.00, 55000, 55000, 2, '', '2025-08-27 17:22:46'),
(17, 124, 50, 'Thuốc lá', 1.00, 30000, 30000, 2, '', '2025-08-27 21:07:05'),
(18, 130, 50, 'Mồi câu', 1.00, 100000, 100000, 2, '', '2025-08-27 23:42:39'),
(20, 131, 50, 'Nước', 1.00, 15000, 15000, 2, '', '2025-08-28 08:13:01'),
(21, 131, 50, 'Cơm', 1.00, 50000, 50000, 2, '', '2025-08-28 09:03:02'),
(22, 131, 50, 'Cơm', 1.00, 50000, 50000, 2, '', '2025-08-28 09:04:03'),
(23, 138, 57, 'Thuốc lá', 1.00, 30000, 30000, 2, '', '2025-08-28 17:26:17'),
(24, 140, 34, 'Thuốc lá', 1.00, 50000, 50000, 2, '', '2025-08-29 16:56:24'),
(25, 139, 34, 'Thuốc lá', 1.00, 12000, 12000, 2, '', '2025-08-29 19:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `cum_ho`
--

CREATE TABLE `cum_ho` (
  `id` int NOT NULL,
  `xa_id` int NOT NULL,
  `chu_ho_id` int NOT NULL,
  `ten_cum_ho` varchar(255) NOT NULL,
  `dia_chi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'địa chỉ...',
  `google_map_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'maps.google.com',
  `mo_ta` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'mô tả...',
  `status` enum('admin_tam_khoa','chuho_tam_khoa','dang_chay') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'dang_chay',
  `last_transferred_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cum_ho`
--

INSERT INTO `cum_ho` (`id`, `xa_id`, `chu_ho_id`, `ten_cum_ho`, `dia_chi`, `google_map_url`, `mo_ta`, `status`, `last_transferred_at`, `created_at`) VALUES
(1, 3, 2, 'Cụm hồ Bảo Ngân', 'Dương Minh Châu, Tây Ninh, Vietnam', 'https://www.google.com/', 'hồ Vip', 'dang_chay', '2025-05-24 20:06:33', '2025-05-24 20:06:33'),
(2, 317, 2, 'Cụm hồ Athen', '37/60 nguyễn văn cừ, Tân An', 'https://www.google.com/', 'hồ chép', 'chuho_tam_khoa', '2025-05-25 20:05:05', '2025-05-25 20:05:05'),
(4, 58, 2, 'Cụm hồ Hoàng Hải', 'TPHCM - xa', 'https://www.google.com/maps', '2222', 'admin_tam_khoa', '2025-06-01 21:34:49', '2025-06-01 21:34:49'),
(5, 169, 17, 'Cụm hồ Bình Chánh', 'okkk', 'https://www.google.com/maps', 'ok', 'dang_chay', '2025-06-03 18:22:59', '2025-06-03 18:22:59'),
(6, 186, 17, 'Cụm Hồ Hoàng Hải', '68 xã nhà bè', 'https://maps.google.com', 'mô tả... admin chỉnh', 'dang_chay', '2025-06-26 20:02:23', '2025-06-26 20:02:23'),
(7, 3, 2, 'Cụm hồ Dương Minh Châu', 'Dương Minh Châu, Tây Ninh, Vietnam', 'https://maps.app.goo.gl/4HVu4yYWxYv8Q7vw6', 'Cụm hồ gồm 3 hồ câu: chuyên chép, chuyên phi và hồ chép - phi đai', 'dang_chay', '2025-06-26 22:04:29', '2025-06-26 22:04:29');

-- --------------------------------------------------------

--
-- Table structure for table `cum_ho_logs`
--

CREATE TABLE `cum_ho_logs` (
  `id` int NOT NULL,
  `cum_ho_id` int NOT NULL,
  `old_chu_ho_id` int DEFAULT NULL,
  `new_chu_ho_id` int DEFAULT NULL,
  `noi_dung_edit` varchar(500) DEFAULT 'Cập nhật bởi admin/moderator',
  `updated_by` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cum_ho_logs`
--

INSERT INTO `cum_ho_logs` (`id`, `cum_ho_id`, `old_chu_ho_id`, `new_chu_ho_id`, `noi_dung_edit`, `updated_by`, `updated_at`) VALUES
(1, 7, 2, 17, 'Đổi chủ cụm hồ từ user ID 2 sang ID 17', 10, '2025-06-26 15:26:22'),
(2, 6, 2, 17, 'Đổi chủ cụm hồ từ user ID 2 sang ID 17', 10, '2025-06-26 15:47:52'),
(3, 7, 17, 2, 'Đổi chủ cụm hồ từ user ID 17 sang ID 2', 10, '2025-06-26 15:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `cum_ho_review`
--

CREATE TABLE `cum_ho_review` (
  `id` int NOT NULL,
  `ten_cum` varchar(100) NOT NULL,
  `tinh_id` int DEFAULT NULL,
  `huyen_id` int DEFAULT NULL,
  `xa_id` int DEFAULT NULL,
  `dia_chi` text,
  `mo_ta` text,
  `hinh_anh` text,
  `link_google_map` text,
  `link_youtube_review` text,
  `link_tiktok_review` text,
  `link_facebook_review` text,
  `added_by_user_id` int DEFAULT NULL,
  `status` enum('uploaded','confirmed','rejected') DEFAULT 'uploaded',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cum_ho_review_loai_ca`
--

CREATE TABLE `cum_ho_review_loai_ca` (
  `id` int NOT NULL,
  `cum_ho_review_id` int NOT NULL,
  `loai_ca_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_tinh`
--

CREATE TABLE `dm_tinh` (
  `id` int NOT NULL,
  `ten_tinh` varchar(100) NOT NULL,
  `ma_tinh` varchar(20) DEFAULT NULL,
  `ghi_chu` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dm_tinh`
--

INSERT INTO `dm_tinh` (`id`, `ten_tinh`, `ma_tinh`, `ghi_chu`, `created_at`) VALUES
(1, 'Tây Ninh', '72', 'Tây Ninh + Long An', '2025-05-24 20:06:33'),
(37, 'Hà Nội', '01', 'TP trực thuộc TW', '2025-06-25 12:13:17'),
(38, 'Cao Bằng', '04', 'Cao Bằng', '2025-06-25 12:13:17'),
(39, 'Tuyên Quang', '08', 'Hà Giang + Tuyên Quang', '2025-06-25 12:13:17'),
(40, 'Điện Biên', '11', 'Điện Biên', '2025-06-25 12:13:17'),
(41, 'Lai Châu', '12', 'Lai Châu', '2025-06-25 12:13:17'),
(42, 'Sơn La', '14', 'Sơn La', '2025-06-25 12:13:17'),
(43, 'Lào Cai', '15', 'Yên Bái + Lào Cai', '2025-06-25 12:13:17'),
(44, 'Thái Nguyên', '19', 'Bắc Kạn + Thái Nguyên', '2025-06-25 12:13:17'),
(45, 'Lạng Sơn', '20', 'Lạng Sơn', '2025-06-25 12:13:17'),
(46, 'Quảng Ninh', '22', 'Quảng Ninh', '2025-06-25 12:13:17'),
(47, 'Bắc Ninh', '24', 'Bắc Ninh + Bắc Giang', '2025-06-25 12:13:17'),
(48, 'Phú Thọ', '25', 'Vĩnh Phúc + Phú Thọ + Hòa Bình', '2025-06-25 12:13:17'),
(49, 'Hải Phòng', '31', 'TP trực thuộc TW (Hải Dương + HP)', '2025-06-25 12:13:17'),
(50, 'Hưng Yên', '33', 'Hưng Yên + Thái Bình', '2025-06-25 12:13:17'),
(51, 'Ninh Bình', '37', 'Hà Nam + Ninh Bình + Nam Định', '2025-06-25 12:13:17'),
(52, 'Thanh Hóa', '38', 'Thanh Hóa', '2025-06-25 12:13:17'),
(53, 'Nghệ An', '40', 'Nghệ An', '2025-06-25 12:13:17'),
(54, 'Hà Tĩnh', '42', 'Hà Tĩnh', '2025-06-25 12:13:17'),
(55, 'Quảng Trị', '44', 'Quảng Bình + Quảng Trị', '2025-06-25 12:13:17'),
(56, 'Huế', '46', 'TP trực thuộc TW', '2025-06-25 12:13:17'),
(57, 'Đà Nẵng', '48', 'TP trực thuộc TW (Quảng Nam + Đà Nẵng)', '2025-06-25 12:13:17'),
(58, 'Quảng Ngãi', '51', 'Kon Tum + Quảng Ngãi', '2025-06-25 12:13:17'),
(59, 'Gia Lai', '52', 'Gia Lai + Bình Định', '2025-06-25 12:13:17'),
(60, 'Khánh Hòa', '56', 'Khánh Hòa + Ninh Thuận', '2025-06-25 12:13:17'),
(61, 'Đắk Lắk', '66', 'Đắk Lắk + Phú Yên', '2025-06-25 12:13:17'),
(62, 'Lâm Đồng', '68', 'Lâm Đồng + Đắk Nông + Bình Thuận', '2025-06-25 12:13:17'),
(63, 'Đồng Nai', '75', 'Đồng Nai + Bình Phước', '2025-06-25 12:13:17'),
(64, 'TP. Hồ Chí Minh', '79', 'TP trực thuộc TW (TP.HCM + BRVT + Bình Dương)', '2025-06-25 12:13:17'),
(65, 'Đồng Tháp', '82', 'Tiền Giang + Đồng Tháp', '2025-06-25 12:13:17'),
(66, 'Vĩnh Long', '86', 'Bến Tre + Vĩnh Long + Trà Vinh', '2025-06-25 12:13:17'),
(67, 'An Giang', '91', 'An Giang + Kiên Giang', '2025-06-25 12:13:17'),
(68, 'Cần Thơ', '92', 'TP trực thuộc TW (Cần Thơ + Sóc Trăng + Hậu Giang)', '2025-06-25 12:13:17'),
(69, 'Cà Mau', '96', 'Bạc Liêu + Cà Mau', '2025-06-25 12:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `dm_xa_phuong`
--

CREATE TABLE `dm_xa_phuong` (
  `id` int NOT NULL,
  `ten_xa_phuong` varchar(100) NOT NULL,
  `ma_xa_phuong` varchar(20) DEFAULT NULL,
  `tinh_id` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dm_xa_phuong`
--

INSERT INTO `dm_xa_phuong` (`id`, `ten_xa_phuong`, `ma_xa_phuong`, `tinh_id`, `is_active`, `created_at`) VALUES
(1, 'Xã Tân Đông', '72064', 1, 1, '2025-05-24 20:06:33'),
(3, 'Xã Dương Minh Châu', '72063', 1, 1, '2025-06-26 11:17:22'),
(4, 'Phường Tân Ninh', '72086', 1, 1, '2025-06-26 11:25:11'),
(5, 'Phường Bình Minh', '72087', 1, 1, '2025-06-26 11:26:15'),
(6, 'Phường Ninh Thạnh', '72088', 1, 1, '2025-06-26 11:27:05'),
(7, 'Phường Long Hoa', '72089', 1, 1, '2025-06-26 11:27:31'),
(8, 'Phường Hòa Thành', '72090', 1, 1, '2025-06-26 11:27:46'),
(9, 'Phường Thanh Điền', '72091', 1, 1, '2025-06-26 11:28:00'),
(10, 'Phường Trảng Bàng', '72092', 1, 1, '2025-06-26 11:28:27'),
(11, 'Phường An Tịnh', '72093', 1, 1, '2025-06-26 11:28:41'),
(12, 'Phường Gò Dầu', '72094', 1, 1, '2025-06-26 11:29:12'),
(13, 'Phường Gia Lộc', '72095', 1, 1, '2025-06-26 11:29:40'),
(14, 'Xã Hưng Thuận', '72057', 1, 1, '2025-06-26 11:30:19'),
(15, 'Xã Phước Chỉ', '72056', 1, 1, '2025-06-26 11:31:15'),
(16, 'Xã Thạnh Đức', '72058', 1, 1, '2025-06-26 11:31:36'),
(17, 'Xã Phước Thạnh', '72059', 1, 1, '2025-06-26 11:31:50'),
(18, 'Xã Truông Mít', '72060', 1, 1, '2025-06-26 11:32:03'),
(19, 'Xã Lộc Ninh', '72061', 1, 1, '2025-06-26 11:32:13'),
(20, 'Xã Cầu Khởi', '72062', 1, 1, '2025-06-26 11:32:23'),
(21, 'Xã Tân Châu', '72065', 1, 1, '2025-06-26 11:33:45'),
(22, 'Xã Tân Phú', '72066', 1, 1, '2025-06-26 11:34:08'),
(23, 'Xã Tân Hội', '72067', 1, 1, '2025-06-26 11:34:18'),
(24, 'Xã Tân Thành', '72068', 1, 1, '2025-06-26 11:34:32'),
(28, 'Xã Thạnh Bình', '72072', 1, 1, '2025-06-26 11:36:15'),
(29, 'Xã Trà Vong', '72073', 1, 1, '2025-06-26 11:36:27'),
(33, 'Xã Châu Thành', '72077', 1, 1, '2025-06-26 11:37:36'),
(34, 'Xã Hảo Đước', '72078', 1, 1, '2025-06-26 11:37:56'),
(38, 'Phường Đông Hòa', '79001', 64, 1, '2025-06-26 11:53:51'),
(39, 'Phường Dĩ An', '79002', 64, 1, '2025-06-26 11:53:51'),
(40, 'Phường Tân Đông Hiệp', '79003', 64, 1, '2025-06-26 11:53:51'),
(41, 'Phường Thuận An', '79004', 64, 1, '2025-06-26 11:53:51'),
(42, 'Phường Thuận Giao', '79005', 64, 1, '2025-06-26 11:53:51'),
(43, 'Phường Bình Hòa', '79006', 64, 1, '2025-06-26 11:53:51'),
(44, 'Phường Lái Thiêu', '79007', 64, 1, '2025-06-26 11:53:51'),
(45, 'Phường An Phú', '79008', 64, 1, '2025-06-26 11:53:51'),
(46, 'Phường Bình Dương', '79009', 64, 1, '2025-06-26 11:53:51'),
(47, 'Phường Chánh Hiệp', '79010', 64, 1, '2025-06-26 11:53:51'),
(48, 'Phường Thủ Dầu Một', '79011', 64, 1, '2025-06-26 11:53:51'),
(49, 'Phường Phú Lợi', '79012', 64, 1, '2025-06-26 11:53:51'),
(50, 'Phường Vĩnh Tân', '79013', 64, 1, '2025-06-26 11:53:51'),
(51, 'Phường Bình Cơ', '79014', 64, 1, '2025-06-26 11:53:51'),
(52, 'Phường Tân Uyên', '79015', 64, 1, '2025-06-26 11:53:51'),
(53, 'Phường Tân Hiệp', '79016', 64, 1, '2025-06-26 11:53:51'),
(54, 'Phường Tân Khánh', '79017', 64, 1, '2025-06-26 11:53:51'),
(55, 'Phường Phú An', '79018', 64, 1, '2025-06-26 11:53:51'),
(56, 'Phường Tây Nam', '79019', 64, 1, '2025-06-26 11:53:51'),
(57, 'Phường Long Nguyên', '79020', 64, 1, '2025-06-26 11:53:51'),
(58, 'Phường Bến Cát', '79021', 64, 1, '2025-06-26 11:53:51'),
(59, 'Phường Chánh Phú Hòa', '79022', 64, 1, '2025-06-26 11:53:51'),
(60, 'Phường Thới Hòa', '79023', 64, 1, '2025-06-26 11:53:51'),
(61, 'Phường Hòa Lợi', '79024', 64, 1, '2025-06-26 11:53:51'),
(62, 'Xã Bắc Tân Uyên', '79025', 64, 1, '2025-06-26 11:53:51'),
(63, 'Xã Thường Tân', '79026', 64, 1, '2025-06-26 11:53:51'),
(64, 'Xã An Long', '79027', 64, 1, '2025-06-26 11:53:51'),
(65, 'Xã Phước Thành', '79028', 64, 1, '2025-06-26 11:53:51'),
(66, 'Xã Phước Hòa', '79029', 64, 1, '2025-06-26 11:53:51'),
(67, 'Xã Phú Giáo', '79030', 64, 1, '2025-06-26 11:53:51'),
(68, 'Xã Trừ Văn Thố', '79031', 64, 1, '2025-06-26 11:53:51'),
(69, 'Xã Bàu Bàng', '79032', 64, 1, '2025-06-26 11:53:51'),
(70, 'Xã Minh Thạnh', '79033', 64, 1, '2025-06-26 11:53:51'),
(71, 'Xã Long Hòa', '79034', 64, 1, '2025-06-26 11:53:51'),
(72, 'Xã Dầu Tiếng', '79035', 64, 1, '2025-06-26 11:53:51'),
(73, 'Xã Thanh An', '79036', 64, 1, '2025-06-26 11:53:51'),
(76, 'Phường Sài Gòn', '79037', 64, 1, '2025-06-28 14:54:48'),
(77, 'Phường Tân Định', '79038', 64, 1, '2025-06-28 14:54:48'),
(78, 'Phường Bến Thành', '79039', 64, 1, '2025-06-28 14:54:48'),
(79, 'Phường Cầu Ông Lãnh', '79040', 64, 1, '2025-06-28 14:54:48'),
(80, 'Phường Bàn Cờ', '79041', 64, 1, '2025-06-28 14:54:48'),
(81, 'Phường Xuân Hòa', '79042', 64, 1, '2025-06-28 14:54:48'),
(82, 'Phường Nhiêu Lộc', '79043', 64, 1, '2025-06-28 14:54:48'),
(83, 'Phường Xóm Chiếu', '79044', 64, 1, '2025-06-28 14:54:48'),
(84, 'Phường Khánh Hội', '79045', 64, 1, '2025-06-28 14:54:48'),
(85, 'Phường Vĩnh Hội', '79046', 64, 1, '2025-06-28 14:54:48'),
(86, 'Phường Chợ Quán', '79047', 64, 1, '2025-06-28 14:54:48'),
(87, 'Phường An Đông', '79048', 64, 1, '2025-06-28 14:54:48'),
(88, 'Phường Chợ Lớn', '79049', 64, 1, '2025-06-28 14:54:48'),
(89, 'Phường Bình Tây', '79050', 64, 1, '2025-06-28 14:54:48'),
(90, 'Phường Bình Tiên', '79051', 64, 1, '2025-06-28 14:54:48'),
(91, 'Phường Bình Phú', '79052', 64, 1, '2025-06-28 14:54:48'),
(92, 'Phường Phú Lâm', '79053', 64, 1, '2025-06-28 14:54:48'),
(93, 'Phường Tân Thuận', '79054', 64, 1, '2025-06-28 14:54:48'),
(94, 'Phường Phú Thuận', '79055', 64, 1, '2025-06-28 14:54:48'),
(95, 'Phường Tân Mỹ', '79056', 64, 1, '2025-06-28 14:54:48'),
(96, 'Phường Tân Hưng', '79057', 64, 1, '2025-06-28 14:54:48'),
(97, 'Phường Chánh Hưng', '79058', 64, 1, '2025-06-28 14:54:48'),
(98, 'Phường Phú Định', '79059', 64, 1, '2025-06-28 14:54:48'),
(99, 'Phường Bình Đông', '79060', 64, 1, '2025-06-28 14:54:48'),
(100, 'Phường Diên Hồng', '79061', 64, 1, '2025-06-28 14:54:48'),
(101, 'Phường Vườn Lài', '79062', 64, 1, '2025-06-28 14:54:48'),
(102, 'Phường HòA Hưng', '79063', 64, 1, '2025-06-28 14:54:48'),
(103, 'Phường Minh Phụng', '79064', 64, 1, '2025-06-28 14:54:48'),
(104, 'Phường Bình Thới', '79065', 64, 1, '2025-06-28 14:54:48'),
(105, 'Phường Hòa Bình', '79066', 64, 1, '2025-06-28 14:54:48'),
(106, 'Phường Phú Thọ', '79067', 64, 1, '2025-06-28 14:54:48'),
(107, 'Phường Đông Hưng Thuận', '79068', 64, 1, '2025-06-28 14:54:48'),
(108, 'Phường Trung Mỹ Tây', '79069', 64, 1, '2025-06-28 14:54:48'),
(109, 'Phường Tân Thới Hiệp', '79070', 64, 1, '2025-06-28 14:54:48'),
(110, 'Phường Thới An', '79071', 64, 1, '2025-06-28 14:54:48'),
(111, 'Phường An Phú Đông', '79072', 64, 1, '2025-06-28 14:54:48'),
(112, 'Phường An Lạc', '79073', 64, 1, '2025-06-28 14:54:48'),
(113, 'Phường Bình Tân', '79074', 64, 1, '2025-06-28 14:54:48'),
(114, 'Phường Tân Tạo', '79075', 64, 1, '2025-06-28 14:54:48'),
(115, 'Phường Bình Trị Đông', '79076', 64, 1, '2025-06-28 14:54:48'),
(116, 'Phường Bình Hưng Hòa', '79077', 64, 1, '2025-06-28 14:54:48'),
(117, 'Phường Gia Định', '79078', 64, 1, '2025-06-28 14:54:48'),
(118, 'Phường Bình Thạnh', '79079', 64, 1, '2025-06-28 14:54:48'),
(119, 'Phường Bình Lợi Trung', '79080', 64, 1, '2025-06-28 14:54:48'),
(120, 'Phường Thạnh Mỹ Tây', '79081', 64, 1, '2025-06-28 14:54:48'),
(121, 'Phường Bình Quới', '79082', 64, 1, '2025-06-28 14:54:48'),
(122, 'Phường Hạnh Thông', '79083', 64, 1, '2025-06-28 14:54:48'),
(123, 'Phường An Nhơn', '79084', 64, 1, '2025-06-28 14:54:48'),
(124, 'Phường Gò Vấp', '79085', 64, 1, '2025-06-28 14:54:48'),
(125, 'Phường An Hội Đông', '79086', 64, 1, '2025-06-28 14:54:48'),
(126, 'Phường Thông Tây Hội', '79087', 64, 1, '2025-06-28 14:54:48'),
(127, 'Phường An Hội Tây', '79088', 64, 1, '2025-06-28 14:54:48'),
(128, 'Phường Đức Nhuận', '79089', 64, 1, '2025-06-28 14:54:48'),
(129, 'Phường Cầu Kiệu', '79090', 64, 1, '2025-06-28 14:54:48'),
(130, 'Phường Phú Nhuận', '79091', 64, 1, '2025-06-28 14:54:48'),
(131, 'Phường Tân Sơn Hòa', '79092', 64, 1, '2025-06-28 14:54:48'),
(132, 'Phường Tân Sơn Nhất', '79093', 64, 1, '2025-06-28 14:54:48'),
(133, 'Phường Tân Hòa', '79094', 64, 1, '2025-06-28 14:54:48'),
(134, 'Phường Bảy Hiền', '79095', 64, 1, '2025-06-28 14:54:48'),
(135, 'Phường Tân Bình', '79096', 64, 1, '2025-06-28 14:54:48'),
(136, 'Phường Tân Sơn', '79097', 64, 1, '2025-06-28 14:54:48'),
(137, 'Phường Tây Thạnh', '79098', 64, 1, '2025-06-28 14:54:48'),
(138, 'Phường Tân Sơn Nhì', '79099', 64, 1, '2025-06-28 14:54:48'),
(139, 'Phường Phú Thọ Hòa', '79100', 64, 1, '2025-06-28 14:54:48'),
(140, 'Phường Tân Phú', '79101', 64, 1, '2025-06-28 14:54:48'),
(141, 'Phường Phú Thạnh', '79102', 64, 1, '2025-06-28 14:54:48'),
(142, 'Phường Hiệp Bình', '79103', 64, 1, '2025-06-28 14:54:48'),
(143, 'Phường Thủ Đức', '79104', 64, 1, '2025-06-28 14:54:48'),
(144, 'Phường Tam Bình', '79105', 64, 1, '2025-06-28 14:54:48'),
(145, 'Phường Linh Xuân', '79106', 64, 1, '2025-06-28 14:54:48'),
(146, 'Phường Tăng Nhơn Phú', '79107', 64, 1, '2025-06-28 14:54:48'),
(147, 'Phường Long Bình', '79108', 64, 1, '2025-06-28 14:54:48'),
(148, 'Phường Long Phước', '79109', 64, 1, '2025-06-28 14:54:48'),
(149, 'Phường Long Trường', '79110', 64, 1, '2025-06-28 14:54:48'),
(150, 'Phường Cát Lái', '79111', 64, 1, '2025-06-28 14:54:48'),
(151, 'Phường Bình Trưng', '79112', 64, 1, '2025-06-28 14:54:48'),
(152, 'Phường Phước Long', '79113', 64, 1, '2025-06-28 14:54:48'),
(153, 'Phường An Khánh', '79114', 64, 1, '2025-06-28 14:54:48'),
(154, 'Phường Vũng Tàu', '79115', 64, 1, '2025-06-28 14:54:48'),
(155, 'Phường Tam Thắng', '79116', 64, 1, '2025-06-28 14:54:48'),
(156, 'Phường Rạch Dừa', '79117', 64, 1, '2025-06-28 14:54:48'),
(157, 'Phường Phước Thắng', '79118', 64, 1, '2025-06-28 14:54:48'),
(158, 'Phường Long Hương', '79119', 64, 1, '2025-06-28 14:54:48'),
(159, 'Phường Bà Rịa', '79120', 64, 1, '2025-06-28 14:54:48'),
(160, 'Phường Tam Long', '79121', 64, 1, '2025-06-28 14:54:48'),
(161, 'Phường Tân Hải', '79122', 64, 1, '2025-06-28 14:54:48'),
(162, 'Phường Tân Phước', '79123', 64, 1, '2025-06-28 14:54:48'),
(163, 'Phường Phú Mỹ', '79124', 64, 1, '2025-06-28 14:54:48'),
(164, 'Phường Tân Thành', '79125', 64, 1, '2025-06-28 14:54:48'),
(165, 'Xã Vĩnh Lộc', '79126', 64, 1, '2025-06-28 14:54:48'),
(166, 'Xã Tân Vĩnh Lộc', '79127', 64, 1, '2025-06-28 14:54:48'),
(167, 'Xã Bình Lợi', '79128', 64, 1, '2025-06-28 14:54:48'),
(168, 'Xã Tân Nhựt', '79129', 64, 1, '2025-06-28 14:54:48'),
(169, 'Xã Bình Chánh', '79130', 64, 1, '2025-06-28 14:54:48'),
(170, 'Xã Hưng Long', '79131', 64, 1, '2025-06-28 14:54:48'),
(171, 'Xã Bình Hưng', '79132', 64, 1, '2025-06-28 14:54:48'),
(172, 'Xã Bình Khánh', '79133', 64, 1, '2025-06-28 14:54:48'),
(173, 'Xã An Thới Đông', '79134', 64, 1, '2025-06-28 14:54:48'),
(174, 'Xã Cần Giờ', '79135', 64, 1, '2025-06-28 14:54:48'),
(175, 'Xã Củ Chi', '79136', 64, 1, '2025-06-28 14:54:48'),
(176, 'Xã Tân An Hội', '79137', 64, 1, '2025-06-28 14:54:48'),
(177, 'Xã Thái Mỹ', '79138', 64, 1, '2025-06-28 14:54:48'),
(178, 'Xã An Nhơn Tây', '79139', 64, 1, '2025-06-28 14:54:48'),
(179, 'Xã Nhuận Đức', '79140', 64, 1, '2025-06-28 14:54:48'),
(180, 'Xã Phú Hòa Đông', '79141', 64, 1, '2025-06-28 14:54:48'),
(181, 'Xã Bình Mỹ', '79142', 64, 1, '2025-06-28 14:54:48'),
(182, 'Xã Đông Thạnh', '79143', 64, 1, '2025-06-28 14:54:48'),
(183, 'Xã Hóc Môn', '79144', 64, 1, '2025-06-28 14:54:48'),
(184, 'Xã Xuân Thới Sơn', '79145', 64, 1, '2025-06-28 14:54:48'),
(185, 'Xã Bà Điểm', '79146', 64, 1, '2025-06-28 14:54:48'),
(186, 'Xã Nhà Bè', '79147', 64, 1, '2025-06-28 14:54:48'),
(187, 'Xã Hiệp Phước', '79148', 64, 1, '2025-06-28 14:54:48'),
(188, 'Xã Châu Pha', '79149', 64, 1, '2025-06-28 14:54:48'),
(189, 'Xã Long Hải', '79150', 64, 1, '2025-06-28 14:54:48'),
(190, 'Xã Long Điền', '79151', 64, 1, '2025-06-28 14:54:48'),
(191, 'Xã Phước Hải', '79152', 64, 1, '2025-06-28 14:54:48'),
(192, 'Xã Đất Đỏ', '79153', 64, 1, '2025-06-28 14:54:48'),
(193, 'Xã Nghĩa Thành', '79154', 64, 1, '2025-06-28 14:54:48'),
(194, 'Xã Ngãi Giao', '79155', 64, 1, '2025-06-28 14:54:48'),
(195, 'Xã Kim Long', '79156', 64, 1, '2025-06-28 14:54:48'),
(196, 'Xã Châu Đức', '79157', 64, 1, '2025-06-28 14:54:48'),
(197, 'Xã Bình Giã', '79158', 64, 1, '2025-06-28 14:54:48'),
(198, 'Xã Xuân Sơn', '79159', 64, 1, '2025-06-28 14:54:48'),
(199, 'Xã Hồ Tràm', '79160', 64, 1, '2025-06-28 14:54:48'),
(200, 'Xã Xuyên Mộc', '79161', 64, 1, '2025-06-28 14:54:48'),
(201, 'Xã Hòa Hội', '79162', 64, 1, '2025-06-28 14:54:48'),
(202, 'Xã Bàu Lâm', '79163', 64, 1, '2025-06-28 14:54:48'),
(204, 'Xã Long Sơn', '79165', 64, 1, '2025-06-28 22:04:42'),
(205, 'Xã Hòa Hiệp', '79166', 64, 1, '2025-06-28 22:05:07'),
(206, 'Xã Bình Châu', '79167', 64, 1, '2025-06-28 22:05:29'),
(207, 'Xã Thạnh An', '79168', 64, 1, '2025-06-28 22:05:59'),
(208, 'Đặc Khu Côn Đảo', '79169', 64, 1, '2025-06-28 22:06:42'),
(209, 'Xã Hưng Điền', '72001', 1, 1, '2025-06-28 15:16:11'),
(210, 'Xã Vĩnh Thạnh', '72002', 1, 1, '2025-06-28 15:16:11'),
(211, 'Xã Tân Hưng', '72003', 1, 1, '2025-06-28 15:16:11'),
(212, 'Xã Vĩnh Châu', '72004', 1, 1, '2025-06-28 15:16:11'),
(213, 'Xã Tuyên Bình', '72005', 1, 1, '2025-06-28 15:16:11'),
(214, 'Xã Vĩnh Hưng', '72006', 1, 1, '2025-06-28 15:16:11'),
(215, 'Xã Khánh Hưng', '72007', 1, 1, '2025-06-28 15:16:11'),
(216, 'Xã Tuyên Thạnh', '72008', 1, 1, '2025-06-28 15:16:11'),
(217, 'Xã Bình Hiệp', '72009', 1, 1, '2025-06-28 15:16:11'),
(218, 'Xã Bình Hòa', '72010', 1, 1, '2025-06-28 15:16:11'),
(219, 'Xã Mộc Hóa', '72011', 1, 1, '2025-06-28 15:16:11'),
(220, 'Xã Hậu Thạnh', '72012', 1, 1, '2025-06-28 15:16:11'),
(221, 'Xã Nhơn Hòa Lập', '72013', 1, 1, '2025-06-28 15:16:11'),
(222, 'Xã Nhơn Ninh', '72014', 1, 1, '2025-06-28 15:16:11'),
(223, 'Xã Bình Thành', '72016', 1, 1, '2025-06-28 15:16:11'),
(224, 'Xã Thạnh Phước', '72017', 1, 1, '2025-06-28 15:16:11'),
(225, 'Xã Thạnh Hóa', '72018', 1, 1, '2025-06-28 15:16:11'),
(226, 'Xã Tân Tây', '72019', 1, 1, '2025-06-28 15:16:11'),
(227, 'Xã Thủ Thừa', '72020', 1, 1, '2025-06-28 15:16:11'),
(228, 'Xã Mỹ An', '72021', 1, 1, '2025-06-28 15:16:11'),
(229, 'Xã Mỹ Thạnh', '72022', 1, 1, '2025-06-28 15:16:11'),
(230, 'Xã Tân Long', '72023', 1, 1, '2025-06-28 15:16:11'),
(231, 'Xã Mỹ Quý', '72024', 1, 1, '2025-06-28 15:16:11'),
(232, 'Xã Đông Thành', '72025', 1, 1, '2025-06-28 15:16:11'),
(233, 'Xã Đức Huệ', '72026', 1, 1, '2025-06-28 15:16:11'),
(234, 'Xã An Ninh', '72027', 1, 1, '2025-06-28 15:16:11'),
(235, 'Xã Hiệp Hòa', '72028', 1, 1, '2025-06-28 15:16:11'),
(236, 'Xã Hậu Nghĩa', '72029', 1, 1, '2025-06-28 15:16:11'),
(237, 'Xã Hòa Khánh', '72030', 1, 1, '2025-06-28 15:16:11'),
(238, 'Xã Đức Lập', '72031', 1, 1, '2025-06-28 15:16:11'),
(239, 'Xã Mỹ Hạnh', '72032', 1, 1, '2025-06-28 15:16:11'),
(240, 'Xã Đức Hòa', '72033', 1, 1, '2025-06-28 15:16:11'),
(241, 'Xã Thạnh Lợi', '72034', 1, 1, '2025-06-28 15:16:11'),
(242, 'Xã Bình Đức', '72035', 1, 1, '2025-06-28 15:16:11'),
(243, 'Xã Lương HòA', '72036', 1, 1, '2025-06-28 15:16:11'),
(244, 'Xã Bến Lức', '72037', 1, 1, '2025-06-28 15:16:11'),
(245, 'Xã Mỹ Yên', '72038', 1, 1, '2025-06-28 15:16:11'),
(246, 'Xã Long Cang', '72039', 1, 1, '2025-06-28 15:16:11'),
(247, 'Xã Rạch Kiến', '72040', 1, 1, '2025-06-28 15:16:11'),
(248, 'Xã Mỹ Lệ', '72041', 1, 1, '2025-06-28 15:16:11'),
(249, 'Xã Tân Lân', '72042', 1, 1, '2025-06-28 15:16:11'),
(250, 'Xã Cần Đước', '72043', 1, 1, '2025-06-28 15:16:11'),
(251, 'Xã Phước Lý', '72044', 1, 1, '2025-06-28 15:16:11'),
(252, 'Xã Mỹ Lộc', '72045', 1, 1, '2025-06-28 15:16:11'),
(253, 'Xã Cần Giuộc', '72046', 1, 1, '2025-06-28 15:16:11'),
(254, 'Xã Phước Vĩnh Tây', '72047', 1, 1, '2025-06-28 15:16:11'),
(255, 'Xã Tân Tập', '72048', 1, 1, '2025-06-28 15:16:11'),
(256, 'Xã Vàm Cỏ', '72049', 1, 1, '2025-06-28 15:16:11'),
(257, 'Xã Tân Trụ', '72050', 1, 1, '2025-06-28 15:16:11'),
(258, 'Xã Nhựt Tảo', '72051', 1, 1, '2025-06-28 15:16:11'),
(259, 'Xã Thuận Mỹ', '72052', 1, 1, '2025-06-28 15:16:11'),
(260, 'Xã An Lục Long', '72053', 1, 1, '2025-06-28 15:16:11'),
(261, 'Xã Tầm Vu', '72054', 1, 1, '2025-06-28 15:16:11'),
(262, 'Xã Vĩnh Công', '72055', 1, 1, '2025-06-28 15:16:11'),
(263, 'Xã Tân Hòa', '72069', 1, 1, '2025-06-28 15:16:11'),
(264, 'Xã Tân Lập', '72070', 1, 1, '2025-06-28 15:16:11'),
(265, 'Xã Tân Biên', '72071', 1, 1, '2025-06-28 15:16:11'),
(266, 'Xã Phước Vinh', '72074', 1, 1, '2025-06-28 15:16:11'),
(272, 'Phường Kiến Tường', '72082', 1, 1, '2025-06-28 15:16:11'),
(273, 'Phường Long An', '72083', 1, 1, '2025-06-28 15:16:11'),
(274, 'Phường Tân An', '72084', 1, 1, '2025-06-28 15:16:11'),
(275, 'Phường Khánh Hậu', '72085', 1, 1, '2025-06-28 15:16:11'),
(276, 'Xã Hòa Hội', '72075', 1, 1, '2025-06-28 15:16:11'),
(277, 'Xã Ninh Điền', '72076', 1, 1, '2025-06-28 15:16:11'),
(278, 'Xã Long Chữ', '72079', 1, 1, '2025-06-28 15:16:11'),
(279, 'Xã Long Thuận', '72080', 1, 1, '2025-06-28 15:16:11'),
(280, 'Xã Bến Cầu', '72081', 1, 1, '2025-06-28 15:16:11'),
(281, 'Xã Long Hựu', '72096', 1, 1, '2025-06-28 23:06:16'),
(282, 'Xã Nhơn Trạch', '75002', 63, 1, '2025-06-29 10:19:02'),
(283, 'Xã Phước An', '75003', 63, 1, '2025-06-29 10:19:02'),
(284, 'Xã Phước Thái', '75004', 63, 1, '2025-06-29 10:19:02'),
(285, 'Xã Long Phước', '75005', 63, 1, '2025-06-29 10:19:02'),
(286, 'Xã Long Thành', '75006', 63, 1, '2025-06-29 10:19:02'),
(287, 'Xã Bình An', '75007', 63, 1, '2025-06-29 10:19:02'),
(288, 'Xã An Phước', '75008', 63, 1, '2025-06-29 10:19:02'),
(289, 'Xã An Viễn', '75009', 63, 1, '2025-06-29 10:19:02'),
(290, 'Xã Bình Minh', '75010', 63, 1, '2025-06-29 10:19:02'),
(291, 'Xã Trảng Bom', '75011', 63, 1, '2025-06-29 10:19:02'),
(292, 'Xã Bàu Hàm', '75012', 63, 1, '2025-06-29 10:19:02'),
(293, 'Xã Hưng Thịnh', '75013', 63, 1, '2025-06-29 10:19:02'),
(294, 'Xã Dầu Giây', '75014', 63, 1, '2025-06-29 10:19:02'),
(295, 'Xã Gia Kiệm', '75015', 63, 1, '2025-06-29 10:19:02'),
(296, 'Xã Thống Nhất', '75016', 63, 1, '2025-06-29 10:19:02'),
(297, 'Xã Xuân Quế', '75017', 63, 1, '2025-06-29 10:19:02'),
(298, 'Xã Xuân Đường', '75018', 63, 1, '2025-06-29 10:19:02'),
(299, 'Xã Cẩm Mỹ', '75019', 63, 1, '2025-06-29 10:19:02'),
(300, 'Xã Sông Ray', '75020', 63, 1, '2025-06-29 10:19:02'),
(301, 'Xã Xuân Đông', '75021', 63, 1, '2025-06-29 10:19:02'),
(302, 'Xã Xuân Định', '75022', 63, 1, '2025-06-29 10:19:02'),
(303, 'Xã Xuân Phú', '75023', 63, 1, '2025-06-29 10:19:02'),
(304, 'Xã Xuân Lộc', '75024', 63, 1, '2025-06-29 10:19:02'),
(305, 'Xã Xuân Hòa', '75025', 63, 1, '2025-06-29 10:19:02'),
(306, 'Xã Xuân Thành', '75026', 63, 1, '2025-06-29 10:19:02'),
(307, 'Xã Xuân Bắc', '75027', 63, 1, '2025-06-29 10:19:02'),
(308, 'Xã La Ngà', '75028', 63, 1, '2025-06-29 10:19:02'),
(309, 'Xã Định Quán', '75029', 63, 1, '2025-06-29 10:19:02'),
(310, 'Xã Phú Vinh', '75030', 63, 1, '2025-06-29 10:19:02'),
(311, 'Xã Phú Hòa', '75031', 63, 1, '2025-06-29 10:19:02'),
(312, 'Xã Tà Lài', '75032', 63, 1, '2025-06-29 10:19:02'),
(313, 'Xã Nam Cát Tiên', '75033', 63, 1, '2025-06-29 10:19:02'),
(314, 'Xã Tân Phú', '75034', 63, 1, '2025-06-29 10:19:02'),
(315, 'Xã Phú Lâm', '75035', 63, 1, '2025-06-29 10:19:02'),
(316, 'Xã Trị An', '75036', 63, 1, '2025-06-29 10:19:02'),
(317, 'Xã Tân An', '75037', 63, 1, '2025-06-29 10:19:02'),
(318, 'Xã Nha Bích', '75038', 63, 1, '2025-06-29 10:19:02'),
(319, 'Xã Tân Quan', '75039', 63, 1, '2025-06-29 10:19:02'),
(320, 'Xã Tân Hưng', '75040', 63, 1, '2025-06-29 10:19:02'),
(321, 'Xã Tân Khai', '75041', 63, 1, '2025-06-29 10:19:02'),
(322, 'Xã Minh Đức', '75042', 63, 1, '2025-06-29 10:19:02'),
(323, 'Xã Lộc Thành', '75043', 63, 1, '2025-06-29 10:19:02'),
(324, 'Xã Lộc Ninh', '75044', 63, 1, '2025-06-29 10:19:02'),
(325, 'Xã Lộc Hưng', '75045', 63, 1, '2025-06-29 10:19:02'),
(326, 'Xã Lộc Tấn', '75046', 63, 1, '2025-06-29 10:19:02'),
(327, 'Xã Lộc Thạnh', '75047', 63, 1, '2025-06-29 10:19:02'),
(328, 'Xã Lộc Quang', '75048', 63, 1, '2025-06-29 10:19:02'),
(329, 'Xã Tân Tiến', '75049', 63, 1, '2025-06-29 10:19:02'),
(330, 'Xã Thiện Hưng', '75050', 63, 1, '2025-06-29 10:19:02'),
(331, 'Xã Hưng Phước', '75051', 63, 1, '2025-06-29 10:19:02'),
(332, 'Xã Phú Nghĩa', '75052', 63, 1, '2025-06-29 10:19:02'),
(333, 'Xã Đa Kia', '75053', 63, 1, '2025-06-29 10:19:02'),
(334, 'Xã Bình Tân', '75054', 63, 1, '2025-06-29 10:19:02'),
(335, 'Xã Long Hà', '75055', 63, 1, '2025-06-29 10:19:02'),
(336, 'Xã Phú Riềng', '75056', 63, 1, '2025-06-29 10:19:02'),
(337, 'Xã Phú Trung', '75057', 63, 1, '2025-06-29 10:19:02'),
(338, 'Xã Thuận Lợi', '75058', 63, 1, '2025-06-29 10:19:02'),
(339, 'Xã Đồng Tâm', '75059', 63, 1, '2025-06-29 10:19:02'),
(340, 'Xã Tân Lợi', '75060', 63, 1, '2025-06-29 10:19:02'),
(341, 'Xã Đồng Phú', '75061', 63, 1, '2025-06-29 10:19:02'),
(342, 'Xã Phước Sơn', '75062', 63, 1, '2025-06-29 10:19:02'),
(343, 'Xã Nghĩa Trung', '75063', 63, 1, '2025-06-29 10:19:02'),
(344, 'Xã Bù Đăng', '75064', 63, 1, '2025-06-29 10:19:02'),
(345, 'Xã Thọ Sơn', '75065', 63, 1, '2025-06-29 10:19:02'),
(346, 'Xã Đak Nhau', '75066', 63, 1, '2025-06-29 10:19:02'),
(347, 'Xã Bom Bo', '75067', 63, 1, '2025-06-29 10:19:02'),
(348, 'Xã Thanh Sơn', '75068', 63, 1, '2025-06-29 10:19:02'),
(349, 'Xã Đak Lua', '75069', 63, 1, '2025-06-29 10:19:02'),
(350, 'Xã Phú Lý', '75070', 63, 1, '2025-06-29 10:19:02'),
(351, 'Xã Bù Gia Mập', '75071', 63, 1, '2025-06-29 10:19:02'),
(352, 'Xã Đăk Ơ', '75072', 63, 1, '2025-06-29 10:19:02'),
(353, 'Phường Biên Hòa', '75073', 63, 1, '2025-06-29 10:19:02'),
(354, 'Phường Trấn Biên', '75074', 63, 1, '2025-06-29 10:19:02'),
(355, 'Phường Tam Hiệp', '75075', 63, 1, '2025-06-29 10:19:02'),
(356, 'Phường Long Bình', '75076', 63, 1, '2025-06-29 10:19:02'),
(357, 'Phường Trảng Dài', '75077', 63, 1, '2025-06-29 10:19:02'),
(358, 'Phường Hố Nai', '75078', 63, 1, '2025-06-29 10:19:02'),
(359, 'Phường Long Hưng', '75079', 63, 1, '2025-06-29 10:19:02'),
(360, 'Phường Bình Lộc', '75080', 63, 1, '2025-06-29 10:19:02'),
(361, 'Phường Bảo Vinh', '75081', 63, 1, '2025-06-29 10:19:02'),
(362, 'Phường Xuân Lập', '75082', 63, 1, '2025-06-29 10:19:02'),
(363, 'Phường Long Khánh', '75083', 63, 1, '2025-06-29 10:19:02'),
(364, 'Phường Hàng Gòn', '75084', 63, 1, '2025-06-29 10:19:02'),
(365, 'Phường Tân Triều', '75085', 63, 1, '2025-06-29 10:19:02'),
(366, 'Phường Minh Hưng', '75086', 63, 1, '2025-06-29 10:19:02'),
(367, 'Phường Chơn Thành', '75087', 63, 1, '2025-06-29 10:19:02'),
(368, 'Phường Bình Long', '75088', 63, 1, '2025-06-29 10:19:02'),
(369, 'Phường An Lộc', '75089', 63, 1, '2025-06-29 10:19:02'),
(370, 'Phường Phước Bình', '75090', 63, 1, '2025-06-29 10:19:02'),
(371, 'Phường Phước Long', '75091', 63, 1, '2025-06-29 10:19:02'),
(372, 'Phường Đồng Xoài', '75092', 63, 1, '2025-06-29 10:19:02'),
(373, 'Phường Bình Phước', '75093', 63, 1, '2025-06-29 10:19:02'),
(374, 'Phường Phước Tân', '75094', 63, 1, '2025-06-29 10:19:02'),
(375, 'Phường Tam Phước', '75095', 63, 1, '2025-06-29 10:19:02'),
(376, 'Xã Đại Phước', '75001', 63, 1, '2025-06-29 10:25:08'),
(377, 'Phường Hải Châu', '48001', 57, 1, '2025-06-29 10:48:21'),
(378, 'Phường Hòa Cường', '48002', 57, 1, '2025-06-29 10:48:21'),
(379, 'Phường Thanh Khê', '48003', 57, 1, '2025-06-29 10:48:21'),
(380, 'Phường An Khê', '48004', 57, 1, '2025-06-29 10:48:21'),
(381, 'Phường An Hải', '48005', 57, 1, '2025-06-29 10:48:21'),
(382, 'Phường Sơn Trà', '48006', 57, 1, '2025-06-29 10:48:21'),
(383, 'Phường Ngũ Hành Sơn', '48007', 57, 1, '2025-06-29 10:48:21'),
(384, 'Phường Hòa Khánh', '48008', 57, 1, '2025-06-29 10:48:21'),
(385, 'Phường Hải Vân', '48009', 57, 1, '2025-06-29 10:48:21'),
(386, 'Phường Liên Chiểu', '48010', 57, 1, '2025-06-29 10:48:21'),
(387, 'Phường Cẩm Lệ', '48011', 57, 1, '2025-06-29 10:48:21'),
(388, 'Phường Hòa Xuân', '48012', 57, 1, '2025-06-29 10:48:21'),
(389, 'Phường Tam Kỳ', '48013', 57, 1, '2025-06-29 10:48:21'),
(390, 'Phường Quảng Phú', '48014', 57, 1, '2025-06-29 10:48:21'),
(391, 'Phường Hương Trà', '48015', 57, 1, '2025-06-29 10:48:21'),
(392, 'Phường Bàn Thạch', '48016', 57, 1, '2025-06-29 10:48:21'),
(393, 'Phường Điện Bàn', '48017', 57, 1, '2025-06-29 10:48:21'),
(394, 'Phường Điện Bàn Đông', '48018', 57, 1, '2025-06-29 10:48:21'),
(395, 'Phường An Thắng', '48019', 57, 1, '2025-06-29 10:48:21'),
(396, 'Phường Điện Bàn Bắc', '48020', 57, 1, '2025-06-29 10:48:21'),
(397, 'Phường Hội An', '48021', 57, 1, '2025-06-29 10:48:21'),
(398, 'Phường Hội An Đông', '48022', 57, 1, '2025-06-29 10:48:21'),
(399, 'Phường Hội An Tây', '48023', 57, 1, '2025-06-29 10:48:21'),
(400, 'Xã Hòa Vang', '48024', 57, 1, '2025-06-29 10:48:21'),
(401, 'Xã Hòa Tiến', '48025', 57, 1, '2025-06-29 10:48:21'),
(402, 'Xã Bà Nà', '48026', 57, 1, '2025-06-29 10:48:21'),
(403, 'Xã Núi Thành', '48027', 57, 1, '2025-06-29 10:48:21'),
(404, 'Xã Tam Mỹ', '48028', 57, 1, '2025-06-29 10:48:21'),
(405, 'Xã Tam Anh', '48029', 57, 1, '2025-06-29 10:48:21'),
(406, 'Xã Đức Phú', '48030', 57, 1, '2025-06-29 10:48:21'),
(407, 'Xã Tam Xuân', '48031', 57, 1, '2025-06-29 10:48:21'),
(408, 'Xã Tây Hồ', '48032', 57, 1, '2025-06-29 10:48:21'),
(409, 'Xã Chiên Đàn', '48033', 57, 1, '2025-06-29 10:48:21'),
(410, 'Xã Phú Ninh', '48034', 57, 1, '2025-06-29 10:48:21'),
(411, 'Xã Lãnh Ngọc', '48035', 57, 1, '2025-06-29 10:48:21'),
(412, 'Xã Tiên Phước', '48036', 57, 1, '2025-06-29 10:48:21'),
(413, 'Xã Thạnh Bình', '48037', 57, 1, '2025-06-29 10:48:21'),
(414, 'Xã Sơn Cẩm Hà', '48038', 57, 1, '2025-06-29 10:48:21'),
(415, 'Xã Trà Liên', '48039', 57, 1, '2025-06-29 10:48:21'),
(416, 'Xã Trà Giáp', '48040', 57, 1, '2025-06-29 10:48:21'),
(417, 'Xã Trà Tân', '48041', 57, 1, '2025-06-29 10:48:21'),
(418, 'Xã Trà Đốc', '48042', 57, 1, '2025-06-29 10:48:21'),
(419, 'Xã Trà My', '48043', 57, 1, '2025-06-29 10:48:21'),
(420, 'Xã Nam Trà My', '48044', 57, 1, '2025-06-29 10:48:21'),
(421, 'Xã Trà Tập', '48045', 57, 1, '2025-06-29 10:48:21'),
(422, 'Xã Trà Vân', '48046', 57, 1, '2025-06-29 10:48:21'),
(423, 'Xã Trà Linh', '48047', 57, 1, '2025-06-29 10:48:21'),
(424, 'Xã Trà Leng', '48048', 57, 1, '2025-06-29 10:48:21'),
(425, 'Xã Thăng Bình', '48049', 57, 1, '2025-06-29 10:48:21'),
(426, 'Xã Thăng An', '48050', 57, 1, '2025-06-29 10:48:21'),
(427, 'Xã Thăng Trường', '48051', 57, 1, '2025-06-29 10:48:21'),
(428, 'Xã Thăng Điền', '48052', 57, 1, '2025-06-29 10:48:21'),
(429, 'Xã Thăng Phú', '48053', 57, 1, '2025-06-29 10:48:21'),
(430, 'Xã Đồng Dương', '48054', 57, 1, '2025-06-29 10:48:21'),
(431, 'Xã Quế Sơn Trung', '48055', 57, 1, '2025-06-29 10:48:21'),
(432, 'Xã Quế Sơn', '48056', 57, 1, '2025-06-29 10:48:21'),
(433, 'Xã Xuân Phú', '48057', 57, 1, '2025-06-29 10:48:21'),
(434, 'Xã Nông Sơn', '48058', 57, 1, '2025-06-29 10:48:21'),
(435, 'Xã Quế Phước', '48059', 57, 1, '2025-06-29 10:48:21'),
(436, 'Xã Duy Nghĩa', '48060', 57, 1, '2025-06-29 10:48:21'),
(437, 'Xã Nam Phước', '48061', 57, 1, '2025-06-29 10:48:21'),
(438, 'Xã Duy Xuyên', '48062', 57, 1, '2025-06-29 10:48:21'),
(439, 'Xã Thu Bồn', '48063', 57, 1, '2025-06-29 10:48:21'),
(440, 'Xã Điện Bàn Tây', '48064', 57, 1, '2025-06-29 10:48:21'),
(441, 'Xã Gò Nổi', '48065', 57, 1, '2025-06-29 10:48:21'),
(442, 'Xã Đại Lộc', '48066', 57, 1, '2025-06-29 10:48:21'),
(443, 'Xã Hà Nha', '48067', 57, 1, '2025-06-29 10:48:21'),
(444, 'Xã Thượng Đức', '48068', 57, 1, '2025-06-29 10:48:21'),
(445, 'Xã Vu Gia', '48069', 57, 1, '2025-06-29 10:48:21'),
(446, 'Xã Phú Thuận', '48070', 57, 1, '2025-06-29 10:48:21'),
(447, 'Xã Thạnh Mỹ', '48071', 57, 1, '2025-06-29 10:48:21'),
(448, 'Xã Bến Giằng', '48072', 57, 1, '2025-06-29 10:48:21'),
(449, 'Xã Nam Giang', '48073', 57, 1, '2025-06-29 10:48:21'),
(450, 'Xã Đắc Pring', '48074', 57, 1, '2025-06-29 10:48:21'),
(451, 'Xã La Dêê', '48075', 57, 1, '2025-06-29 10:48:21'),
(452, 'Xã La Êê', '48076', 57, 1, '2025-06-29 10:48:21'),
(453, 'Xã Sông Vàng', '48077', 57, 1, '2025-06-29 10:48:21'),
(454, 'Xã Sông Kôn', '48078', 57, 1, '2025-06-29 10:48:21'),
(455, 'Xã Đông Giang', '48079', 57, 1, '2025-06-29 10:48:21'),
(456, 'Xã Bến Hiên', '48080', 57, 1, '2025-06-29 10:48:21'),
(457, 'Xã Avương', '48081', 57, 1, '2025-06-29 10:48:21'),
(458, 'Xã Tây Giang', '48082', 57, 1, '2025-06-29 10:48:21'),
(459, 'Xã Hùng Sơn', '48083', 57, 1, '2025-06-29 10:48:21'),
(460, 'Xã Hiệp Đức', '48084', 57, 1, '2025-06-29 10:48:21'),
(461, 'Xã Việt An', '48085', 57, 1, '2025-06-29 10:48:21'),
(462, 'Xã Phước Trà', '48086', 57, 1, '2025-06-29 10:48:21'),
(463, 'Xã Khâm Đức', '48087', 57, 1, '2025-06-29 10:48:21'),
(464, 'Xã Phước Năng', '48088', 57, 1, '2025-06-29 10:48:21'),
(465, 'Xã Phước Chánh', '48089', 57, 1, '2025-06-29 10:48:21'),
(466, 'Xã Phước Thành', '48090', 57, 1, '2025-06-29 10:48:21'),
(467, 'Xã Phước Hiệp', '48091', 57, 1, '2025-06-29 10:48:21'),
(468, 'Xã Tam Hải', '48092', 57, 1, '2025-06-29 10:48:21'),
(469, 'Xã Tân Hiệp', '48093', 57, 1, '2025-06-29 10:48:21'),
(470, 'Đặc Khu Hoàng Sa', '48094', 57, 1, '2025-06-29 10:48:21'),
(471, 'Xã Lạc Dương', '68001', 62, 1, '2025-06-29 13:19:07'),
(472, 'Xã Đơn Dương', '68002', 62, 1, '2025-06-29 13:19:07'),
(473, 'Xã Ka Đô', '68003', 62, 1, '2025-06-29 13:19:07'),
(474, 'Xã Quảng Lập', '68004', 62, 1, '2025-06-29 13:19:07'),
(475, 'Xã D’Ran', '68005', 62, 1, '2025-06-29 13:19:07'),
(476, 'Xã Hiệp Thạnh', '68006', 62, 1, '2025-06-29 13:19:07'),
(477, 'Xã Đức Trọng', '68007', 62, 1, '2025-06-29 13:19:07'),
(478, 'Xã Tân Hội', '68008', 62, 1, '2025-06-29 13:19:07'),
(479, 'Xã Tà Hine', '68009', 62, 1, '2025-06-29 13:19:07'),
(480, 'Xã Tà Năng', '68010', 62, 1, '2025-06-29 13:19:07'),
(481, 'Xã Đinh Văn Lâm Hà', '68011', 62, 1, '2025-06-29 13:19:07'),
(482, 'Xã Phú Sơn Lâm Hà', '68012', 62, 1, '2025-06-29 13:19:07'),
(483, 'Xã Nam Hà Lâm Hà', '68013', 62, 1, '2025-06-29 13:19:07'),
(484, 'Xã Nam Ban Lâm Hà', '68014', 62, 1, '2025-06-29 13:19:08'),
(485, 'Xã Tân Hà Lâm Hà', '68015', 62, 1, '2025-06-29 13:19:08'),
(486, 'Xã Phúc Thọ Lâm Hà', '68016', 62, 1, '2025-06-29 13:19:08'),
(487, 'Xã Đam Rông 1', '68017', 62, 1, '2025-06-29 13:19:08'),
(488, 'Xã Đam Rông 2', '68018', 62, 1, '2025-06-29 13:19:08'),
(489, 'Xã Đam Rông 3', '68019', 62, 1, '2025-06-29 13:19:08'),
(490, 'Xã Đam Rông 4', '68020', 62, 1, '2025-06-29 13:19:08'),
(491, 'Xã Di Linh', '68021', 62, 1, '2025-06-29 13:19:08'),
(492, 'Xã Hòa Ninh', '68022', 62, 1, '2025-06-29 13:19:08'),
(493, 'Xã Hòa Bắc', '68023', 62, 1, '2025-06-29 13:19:08'),
(494, 'Xã Đinh Trang Thượng', '68024', 62, 1, '2025-06-29 13:19:08'),
(495, 'Xã Bảo Thuận', '68025', 62, 1, '2025-06-29 13:19:08'),
(496, 'Xã Sơn Điền', '68026', 62, 1, '2025-06-29 13:19:08'),
(497, 'Xã Gia Hiệp', '68027', 62, 1, '2025-06-29 13:19:08'),
(498, 'Xã Bảo Lâm 1', '68028', 62, 1, '2025-06-29 13:19:08'),
(499, 'Xã Bảo Lâm 2', '68029', 62, 1, '2025-06-29 13:19:08'),
(500, 'Xã Bảo Lâm 3', '68030', 62, 1, '2025-06-29 13:19:08'),
(501, 'Xã Bảo Lâm 4', '68031', 62, 1, '2025-06-29 13:19:08'),
(502, 'Xã Bảo Lâm 5', '68032', 62, 1, '2025-06-29 13:19:08'),
(503, 'Xã Đạ Huoai', '68033', 62, 1, '2025-06-29 13:19:08'),
(504, 'Xã Đạ Huoai 2', '68034', 62, 1, '2025-06-29 13:19:08'),
(505, 'Xã Đạ Tẻh', '68035', 62, 1, '2025-06-29 13:19:08'),
(506, 'Xã Đạ Tẻh 2', '68036', 62, 1, '2025-06-29 13:19:08'),
(507, 'Xã Đạ Tẻh 3', '68037', 62, 1, '2025-06-29 13:19:08'),
(508, 'Xã Cát Tiên', '68038', 62, 1, '2025-06-29 13:19:08'),
(509, 'Xã Cát Tiên 2', '68039', 62, 1, '2025-06-29 13:19:08'),
(510, 'Xã Cát Tiên 3', '68040', 62, 1, '2025-06-29 13:19:08'),
(511, 'Xã Vĩnh Hảo', '68041', 62, 1, '2025-06-29 13:19:08'),
(512, 'Xã Liên Hương', '68042', 62, 1, '2025-06-29 13:19:08'),
(513, 'Xã Tuy Phong', '68043', 62, 1, '2025-06-29 13:19:08'),
(514, 'Xã Phan Rí Cửa', '68044', 62, 1, '2025-06-29 13:19:08'),
(515, 'Xã Bắc Bình', '68045', 62, 1, '2025-06-29 13:19:08'),
(516, 'Xã Hồng Thái', '68046', 62, 1, '2025-06-29 13:19:08'),
(517, 'Xã Hải Ninh', '68047', 62, 1, '2025-06-29 13:19:08'),
(518, 'Xã Phan Sơn', '68048', 62, 1, '2025-06-29 13:19:08'),
(519, 'Xã Sông Lũy', '68049', 62, 1, '2025-06-29 13:19:08'),
(520, 'Xã Lương Sơn', '68050', 62, 1, '2025-06-29 13:19:08'),
(521, 'Xã Hòa Thắng', '68051', 62, 1, '2025-06-29 13:19:08'),
(522, 'Xã Đông Giang', '68052', 62, 1, '2025-06-29 13:19:08'),
(523, 'Xã La Dạ', '68053', 62, 1, '2025-06-29 13:19:08'),
(524, 'Xã Hàm Thuận Bắc', '68054', 62, 1, '2025-06-29 13:19:08'),
(525, 'Xã Hàm Thuận', '68055', 62, 1, '2025-06-29 13:19:08'),
(526, 'Xã Hồng Sơn', '68056', 62, 1, '2025-06-29 13:19:08'),
(527, 'Xã Hàm Liêm', '68057', 62, 1, '2025-06-29 13:19:08'),
(528, 'Xã Tuyên Quang', '68058', 62, 1, '2025-06-29 13:19:08'),
(529, 'Xã Hàm Thạnh', '68059', 62, 1, '2025-06-29 13:19:08'),
(530, 'Xã Hàm Kiệm', '68060', 62, 1, '2025-06-29 13:19:08'),
(531, 'Xã Tân Thành', '68061', 62, 1, '2025-06-29 13:19:08'),
(532, 'Xã Hàm Thuận Nam', '68062', 62, 1, '2025-06-29 13:19:08'),
(533, 'Xã Tân Lập', '68063', 62, 1, '2025-06-29 13:19:08'),
(534, 'Xã Tân Minh', '68064', 62, 1, '2025-06-29 13:19:08'),
(535, 'Xã Hàm Tân', '68065', 62, 1, '2025-06-29 13:19:08'),
(536, 'Xã Sơn Mỹ', '68066', 62, 1, '2025-06-29 13:19:08'),
(537, 'Xã Tân Hải', '68067', 62, 1, '2025-06-29 13:19:08'),
(538, 'Xã Nghị Đức', '68068', 62, 1, '2025-06-29 13:19:08'),
(539, 'Xã Bắc Ruộng', '68069', 62, 1, '2025-06-29 13:19:08'),
(540, 'Xã Đồng Kho', '68070', 62, 1, '2025-06-29 13:19:08'),
(541, 'Xã Tánh Linh', '68071', 62, 1, '2025-06-29 13:19:08'),
(542, 'Xã Suối Kiết', '68072', 62, 1, '2025-06-29 13:19:08'),
(543, 'Xã Nam Thành', '68073', 62, 1, '2025-06-29 13:19:08'),
(544, 'Xã Đức Linh', '68074', 62, 1, '2025-06-29 13:19:08'),
(545, 'Xã Hoài Đức', '68075', 62, 1, '2025-06-29 13:19:08'),
(546, 'Xã Trà Tân', '68076', 62, 1, '2025-06-29 13:19:08'),
(547, 'Xã Đắk Wil', '68077', 62, 1, '2025-06-29 13:19:08'),
(548, 'Xã Nam Dong', '68078', 62, 1, '2025-06-29 13:19:08'),
(549, 'Xã Cư Jút', '68079', 62, 1, '2025-06-29 13:19:08'),
(550, 'Xã Thuận An', '68080', 62, 1, '2025-06-29 13:19:08'),
(551, 'Xã Đức Lập', '68081', 62, 1, '2025-06-29 13:19:08'),
(552, 'Xã Đắk Mil', '68082', 62, 1, '2025-06-29 13:19:08'),
(553, 'Xã Đắk Sắk', '68083', 62, 1, '2025-06-29 13:19:08'),
(554, 'Xã Nam Đà', '68084', 62, 1, '2025-06-29 13:19:08'),
(555, 'Xã Krông Nô', '68085', 62, 1, '2025-06-29 13:19:08'),
(556, 'Xã Nâm Nung', '68086', 62, 1, '2025-06-29 13:19:08'),
(557, 'Xã Quảng Phú', '68087', 62, 1, '2025-06-29 13:19:08'),
(558, 'Xã Đắk Song', '68088', 62, 1, '2025-06-29 13:19:08'),
(559, 'Xã Đức An', '68089', 62, 1, '2025-06-29 13:19:08'),
(560, 'Xã Thuận Hạnh', '68090', 62, 1, '2025-06-29 13:19:08'),
(561, 'Xã Trường Xuân', '68091', 62, 1, '2025-06-29 13:19:08'),
(562, 'Xã Tà Đùng', '68092', 62, 1, '2025-06-29 13:19:08'),
(563, 'Xã Quảng Khê', '68093', 62, 1, '2025-06-29 13:19:08'),
(564, 'Xã Quảng Tân', '68094', 62, 1, '2025-06-29 13:19:08'),
(565, 'Xã Tuy Đức', '68095', 62, 1, '2025-06-29 13:19:08'),
(566, 'Xã Kiến Đức', '68096', 62, 1, '2025-06-29 13:19:08'),
(567, 'Xã Nhân Cơ', '68097', 62, 1, '2025-06-29 13:19:08'),
(568, 'Xã Quảng Tín', '68098', 62, 1, '2025-06-29 13:19:08'),
(569, 'Xã Đạ Huoai 3', '68099', 62, 1, '2025-06-29 13:19:08'),
(570, 'Xã Quảng Hòa', '68100', 62, 1, '2025-06-29 13:19:08'),
(571, 'Xã Quảng Sơn', '68101', 62, 1, '2025-06-29 13:19:08'),
(572, 'Xã Quảng Trực', '68102', 62, 1, '2025-06-29 13:19:08'),
(573, 'Xã Ninh Gia', '68103', 62, 1, '2025-06-29 13:19:08'),
(574, 'Phường Xuân Hương - Đà Lạt', '68104', 62, 1, '2025-06-29 13:19:08'),
(575, 'Phường Cam Ly - Đà Lạt', '68105', 62, 1, '2025-06-29 13:19:08'),
(576, 'Phường Lâm Viên - Đà Lạt', '68106', 62, 1, '2025-06-29 13:19:08'),
(577, 'Phường Xuân Trường - Đà Lạt', '68107', 62, 1, '2025-06-29 13:19:08'),
(578, 'Phường Lang Biang - Đà Lạt', '68108', 62, 1, '2025-06-29 13:19:08'),
(579, 'Phường 1 Bảo Lộc', '68109', 62, 1, '2025-06-29 13:19:08'),
(580, 'Phường 2 Bảo Lộc', '68110', 62, 1, '2025-06-29 13:19:08'),
(581, 'Phường 3 Bảo Lộc', '68111', 62, 1, '2025-06-29 13:19:08'),
(582, 'Phường B’Lao', '68112', 62, 1, '2025-06-29 13:19:08'),
(583, 'Phường Hàm Thắng', '68113', 62, 1, '2025-06-29 13:19:08'),
(584, 'Phường Bình Thuận', '68114', 62, 1, '2025-06-29 13:19:08'),
(585, 'Phường Mũi Né', '68115', 62, 1, '2025-06-29 13:19:08'),
(586, 'Phường Phú Thủy', '68116', 62, 1, '2025-06-29 13:19:08'),
(587, 'Phường Phan Thiết', '68117', 62, 1, '2025-06-29 13:19:08'),
(588, 'Phường Tiến Thành', '68118', 62, 1, '2025-06-29 13:19:08'),
(589, 'Phường La Gi', '68119', 62, 1, '2025-06-29 13:19:08'),
(590, 'Phường Phước Hội', '68120', 62, 1, '2025-06-29 13:19:08'),
(591, 'Phường Bắc Gia Nghĩa', '68121', 62, 1, '2025-06-29 13:19:08'),
(592, 'Phường Nam Gia Nghĩa', '68122', 62, 1, '2025-06-29 13:19:08'),
(593, 'Phường Đông Gia Nghĩa', '68123', 62, 1, '2025-06-29 13:19:08'),
(594, 'Đặc Khu Phú Quý', '68124', 62, 1, '2025-06-29 13:19:08');

-- --------------------------------------------------------

--
-- Table structure for table `dm_xa_phuong_backup`
--

CREATE TABLE `dm_xa_phuong_backup` (
  `id` int NOT NULL DEFAULT '0',
  `ten_xa_phuong` varchar(100) NOT NULL,
  `ma_xa_phuong` varchar(20) DEFAULT NULL,
  `tinh_id` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dm_xa_phuong_backup`
--

INSERT INTO `dm_xa_phuong_backup` (`id`, `ten_xa_phuong`, `ma_xa_phuong`, `tinh_id`, `is_active`, `created_at`) VALUES
(1, 'Xã Tân Đông', '72.19', 1, 1, '2025-05-24 20:06:33'),
(2, 'Xã Thủ Dầu Một', 'XP0022', 64, 1, '2025-05-24 20:06:33'),
(3, 'Xã Dương Minh Châu', '72.18', 1, 1, '2025-06-26 11:17:22'),
(4, 'Phường Tân Ninh', '72.01', 1, 1, '2025-06-26 11:25:11'),
(5, 'Phường Bình Minh', '72.02', 1, 1, '2025-06-26 11:26:15'),
(6, 'Phường Ninh Thạnh', '72.03', 1, 1, '2025-06-26 11:27:05'),
(7, 'Phường Long Hoa', '72.04', 1, 1, '2025-06-26 11:27:31'),
(8, 'Phường Hòa Thành', '72.05', 1, 1, '2025-06-26 11:27:46'),
(9, 'Phường Thanh Điền', '72.06', 1, 1, '2025-06-26 11:28:00'),
(10, 'Phường Trảng Bàng', '72.07', 1, 1, '2025-06-26 11:28:27'),
(11, 'Phường An Tịnh', '72.08', 1, 1, '2025-06-26 11:28:41'),
(12, 'Phường Gò Dầu', '72.09', 1, 1, '2025-06-26 11:29:12'),
(13, 'Phường Gia Lộc', '72.10', 1, 1, '2025-06-26 11:29:40'),
(14, 'Xã Hưng Thuận', '72.11', 1, 1, '2025-06-26 11:30:19'),
(15, 'Xã Phước Chỉ', '72.12', 1, 1, '2025-06-26 11:31:15'),
(16, 'Xã Thạnh Đức', '72.13', 1, 1, '2025-06-26 11:31:36'),
(17, 'Xã Phước Thạnh', '72.14', 1, 1, '2025-06-26 11:31:50'),
(18, 'Xã Truông Mít', '72.15', 1, 1, '2025-06-26 11:32:03'),
(19, 'Xã Lộc Ninh', '72.16', 1, 1, '2025-06-26 11:32:13'),
(20, 'Xã Cầu Khởi', '72.17', 1, 1, '2025-06-26 11:32:23'),
(21, 'Xã Tân Châu', '72.20', 1, 1, '2025-06-26 11:33:45'),
(22, 'Xã Tân Phú', '72.21', 1, 1, '2025-06-26 11:34:08'),
(23, 'Xã Tân Hội', '72.22', 1, 1, '2025-06-26 11:34:18'),
(24, 'Xã Tân Thành', '72.23', 1, 1, '2025-06-26 11:34:32'),
(25, 'Xã Tân Hoà (xã biên giới)', '72.24', 1, 1, '2025-06-26 11:34:55'),
(26, 'Xã Tân Lập (xã biên giới)', '72.25', 1, 1, '2025-06-26 11:35:12'),
(27, 'Xã Tân Biên (xã biên giới)', '72.26', 1, 1, '2025-06-26 11:35:57'),
(28, 'Xã Thạnh Bình', '72.27', 1, 1, '2025-06-26 11:36:15'),
(29, 'Xã Trà Vong', '72.28', 1, 1, '2025-06-26 11:36:27'),
(30, 'Xã Phước Vinh (xã biên giới)', '72.29', 1, 1, '2025-06-26 11:36:41'),
(31, 'Xã Hòa Hội (xã biên giới)', '72.30', 1, 1, '2025-06-26 11:36:57'),
(32, 'Xã Ninh Điền (xã biên giới)', '72.31', 1, 1, '2025-06-26 11:37:07'),
(33, 'Xã Châu Thành', '72.32', 1, 1, '2025-06-26 11:37:36'),
(34, 'Xã Hảo Đước', '72.33', 1, 1, '2025-06-26 11:37:56'),
(35, 'Xã Long Chữ (xã biên giới)', '72.34', 1, 1, '2025-06-26 11:38:13'),
(36, 'Xã Long Thuận (xã biên giới)', '72.35', 1, 1, '2025-06-26 11:38:25'),
(37, 'Xã Bến Cầu (xã biên giới)', '72.36', 1, 1, '2025-06-26 11:38:38'),
(38, 'Phường Đông Hòa', '79001', 64, 1, '2025-06-26 11:53:51'),
(39, 'Phường Dĩ An', '79002', 64, 1, '2025-06-26 11:53:51'),
(40, 'Phường Tân Đông Hiệp', '79003', 64, 1, '2025-06-26 11:53:51'),
(41, 'Phường Thuận An', '79004', 64, 1, '2025-06-26 11:53:51'),
(42, 'Phường Thuận Giao', '79005', 64, 1, '2025-06-26 11:53:51'),
(43, 'Phường Bình Hòa', '79006', 64, 1, '2025-06-26 11:53:51'),
(44, 'Phường Lái Thiêu', '79007', 64, 1, '2025-06-26 11:53:51'),
(45, 'Phường An Phú', '79008', 64, 1, '2025-06-26 11:53:51'),
(46, 'Phường Bình Dương', '79009', 64, 1, '2025-06-26 11:53:51'),
(47, 'Phường Chánh Hiệp', '79010', 64, 1, '2025-06-26 11:53:51'),
(48, 'Phường Thủ Dầu Một', '79011', 64, 1, '2025-06-26 11:53:51'),
(49, 'Phường Phú Lợi', '79012', 64, 1, '2025-06-26 11:53:51'),
(50, 'Phường Vĩnh Tân', '79013', 64, 1, '2025-06-26 11:53:51'),
(51, 'Phường Bình Cơ', '79014', 64, 1, '2025-06-26 11:53:51'),
(52, 'Phường Tân Uyên', '79015', 64, 1, '2025-06-26 11:53:51'),
(53, 'Phường Tân Hiệp', '79016', 64, 1, '2025-06-26 11:53:51'),
(54, 'Phường Tân Khánh', '79017', 64, 1, '2025-06-26 11:53:51'),
(55, 'Phường Phú An', '79018', 64, 1, '2025-06-26 11:53:51'),
(56, 'Phường Tây Nam', '79019', 64, 1, '2025-06-26 11:53:51'),
(57, 'Phường Long Nguyên', '79020', 64, 1, '2025-06-26 11:53:51'),
(58, 'Phường Bến Cát', '79021', 64, 1, '2025-06-26 11:53:51'),
(59, 'Phường Chánh Phú Hòa', '79022', 64, 1, '2025-06-26 11:53:51'),
(60, 'Phường Thới Hòa', '79023', 64, 1, '2025-06-26 11:53:51'),
(61, 'Phường Hòa Lợi', '79024', 64, 1, '2025-06-26 11:53:51'),
(62, 'Xã Bắc Tân Uyên', '79025', 64, 1, '2025-06-26 11:53:51'),
(63, 'Xã Thường Tân', '79026', 64, 1, '2025-06-26 11:53:51'),
(64, 'Xã An Long', '79027', 64, 1, '2025-06-26 11:53:51'),
(65, 'Xã Phước Thành', '79028', 64, 1, '2025-06-26 11:53:51'),
(66, 'Xã Phước Hòa', '79029', 64, 1, '2025-06-26 11:53:51'),
(67, 'Xã Phú Giáo', '79030', 64, 1, '2025-06-26 11:53:51'),
(68, 'Xã Trừ Văn Thố', '79031', 64, 1, '2025-06-26 11:53:51'),
(69, 'Xã Bàu Bàng', '79032', 64, 1, '2025-06-26 11:53:51'),
(70, 'Xã Minh Thạnh', '79033', 64, 1, '2025-06-26 11:53:51'),
(71, 'Xã Long Hòa', '79034', 64, 1, '2025-06-26 11:53:51'),
(72, 'Xã Dầu Tiếng', '79035', 64, 1, '2025-06-26 11:53:51'),
(73, 'Xã Thanh An', '79036', 64, 1, '2025-06-26 11:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `game_ket_qua`
--

CREATE TABLE `game_ket_qua` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `hiep` int DEFAULT '1',
  `so_kg` float DEFAULT '0',
  `diem` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_list`
--

CREATE TABLE `game_list` (
  `id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `hinh_thuc_id` int NOT NULL,
  `ten_game` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Tên game...',
  `so_luong_can_thu` int DEFAULT '0',
  `so_bang` int DEFAULT '1',
  `so_hiep` int DEFAULT '1',
  `luat_choi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Luật game...',
  `gia_game_stake` int DEFAULT '100000',
  `tong_phi_game` int DEFAULT '0',
  `system_fee` int DEFAULT '0',
  `game_online_discount` int DEFAULT '40000',
  `ngay_to_chuc` date DEFAULT NULL,
  `gio_bat_dau` time DEFAULT NULL,
  `thoi_gian_dong_dang_ky` date DEFAULT NULL,
  `status` enum('cho_xac_nhan','dang_dien_ra','hoan_tat','huy') DEFAULT 'cho_xac_nhan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `thoi_luong_phut_hiep` int DEFAULT '60',
  `min_user_exp` int DEFAULT '0',
  `min_user_level` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `game_list`
--

INSERT INTO `game_list` (`id`, `ho_cau_id`, `creator_id`, `hinh_thuc_id`, `ten_game`, `so_luong_can_thu`, `so_bang`, `so_hiep`, `luat_choi`, `gia_game_stake`, `tong_phi_game`, `system_fee`, `game_online_discount`, `ngay_to_chuc`, `gio_bat_dau`, `thoi_gian_dong_dang_ky`, `status`, `created_at`, `thoi_luong_phut_hiep`, `min_user_exp`, `min_user_level`) VALUES
(12, 37, 1, 3, 'Game Xúc Xích 5', 10, 1, 1, 'Luật game...', 100000, 55000, 0, 40000, '2025-07-04', '06:00:00', NULL, 'dang_dien_ra', '2025-06-23 11:57:03', 60, 0, 0),
(13, 36, 1, 4, 'Game Xúc Xích 6', 20, 1, 1, 'Luật game...', 200000, 110000, 0, 40000, '2025-06-26', '06:00:00', NULL, 'dang_dien_ra', '2025-06-23 12:54:34', 60, 0, 0),
(14, 37, 1, 3, 'Game Xúc Xích 6', 10, 1, 1, '', 20000, 37000, 0, 40000, '2025-06-25', '06:00:00', NULL, 'dang_dien_ra', '2025-06-24 10:15:00', 60, 0, 0),
(15, 37, 1, 1, 'Game Xúc Xích 5', 12, 1, 1, '', 100000, 528000, 0, 40000, '2025-07-11', '08:00:00', NULL, 'huy', '2025-06-24 11:08:53', 60, 0, 0),
(16, 36, 1, 3, 'Game Xúc Xích 7', 15, 1, 1, '', 100000, 70000, 0, 40000, '2025-06-24', '06:00:00', NULL, 'dang_dien_ra', '2025-06-24 11:14:09', 60, 0, 0),
(17, 37, 1, 3, 'Game Xúc Xích 8', 15, 1, 1, '', 100000, 64500, 0, 40000, '2025-06-25', '06:00:00', NULL, 'cho_xac_nhan', '2025-06-24 14:14:51', 60, 0, 0),
(18, 37, 1, 3, 'Game Xúc Xích 8', 15, 1, 1, '', 100000, 450000, 0, 40000, '2025-06-25', '07:00:00', NULL, 'huy', '2025-06-24 14:29:09', 90, 0, 0),
(19, 37, 1, 3, 'Game Xúc Xích 9', 20, 1, 1, '', 100000, 800000, 0, 40000, '2025-06-25', '07:00:00', NULL, 'huy', '2025-06-24 14:40:15', 120, 0, 0),
(20, 37, 1, 3, 'Game Xúc Xích 10', 20, 1, 1, 'Game Xúc Xích 10', 100000, 440000, 0, 40000, '2025-06-25', '07:00:00', NULL, 'cho_xac_nhan', '2025-06-24 14:54:48', 60, 0, 0),
(21, 37, 1, 3, 'Game Xúc Xích 11', 20, 1, 1, 'tự do', 100000, 880000, 0, 40000, '2025-06-26', '07:00:00', NULL, 'huy', '2025-06-24 15:11:10', 120, 0, 0),
(22, 36, 1, 4, 'Game Xúc Xích 16', 24, 1, 1, 'Luật đài hiện hành, ko xả tay và mồi động vật tươi sống', 100000, 990000, 0, 40000, '2025-06-28', '07:00:00', NULL, 'hoan_tat', '2025-06-24 16:32:05', 90, 0, 0),
(23, 37, 1, 5, 'Game Xúc Xích 19', 20, 1, 1, '', 10000010, 550000, 0, 40000, '2025-06-25', '08:00:00', NULL, 'hoan_tat', '2025-06-24 18:27:42', 75, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `game_log`
--

CREATE TABLE `game_log` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `action` varchar(50) NOT NULL,
  `message` text,
  `actor_id` int DEFAULT NULL,
  `actor_vai_tro` enum('admin','moderator','chuho','canthu','system') DEFAULT 'system',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_schedule`
--

CREATE TABLE `game_schedule` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `bang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `vi_tri` int DEFAULT '0',
  `hiep` int DEFAULT '1',
  `so_kg` float DEFAULT '0',
  `diem` int DEFAULT '0',
  `vi_pham` int DEFAULT '0',
  `tong_diem` int GENERATED ALWAYS AS ((`diem` + `vi_pham`)) STORED,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_tong_hop`
--

CREATE TABLE `game_tong_hop` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `tong_diem` int DEFAULT '0',
  `tong_kg` float DEFAULT '0',
  `hang_xep_hang` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_user`
--

CREATE TABLE `game_user` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Nick name...',
  `trang_thai` enum('cho_xac_nhan','xac_nhan','da_thanh_toan','tu_choi','bi_loai') DEFAULT 'cho_xac_nhan',
  `da_thanh_toan` tinyint(1) DEFAULT '0',
  `payment_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giai_game_hinh_thuc`
--

CREATE TABLE `giai_game_hinh_thuc` (
  `id` int NOT NULL,
  `ten_hinh_thuc` varchar(255) NOT NULL,
  `hinh_thuc` enum('giai','game','giai_tron') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'game',
  `mo_ta` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `so_nguoi_min` int DEFAULT '10',
  `so_nguoi_max` int DEFAULT '500',
  `so_bang` int DEFAULT '1',
  `so_hiep` int DEFAULT '1',
  `nguyen_tac` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'nguyên tắc game...',
  `cho_phep_canthu_tao` tinyint(1) DEFAULT '0',
  `luat_xoay_bang` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giai_game_hinh_thuc`
--

INSERT INTO `giai_game_hinh_thuc` (`id`, `ten_hinh_thuc`, `hinh_thuc`, `mo_ta`, `so_nguoi_min`, `so_nguoi_max`, `so_bang`, `so_hiep`, `nguyen_tac`, `cho_phep_canthu_tao`, `luat_xoay_bang`) VALUES
(1, 'Giải đấu 2-Hiệp 4-Bảng (dùng nhiều)', 'giai', 'Câu giải cá phi, chép hỗn hợp', 16, 200, 4, 2, 'Chia cần thủ ra 4 bảng (A, B, C, D).\r\nCâu xong hiệp 1 sẽ đổi bờ theo nguyên tắc:\r\nA ↔ C; B ↔ D.\r\nKhông trùng biên.', 1, NULL),
(2, 'Giải đấu 2-Hiệp 2-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp', 8, 100, 2, 2, 'Chia cần thủ ra 2 bảng (A, B).\r\nCâu xong hiệp 1 sẽ đổi bờ theo nguyên tắc:\r\nA ↔ B.\r\nKhông trùng biên.', 1, NULL),
(3, 'Game- Mini', 'game', 'Dành cho solo, nhóm bạn câu, mini game vui vẻ số lượng <20 người', 2, 20, 1, 1, 'Tự random vị trí sau mỗi trận', 1, NULL),
(4, 'Game - Mini khui hồ', 'game', 'Dành cho hồ câu mới khui, khai trương từ 20- 100 người', 20, 100, 2, 1, 'Random vị trí cần thủ khi bắt đầu.\r\nCâu 1 hiệp duy nhất, thời lượng 3–5 tiếng.\r\nThích hợp khui hồ hoặc giải nội bộ.', 1, NULL),
(5, 'Giải đấu 4-Hiệp 4-Bảng (dùng nhiều)', 'giai', 'Câu giải cá phi, chép hỗn hợp', 16, 200, 4, 4, 'Chia cần thủ ra 4 bảng: A, B, C, D, E; câu 4 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: A → B; B → C; C → D; D → E, E→ A. Nguyên tắc: Xoay vòng tròn - Không trùng biên bảng trước.', 1, NULL),
(6, 'Giải đấu 5-Hiệp 5-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp ', 20, 250, 5, 5, 'Chia cần thủ ra 5 bảng: A, B, C, D, E; câu 5 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: A → B; B → C; C → D; D → E, E→ A. Nguyên tắc: Xoay vòng tròn - Không trùng biên bảng trước.', 0, NULL),
(7, 'Giải đấu 6-Hiệp 06-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp', 24, 300, 6, 6, 'Chia cần thủ ra 6 bảng: A, B, C, D, E, F; câu 6 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: A → B; B → C; C → D; D → E, E → F, E→ A. Nguyên tắc: Xoay vòng tròn - Không trùng biên bảng trước.', 0, NULL),
(8, 'Giải đấu 3-Hiệp 3-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp', 12, 150, 3, 3, 'Chia cần thủ ra 3 bảng: A, B, C; câu 3 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: A → B; B → C; C → A. Nguyên tắc: Xoay vòng tròn - Không trùng biên bảng trước.', 0, NULL),
(9, 'Giải đấu 3-Hiệp 6-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp', 24, 300, 6, 3, 'Chia cần thủ ra 6 bảng: A, B, C, D, E, F, đấu 3 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: [A → C → E → A] ; [B → D → F→ B] Nguyên tắc: Xoay chéo bảng - Không trùng biên bảng trước.', 0, NULL),
(10, 'Giải đấu 4-Hiệp 8-Bảng (dùng nhiều)', 'giai', 'Câu giải cá phi, chép hỗn hợp', 32, 400, 8, 4, 'Chia cần thủ ra 8 bảng: A, B, C, D, E, F, G, H đấu 4 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: [A → C → E → G → A] ; [B → D → F→ H → B] Nguyên tắc: Xoay chéo bảng - Không trùng biên bảng trước.', 0, NULL),
(11, 'Giải đấu 5-Hiệp 10-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp', 40, 500, 10, 5, 'Chia cần thủ ra 10 bảng: A, B, C, D, E, F, G, H, I, J đấu 5 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: [A → C → E → G → I → A] ; [B → D → F→ H → J → B] Nguyên tắc: Xoay chéo bảng - Không trùng biên bảng trước.', 0, NULL),
(12, 'Giải đấu 6-Hiệp 12-Bảng', 'giai', 'Câu giải cá phi, chép hỗn hợp', 48, 600, 12, 6, 'Chia cần thủ ra 10 bảng: A, B, C, D, E, F, G, H, I, J, K, L đấu 5 hiệp. Câu xong mỗi hiệp sẽ đổi bờ theo nguyên tắc: [A → C → E → G → I → K→ A] ; [B → D → F→ H → J → L→ B] Nguyên tắc: Xoay chéo bảng - Không trùng biên bảng trước.', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `giai_list`
--

CREATE TABLE `giai_list` (
  `id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `hinh_thuc_id` int NOT NULL,
  `ten_giai` varchar(255) NOT NULL,
  `so_luong_can_thu` int NOT NULL,
  `so_bang` int DEFAULT '0',
  `so_hiep` int DEFAULT '1',
  `thoi_luong_phut_hiep` int DEFAULT '60',
  `ngay_to_chuc` date NOT NULL,
  `gio_bat_dau` time NOT NULL,
  `thoi_gian_dong_dang_ky` datetime NOT NULL,
  `tien_cuoc` int DEFAULT '0',
  `phi_giai` int DEFAULT '0',
  `phi_ho` int DEFAULT '0',
  `luat_choi` text,
  `status` enum('dang_cho_xac_nhan','dang_mo_dang_ky','chot_xong_danh_sach','dang_dau_hiep_1','dang_dau_hiep_2','dang_dau_hiep_3','dang_dau_hiep_4','so_ket_giai','hoan_tat_giai','huy_giai','chuyen_chu_ho_duyet','dang_dau_hiep_5','dang_dau_hiep_6','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'dang_cho_xac_nhan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `min_user_exp` int DEFAULT '0',
  `min_user_level` int DEFAULT '0',
  `quy_tac_xoay_tu_chon` varchar(2000) DEFAULT '0' COMMENT 'Lưu JSON xoay bảng tùy chỉnh nếu chọn custom'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giai_list`
--

INSERT INTO `giai_list` (`id`, `ho_cau_id`, `creator_id`, `hinh_thuc_id`, `ten_giai`, `so_luong_can_thu`, `so_bang`, `so_hiep`, `thoi_luong_phut_hiep`, `ngay_to_chuc`, `gio_bat_dau`, `thoi_gian_dong_dang_ky`, `tien_cuoc`, `phi_giai`, `phi_ho`, `luat_choi`, `status`, `created_at`, `min_user_exp`, `min_user_level`, `quy_tac_xoay_tu_chon`) VALUES
(1, 37, 1, 2, 'Giải Chấn Thành fishing', 20, 2, 2, 90, '2025-07-26', '07:00:00', '2025-07-25 23:59:00', 800000, 864000, 0, 'Tự do, 3m6', 'dang_dau_hiep_1', '2025-07-01 13:22:37', 0, 0, '0'),
(2, 37, 1, 1, 'Giải Chấn Thành fishing', 20, 4, 2, 60, '2025-07-26', '07:00:00', '2025-07-25 23:59:00', 800000, 1320000, 0, 'tự do', 'dang_mo_dang_ky', '2025-07-01 13:58:07', 0, 0, '0'),
(3, 37, 1, 1, 'Giải Chấn Thành fishing 33', 20, 4, 2, 75, '2025-07-16', '07:00:00', '2025-07-01 23:59:00', 700000, 1650000, 0, 'Tự do', 'dang_mo_dang_ky', '2025-07-01 16:18:13', 0, 0, '0'),
(4, 37, 1, 1, 'Giải Chấn Thành fishing', 40, 4, 2, 60, '2025-07-02', '07:00:00', '2025-07-01 23:59:00', 700000, 2200000, 0, '', 'dang_cho_xac_nhan', '2025-07-01 16:20:10', 0, 0, '0'),
(5, 40, 1, 2, 'Giải Chấn Thành fishing', 40, 4, 2, 120, '2025-07-07', '07:00:00', '2025-07-01 23:59:00', 700000, 5280000, 0, '', 'huy_giai', '2025-07-01 16:30:19', 0, 0, '0'),
(6, 40, 1, 1, 'Giải Chấn Thành fishing', 20, 4, 2, 60, '2025-07-02', '07:00:00', '2025-07-01 23:59:00', 600000, 1296000, 0, 'tự do', 'hoan_tat_giai', '2025-07-01 16:34:20', 0, 0, '0'),
(7, 39, 1, 1, 'Giải Chấn Thành fishing 72', 20, 4, 2, 60, '2025-07-03', '07:00:00', '2025-07-02 23:59:00', 700000, 1296000, 0, 'tự do trùng chỉ', 'so_ket_giai', '2025-07-02 09:10:16', 0, 0, '0'),
(8, 40, 1, 1, 'Giải Chấn Thành fishing 888', 20, 4, 2, 60, '2025-07-13', '07:00:00', '2025-07-05 23:59:00', 700000, 1320000, 0, 'rg', 'dang_cho_xac_nhan', '2025-07-05 09:39:28', 0, 0, '0'),
(9, 35, 1, 1, 'Giải Chấn Thành fishing 77', 20, 4, 2, 90, '2025-07-17', '07:00:00', '2025-07-05 23:59:00', 700000, 1980000, 0, 'Tự do, mồi về, không xả', 'chuyen_chu_ho_duyet', '2025-07-05 13:43:44', 0, 0, '0'),
(10, 39, 1, 5, 'Giải Chấn Thành fishing 66', 17, 4, 4, 60, '2025-07-13', '07:00:00', '2025-07-02 23:59:00', 700000, 1320000, 0, '', 'hoan_tat_giai', '2025-07-05 13:53:30', 0, 0, '0'),
(11, 40, 1, 1, 'Giải Chấn Thành fishing 55', 20, 4, 2, 60, '2025-07-17', '07:00:00', '2025-07-05 23:59:00', 700000, 1320000, 0, 'tự do', 'chot_xong_danh_sach', '2025-07-05 13:57:47', 0, 0, '0'),
(12, 36, 1, 1, 'Giải Chấn Thành fishing 55', 20, 4, 2, 45, '2025-07-15', '07:00:00', '2025-07-02 23:59:00', 700000, 990000, 0, '', 'dang_dau_hiep_1', '2025-07-05 14:07:31', 0, 0, '0'),
(13, 40, 1, 1, 'Giải Chấn Thành fishing 5544', 20, 4, 2, 45, '2025-07-13', '07:00:00', '2025-07-11 23:59:00', 700000, 990000, 0, '', 'dang_dau_hiep_2', '2025-07-05 14:44:41', 0, 0, '0'),
(14, 46, 1, 2, 'Giải HH mở rộng 1', 20, 2, 2, 60, '2025-07-15', '07:00:00', '2025-07-13 23:59:00', 700000, 1320000, 0, '', 'dang_dau_hiep_3', '2025-07-07 03:02:05', 0, 0, '0'),
(15, 46, 1, 5, 'Giải HH mở rộng 22', 20, 4, 4, 60, '2025-07-14', '07:00:00', '2025-07-13 23:59:00', 700000, 2640000, 0, '', 'dang_dau_hiep_4', '2025-07-07 05:07:53', 0, 0, '0'),
(16, 46, 1, 5, 'Giải HH mở rộng 33', 17, 4, 4, 45, '2025-07-14', '07:00:00', '2025-07-13 23:59:00', 700000, 1980000, 0, '', 'hoan_tat_giai', '2025-07-07 05:09:14', 0, 0, '0'),
(17, 47, 1, 2, 'Giải HH mở rộng 44', 20, 2, 2, 60, '2025-07-16', '07:00:00', '2025-07-14 23:59:00', 700000, 1320000, 0, '', 'so_ket_giai', '2025-07-08 10:23:45', 0, 0, '0'),
(18, 36, 1, 1, 'Giải HH mở rộng 77', 20, 4, 2, 45, '2025-07-17', '07:00:00', '2025-07-16 23:59:00', 700000, 990000, 0, '', 'huy_giai', '2025-07-10 08:43:02', 0, 0, '0'),
(19, 36, 1, 2, 'Giải HH mở rộng 88', 30, 2, 2, 75, '2025-07-17', '07:00:00', '2025-07-16 23:59:00', 700000, 2475000, 0, '', 'chuyen_chu_ho_duyet', '2025-07-10 08:56:00', 0, 0, '0'),
(20, 40, 1, 2, 'Giải HH mở rộng 99', 19, 2, 2, 45, '2025-07-17', '07:00:00', '2025-07-16 23:59:00', 700000, 990000, 0, '', 'dang_dau_hiep_3', '2025-07-10 09:04:36', 0, 0, '0'),
(21, 34, 1, 5, 'Test 4-4 Cho_xac_nhan', 20, 4, 4, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'dang_cho_xac_nhan', '2025-07-13 07:08:38', 0, 0, '0'),
(22, 34, 1, 5, 'Test 4-4 chuyen_chu_ho_duyet', 20, 4, 4, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'chuyen_chu_ho_duyet', '2025-07-13 07:09:12', 0, 0, '0'),
(23, 34, 1, 5, 'Test 4-4 dang_mo_dang_ky', 20, 4, 4, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'dang_mo_dang_ky', '2025-07-13 07:10:28', 0, 0, '0'),
(24, 34, 1, 5, 'Test 4-4 chot_xong_danh_sach', 17, 3, 3, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'so_ket_giai', '2025-07-13 07:14:19', 0, 0, '0'),
(25, 34, 1, 5, 'Test 4-4 dang_dau_hiep 1', 49, 12, 6, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'so_ket_giai', '2025-07-13 07:49:46', 0, 0, '0'),
(26, 34, 1, 5, 'Test 4-4 chot_xong_danh_sach', 18, 2, 2, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'dang_mo_dang_ky', '2025-07-13 07:14:19', 0, 0, '0'),
(27, 34, 1, 5, 'Test 4-4 chot_xong_danh_sach', 17, 3, 3, 60, '2025-07-20', '07:00:00', '2025-07-19 23:59:00', 700000, 2640000, 0, '', 'dang_mo_dang_ky', '2025-07-13 07:14:19', 0, 0, '0'),
(28, 38, 2, 5, 'Tự tạo Hồ số 1', 30, 4, 4, 60, '2025-07-24', '07:00:00', '2025-07-23 23:59:00', 700000, 3960000, 0, 'tự do', 'dang_cho_xac_nhan', '2025-07-17 07:04:30', 0, 0, '0'),
(29, 35, 2, 5, 'Tự tạo Hồ số 2', 17, 4, 4, 60, '2025-07-24', '07:00:00', '2025-07-23 23:59:00', 700000, 2640000, 0, 'Tự do 2', 'hoan_tat_giai', '2025-07-17 07:06:53', 0, 0, '0'),
(30, 40, 1, 2, 'Giải Chấn Thành fishing 111', 20, 2, 2, 60, '2025-07-24', '07:00:00', '2025-07-23 23:59:00', 700000, 1320000, 0, 'tự do', 'chuyen_chu_ho_duyet', '2025-07-17 07:27:51', 0, 0, '0'),
(31, 40, 2, 1, 'Tự tạo Hồ số  3', 20, 4, 2, 60, '2025-07-24', '07:00:00', '2025-07-23 23:59:00', 700000, 1320000, 0, '', 'dang_cho_xac_nhan', '2025-07-17 13:50:05', 0, 0, '0'),
(32, 40, 2, 1, 'Tự tạo Hồ số  5', 60, 4, 2, 60, '2025-07-24', '07:00:00', '2025-07-23 23:59:00', 700000, 3960000, 0, '', 'dang_cho_xac_nhan', '2025-07-17 14:54:52', 0, 0, '0'),
(33, 40, 1, 1, 'Giải Chấn Thành fishing 55', 50, 4, 2, 60, '2025-07-25', '07:00:00', '2025-07-24 23:59:00', 700000, 3300000, 0, 'tự do', 'chuyen_chu_ho_duyet', '2025-07-18 00:47:43', 0, 0, '0'),
(34, 40, 1, 2, 'Hồ câu Bình Chánh 1 Thông tin hồ', 10, 2, 2, 60, '2025-07-25', '07:00:00', '2025-07-24 23:59:00', 700000, 550000, 440000, '', 'dang_mo_dang_ky', '2025-07-18 03:33:38', 0, 0, '0'),
(35, 40, 1, 2, 'Hồ câu Bình Chánh 1 Thông tin hồ', 10, 2, 2, 60, '2025-07-25', '07:00:00', '2025-07-24 23:59:00', 700000, 550000, 440000, '', 'dang_cho_xac_nhan', '2025-07-18 03:38:16', 0, 0, '0'),
(36, 35, 2, 8, 'Tự tạo Hồ số 33', 13, 3, 3, 60, '2025-07-28', '07:00:00', '2025-07-27 23:59:00', 700000, 1650000, 1320000, '', 'so_ket_giai', '2025-07-21 06:14:47', 0, 0, '0'),
(37, 40, 2, 6, 'Tự tạo Hồ số 55', 20, 5, 5, 60, '2025-07-28', '07:00:00', '2025-07-27 23:59:00', 700000, 2750000, 2200000, 'tự do', 'dang_mo_dang_ky', '2025-07-21 06:25:25', 0, 0, '0'),
(38, 40, 1, 5, 'Giải Chấn Thành fishing 55', 20, 4, 4, 60, '2025-07-31', '07:00:00', '2025-07-30 23:59:00', 700000, 2200000, 1760000, '', 'dang_mo_dang_ky', '2025-07-24 05:51:37', 0, 0, '0'),
(39, 40, 1, 1, 'Giải Chấn Thành fishing 11', 20, 4, 2, 60, '2025-08-14', '07:00:00', '2025-08-13 23:59:00', 700000, 1100000, 880000, '', 'dang_mo_dang_ky', '2025-08-07 05:48:17', 0, 0, '0'),
(40, 40, 2, 2, 'Tự tạo Hồ số 66', 20, 2, 2, 60, '2025-08-14', '07:00:00', '2025-08-13 23:59:00', 700000, 1100000, 880000, '', 'dang_mo_dang_ky', '2025-08-07 05:51:16', 0, 0, '0'),
(41, 40, 1, 1, 'Giải ngày 20-08', 17, 4, 2, 60, '2025-08-27', '07:00:00', '2025-08-26 23:59:00', 700000, 1100000, 880000, '', 'so_ket_giai', '2025-08-20 08:34:21', 0, 0, '0'),
(42, 40, 2, 8, 'Giải Hoà bình 20-08111', 21, 3, 3, 60, '2025-08-27', '07:00:00', '2025-08-26 23:59:00', 700000, 1732500, 1386000, '', 'dang_mo_dang_ky', '2025-08-20 15:30:47', 0, 0, '0'),
(43, 40, 1, 2, 'Giiải 2108000', 10, 2, 2, 60, '2025-08-28', '07:00:00', '2025-08-27 23:59:00', 700000, 550000, 440000, '', 'dang_mo_dang_ky', '2025-08-21 10:51:25', 0, 0, '0'),
(44, 40, 2, 2, 'Giải Hoà bình 20-08111', 20, 2, 2, 60, '2025-08-28', '07:00:00', '2025-08-27 23:59:00', 700000, 1100000, 880000, '', 'dang_mo_dang_ky', '2025-08-21 14:28:50', 0, 0, '0'),
(45, 40, 1, 5, 'Giải rô mâm 0821', 20, 4, 4, 60, '2025-08-28', '07:00:00', '2025-08-27 23:59:00', 700000, 2200000, 1760000, '', 'dang_cho_xac_nhan', '2025-08-21 16:20:04', 0, 0, '0'),
(46, 50, 2, 2, 'Tự tạo Hồ số  3', 20, 2, 2, 60, '2025-09-02', '07:00:00', '2025-09-01 23:59:00', 700000, 1100000, 880000, '', 'dang_cho_xac_nhan', '2025-08-26 15:44:21', 0, 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `giai_log`
--

CREATE TABLE `giai_log` (
  `id` int NOT NULL,
  `giai_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` enum('create','update','duyet_chuho','huy_chuho','tham_gia','roi_giai','chot_danh_sach','bat_dau_hiep','chot_hiep','hoan_tat','he_thong') NOT NULL,
  `note` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giai_log`
--

INSERT INTO `giai_log` (`id`, `giai_id`, `user_id`, `action`, `note`, `created_at`) VALUES
(1, 14, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-07 12:54:57'),
(2, 15, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-07 12:59:05'),
(3, 15, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-07 13:18:22'),
(4, 16, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-07 13:18:26'),
(5, 16, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-07 13:24:12'),
(6, 16, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-07 13:27:17'),
(7, 16, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-07 13:28:02'),
(8, 16, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-07 13:36:01'),
(9, 16, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-07 13:37:42'),
(10, 16, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-07 23:49:02'),
(11, 2, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-07 23:50:36'),
(12, 3, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-08 15:21:59'),
(13, 3, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-08 15:23:26'),
(14, 17, 17, 'huy_chuho', 'Chủ hồ từ chối duyệt – hoàn tiền cho người tạo', '2025-07-08 17:32:25'),
(15, 17, 17, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-08 17:41:58'),
(16, 12, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-10 00:05:57'),
(17, 20, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-10 16:05:43'),
(18, 23, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-13 14:12:37'),
(19, 24, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-13 14:15:05'),
(20, 25, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-13 14:50:01'),
(21, 29, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-17 14:29:28'),
(22, 36, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-21 13:15:09'),
(23, 38, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-07-24 12:52:30'),
(24, 39, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-07 12:48:49'),
(25, 37, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-07 12:49:49'),
(26, 40, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-07 12:51:33'),
(27, 41, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-20 15:35:01'),
(28, 42, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-20 22:31:11'),
(29, 43, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-21 17:51:43'),
(30, 44, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-21 21:32:05'),
(31, 34, 2, 'duyet_chuho', 'Chủ hồ đã duyệt mở giải.', '2025-08-26 22:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `giai_schedule`
--

CREATE TABLE `giai_schedule` (
  `id` int NOT NULL,
  `giai_id` int NOT NULL,
  `user_id` int NOT NULL,
  `so_hiep` int NOT NULL,
  `so_bang` varchar(1) NOT NULL,
  `vi_tri_ngoi` int NOT NULL,
  `so_kg` decimal(7,2) DEFAULT '0.00',
  `so_diem` decimal(4,1) DEFAULT '0.0',
  `tong_diem` decimal(5,1) DEFAULT '0.0',
  `diem_cong_vi_pham` decimal(4,1) DEFAULT '0.0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_bien` tinyint(1) DEFAULT '0' COMMENT '1 = ngồi biên (đầu/cuối bảng), 0 = vị trí thường'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giai_schedule`
--

INSERT INTO `giai_schedule` (`id`, `giai_id`, `user_id`, `so_hiep`, `so_bang`, `vi_tri_ngoi`, `so_kg`, `so_diem`, `tong_diem`, `diem_cong_vi_pham`, `created_at`, `is_bien`) VALUES
(1, 16, 1, 1, 'A', 1, 20.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 1),
(2, 16, 1, 2, 'B', 3, 40.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(3, 16, 1, 3, 'C', 2, 60.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(4, 16, 1, 4, 'D', 1, 90.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(5, 16, 19, 1, 'B', 4, 12.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 1),
(6, 16, 19, 2, 'C', 3, 11.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(7, 16, 19, 3, 'D', 1, 51.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 1),
(8, 16, 19, 4, 'A', 2, 23.66, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(9, 16, 23, 1, 'D', 3, 6.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(10, 16, 23, 2, 'A', 1, 12.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(11, 16, 23, 3, 'B', 2, 14.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(12, 16, 23, 4, 'C', 4, 34.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(13, 16, 24, 1, 'A', 3, 13.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(14, 16, 24, 2, 'B', 4, 33.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(15, 16, 24, 3, 'C', 5, 45.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(16, 16, 24, 4, 'D', 4, 22.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(21, 16, 26, 1, 'C', 1, 30.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 1),
(22, 16, 26, 2, 'D', 2, 6.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(23, 16, 26, 3, 'A', 4, 12.30, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(24, 16, 26, 4, 'B', 3, 34.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(25, 16, 27, 1, 'A', 2, 12.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(26, 16, 27, 2, 'B', 1, 12.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(27, 16, 27, 3, 'C', 4, 55.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(28, 16, 27, 4, 'D', 3, 11.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(29, 16, 28, 1, 'D', 2, 5.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(30, 16, 28, 2, 'A', 4, 28.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(31, 16, 28, 3, 'B', 3, 25.55, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(32, 16, 28, 4, 'C', 1, 45.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(33, 16, 29, 1, 'C', 2, 55.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(34, 16, 29, 2, 'D', 4, 22.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 1),
(35, 16, 29, 3, 'A', 2, 15.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(36, 16, 29, 4, 'B', 4, 12.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(37, 16, 30, 1, 'A', 4, 80.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(38, 16, 30, 2, 'B', 5, 11.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(39, 16, 30, 3, 'C', 3, 23.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(40, 16, 30, 4, 'D', 5, 22.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(41, 16, 31, 1, 'B', 3, 16.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(42, 16, 31, 2, 'C', 4, 22.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(43, 16, 31, 3, 'D', 2, 45.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(44, 16, 31, 4, 'A', 4, 45.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(45, 16, 32, 1, 'B', 1, 2.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(46, 16, 32, 2, 'C', 2, 11.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(47, 16, 32, 3, 'D', 4, 56.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(48, 16, 32, 4, 'A', 3, 34.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(49, 16, 33, 1, 'C', 4, 2.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(50, 16, 33, 2, 'D', 3, 6.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(51, 16, 33, 3, 'A', 1, 43.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(52, 16, 33, 4, 'B', 2, 36.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 0),
(53, 16, 34, 1, 'D', 1, 4.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(54, 16, 34, 2, 'A', 2, 14.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(55, 16, 34, 3, 'B', 4, 12.65, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 1),
(56, 16, 34, 4, 'C', 2, 12.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(57, 16, 35, 1, 'B', 2, 12.00, 3.0, 3.0, 0.0, '2025-07-08 12:03:54', 0),
(58, 16, 35, 2, 'C', 1, 11.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(59, 16, 35, 3, 'D', 3, 45.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(60, 16, 35, 4, 'A', 1, 12.30, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(61, 16, 36, 1, 'D', 4, 33.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(62, 16, 36, 2, 'A', 3, 17.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(63, 16, 36, 3, 'B', 1, 12.23, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(64, 16, 36, 4, 'C', 3, 43.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(65, 16, 37, 1, 'C', 3, 1.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 0),
(66, 16, 37, 2, 'D', 1, 60.00, 1.0, 1.0, 0.0, '2025-07-08 12:03:54', 1),
(67, 16, 37, 3, 'A', 3, 23.00, 2.0, 2.0, 0.0, '2025-07-08 12:03:54', 0),
(68, 16, 37, 4, 'B', 1, 12.00, 4.0, 4.0, 0.0, '2025-07-08 12:03:54', 1),
(137, 20, 24, 1, 'A', 10, 0.00, 1.0, 1.0, 0.0, '2025-07-10 10:03:19', 1),
(138, 20, 24, 2, 'B', 7, 0.00, 1.0, 1.0, 0.0, '2025-07-10 10:03:19', 0),
(139, 20, 1, 1, 'B', 8, 0.00, 1.0, 1.0, 0.0, '2025-07-10 10:03:19', 0),
(140, 20, 1, 2, 'C', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-10 10:03:19', 0),
(141, 20, 42, 1, 'A', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-10 10:03:19', 0),
(142, 20, 42, 2, 'B', 10, 0.00, 2.0, 2.0, 0.0, '2025-07-10 10:03:19', 1),
(143, 20, 43, 1, 'B', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-10 10:03:19', 0),
(144, 20, 43, 2, 'C', 7, 0.00, 2.0, 2.0, 0.0, '2025-07-10 10:03:19', 0),
(145, 20, 44, 1, 'A', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-10 10:03:19', 0),
(146, 20, 44, 2, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-10 10:03:19', 0),
(147, 20, 45, 1, 'A', 9, 0.00, 4.0, 4.0, 0.0, '2025-07-10 10:03:19', 0),
(148, 20, 45, 2, 'B', 9, 0.00, 4.0, 4.0, 0.0, '2025-07-10 10:03:19', 0),
(149, 20, 46, 1, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-10 10:03:19', 0),
(150, 20, 46, 2, 'C', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-10 10:03:19', 1),
(151, 20, 47, 1, 'A', 7, 0.00, 5.0, 5.0, 0.0, '2025-07-10 10:03:19', 0),
(152, 20, 47, 2, 'B', 1, 0.00, 5.0, 5.0, 0.0, '2025-07-10 10:03:19', 1),
(153, 20, 48, 1, 'A', 5, 0.00, 6.0, 6.0, 0.0, '2025-07-10 10:03:19', 0),
(154, 20, 48, 2, 'B', 8, 0.00, 6.0, 6.0, 0.0, '2025-07-10 10:03:19', 0),
(155, 20, 49, 1, 'B', 9, 0.00, 4.0, 4.0, 0.0, '2025-07-10 10:03:19', 1),
(156, 20, 49, 2, 'C', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-10 10:03:19', 0),
(157, 20, 50, 1, 'A', 6, 0.00, 7.0, 7.0, 0.0, '2025-07-10 10:03:19', 0),
(158, 20, 50, 2, 'B', 4, 0.00, 7.0, 7.0, 0.0, '2025-07-10 10:03:19', 0),
(159, 20, 51, 1, 'B', 6, 0.00, 5.0, 5.0, 0.0, '2025-07-10 10:03:19', 0),
(160, 20, 51, 2, 'C', 5, 0.00, 5.0, 5.0, 0.0, '2025-07-10 10:03:19', 0),
(161, 20, 52, 1, 'A', 8, 0.00, 8.0, 8.0, 0.0, '2025-07-10 10:03:19', 0),
(162, 20, 52, 2, 'B', 5, 0.00, 8.0, 8.0, 0.0, '2025-07-10 10:03:19', 0),
(163, 20, 53, 1, 'B', 7, 0.00, 6.0, 6.0, 0.0, '2025-07-10 10:03:19', 0),
(164, 20, 53, 2, 'C', 8, 0.00, 6.0, 6.0, 0.0, '2025-07-10 10:03:19', 0),
(165, 20, 54, 1, 'A', 1, 0.00, 9.0, 9.0, 0.0, '2025-07-10 10:03:19', 1),
(166, 20, 54, 2, 'B', 6, 0.00, 9.0, 9.0, 0.0, '2025-07-10 10:03:19', 0),
(167, 20, 55, 1, 'A', 2, 0.00, 10.0, 10.0, 0.0, '2025-07-10 10:03:19', 0),
(168, 20, 55, 2, 'B', 2, 0.00, 10.0, 10.0, 0.0, '2025-07-10 10:03:19', 0),
(169, 20, 56, 1, 'B', 5, 0.00, 7.0, 7.0, 0.0, '2025-07-10 10:03:19', 0),
(170, 20, 56, 2, 'C', 9, 0.00, 7.0, 7.0, 0.0, '2025-07-10 10:03:19', 1),
(171, 20, 57, 1, 'B', 1, 0.00, 8.0, 8.0, 0.0, '2025-07-10 10:03:19', 1),
(172, 20, 57, 2, 'C', 3, 0.00, 8.0, 8.0, 0.0, '2025-07-10 10:03:19', 0),
(173, 20, 58, 1, 'B', 4, 0.00, 9.0, 9.0, 0.0, '2025-07-10 10:03:19', 0),
(174, 20, 58, 2, 'C', 6, 0.00, 9.0, 9.0, 0.0, '2025-07-10 10:03:19', 0),
(441, 10, 62, 1, 'C', 2, 8.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 0),
(442, 10, 62, 2, 'D', 1, 3.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:21', 1),
(443, 10, 62, 3, 'A', 3, 6.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 0),
(444, 10, 62, 4, 'B', 4, 1.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:21', 1),
(445, 10, 63, 1, 'A', 1, 40.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 1),
(446, 10, 63, 2, 'B', 3, 4.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:21', 0),
(447, 10, 63, 3, 'C', 5, 6.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 1),
(448, 10, 63, 4, 'D', 4, 4.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 0),
(449, 10, 44, 1, 'A', 5, 2.00, 5.0, 5.0, 0.0, '2025-07-12 14:36:21', 1),
(450, 10, 44, 2, 'B', 4, 1.00, 5.0, 5.0, 0.0, '2025-07-12 14:36:21', 0),
(451, 10, 44, 3, 'C', 4, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:21', 0),
(452, 10, 44, 4, 'D', 5, 4.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:21', 1),
(453, 10, 59, 1, 'C', 1, 2.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:21', 1),
(454, 10, 59, 2, 'D', 3, 4.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:21', 0),
(455, 10, 59, 3, 'A', 4, 4.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:21', 1),
(456, 10, 59, 4, 'B', 2, 3.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:21', 0),
(457, 10, 64, 1, 'B', 2, 3.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:21', 0),
(458, 10, 64, 2, 'C', 1, 3.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:21', 1),
(459, 10, 64, 3, 'D', 2, 2.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:21', 0),
(460, 10, 64, 4, 'A', 1, 30.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 1),
(461, 10, 60, 1, 'B', 4, 11.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:21', 1),
(462, 10, 60, 2, 'C', 3, 0.22, 4.0, 4.0, 0.0, '2025-07-12 14:36:21', 0),
(463, 10, 60, 3, 'D', 4, 4.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 1),
(464, 10, 60, 4, 'A', 2, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(465, 10, 61, 1, 'A', 2, 7.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 0),
(466, 10, 61, 2, 'B', 2, 6.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 0),
(467, 10, 61, 3, 'C', 1, 2.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 1),
(468, 10, 61, 4, 'D', 3, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(469, 10, 65, 1, 'C', 3, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(470, 10, 65, 2, 'D', 4, 6.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 1),
(471, 10, 65, 3, 'A', 2, 2.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 0),
(472, 10, 65, 4, 'B', 1, 4.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 1),
(473, 10, 66, 1, 'A', 4, 4.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 0),
(474, 10, 66, 2, 'B', 5, 2.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 1),
(475, 10, 66, 3, 'C', 2, 2.00, 5.0, 5.0, 0.0, '2025-07-12 14:36:22', 0),
(476, 10, 66, 4, 'D', 2, 4.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 0),
(477, 10, 67, 1, 'B', 1, 5.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 1),
(478, 10, 67, 2, 'C', 2, 6.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 0),
(479, 10, 67, 3, 'D', 1, 1.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 1),
(480, 10, 67, 4, 'A', 3, 5.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 0),
(481, 10, 68, 1, 'C', 4, 5.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 1),
(482, 10, 68, 2, 'D', 2, 1.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 0),
(483, 10, 68, 3, 'A', 1, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 1),
(484, 10, 68, 4, 'B', 3, 2.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(485, 10, 69, 1, 'A', 3, 6.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(486, 10, 69, 2, 'B', 1, 2.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 1),
(487, 10, 69, 3, 'C', 3, 6.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 0),
(488, 10, 69, 4, 'D', 1, 2.00, 5.0, 5.0, 0.0, '2025-07-12 14:36:22', 1),
(489, 10, 70, 1, 'B', 3, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(490, 10, 70, 2, 'C', 4, 12.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 1),
(491, 10, 70, 3, 'D', 3, 2.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(492, 10, 70, 4, 'A', 4, 3.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 1),
(493, 10, 71, 1, 'D', 3, 3.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 0),
(494, 10, 71, 2, 'A', 4, 11.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 1),
(495, 10, 71, 3, 'B', 3, 2.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 0),
(496, 10, 71, 4, 'C', 4, 1.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 1),
(497, 10, 72, 1, 'D', 2, 2.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 0),
(498, 10, 72, 2, 'A', 1, 1.00, 4.0, 4.0, 0.0, '2025-07-12 14:36:22', 1),
(499, 10, 72, 3, 'B', 2, 4.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 0),
(500, 10, 72, 4, 'C', 1, 4.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 1),
(501, 10, 73, 1, 'D', 1, 3.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 1),
(502, 10, 73, 2, 'A', 2, 2.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(503, 10, 73, 3, 'B', 4, 3.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 1),
(504, 10, 73, 4, 'C', 3, 4.00, 3.0, 3.0, 0.0, '2025-07-12 14:36:22', 0),
(505, 10, 1, 1, 'D', 4, 6.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 1),
(506, 10, 1, 2, 'A', 3, 5.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 0),
(507, 10, 1, 3, 'B', 1, 4.00, 2.0, 2.0, 0.0, '2025-07-12 14:36:22', 1),
(508, 10, 1, 4, 'C', 2, 5.00, 1.0, 1.0, 0.0, '2025-07-12 14:36:22', 0),
(1069, 24, 74, 1, 'C', 2, 34.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 0),
(1070, 24, 74, 2, 'A', 1, 20.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 1),
(1071, 24, 74, 3, 'B', 3, 1.30, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 0),
(1072, 24, 42, 1, 'A', 4, 54.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 0),
(1073, 24, 42, 2, 'B', 6, 32.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 1),
(1074, 24, 42, 3, 'C', 3, 12.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 0),
(1075, 24, 75, 1, 'C', 3, 65.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 0),
(1076, 24, 75, 2, 'A', 3, 43.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 0),
(1077, 24, 75, 3, 'B', 5, 15.20, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 1),
(1078, 24, 76, 1, 'C', 5, 23.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 1),
(1079, 24, 76, 2, 'A', 4, 24.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1080, 24, 76, 3, 'B', 1, 3.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 1),
(1081, 24, 77, 1, 'B', 3, 34.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 0),
(1082, 24, 77, 2, 'C', 4, 34.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1083, 24, 77, 3, 'A', 3, 3.00, 6.0, 6.0, 0.0, '2025-07-14 09:45:58', 0),
(1084, 24, 78, 1, 'A', 6, 23.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 1),
(1085, 24, 78, 2, 'B', 3, 54.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 0),
(1086, 24, 78, 3, 'C', 2, 4.11, 6.0, 6.0, 0.0, '2025-07-14 09:45:58', 0),
(1087, 24, 79, 1, 'A', 1, 43.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 1),
(1088, 24, 79, 2, 'B', 5, 23.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 0),
(1089, 24, 79, 3, 'C', 1, 23.44, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 1),
(1090, 24, 80, 1, 'A', 5, 33.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1091, 24, 80, 2, 'B', 4, 34.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1092, 24, 80, 3, 'C', 6, 43.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 1),
(1093, 24, 81, 1, 'B', 4, 34.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1094, 24, 81, 2, 'C', 5, 23.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 0),
(1095, 24, 81, 3, 'A', 6, 12.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 1),
(1096, 24, 82, 1, 'B', 6, 34.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 1),
(1097, 24, 82, 2, 'C', 2, 23.00, 6.0, 6.0, 0.0, '2025-07-14 09:45:58', 0),
(1098, 24, 82, 3, 'A', 5, 12.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1099, 24, 83, 1, 'B', 2, 22.00, 6.0, 6.0, 0.0, '2025-07-14 09:45:58', 0),
(1100, 24, 83, 2, 'C', 6, 34.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 1),
(1101, 24, 83, 3, 'A', 2, 12.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 0),
(1102, 24, 84, 1, 'B', 1, 43.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 1),
(1103, 24, 84, 2, 'C', 3, 43.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 0),
(1104, 24, 84, 3, 'A', 1, 22.00, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 1),
(1105, 24, 85, 1, 'A', 3, 33.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 0),
(1106, 24, 85, 2, 'B', 1, 45.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 1),
(1107, 24, 85, 3, 'C', 5, 23.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1108, 24, 86, 1, 'A', 2, 22.00, 6.0, 6.0, 0.0, '2025-07-14 09:45:58', 0),
(1109, 24, 86, 2, 'B', 2, 23.00, 6.0, 6.0, 0.0, '2025-07-14 09:45:58', 0),
(1110, 24, 86, 3, 'C', 4, 23.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 0),
(1111, 24, 87, 1, 'C', 4, 34.00, 3.0, 3.0, 0.0, '2025-07-14 09:45:58', 0),
(1112, 24, 87, 2, 'A', 5, 43.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 1),
(1113, 24, 87, 3, 'B', 4, 23.10, 1.0, 1.0, 0.0, '2025-07-14 09:45:58', 0),
(1114, 24, 88, 1, 'B', 5, 34.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 0),
(1115, 24, 88, 2, 'C', 1, 43.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 1),
(1116, 24, 88, 3, 'A', 4, 12.00, 5.0, 5.0, 0.0, '2025-07-14 09:45:58', 0),
(1117, 24, 89, 1, 'C', 1, 32.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 1),
(1118, 24, 89, 2, 'A', 2, 23.00, 4.0, 4.0, 0.0, '2025-07-14 09:45:58', 0),
(1119, 24, 89, 3, 'B', 2, 21.00, 2.0, 2.0, 0.0, '2025-07-14 09:45:58', 0),
(1840, 25, 97, 1, 'L', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1841, 25, 97, 2, 'B', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1842, 25, 97, 3, 'D', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1843, 25, 97, 4, 'F', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1844, 25, 97, 5, 'H', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1845, 25, 97, 6, 'J', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1846, 25, 1, 1, 'F', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1847, 25, 1, 2, 'H', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1848, 25, 1, 3, 'J', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1849, 25, 1, 4, 'L', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1850, 25, 1, 5, 'B', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1851, 25, 1, 6, 'D', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1852, 25, 98, 1, 'C', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1853, 25, 98, 2, 'E', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1854, 25, 98, 3, 'G', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1855, 25, 98, 4, 'I', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1856, 25, 98, 5, 'K', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1857, 25, 98, 6, 'A', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1858, 25, 99, 1, 'B', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1859, 25, 99, 2, 'D', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1860, 25, 99, 3, 'F', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1861, 25, 99, 4, 'H', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1862, 25, 99, 5, 'J', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1863, 25, 99, 6, 'L', 3, 12.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1864, 25, 100, 1, 'C', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1865, 25, 100, 2, 'E', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1866, 25, 100, 3, 'G', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1867, 25, 100, 4, 'I', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1868, 25, 100, 5, 'K', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1869, 25, 100, 6, 'A', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1870, 25, 101, 1, 'C', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1871, 25, 101, 2, 'E', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1872, 25, 101, 3, 'G', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1873, 25, 101, 4, 'I', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1874, 25, 101, 5, 'K', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1875, 25, 101, 6, 'A', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1876, 25, 102, 1, 'H', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1877, 25, 102, 2, 'J', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1878, 25, 102, 3, 'L', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1879, 25, 102, 4, 'B', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1880, 25, 102, 5, 'D', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1881, 25, 102, 6, 'F', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1882, 25, 103, 1, 'G', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1883, 25, 103, 2, 'I', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1884, 25, 103, 3, 'K', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1885, 25, 103, 4, 'A', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1886, 25, 103, 5, 'C', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1887, 25, 103, 6, 'E', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1888, 25, 104, 1, 'F', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1889, 25, 104, 2, 'H', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1890, 25, 104, 3, 'J', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1891, 25, 104, 4, 'L', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1892, 25, 104, 5, 'B', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1893, 25, 104, 6, 'D', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1894, 25, 105, 1, 'B', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1895, 25, 105, 2, 'D', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1896, 25, 105, 3, 'F', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1897, 25, 105, 4, 'H', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1898, 25, 105, 5, 'J', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1899, 25, 105, 6, 'L', 2, 25.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1900, 25, 106, 1, 'D', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1901, 25, 106, 2, 'F', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1902, 25, 106, 3, 'H', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1903, 25, 106, 4, 'J', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1904, 25, 106, 5, 'L', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1905, 25, 106, 6, 'B', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1906, 25, 107, 1, 'J', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1907, 25, 107, 2, 'L', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1908, 25, 107, 3, 'B', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1909, 25, 107, 4, 'D', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1910, 25, 107, 5, 'F', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1911, 25, 107, 6, 'H', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1912, 25, 108, 1, 'G', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1913, 25, 108, 2, 'I', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1914, 25, 108, 3, 'K', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1915, 25, 108, 4, 'A', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1916, 25, 108, 5, 'C', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1917, 25, 108, 6, 'E', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1918, 25, 109, 1, 'A', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1919, 25, 109, 2, 'C', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1920, 25, 109, 3, 'E', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1921, 25, 109, 4, 'G', 5, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1922, 25, 109, 5, 'I', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1923, 25, 109, 6, 'K', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1924, 25, 110, 1, 'H', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1925, 25, 110, 2, 'J', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1926, 25, 110, 3, 'L', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1927, 25, 110, 4, 'B', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1928, 25, 110, 5, 'D', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1929, 25, 110, 6, 'F', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1930, 25, 111, 1, 'H', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1931, 25, 111, 2, 'J', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1932, 25, 111, 3, 'L', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1933, 25, 111, 4, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1934, 25, 111, 5, 'D', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1935, 25, 111, 6, 'F', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1936, 25, 112, 1, 'E', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1937, 25, 112, 2, 'G', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1938, 25, 112, 3, 'I', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1939, 25, 112, 4, 'K', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1940, 25, 112, 5, 'A', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1941, 25, 112, 6, 'C', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1942, 25, 113, 1, 'E', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1943, 25, 113, 2, 'G', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1944, 25, 113, 3, 'I', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1945, 25, 113, 4, 'K', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1946, 25, 113, 5, 'A', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1947, 25, 113, 6, 'C', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1948, 25, 114, 1, 'D', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1949, 25, 114, 2, 'F', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1950, 25, 114, 3, 'H', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1951, 25, 114, 4, 'J', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1952, 25, 114, 5, 'L', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1953, 25, 114, 6, 'B', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1954, 25, 115, 1, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1955, 25, 115, 2, 'D', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1956, 25, 115, 3, 'F', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1957, 25, 115, 4, 'H', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1958, 25, 115, 5, 'J', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1959, 25, 115, 6, 'L', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1960, 25, 116, 1, 'D', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1961, 25, 116, 2, 'F', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1962, 25, 116, 3, 'H', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1963, 25, 116, 4, 'J', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1964, 25, 116, 5, 'L', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1965, 25, 116, 6, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1966, 25, 117, 1, 'G', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1967, 25, 117, 2, 'I', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1968, 25, 117, 3, 'K', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1969, 25, 117, 4, 'A', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1970, 25, 117, 5, 'C', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1971, 25, 117, 6, 'E', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1972, 25, 118, 1, 'F', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1973, 25, 118, 2, 'H', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1974, 25, 118, 3, 'J', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1975, 25, 118, 4, 'L', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1976, 25, 118, 5, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1977, 25, 118, 6, 'D', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1978, 25, 119, 1, 'H', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:20', 0),
(1979, 25, 119, 2, 'J', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:20', 1),
(1980, 25, 119, 3, 'L', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:20', 0),
(1981, 25, 119, 4, 'B', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:20', 1),
(1982, 25, 119, 5, 'D', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:20', 0),
(1983, 25, 119, 6, 'F', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:20', 1),
(1984, 25, 120, 1, 'E', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1985, 25, 120, 2, 'G', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1986, 25, 120, 3, 'I', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1987, 25, 120, 4, 'K', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1988, 25, 120, 5, 'A', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 0),
(1989, 25, 120, 6, 'C', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:20', 1),
(1990, 25, 121, 1, 'J', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1991, 25, 121, 2, 'L', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1992, 25, 121, 3, 'B', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1993, 25, 121, 4, 'D', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1994, 25, 121, 5, 'F', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 0),
(1995, 25, 121, 6, 'H', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:20', 1),
(1996, 25, 122, 1, 'K', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1997, 25, 122, 2, 'A', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(1998, 25, 122, 3, 'C', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(1999, 25, 122, 4, 'E', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(2000, 25, 122, 5, 'G', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 0),
(2001, 25, 122, 6, 'I', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:20', 1),
(2002, 25, 123, 1, 'J', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2003, 25, 123, 2, 'L', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2004, 25, 123, 3, 'B', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2005, 25, 123, 4, 'D', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2006, 25, 123, 5, 'F', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2007, 25, 123, 6, 'H', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2008, 25, 124, 1, 'A', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2009, 25, 124, 2, 'C', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2010, 25, 124, 3, 'E', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2011, 25, 124, 4, 'G', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2012, 25, 124, 5, 'I', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2013, 25, 124, 6, 'K', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2014, 25, 125, 1, 'K', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2015, 25, 125, 2, 'A', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2016, 25, 125, 3, 'C', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2017, 25, 125, 4, 'E', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2018, 25, 125, 5, 'G', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2019, 25, 125, 6, 'I', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2020, 25, 126, 1, 'C', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2021, 25, 126, 2, 'E', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2022, 25, 126, 3, 'G', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2023, 25, 126, 4, 'I', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2024, 25, 126, 5, 'K', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2025, 25, 126, 6, 'A', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2026, 25, 127, 1, 'E', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2027, 25, 127, 2, 'G', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2028, 25, 127, 3, 'I', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2029, 25, 127, 4, 'K', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2030, 25, 127, 5, 'A', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2031, 25, 127, 6, 'C', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2032, 25, 128, 1, 'A', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2033, 25, 128, 2, 'C', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2034, 25, 128, 3, 'E', 5, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2035, 25, 128, 4, 'G', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2036, 25, 128, 5, 'I', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2037, 25, 128, 6, 'K', 5, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2038, 25, 129, 1, 'K', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2039, 25, 129, 2, 'A', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2040, 25, 129, 3, 'C', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2041, 25, 129, 4, 'E', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2042, 25, 129, 5, 'G', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2043, 25, 129, 6, 'I', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2044, 25, 130, 1, 'L', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2045, 25, 130, 2, 'B', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2046, 25, 130, 3, 'D', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2047, 25, 130, 4, 'F', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2048, 25, 130, 5, 'H', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2049, 25, 130, 6, 'J', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2050, 25, 131, 1, 'A', 5, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2051, 25, 131, 2, 'C', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2052, 25, 131, 3, 'E', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2053, 25, 131, 4, 'G', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2054, 25, 131, 5, 'I', 5, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2055, 25, 131, 6, 'K', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2056, 25, 132, 1, 'L', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2057, 25, 132, 2, 'B', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2058, 25, 132, 3, 'D', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2059, 25, 132, 4, 'F', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2060, 25, 132, 5, 'H', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2061, 25, 132, 6, 'J', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2062, 25, 133, 1, 'I', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:21', 1),
(2063, 25, 133, 2, 'K', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:21', 0),
(2064, 25, 133, 3, 'A', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:21', 1),
(2065, 25, 133, 4, 'C', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:21', 0),
(2066, 25, 133, 5, 'E', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:21', 1),
(2067, 25, 133, 6, 'G', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-16 05:37:21', 0),
(2068, 25, 134, 1, 'D', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2069, 25, 134, 2, 'F', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2070, 25, 134, 3, 'H', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2071, 25, 134, 4, 'J', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2072, 25, 134, 5, 'L', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2073, 25, 134, 6, 'B', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2074, 25, 135, 1, 'I', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2075, 25, 135, 2, 'K', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2076, 25, 135, 3, 'A', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2077, 25, 135, 4, 'C', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2078, 25, 135, 5, 'E', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 1),
(2079, 25, 135, 6, 'G', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-16 05:37:21', 0),
(2080, 25, 136, 1, 'A', 4, 0.00, 5.0, 5.0, 0.0, '2025-07-16 05:37:21', 0),
(2081, 25, 136, 2, 'C', 5, 0.00, 5.0, 5.0, 0.0, '2025-07-16 05:37:21', 1),
(2082, 25, 136, 3, 'E', 2, 0.00, 5.0, 5.0, 0.0, '2025-07-16 05:37:21', 0),
(2083, 25, 136, 4, 'G', 4, 0.00, 5.0, 5.0, 0.0, '2025-07-16 05:37:21', 0),
(2084, 25, 136, 5, 'I', 1, 0.00, 5.0, 5.0, 0.0, '2025-07-16 05:37:21', 1),
(2085, 25, 136, 6, 'K', 4, 0.00, 5.0, 5.0, 0.0, '2025-07-16 05:37:21', 0),
(2086, 25, 137, 1, 'K', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2087, 25, 137, 2, 'A', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2088, 25, 137, 3, 'C', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2089, 25, 137, 4, 'E', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2090, 25, 137, 5, 'G', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2091, 25, 137, 6, 'I', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2092, 25, 138, 1, 'G', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2093, 25, 138, 2, 'I', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2094, 25, 138, 3, 'K', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2095, 25, 138, 4, 'A', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2096, 25, 138, 5, 'C', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2097, 25, 138, 6, 'E', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2098, 25, 139, 1, 'J', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2099, 25, 139, 2, 'L', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2100, 25, 139, 3, 'B', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2101, 25, 139, 4, 'D', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2102, 25, 139, 5, 'F', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2103, 25, 139, 6, 'H', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2104, 25, 140, 1, 'L', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2105, 25, 140, 2, 'B', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2106, 25, 140, 3, 'D', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2107, 25, 140, 4, 'F', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2108, 25, 140, 5, 'H', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2109, 25, 140, 6, 'J', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2110, 25, 141, 1, 'B', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2111, 25, 141, 2, 'D', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2112, 25, 141, 3, 'F', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2113, 25, 141, 4, 'H', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2114, 25, 141, 5, 'J', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2115, 25, 141, 6, 'L', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2116, 25, 142, 1, 'F', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2117, 25, 142, 2, 'H', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2118, 25, 142, 3, 'J', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2119, 25, 142, 4, 'L', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2120, 25, 142, 5, 'B', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2121, 25, 142, 6, 'D', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2122, 25, 143, 1, 'I', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2123, 25, 143, 2, 'K', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2124, 25, 143, 3, 'A', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2125, 25, 143, 4, 'C', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2126, 25, 143, 5, 'E', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 0),
(2127, 25, 143, 6, 'G', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-16 05:37:21', 1),
(2128, 25, 144, 1, 'I', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2129, 25, 144, 2, 'K', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2130, 25, 144, 3, 'A', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2131, 25, 144, 4, 'C', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2132, 25, 144, 5, 'E', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 0),
(2133, 25, 144, 6, 'G', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-16 05:37:21', 1),
(2134, 29, 145, 1, 'B', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2135, 29, 145, 2, 'C', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2136, 29, 145, 3, 'D', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2137, 29, 145, 4, 'A', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2138, 29, 146, 1, 'D', 2, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2139, 29, 146, 2, 'A', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2140, 29, 146, 3, 'B', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2141, 29, 146, 4, 'C', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2142, 29, 147, 1, 'D', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2143, 29, 147, 2, 'A', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2144, 29, 147, 3, 'B', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2145, 29, 147, 4, 'C', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2146, 29, 17, 1, 'A', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2147, 29, 17, 2, 'B', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2148, 29, 17, 3, 'C', 4, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2149, 29, 17, 4, 'D', 5, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2150, 29, 148, 1, 'D', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2151, 29, 148, 2, 'A', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2152, 29, 148, 3, 'B', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2153, 29, 148, 4, 'C', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2154, 29, 149, 1, 'B', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2155, 29, 149, 2, 'C', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2156, 29, 149, 3, 'D', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2157, 29, 149, 4, 'A', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2158, 29, 150, 1, 'D', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 1),
(2159, 29, 150, 2, 'A', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2160, 29, 150, 3, 'B', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 1),
(2161, 29, 150, 4, 'C', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2162, 29, 151, 1, 'A', 5, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2163, 29, 151, 2, 'B', 2, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2164, 29, 151, 3, 'C', 1, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2165, 29, 151, 4, 'D', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2166, 29, 152, 1, 'C', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2167, 29, 152, 2, 'D', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2168, 29, 152, 3, 'A', 1, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 1),
(2169, 29, 152, 4, 'B', 3, 0.00, 1.0, 1.0, 0.0, '2025-07-18 01:55:13', 0),
(2170, 29, 153, 1, 'C', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2171, 29, 153, 2, 'D', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2172, 29, 153, 3, 'A', 3, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 0),
(2173, 29, 153, 4, 'B', 4, 0.00, 2.0, 2.0, 0.0, '2025-07-18 01:55:13', 1),
(2174, 29, 154, 1, 'A', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2175, 29, 154, 2, 'B', 3, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2176, 29, 154, 3, 'C', 5, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2177, 29, 154, 4, 'D', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2178, 29, 155, 1, 'A', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2179, 29, 155, 2, 'B', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2180, 29, 155, 3, 'C', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2181, 29, 155, 4, 'D', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2182, 29, 156, 1, 'A', 2, 0.00, 5.0, 5.0, 0.0, '2025-07-18 01:55:13', 0),
(2183, 29, 156, 2, 'B', 5, 0.00, 5.0, 5.0, 0.0, '2025-07-18 01:55:13', 1),
(2184, 29, 156, 3, 'C', 2, 0.00, 5.0, 5.0, 0.0, '2025-07-18 01:55:13', 0),
(2185, 29, 156, 4, 'D', 1, 0.00, 5.0, 5.0, 0.0, '2025-07-18 01:55:13', 1),
(2186, 29, 157, 1, 'C', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2187, 29, 157, 2, 'D', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2188, 29, 157, 3, 'A', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2189, 29, 157, 4, 'B', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2190, 29, 158, 1, 'B', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2191, 29, 158, 2, 'C', 4, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2192, 29, 158, 3, 'D', 2, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 0),
(2193, 29, 158, 4, 'A', 1, 0.00, 3.0, 3.0, 0.0, '2025-07-18 01:55:13', 1),
(2194, 29, 159, 1, 'B', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 1),
(2195, 29, 159, 2, 'C', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2196, 29, 159, 3, 'D', 4, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 1),
(2197, 29, 159, 4, 'A', 3, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2198, 29, 160, 1, 'C', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2199, 29, 160, 2, 'D', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 1),
(2200, 29, 160, 3, 'A', 2, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 0),
(2201, 29, 160, 4, 'B', 1, 0.00, 4.0, 4.0, 0.0, '2025-07-18 01:55:13', 1),
(2202, 36, 17, 1, 'B', 1, 4.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2203, 36, 17, 2, 'C', 3, 3.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2204, 36, 17, 3, 'A', 4, 7.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 1),
(2205, 36, 161, 1, 'A', 3, 5.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 0),
(2206, 36, 161, 2, 'B', 4, 1.00, 5.0, 5.0, 0.0, '2025-07-21 06:19:09', 0),
(2207, 36, 161, 3, 'C', 5, 6.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2208, 36, 162, 1, 'B', 2, 1.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 0),
(2209, 36, 162, 2, 'C', 1, 3.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 1),
(2210, 36, 162, 3, 'A', 2, 5.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2211, 36, 163, 1, 'C', 1, 8.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 1),
(2212, 36, 163, 2, 'A', 2, 2.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 0),
(2213, 36, 163, 3, 'B', 1, 8.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 1),
(2214, 36, 164, 1, 'A', 2, 2.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 0),
(2215, 36, 164, 2, 'B', 1, 6.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2216, 36, 164, 3, 'C', 4, 7.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 0),
(2217, 36, 165, 1, 'B', 3, 4.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2218, 36, 165, 2, 'C', 4, 4.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 1),
(2219, 36, 165, 3, 'A', 3, 6.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 0),
(2220, 36, 166, 1, 'C', 2, 4.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2221, 36, 166, 2, 'A', 4, 5.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 1),
(2222, 36, 166, 3, 'B', 3, 2.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2223, 36, 167, 1, 'A', 5, 3.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2224, 36, 167, 2, 'B', 3, 7.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 0),
(2225, 36, 167, 3, 'C', 2, 5.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 0),
(2226, 36, 168, 1, 'C', 3, 2.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 0),
(2227, 36, 168, 2, 'A', 1, 3.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2228, 36, 168, 3, 'B', 2, 1.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 0),
(2229, 36, 169, 1, 'C', 4, 5.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2230, 36, 169, 2, 'A', 3, 3.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2231, 36, 169, 3, 'B', 4, 3.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 1),
(2232, 36, 170, 1, 'B', 4, 70.00, 1.0, 1.0, 0.0, '2025-07-21 06:19:09', 1),
(2233, 36, 170, 2, 'C', 2, 4.00, 2.0, 2.0, 0.0, '2025-07-21 06:19:09', 0),
(2234, 36, 170, 3, 'A', 1, 4.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 1),
(2235, 36, 171, 1, 'A', 4, 3.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2236, 36, 171, 2, 'B', 5, 2.00, 4.0, 4.0, 0.0, '2025-07-21 06:19:09', 1),
(2237, 36, 171, 3, 'C', 3, 6.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2238, 36, 172, 1, 'A', 1, 0.01, 5.0, 5.0, 0.0, '2025-07-21 06:19:09', 1),
(2239, 36, 172, 2, 'B', 2, 6.00, 3.0, 3.0, 0.0, '2025-07-21 06:19:09', 0),
(2240, 36, 172, 3, 'C', 1, 4.00, 5.0, 5.0, 0.0, '2025-07-21 06:19:09', 1),
(2241, 41, 1, 1, 'C', 2, 7.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 0),
(2242, 41, 1, 2, 'A', 4, 5.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 1),
(2243, 41, 42, 1, 'D', 1, 7.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 1),
(2244, 41, 42, 2, 'B', 3, 3.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 0),
(2245, 41, 144, 1, 'D', 2, 2.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 0),
(2246, 41, 144, 2, 'B', 4, 5.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 1),
(2247, 41, 43, 1, 'A', 3, 3.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 0),
(2248, 41, 43, 2, 'C', 5, 2.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 1),
(2249, 41, 59, 1, 'A', 4, 4.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 0),
(2250, 41, 59, 2, 'C', 1, 6.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 1),
(2251, 41, 18, 1, 'A', 5, 5.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 1),
(2252, 41, 18, 2, 'C', 4, 1.00, 5.0, 5.0, 0.0, '2025-08-20 15:14:24', 0),
(2253, 41, 173, 1, 'D', 3, 3.40, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 0),
(2254, 41, 173, 2, 'B', 1, 6.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 1),
(2255, 41, 61, 1, 'A', 1, 1.00, 5.0, 5.0, 0.0, '2025-08-20 15:14:24', 1),
(2256, 41, 61, 2, 'C', 2, 7.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 0),
(2257, 41, 65, 1, 'C', 3, 6.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 0),
(2258, 41, 65, 2, 'A', 1, 3.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 1),
(2259, 41, 66, 1, 'C', 1, 4.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 1),
(2260, 41, 66, 2, 'A', 3, 4.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 0),
(2261, 41, 67, 1, 'A', 2, 2.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 0),
(2262, 41, 67, 2, 'C', 3, 3.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 0),
(2263, 41, 68, 1, 'D', 4, 4.50, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 1),
(2264, 41, 68, 2, 'B', 2, 7.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 0),
(2265, 41, 69, 1, 'B', 3, 2.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 0),
(2266, 41, 69, 2, 'D', 1, 3.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 1),
(2267, 41, 70, 1, 'B', 4, 3.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 1),
(2268, 41, 70, 2, 'D', 2, 4.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 0),
(2269, 41, 71, 1, 'B', 2, 1.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 0),
(2270, 41, 71, 2, 'D', 4, 2.00, 3.0, 3.0, 0.0, '2025-08-20 15:14:24', 1),
(2271, 41, 72, 1, 'C', 4, 7.00, 2.0, 2.0, 0.0, '2025-08-20 15:14:24', 1),
(2272, 41, 72, 2, 'A', 2, 3.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 0),
(2273, 41, 73, 1, 'B', 1, 6.00, 1.0, 1.0, 0.0, '2025-08-20 15:14:24', 1),
(2274, 41, 73, 2, 'D', 3, 2.00, 4.0, 4.0, 0.0, '2025-08-20 15:14:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `giai_user`
--

CREATE TABLE `giai_user` (
  `id` int NOT NULL,
  `giai_id` int NOT NULL,
  `user_id` int NOT NULL,
  `nickname` varchar(100) DEFAULT 'Nick name...',
  `trang_thai` enum('moi_cho_phan_hoi','cho_xac_nhan','moi_da_tu_choi','da_thanh_toan','huy') NOT NULL DEFAULT 'moi_cho_phan_hoi',
  `payment_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(255) DEFAULT NULL COMMENT 'Ghi chú nguồn đăng ký: tự online / được chủ giải thêm',
  `tong_diem` decimal(6,2) DEFAULT '0.00',
  `tong_kg` decimal(7,2) DEFAULT '0.00',
  `xep_hang` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `giai_user`
--

INSERT INTO `giai_user` (`id`, `giai_id`, `user_id`, `nickname`, `trang_thai`, `payment_time`, `created_at`, `note`, `tong_diem`, `tong_kg`, `xep_hang`) VALUES
(6, 16, 19, 'guest_9057', 'cho_xac_nhan', NULL, '2025-07-08 06:50:43', 'được chủ giải thêm vào', 9.00, 97.66, 6),
(7, 16, 23, 'Khach_46452', 'cho_xac_nhan', NULL, '2025-07-08 06:51:06', 'được chủ giải thêm vào', 11.00, 66.00, 12),
(8, 16, 24, 'Khach_29691', 'cho_xac_nhan', NULL, '2025-07-08 06:54:10', 'được chủ giải thêm vào', 10.00, 113.00, 8),
(10, 16, 26, 'guest_4770', 'cho_xac_nhan', NULL, '2025-07-08 07:24:45', 'được chủ giải thêm vào', 11.00, 82.30, 11),
(11, 16, 27, 'guest_6973', 'cho_xac_nhan', NULL, '2025-07-08 07:24:52', 'được chủ giải thêm vào', 13.00, 90.00, 14),
(12, 16, 28, 'guest_4779', 'cho_xac_nhan', NULL, '2025-07-08 07:25:03', 'được chủ giải thêm vào', 6.00, 103.55, 3),
(13, 16, 29, 'guest_842191', 'cho_xac_nhan', NULL, '2025-07-08 07:25:57', 'được chủ giải thêm vào', 9.00, 104.00, 5),
(14, 16, 30, 'guest_112921', 'cho_xac_nhan', NULL, '2025-07-08 07:26:07', 'được chủ giải thêm vào', 12.00, 136.00, 13),
(15, 16, 31, 'nickname_713873', 'cho_xac_nhan', NULL, '2025-07-08 07:26:42', 'được chủ giải thêm vào', 6.00, 128.00, 2),
(16, 16, 32, 'nickname_272808', 'cho_xac_nhan', NULL, '2025-07-08 07:26:54', 'được chủ giải thêm vào', 10.00, 103.00, 9),
(17, 16, 33, 'nickname_965746', 'cho_xac_nhan', NULL, '2025-07-08 07:29:06', 'được chủ giải thêm vào', 9.00, 87.00, 7),
(18, 16, 34, 'nickname_922320', 'cho_xac_nhan', NULL, '2025-07-08 07:29:15', 'được chủ giải thêm vào', 14.00, 42.65, 15),
(19, 16, 35, 'nickname_258675', 'cho_xac_nhan', NULL, '2025-07-08 07:29:28', 'được chủ giải thêm vào', 15.00, 80.30, 16),
(20, 16, 36, 'nickname_942731', 'cho_xac_nhan', NULL, '2025-07-08 07:29:40', 'được chủ giải thêm vào', 9.00, 105.23, 4),
(21, 16, 37, 'nickname_214320', 'cho_xac_nhan', NULL, '2025-07-08 07:29:50', 'được chủ giải thêm vào', 11.00, 96.00, 10),
(25, 14, 19, 'guest_9057', 'cho_xac_nhan', NULL, '2025-07-08 08:52:24', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(26, 14, 24, 'Khach_29691', 'cho_xac_nhan', NULL, '2025-07-08 08:52:41', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(27, 14, 33, 'nickname_965746', 'cho_xac_nhan', NULL, '2025-07-08 08:52:56', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(28, 17, 1, 'Chim Sẽ Già', 'cho_xac_nhan', NULL, '2025-07-08 11:28:58', 'được chủ giải thêm vào', 0.00, 0.00, 1),
(29, 16, 1, 'Chim Sẽ Già', 'cho_xac_nhan', NULL, '2025-07-08 17:01:08', 'được chủ giải thêm vào', 5.00, 210.00, 1),
(30, 17, 19, 'guest_9057', 'cho_xac_nhan', NULL, '2025-07-09 14:43:31', 'được chủ giải thêm vào', 0.00, 0.00, 2),
(31, 17, 41, 'nickname_565888', 'cho_xac_nhan', NULL, '2025-07-09 14:43:46', 'được chủ giải thêm vào', 0.00, 0.00, 3),
(32, 17, 24, 'Khach_29691', 'cho_xac_nhan', NULL, '2025-07-09 16:46:17', 'được chủ giải thêm vào', 0.00, 0.00, 4),
(34, 20, 24, 'Khach_29691', 'cho_xac_nhan', NULL, '2025-07-10 09:13:15', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(35, 20, 1, 'Chim Sẽ Già', 'cho_xac_nhan', NULL, '2025-07-10 09:14:31', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(36, 20, 42, 'nickname_797535', 'cho_xac_nhan', NULL, '2025-07-10 09:33:33', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(37, 20, 43, 'nickname_361014', 'cho_xac_nhan', NULL, '2025-07-10 09:53:55', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(38, 20, 44, 'nickname_870102', 'cho_xac_nhan', NULL, '2025-07-10 09:58:14', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(39, 20, 45, 'nickname_741150', 'cho_xac_nhan', NULL, '2025-07-10 10:00:13', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(40, 20, 46, 'nickname_530236', 'cho_xac_nhan', NULL, '2025-07-10 10:00:50', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(41, 20, 47, 'nickname_589614', 'cho_xac_nhan', NULL, '2025-07-10 10:00:57', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(42, 20, 48, 'nickname_145652', 'cho_xac_nhan', NULL, '2025-07-10 10:01:05', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(43, 20, 49, 'nickname_818740', 'cho_xac_nhan', NULL, '2025-07-10 10:01:12', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(44, 20, 50, 'nickname_285632', 'cho_xac_nhan', NULL, '2025-07-10 10:01:19', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(45, 20, 51, 'nickname_335647', 'cho_xac_nhan', NULL, '2025-07-10 10:01:27', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(46, 20, 52, 'nickname_854088', 'cho_xac_nhan', NULL, '2025-07-10 10:01:36', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(47, 20, 53, 'nickname_331951', 'cho_xac_nhan', NULL, '2025-07-10 10:01:46', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(48, 20, 54, 'nickname_494537', 'cho_xac_nhan', NULL, '2025-07-10 10:01:52', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(49, 20, 55, 'nickname_651649', 'cho_xac_nhan', NULL, '2025-07-10 10:01:58', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(50, 20, 56, 'nickname_824025', 'cho_xac_nhan', NULL, '2025-07-10 10:02:04', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(51, 20, 57, 'nickname_689626', 'cho_xac_nhan', NULL, '2025-07-10 10:02:15', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(52, 20, 58, 'nickname_580294', 'cho_xac_nhan', NULL, '2025-07-10 10:02:23', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(53, 12, 59, 'nickname_338328', 'cho_xac_nhan', NULL, '2025-07-12 10:16:35', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(54, 12, 60, 'nickname_541175', 'cho_xac_nhan', NULL, '2025-07-12 10:16:48', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(55, 12, 61, 'nickname_221916', 'cho_xac_nhan', NULL, '2025-07-12 10:16:59', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(58, 10, 62, 'nickname_518474', 'cho_xac_nhan', NULL, '2025-07-12 10:59:51', 'được chủ giải thêm vào', 9.00, 18.00, 4),
(59, 10, 63, 'nickname_373589', 'cho_xac_nhan', NULL, '2025-07-12 10:59:56', 'được chủ giải thêm vào', 5.00, 54.00, 1),
(60, 10, 44, 'nickname_870102', 'cho_xac_nhan', NULL, '2025-07-12 11:00:01', 'được chủ giải thêm vào', 15.00, 11.00, 16),
(61, 10, 59, 'nickname_338328', 'cho_xac_nhan', NULL, '2025-07-12 11:00:10', 'được chủ giải thêm vào', 10.00, 13.00, 9),
(62, 10, 64, 'nickname_487742', 'cho_xac_nhan', NULL, '2025-07-12 11:00:22', 'được chủ giải thêm vào', 10.00, 38.00, 6),
(63, 10, 60, 'nickname_541175', 'cho_xac_nhan', NULL, '2025-07-12 11:00:31', 'được chủ giải thêm vào', 9.00, 19.22, 3),
(64, 10, 61, 'nickname_221916', 'cho_xac_nhan', NULL, '2025-07-12 11:00:39', 'được chủ giải thêm vào', 10.00, 19.00, 7),
(65, 10, 65, 'nickname_878636', 'cho_xac_nhan', NULL, '2025-07-12 11:00:46', 'được chủ giải thêm vào', 9.00, 16.00, 5),
(66, 10, 66, 'nickname_401478', 'cho_xac_nhan', NULL, '2025-07-12 11:00:52', 'được chủ giải thêm vào', 16.00, 12.00, 17),
(67, 10, 67, 'nickname_169129', 'cho_xac_nhan', NULL, '2025-07-12 11:00:58', 'được chủ giải thêm vào', 10.00, 17.00, 8),
(68, 10, 68, 'nickname_233998', 'cho_xac_nhan', NULL, '2025-07-12 11:01:03', 'được chủ giải thêm vào', 12.00, 12.00, 13),
(69, 10, 69, 'nickname_498849', 'cho_xac_nhan', NULL, '2025-07-12 11:01:11', 'được chủ giải thêm vào', 14.00, 16.00, 15),
(70, 10, 70, 'nickname_598964', 'cho_xac_nhan', NULL, '2025-07-12 11:01:17', 'được chủ giải thêm vào', 11.00, 21.00, 10),
(71, 10, 71, 'nickname_102507', 'cho_xac_nhan', NULL, '2025-07-12 11:01:23', 'được chủ giải thêm vào', 11.00, 17.00, 11),
(72, 10, 72, 'nickname_441653', 'cho_xac_nhan', NULL, '2025-07-12 11:01:31', 'được chủ giải thêm vào', 11.00, 11.00, 12),
(73, 10, 73, 'nickname_531884', 'cho_xac_nhan', NULL, '2025-07-12 11:01:38', 'được chủ giải thêm vào', 12.00, 12.00, 14),
(74, 10, 1, 'Chim Sẽ Già', 'cho_xac_nhan', NULL, '2025-07-12 11:01:42', 'được chủ giải thêm vào', 6.00, 20.00, 2),
(78, 24, 74, 'nickname_117115', 'cho_xac_nhan', NULL, '2025-07-13 07:16:32', 'được chủ giải thêm vào', 12.00, 55.30, 13),
(79, 24, 42, 'nickname_797535', 'cho_xac_nhan', NULL, '2025-07-13 07:17:53', 'được chủ giải thêm vào', 10.00, 98.00, 7),
(80, 24, 75, 'nickname_173290', 'cho_xac_nhan', NULL, '2025-07-13 07:18:02', 'được chủ giải thêm vào', 5.00, 123.20, 2),
(81, 24, 76, 'nickname_983942', 'cho_xac_nhan', NULL, '2025-07-13 07:18:08', 'được chủ giải thêm vào', 12.00, 50.00, 14),
(82, 24, 77, 'nickname_173796', 'cho_xac_nhan', NULL, '2025-07-13 07:18:14', 'được chủ giải thêm vào', 11.00, 71.00, 10),
(83, 24, 78, 'nickname_667204', 'cho_xac_nhan', NULL, '2025-07-13 07:18:19', 'được chủ giải thêm vào', 12.00, 81.11, 12),
(84, 24, 79, 'nickname_338606', 'cho_xac_nhan', NULL, '2025-07-13 07:18:25', 'được chủ giải thêm vào', 9.00, 89.44, 6),
(85, 24, 80, 'nickname_131513', 'cho_xac_nhan', NULL, '2025-07-13 07:18:31', 'được chủ giải thêm vào', 7.00, 110.00, 4),
(86, 24, 81, 'nickname_358403', 'cho_xac_nhan', NULL, '2025-07-13 07:18:37', 'được chủ giải thêm vào', 10.00, 69.00, 9),
(87, 24, 82, 'nickname_784051', 'cho_xac_nhan', NULL, '2025-07-13 07:18:43', 'được chủ giải thêm vào', 13.00, 69.00, 15),
(88, 24, 83, 'nickname_724435', 'cho_xac_nhan', NULL, '2025-07-13 07:18:48', 'được chủ giải thêm vào', 14.00, 68.00, 16),
(89, 24, 84, 'nickname_725717', 'cho_xac_nhan', NULL, '2025-07-13 07:18:59', 'được chủ giải thêm vào', 3.00, 108.00, 1),
(90, 24, 85, 'nickname_963789', 'cho_xac_nhan', NULL, '2025-07-13 07:19:21', 'được chủ giải thêm vào', 9.00, 101.00, 5),
(91, 24, 86, 'nickname_217520', 'cho_xac_nhan', NULL, '2025-07-13 07:19:32', 'được chủ giải thêm vào', 16.00, 68.00, 17),
(92, 24, 87, 'nickname_329120', 'cho_xac_nhan', NULL, '2025-07-13 07:31:03', 'được chủ giải thêm vào', 6.00, 100.10, 3),
(93, 24, 88, 'nickname_804701', 'cho_xac_nhan', NULL, '2025-07-13 07:34:00', 'được chủ giải thêm vào', 12.00, 89.00, 11),
(94, 24, 89, 'nickname_843581', 'cho_xac_nhan', NULL, '2025-07-13 07:45:22', 'được chủ giải thêm vào', 10.00, 76.00, 8),
(95, 23, 1, 'Chim Sẽ Già', 'cho_xac_nhan', NULL, '2025-07-13 07:46:57', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(96, 23, 59, 'nickname_338328', 'cho_xac_nhan', NULL, '2025-07-13 07:47:06', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(97, 23, 90, 'nickname_281327', 'cho_xac_nhan', NULL, '2025-07-13 07:47:21', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(98, 23, 91, 'nickname_552350', 'cho_xac_nhan', NULL, '2025-07-13 07:47:27', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(99, 23, 92, 'nickname_980987', 'cho_xac_nhan', NULL, '2025-07-13 07:47:38', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(100, 23, 93, 'nickname_227460', 'cho_xac_nhan', NULL, '2025-07-13 07:47:44', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(101, 23, 94, 'nickname_381819', 'cho_xac_nhan', NULL, '2025-07-13 07:47:50', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(102, 23, 95, 'nickname_437433', 'cho_xac_nhan', NULL, '2025-07-13 07:47:59', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(103, 23, 96, 'nickname_484535', 'cho_xac_nhan', NULL, '2025-07-13 07:48:08', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(104, 23, 97, 'nickname_121039', 'cho_xac_nhan', NULL, '2025-07-13 07:48:15', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(105, 25, 97, 'nickname_121039', 'cho_xac_nhan', NULL, '2025-07-13 07:50:14', 'được chủ giải thêm vào', 6.00, 0.00, 1),
(106, 25, 1, 'Chim Sẽ Già', 'cho_xac_nhan', NULL, '2025-07-13 07:50:19', 'được chủ giải thêm vào', 6.00, 0.00, 2),
(107, 25, 98, 'nickname_824025', 'cho_xac_nhan', NULL, '2025-07-13 07:50:44', 'được chủ giải thêm vào', 6.00, 0.00, 3),
(108, 25, 99, 'nickname_574752', 'cho_xac_nhan', NULL, '2025-07-13 07:50:49', 'được chủ giải thêm vào', 7.00, 12.00, 12),
(109, 25, 100, 'nickname_722577', 'cho_xac_nhan', NULL, '2025-07-13 07:50:53', 'được chủ giải thêm vào', 12.00, 0.00, 14),
(110, 25, 101, 'nickname_363844', 'cho_xac_nhan', NULL, '2025-07-13 07:50:59', 'được chủ giải thêm vào', 18.00, 0.00, 25),
(111, 25, 102, 'nickname_600099', 'cho_xac_nhan', NULL, '2025-07-13 07:51:04', 'được chủ giải thêm vào', 6.00, 0.00, 4),
(112, 25, 103, 'nickname_925721', 'cho_xac_nhan', NULL, '2025-07-13 07:51:10', 'được chủ giải thêm vào', 6.00, 0.00, 5),
(113, 25, 104, 'nickname_952798', 'cho_xac_nhan', NULL, '2025-07-13 07:51:14', 'được chủ giải thêm vào', 12.00, 0.00, 15),
(114, 25, 105, 'nickname_867163', 'cho_xac_nhan', NULL, '2025-07-13 07:51:20', 'được chủ giải thêm vào', 11.00, 25.00, 13),
(115, 25, 106, 'nickname_726978', 'cho_xac_nhan', NULL, '2025-07-13 07:51:25', 'được chủ giải thêm vào', 6.00, 0.00, 6),
(116, 25, 107, 'nickname_812351', 'cho_xac_nhan', NULL, '2025-07-13 07:51:32', 'được chủ giải thêm vào', 6.00, 0.00, 7),
(117, 25, 108, 'nickname_195071', 'cho_xac_nhan', NULL, '2025-07-13 07:51:39', 'được chủ giải thêm vào', 12.00, 0.00, 16),
(118, 25, 109, 'nickname_307406', 'cho_xac_nhan', NULL, '2025-07-13 07:51:46', 'được chủ giải thêm vào', 6.00, 0.00, 8),
(119, 25, 110, 'nickname_216344', 'cho_xac_nhan', NULL, '2025-07-13 07:51:51', 'được chủ giải thêm vào', 12.00, 0.00, 17),
(120, 25, 111, 'nickname_376847', 'cho_xac_nhan', NULL, '2025-07-13 07:51:56', 'được chủ giải thêm vào', 18.00, 0.00, 26),
(121, 25, 112, 'nickname_702508', 'cho_xac_nhan', NULL, '2025-07-13 07:52:02', 'được chủ giải thêm vào', 6.00, 0.00, 9),
(122, 25, 113, 'nickname_727708', 'cho_xac_nhan', NULL, '2025-07-13 07:52:07', 'được chủ giải thêm vào', 12.00, 0.00, 18),
(123, 25, 114, 'nickname_954316', 'cho_xac_nhan', NULL, '2025-07-13 07:52:13', 'được chủ giải thêm vào', 12.00, 0.00, 19),
(124, 25, 115, 'nickname_420521', 'cho_xac_nhan', NULL, '2025-07-13 07:52:19', 'được chủ giải thêm vào', 18.00, 0.00, 27),
(125, 25, 116, 'nickname_199339', 'cho_xac_nhan', NULL, '2025-07-14 12:05:19', 'được chủ giải thêm vào', 18.00, 0.00, 28),
(126, 25, 117, 'nickname_349653', 'cho_xac_nhan', NULL, '2025-07-14 12:05:25', 'được chủ giải thêm vào', 18.00, 0.00, 29),
(127, 25, 118, 'nickname_911301', 'cho_xac_nhan', NULL, '2025-07-14 12:05:35', 'được chủ giải thêm vào', 18.00, 0.00, 30),
(128, 25, 119, 'nickname_342470', 'cho_xac_nhan', NULL, '2025-07-14 12:05:54', 'được chủ giải thêm vào', 24.00, 0.00, 37),
(129, 25, 120, 'nickname_325790', 'cho_xac_nhan', NULL, '2025-07-14 12:05:59', 'được chủ giải thêm vào', 18.00, 0.00, 31),
(130, 25, 121, 'nickname_234997', 'cho_xac_nhan', NULL, '2025-07-16 05:34:29', 'được chủ giải thêm vào', 12.00, 0.00, 20),
(131, 25, 122, 'nickname_443647', 'cho_xac_nhan', NULL, '2025-07-16 05:34:35', 'được chủ giải thêm vào', 6.00, 0.00, 10),
(132, 25, 123, 'nickname_942249', 'cho_xac_nhan', NULL, '2025-07-16 05:34:46', 'được chủ giải thêm vào', 18.00, 0.00, 32),
(133, 25, 124, 'nickname_755523', 'cho_xac_nhan', NULL, '2025-07-16 05:34:53', 'được chủ giải thêm vào', 12.00, 0.00, 21),
(134, 25, 125, 'nickname_760091', 'cho_xac_nhan', NULL, '2025-07-16 05:35:00', 'được chủ giải thêm vào', 12.00, 0.00, 22),
(135, 25, 126, 'nickname_574377', 'cho_xac_nhan', NULL, '2025-07-16 05:35:05', 'được chủ giải thêm vào', 24.00, 0.00, 38),
(136, 25, 127, 'nickname_522588', 'cho_xac_nhan', NULL, '2025-07-16 05:35:10', 'được chủ giải thêm vào', 24.00, 0.00, 39),
(137, 25, 128, 'nickname_960184', 'cho_xac_nhan', NULL, '2025-07-16 05:35:15', 'được chủ giải thêm vào', 18.00, 0.00, 33),
(138, 25, 129, 'nickname_182280', 'cho_xac_nhan', NULL, '2025-07-16 05:35:20', 'được chủ giải thêm vào', 18.00, 0.00, 34),
(139, 25, 130, 'nickname_386101', 'cho_xac_nhan', NULL, '2025-07-16 05:35:26', 'được chủ giải thêm vào', 12.00, 0.00, 23),
(140, 25, 131, 'nickname_833612', 'cho_xac_nhan', NULL, '2025-07-16 05:35:34', 'được chủ giải thêm vào', 24.00, 0.00, 40),
(141, 25, 132, 'nickname_441984', 'cho_xac_nhan', NULL, '2025-07-16 05:35:40', 'được chủ giải thêm vào', 18.00, 0.00, 35),
(142, 25, 133, 'nickname_660785', 'cho_xac_nhan', NULL, '2025-07-16 05:35:45', 'được chủ giải thêm vào', 6.00, 0.00, 11),
(143, 25, 134, 'nickname_970191', 'cho_xac_nhan', NULL, '2025-07-16 05:35:48', 'được chủ giải thêm vào', 24.00, 0.00, 41),
(144, 25, 135, 'nickname_790516', 'cho_xac_nhan', NULL, '2025-07-16 05:35:57', 'được chủ giải thêm vào', 12.00, 0.00, 24),
(145, 25, 136, 'nickname_525963', 'cho_xac_nhan', NULL, '2025-07-16 05:36:17', 'được chủ giải thêm vào', 30.00, 0.00, 49),
(146, 25, 137, 'nickname_303512', 'cho_xac_nhan', NULL, '2025-07-16 05:36:23', 'được chủ giải thêm vào', 24.00, 0.00, 42),
(147, 25, 138, 'nickname_233463', 'cho_xac_nhan', NULL, '2025-07-16 05:36:38', 'được chủ giải thêm vào', 24.00, 0.00, 43),
(148, 25, 139, 'nickname_891722', 'cho_xac_nhan', NULL, '2025-07-16 05:36:46', 'được chủ giải thêm vào', 24.00, 0.00, 44),
(149, 25, 140, 'nickname_151667', 'cho_xac_nhan', NULL, '2025-07-16 05:36:50', 'được chủ giải thêm vào', 24.00, 0.00, 45),
(150, 25, 141, 'nickname_990389', 'cho_xac_nhan', NULL, '2025-07-16 05:36:55', 'được chủ giải thêm vào', 24.00, 0.00, 46),
(151, 25, 142, 'nickname_290305', 'cho_xac_nhan', NULL, '2025-07-16 05:37:00', 'được chủ giải thêm vào', 24.00, 0.00, 47),
(152, 25, 143, 'nickname_499969', 'cho_xac_nhan', NULL, '2025-07-16 05:37:05', 'được chủ giải thêm vào', 18.00, 0.00, 36),
(153, 25, 144, 'nickname_735038', 'cho_xac_nhan', NULL, '2025-07-16 05:37:10', 'được chủ giải thêm vào', 24.00, 0.00, 48),
(154, 29, 145, 'nickname_695551', 'cho_xac_nhan', NULL, '2025-07-17 07:29:53', 'được chủ giải thêm vào', 4.00, 0.00, 1),
(155, 29, 146, 'nickname_142691', 'cho_xac_nhan', NULL, '2025-07-17 07:30:15', 'được chủ giải thêm vào', 4.00, 0.00, 2),
(156, 29, 147, 'nickname_698804', 'cho_xac_nhan', NULL, '2025-07-17 07:30:43', 'được chủ giải thêm vào', 8.00, 0.00, 5),
(157, 29, 17, 'Hồ Câu Hoàng Hải', 'cho_xac_nhan', NULL, '2025-07-18 01:52:33', 'được chủ giải thêm vào', 4.00, 0.00, 3),
(158, 29, 148, 'nickname_279433', 'cho_xac_nhan', NULL, '2025-07-18 01:52:58', 'được chủ giải thêm vào', 12.00, 0.00, 9),
(159, 29, 149, 'nickname_600412', 'cho_xac_nhan', NULL, '2025-07-18 01:53:20', 'được chủ giải thêm vào', 8.00, 0.00, 6),
(160, 29, 150, 'nickname_382053', 'cho_xac_nhan', NULL, '2025-07-18 01:53:25', 'được chủ giải thêm vào', 16.00, 0.00, 13),
(161, 29, 151, 'nickname_233190', 'cho_xac_nhan', NULL, '2025-07-18 01:53:31', 'được chủ giải thêm vào', 8.00, 0.00, 7),
(162, 29, 152, 'nickname_235432', 'cho_xac_nhan', NULL, '2025-07-18 01:53:53', 'được chủ giải thêm vào', 4.00, 0.00, 4),
(163, 29, 153, 'nickname_583850', 'cho_xac_nhan', NULL, '2025-07-18 01:54:21', 'được chủ giải thêm vào', 8.00, 0.00, 8),
(164, 29, 154, 'nickname_618864', 'cho_xac_nhan', NULL, '2025-07-18 01:54:31', 'được chủ giải thêm vào', 12.00, 0.00, 10),
(165, 29, 155, 'nickname_664168', 'cho_xac_nhan', NULL, '2025-07-18 01:54:36', 'được chủ giải thêm vào', 16.00, 0.00, 14),
(166, 29, 156, 'nickname_896248', 'cho_xac_nhan', NULL, '2025-07-18 01:54:41', 'được chủ giải thêm vào', 20.00, 0.00, 17),
(167, 29, 157, 'nickname_491880', 'cho_xac_nhan', NULL, '2025-07-18 01:54:46', 'được chủ giải thêm vào', 12.00, 0.00, 11),
(168, 29, 158, 'nickname_741818', 'cho_xac_nhan', NULL, '2025-07-18 01:54:51', 'được chủ giải thêm vào', 12.00, 0.00, 12),
(169, 29, 159, 'nickname_929429', 'cho_xac_nhan', NULL, '2025-07-18 01:54:58', 'được chủ giải thêm vào', 16.00, 0.00, 15),
(170, 29, 160, 'nickname_649536', 'cho_xac_nhan', NULL, '2025-07-18 01:55:04', 'được chủ giải thêm vào', 16.00, 0.00, 16),
(171, 36, 17, 'Hồ Câu Hoàng Hải', 'cho_xac_nhan', NULL, '2025-07-21 06:17:42', 'được chủ giải thêm vào', 6.00, 14.00, 2),
(172, 36, 161, 'nickname_648506', 'cho_xac_nhan', NULL, '2025-07-21 06:17:47', 'được chủ giải thêm vào', 8.00, 12.00, 9),
(173, 36, 162, 'nickname_198675', 'cho_xac_nhan', NULL, '2025-07-21 06:17:52', 'được chủ giải thêm vào', 11.00, 9.00, 12),
(174, 36, 163, 'nickname_171835', 'cho_xac_nhan', NULL, '2025-07-21 06:17:57', 'được chủ giải thêm vào', 6.00, 18.00, 1),
(175, 36, 164, 'nickname_264447', 'cho_xac_nhan', NULL, '2025-07-21 06:18:02', 'được chủ giải thêm vào', 7.00, 15.00, 5),
(176, 36, 165, 'nickname_464658', 'cho_xac_nhan', NULL, '2025-07-21 06:18:06', 'được chủ giải thêm vào', 6.00, 14.00, 3),
(177, 36, 166, 'nickname_788425', 'cho_xac_nhan', NULL, '2025-07-21 06:18:17', 'được chủ giải thêm vào', 7.00, 11.00, 7),
(178, 36, 167, 'nickname_953455', 'cho_xac_nhan', NULL, '2025-07-21 06:18:30', 'được chủ giải thêm vào', 7.00, 15.00, 6),
(179, 36, 168, 'nickname_894344', 'cho_xac_nhan', NULL, '2025-07-21 06:18:44', 'được chủ giải thêm vào', 10.00, 6.00, 11),
(180, 36, 169, 'nickname_162290', 'cho_xac_nhan', NULL, '2025-07-21 06:18:49', 'được chủ giải thêm vào', 7.00, 11.00, 8),
(181, 36, 170, 'nickname_997796', 'cho_xac_nhan', NULL, '2025-07-21 06:18:54', 'được chủ giải thêm vào', 7.00, 78.00, 4),
(182, 36, 171, 'nickname_951694', 'cho_xac_nhan', NULL, '2025-07-21 06:19:00', 'được chủ giải thêm vào', 10.00, 11.00, 10),
(183, 36, 172, 'nickname_159255', 'cho_xac_nhan', NULL, '2025-07-21 06:19:06', 'được chủ giải thêm vào', 13.00, 10.01, 13),
(184, 38, 144, 'nickname_735038', 'cho_xac_nhan', NULL, '2025-07-24 05:53:23', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(185, 38, 10, 'Đài Sư Tập Sự', 'cho_xac_nhan', NULL, '2025-07-24 05:53:56', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(188, 41, 1, NULL, 'da_thanh_toan', '2025-08-20 16:03:25', '2025-08-20 09:03:25', 'tự tham gia online', 2.00, 12.00, 1),
(189, 41, 42, 'nickname_797535', 'cho_xac_nhan', NULL, '2025-08-20 13:11:12', 'được chủ giải thêm vào', 5.00, 10.00, 5),
(190, 41, 144, 'nickname_735038', 'cho_xac_nhan', NULL, '2025-08-20 13:17:34', 'được chủ giải thêm vào', 7.00, 7.00, 14),
(191, 41, 43, 'nickname_361014', 'cho_xac_nhan', NULL, '2025-08-20 13:17:56', 'được chủ giải thêm vào', 7.00, 5.00, 15),
(192, 41, 59, 'nickname_338328', 'cho_xac_nhan', NULL, '2025-08-20 13:18:17', 'được chủ giải thêm vào', 4.00, 10.00, 4),
(194, 41, 173, 'nickname_437062', 'cho_xac_nhan', NULL, '2025-08-20 14:04:49', 'được chủ giải thêm vào', 5.00, 9.40, 6),
(195, 41, 61, 'nickname_221916', 'cho_xac_nhan', NULL, '2025-08-20 14:05:05', 'được chủ giải thêm vào', 6.00, 8.00, 11),
(196, 41, 65, 'nickname_878636', 'cho_xac_nhan', NULL, '2025-08-20 14:05:14', 'được chủ giải thêm vào', 6.00, 9.00, 10),
(197, 41, 66, 'nickname_401478', 'cho_xac_nhan', NULL, '2025-08-20 14:05:19', 'được chủ giải thêm vào', 6.00, 8.00, 12),
(198, 41, 67, 'nickname_169129', 'cho_xac_nhan', NULL, '2025-08-20 14:05:25', 'được chủ giải thêm vào', 7.00, 5.00, 16),
(199, 41, 68, 'nickname_233998', 'cho_xac_nhan', NULL, '2025-08-20 14:05:41', 'được chủ giải thêm vào', 3.00, 11.50, 2),
(200, 41, 69, 'nickname_498849', 'cho_xac_nhan', NULL, '2025-08-20 14:05:47', 'được chủ giải thêm vào', 5.00, 5.00, 8),
(201, 41, 70, 'nickname_598964', 'cho_xac_nhan', NULL, '2025-08-20 14:05:52', 'được chủ giải thêm vào', 3.00, 7.00, 3),
(202, 41, 71, 'nickname_102507', 'cho_xac_nhan', NULL, '2025-08-20 14:05:57', 'được chủ giải thêm vào', 7.00, 3.00, 17),
(203, 41, 72, 'nickname_441653', 'cho_xac_nhan', NULL, '2025-08-20 14:06:01', 'được chủ giải thêm vào', 6.00, 10.00, 9),
(205, 41, 73, 'nickname_531884', 'cho_xac_nhan', NULL, '2025-08-20 14:45:38', 'được chủ giải thêm vào', 5.00, 8.00, 7),
(209, 42, 1, NULL, 'da_thanh_toan', '2025-08-20 22:36:49', '2025-08-20 15:36:49', 'tự tham gia online', 0.00, 0.00, 0),
(215, 42, 18, 'Lưu Chí Cường', 'da_thanh_toan', NULL, '2025-08-21 10:42:17', 'được chủ giải thêm vào | Chấp nhận và đã thanh toán', 0.00, 0.00, 0),
(220, 43, 1, 'Chim Sẽ Già', 'da_thanh_toan', NULL, '2025-08-21 11:16:41', 'được chủ giải thêm vào | Chấp nhận', 0.00, 0.00, 0),
(221, 43, 175, 'nickname_819212', 'moi_cho_phan_hoi', NULL, '2025-08-21 11:24:22', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(222, 43, 18, 'Lưu Chí Cường', 'da_thanh_toan', NULL, '2025-08-21 11:25:40', 'được chủ giải thêm vào | Chấp nhận', 0.00, 0.00, 0),
(230, 44, 1, NULL, 'da_thanh_toan', '2025-08-21 22:12:51', '2025-08-21 15:12:51', 'Tham gia online', 0.00, 0.00, 0),
(232, 44, 18, 'Lưu Chí Cường', 'da_thanh_toan', NULL, '2025-08-21 15:17:43', 'được chủ giải thêm vào và tham gia', 0.00, 0.00, 0),
(233, 44, 2, 'Hồ Câu Bảo Ngân', 'moi_cho_phan_hoi', NULL, '2025-08-26 15:40:39', 'được chủ giải thêm vào', 0.00, 0.00, 0),
(234, 44, 145, 'nickname_695551', 'moi_cho_phan_hoi', NULL, '2025-08-26 15:40:48', 'được chủ giải thêm vào', 0.00, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gia_ca_thit_phut`
--

CREATE TABLE `gia_ca_thit_phut` (
  `id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `ten_bang_gia` enum('Cơ Bản','Trung Cấp','Đài Sư') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Cơ Bản',
  `base_duration` int NOT NULL DEFAULT '240',
  `base_price` int NOT NULL DEFAULT '240000',
  `extra_unit_price` int DEFAULT '30000',
  `discount_2x_duration` int DEFAULT '60000',
  `discount_3x_duration` int DEFAULT '120000',
  `discount_4x_duration` int DEFAULT '180000',
  `gia_ban_ca` int DEFAULT '0',
  `gia_thu_lai` int DEFAULT '25000',
  `loai_thu` enum('kg','con') DEFAULT 'kg',
  `status` enum('open','closed') DEFAULT 'open',
  `ghi_chu` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gia_ca_thit_phut`
--

INSERT INTO `gia_ca_thit_phut` (`id`, `ho_cau_id`, `ten_bang_gia`, `base_duration`, `base_price`, `extra_unit_price`, `discount_2x_duration`, `discount_3x_duration`, `discount_4x_duration`, `gia_ban_ca`, `gia_thu_lai`, `loai_thu`, `status`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(49, 34, 'Cơ Bản', 240, 240000, 1000, 20000, 40000, 60000, 55000, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-02 09:41:59', '2025-08-28 09:32:54'),
(50, 34, 'Trung Cấp', 240, 480000, 30000, 120000, 240000, 360000, 0, 50000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-02 09:41:59', '2025-06-27 11:06:31'),
(51, 34, 'Đài Sư', 240, 960000, 60000, 60000, 120000, 180000, 0, 100000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-02 09:41:59', '2025-06-08 11:45:15'),
(52, 35, 'Cơ Bản', 240, 300000, 1250, 60000, 120000, 180000, 30000, 15000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-02 10:07:36', '2025-08-24 09:37:43'),
(53, 35, 'Trung Cấp', 240, 600000, 2500, 60000, 120000, 180000, 50000, 30000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-02 10:07:36', '2025-08-24 09:38:04'),
(54, 35, 'Đài Sư', 240, 900000, 3750, 120000, 240000, 360000, 50000, 45000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-02 10:07:36', '2025-08-24 09:38:25'),
(55, 36, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-02 10:08:52', '2025-08-28 07:40:17'),
(56, 36, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-02 10:08:52', NULL),
(57, 36, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-02 10:08:52', NULL),
(58, 37, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-03 11:23:26', NULL),
(59, 37, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-03 11:23:26', '2025-07-07 15:38:40'),
(60, 37, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-03 11:23:26', NULL),
(67, 45, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-06-27 10:15:14', '2025-08-25 07:54:42'),
(68, 45, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-27 10:15:14', NULL),
(69, 45, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-06-27 10:15:14', NULL),
(79, 46, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-07-07 02:59:24', NULL),
(80, 46, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-07 02:59:24', NULL),
(81, 46, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-07 02:59:24', NULL),
(82, 47, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-07-07 15:51:36', NULL),
(83, 47, 'Trung Cấp', 240, 480000, 60000, 60000, 120000, 180000, 0, 50000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-07-07 15:51:36', '2025-07-08 02:47:58'),
(84, 47, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-07 15:51:36', NULL),
(85, 48, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-07-07 15:54:09', NULL),
(86, 48, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-07 15:54:09', NULL),
(87, 48, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-07 15:54:09', NULL),
(88, 49, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 60000, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-07-17 14:23:12', '2025-08-28 08:40:25'),
(89, 49, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-17 14:23:12', NULL),
(90, 49, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-07-17 14:23:12', NULL),
(91, 50, 'Cơ Bản', 240, 240000, 1200, 20000, 40000, 60000, 60000, 20000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-08-23 14:45:34', '2025-08-28 01:52:33'),
(92, 50, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-23 14:45:34', NULL),
(93, 50, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-23 14:45:34', NULL),
(94, 51, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 60000, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-08-28 05:42:59', '2025-08-28 08:40:32'),
(95, 51, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 05:42:59', NULL),
(96, 51, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 05:42:59', NULL),
(97, 52, 'Cơ Bản', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-08-28 06:07:25', '2025-08-28 07:26:01'),
(98, 52, 'Trung Cấp', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 06:07:25', NULL),
(99, 52, 'Đài Sư', 240, 240000, 30000, 60000, 120000, 180000, 0, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 06:07:25', NULL),
(109, 56, 'Cơ Bản', 240, 240000, 1000, 20000, 40000, 6000, 60000, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-08-28 08:44:44', NULL),
(110, 56, 'Trung Cấp', 240, 240000, 1000, 20000, 40000, 6000, 60000, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 08:44:44', NULL),
(111, 56, 'Đài Sư', 240, 240000, 1000, 20000, 40000, 6000, 60000, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 08:44:44', NULL),
(112, 57, 'Cơ Bản', 240, 240000, 1000, 20000, 40000, 6000, 60000, 25000, 'kg', 'open', 'Tự động tạo khi thêm hồ', '2025-08-28 09:34:13', NULL),
(113, 57, 'Trung Cấp', 240, 240000, 1000, 20000, 40000, 6000, 60000, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 09:34:13', NULL),
(114, 57, 'Đài Sư', 240, 240000, 1000, 20000, 40000, 6000, 60000, 25000, 'kg', 'closed', 'Tự động tạo khi thêm hồ', '2025-08-28 09:34:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ho_cau`
--

CREATE TABLE `ho_cau` (
  `id` int NOT NULL,
  `cum_ho_id` int NOT NULL,
  `loai_ca_id` int DEFAULT NULL,
  `ten_ho` varchar(255) NOT NULL,
  `dien_tich` float DEFAULT '1500',
  `max_chieu_dai_can` float DEFAULT '540',
  `max_truc_theo` float DEFAULT '30',
  `mo_ta` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'mô tả...',
  `cam_moi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'cấm mồi...',
  `status` enum('admin_tam_khoa','chuho_tam_khoa','dong_vinh_vien','dang_hoat_dong','tam_dung') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'dang_hoat_dong',
  `ly_do_dong` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'lý do đóng hồ...',
  `luong_ca` int DEFAULT '1500',
  `so_cho_ngoi` int DEFAULT '30',
  `cho_phep_danh_game` tinyint(1) DEFAULT '1',
  `cho_phep_danh_giai` tinyint(1) DEFAULT '1',
  `gia_game` int DEFAULT '30000',
  `gia_giai` int DEFAULT '20000',
  `cho_phep_xoi` tinyint NOT NULL DEFAULT '1',
  `gia_xoi` int NOT NULL DEFAULT '50000',
  `cho_phep_khoen` tinyint NOT NULL DEFAULT '1',
  `gia_khoen` int NOT NULL DEFAULT '100000',
  `cho_phep_danh_thit` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `cho_phep_heo` tinyint NOT NULL DEFAULT '1',
  `gia_heo` int NOT NULL DEFAULT '0',
  `cho_phep_xe_heo` tinyint NOT NULL DEFAULT '1',
  `gia_xe_heo` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ho_cau`
--

INSERT INTO `ho_cau` (`id`, `cum_ho_id`, `loai_ca_id`, `ten_ho`, `dien_tich`, `max_chieu_dai_can`, `max_truc_theo`, `mo_ta`, `cam_moi`, `status`, `ly_do_dong`, `luong_ca`, `so_cho_ngoi`, `cho_phep_danh_game`, `cho_phep_danh_giai`, `gia_game`, `gia_giai`, `cho_phep_xoi`, `gia_xoi`, `cho_phep_khoen`, `gia_khoen`, `cho_phep_danh_thit`, `created_at`, `cho_phep_heo`, `gia_heo`, `cho_phep_xe_heo`, `gia_xe_heo`) VALUES
(34, 1, 3, 'Hồ số 2: Chuyên chép ', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 0, 40, 1, 1, 15000, 20000, 1, 50, 0, 0, 1, '2025-08-28 16:25:04', 1, 500000, 1, 20000),
(35, 2, 2, 'Hồ câu số 1: Chuyên chép', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'tam_dung', 'lý do đóng hồ...', 2000, 50, 1, 1, 20000, 20000, 0, 0, 0, 0, 1, '2025-08-28 15:28:44', 1, 2000000, 1, 30000),
(36, 4, 4, 'Hồ câu Hoàng Hải 1', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'tam_dung', 'lý do đóng hồ...', 1500, 35, 1, 1, 25000, 20000, 0, 0, 0, 0, 1, '2025-08-28 15:37:36', 1, 1, 1, 1),
(37, 5, 1, 'Hồ câu Bình Chánh 2', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 2000, 40, 1, 1, 20000, 20000, 0, 0, 0, 0, 1, '2025-06-03 18:23:26', 1, 1, 1, 1),
(45, 2, 4, 'Hồ câu số 3: Chép Phi Đại', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'tam_dung', 'lý do đóng hồ...', 0, 30, 1, 0, 30000, 20000, 0, 0, 0, 0, 1, '2025-08-28 15:28:31', 1, 1, 1, 1),
(46, 5, 2, 'Hồ câu Bình Chánh 1', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 1500, 30, 1, 1, 20000, 20000, 0, 0, 0, 0, 1, '2025-07-07 09:59:24', 1, 1, 1, 1),
(47, 6, 3, 'Hồ câu Hoàng Hải 1', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 0, 30, 1, 1, 25000, 20000, 0, 0, 0, 0, 1, '2025-07-07 22:51:36', 1, 1, 1, 1),
(48, 6, 2, 'Hồ câu Hoàng Hải 2', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 1500, 30, 1, 1, 20000, 20000, 0, 0, 0, 0, 1, '2025-07-07 22:54:09', 1, 1, 1, 1),
(49, 7, 2, 'Hồ câu số 2: Chuyên phi', 1500, 540, 30, 'mô tả... chuyên cá phi', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 1500, 20, 1, 1, 20000, 20000, 1, 50000, 1, 100000, 1, '2025-08-28 12:41:01', 1, 500000, 1, 20000),
(50, 2, 1, 'Hồ câu Bình hoà 2', 1500, 540, 30, 'mồi tự do', 'cấm mồi...', 'tam_dung', 'lý do đóng hồ...', 1500, 30, 1, 1, 20000, 30000, 1, 50000, 1, 100000, 1, '2025-08-28 15:28:51', 1, 3000000, 1, 25000),
(51, 7, 4, 'Hồ câu số 3: Chép Phi Đại', 1500, 540, 30, 'mô tả... chuyên siêu tạp, phi mâm', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 4500, 30, 1, 1, 20000, 20000, 1, 50000, 1, 100000, 1, '2025-08-28 12:43:40', 1, 0, 1, 0),
(52, 4, 1, 'Hồ câu Hoàng Hải 2', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'tam_dung', 'lý do đóng hồ...', 1500, 30, 1, 1, 30000, 35000, 1, 50000, 1, 100000, 1, '2025-08-28 15:26:58', 1, 0, 1, 0),
(56, 7, 3, 'Hồ câu số 1: Chuyên chép', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 1500, 30, 1, 1, 20000, 25000, 1, 50000, 1, 100000, 1, '2025-08-28 15:44:44', 1, 0, 1, 0),
(57, 1, 2, 'Bảo ngân 2', 1500, 540, 30, 'mô tả...', 'cấm mồi...', 'dang_hoat_dong', 'lý do đóng hồ...', 1500, 30, 1, 1, 20000, 25000, 1, 50000, 1, 100000, 1, '2025-08-28 16:34:13', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ho_cau_loai_ca`
--

CREATE TABLE `ho_cau_loai_ca` (
  `id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `loai_ca_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lich_hoat_dong_ho_cau`
--

CREATE TABLE `lich_hoat_dong_ho_cau` (
  `id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `thu` varchar(5) DEFAULT NULL,
  `gio_mo` time NOT NULL,
  `gio_dong` time NOT NULL,
  `trang_thai` enum('mo','nghi') NOT NULL DEFAULT 'mo',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lich_hoat_dong_ho_cau`
--

INSERT INTO `lich_hoat_dong_ho_cau` (`id`, `ho_cau_id`, `thu`, `gio_mo`, `gio_dong`, `trang_thai`, `created_at`) VALUES
(624, 46, '2', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(625, 46, '3', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(626, 46, '4', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(627, 46, '5', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(628, 46, '6', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(629, 46, '7', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(630, 46, 'CN', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:08'),
(631, 37, '2', '06:00:00', '18:00:00', 'nghi', '2025-07-07 22:51:22'),
(632, 37, '3', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:22'),
(633, 37, '4', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:22'),
(634, 37, '5', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:22'),
(635, 37, '6', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:22'),
(636, 37, '7', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:22'),
(637, 37, 'CN', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:51:22'),
(645, 48, '2', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(646, 48, '3', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(647, 48, '4', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(648, 48, '5', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(649, 48, '6', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(650, 48, '7', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(651, 48, 'CN', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:09'),
(652, 47, '2', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(653, 47, '3', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(654, 47, '4', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(655, 47, '5', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(656, 47, '6', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(657, 47, '7', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(658, 47, 'CN', '06:00:00', '18:00:00', 'mo', '2025-07-07 22:54:23'),
(925, 49, '2', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(926, 49, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(927, 49, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(928, 49, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(929, 49, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(930, 49, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(931, 49, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:41:01'),
(946, 51, '2', '06:00:00', '18:00:00', 'nghi', '2025-08-28 12:43:40'),
(947, 51, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:43:40'),
(948, 51, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:43:40'),
(949, 51, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:43:40'),
(950, 51, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:43:40'),
(951, 51, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:43:40'),
(952, 51, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 12:43:40'),
(1107, 52, '2', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1108, 52, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1109, 52, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1110, 52, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1111, 52, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1112, 52, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1113, 52, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:26:58'),
(1121, 45, '2', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1122, 45, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1123, 45, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1124, 45, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1125, 45, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1126, 45, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1127, 45, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:31'),
(1128, 35, '2', '06:00:00', '18:00:00', 'nghi', '2025-08-28 15:28:44'),
(1129, 35, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:44'),
(1130, 35, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:44'),
(1131, 35, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:44'),
(1132, 35, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:44'),
(1133, 35, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:44'),
(1134, 35, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:44'),
(1135, 50, '2', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1136, 50, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1137, 50, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1138, 50, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1139, 50, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1140, 50, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1141, 50, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:28:51'),
(1149, 36, '2', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:37:36'),
(1150, 36, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:37:36'),
(1151, 36, '4', '06:00:00', '18:00:00', 'nghi', '2025-08-28 15:37:36'),
(1152, 36, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:37:36'),
(1153, 36, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:37:36'),
(1154, 36, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:37:37'),
(1155, 36, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:37:37'),
(1156, 56, '2', '06:00:00', '18:00:00', 'nghi', '2025-08-28 15:44:44'),
(1157, 56, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:44:44'),
(1158, 56, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:44:44'),
(1159, 56, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:44:44'),
(1160, 56, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:44:44'),
(1161, 56, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:44:44'),
(1162, 56, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 15:44:44'),
(1170, 34, '2', '06:00:00', '18:00:00', 'nghi', '2025-08-28 16:25:04'),
(1171, 34, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:25:04'),
(1172, 34, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:25:04'),
(1173, 34, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:25:04'),
(1174, 34, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:25:04'),
(1175, 34, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:25:04'),
(1176, 34, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:25:04'),
(1177, 57, '2', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13'),
(1178, 57, '3', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13'),
(1179, 57, '4', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13'),
(1180, 57, '5', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13'),
(1181, 57, '6', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13'),
(1182, 57, '7', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13'),
(1183, 57, 'CN', '06:00:00', '18:00:00', 'mo', '2025-08-28 16:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `loai_ca`
--

CREATE TABLE `loai_ca` (
  `id` int NOT NULL,
  `ten_ca` varchar(100) NOT NULL,
  `mo_ta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'mô tả...',
  `trang_thai` enum('hoat_dong','an') DEFAULT 'hoat_dong',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `loai_ca`
--

INSERT INTO `loai_ca` (`id`, `ten_ca`, `mo_ta`, `trang_thai`, `created_at`) VALUES
(1, 'Săn Hàng (cá >5kg)', 'Trọng lượng cá tối thiểu Săn Hàng phải >= 5kg', 'hoat_dong', '2025-05-24 20:06:33'),
(2, 'Chuyên Phi (>=95% phi)', 'Lượng cá phi trong hồ phải đạt >=95%', 'hoat_dong', '2025-05-24 20:06:33'),
(3, 'Chuyên Chép (>95% chép)', 'lượng cá chép trong hồ phải đạt >=95%', 'hoat_dong', '2025-06-23 20:25:32'),
(4, 'Cá Hỗn Hợp (chép-phi-trôi)', 'chép, phi, trôi số lượng đều nhau', 'hoat_dong', '2025-06-23 20:25:32'),
(5, 'Cá tạp Chép (cá chép >=50%)', 'Lượng cá chép >=50% còn lại phi, trôi, cá khác...', 'hoat_dong', '2025-06-23 20:25:32'),
(6, 'Cá tạp Phi - (cá phi >=50%)', 'Lượng cá chép >=50% còn lại phi, trôi, cá khác...', 'hoat_dong', '2025-06-23 20:25:32'),
(7, 'Siêu Săn Hàng (cá > 20kg)', 'Siêu Săn Hàng (cá > 20kg)', 'hoat_dong', '2025-06-23 20:25:32'),
(8, 'Tôm càng xanh', 'Loài tôm nước ngọt, đánh giá cao về kích thước', 'hoat_dong', '2025-06-23 20:25:32'),
(9, 'Chuyên cá tra', 'Hồ cá tra', 'hoat_dong', '2025-06-27 16:19:40'),
(11, 'Chuyên cá trê', 'Chuyên cá trê', 'hoat_dong', '2025-06-27 16:20:42'),
(12, 'Chuyên Lure', 'Chuyên Lure cá lóc, cá chẽm', 'hoat_dong', '2025-06-27 16:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `amount` int NOT NULL,
  `type` enum('in','out') NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `method` enum('momo','bank_qr','shop','admin') DEFAULT 'bank_qr',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_code_logs`
--

CREATE TABLE `qr_code_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `qr_type` enum('momo','vietqr_bank') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'momo',
  `qr_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'QR Link...',
  `qr_amount` int NOT NULL DEFAULT '0',
  `qr_content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'QR nội dung...',
  `qr_status` enum('pending','paid','expired','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_logs`
--

CREATE TABLE `referral_logs` (
  `id` int NOT NULL,
  `ref_user_id` int NOT NULL,
  `new_user_id` int NOT NULL,
  `action` enum('register','update_profile') DEFAULT 'register',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_payouts`
--

CREATE TABLE `referral_payouts` (
  `id` int NOT NULL,
  `payout_code` varchar(20) DEFAULT NULL,
  `ref_user_id` int NOT NULL,
  `payout_month` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `total_paid` int NOT NULL DEFAULT '0',
  `payment_method` enum('bank','momo','zalo','cash','admin') DEFAULT 'admin',
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'payout note...',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_rewards`
--

CREATE TABLE `referral_rewards` (
  `id` int NOT NULL,
  `ref_user_id` int NOT NULL,
  `from_user_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `reward_amount` int NOT NULL DEFAULT '0',
  `status` enum('pending','paid') DEFAULT 'pending',
  `paid_by_payout_id` int DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Nick name...',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Email...',
  `vai_tro` enum('admin','moderator','canthu','chuho','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'canthu',
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Họ và Tên...',
  `bank_account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Số tài khoản...',
  `bank_info` enum('970436-Vietcombank','970415-VietinBank','970418-BIDV','970405-Agribank','970407-Techcombank','970422-MBbank','970423-TPBank','970403-Sacombank','970432-VPBank','970416-ACB') DEFAULT NULL,
  `qr_image_path` varchar(255) DEFAULT NULL,
  `CCCD_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Số CCCD...',
  `balance` decimal(12,2) DEFAULT '100000.00',
  `balance_ref` decimal(12,2) DEFAULT '50000.00',
  `ref_code` varchar(20) DEFAULT '0935192079',
  `user_exp` int DEFAULT '10',
  `cnt_ho` int NOT NULL DEFAULT '0',
  `cnt_xa` int NOT NULL DEFAULT '0',
  `cnt_giai` int NOT NULL DEFAULT '0',
  `cnt_game` int NOT NULL DEFAULT '0',
  `user_lever` int DEFAULT '1',
  `user_note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Ghi chú user...',
  `review_status` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'yes',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Chưa xác minh','Đã xác minh','Tạm dừng','banned') NOT NULL DEFAULT 'Chưa xác minh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone`, `password`, `nickname`, `email`, `vai_tro`, `full_name`, `bank_account`, `bank_info`, `qr_image_path`, `CCCD_number`, `balance`, `balance_ref`, `ref_code`, `user_exp`, `cnt_ho`, `cnt_xa`, `cnt_giai`, `cnt_game`, `user_lever`, `user_note`, `review_status`, `created_at`, `status`) VALUES
(1, '0922222222', '$2y$10$A5D9QlQlx.2iIAEuiuzgq.5rI0LqkcllL76DvULm/HglXPtk27p2q', 'Chim Sẽ Già', 'a@example.com', 'canthu', 'Nguyễn Văn A', '5512345678', NULL, NULL, NULL, 4193250.00, 50000.00, NULL, 10, 0, 0, 0, 0, 1, NULL, 'yes', '2025-05-24 20:06:33', 'Đã xác minh'),
(2, '0343808950', '$2y$10$3YQDv.qdGUhK8qEH2YeLm.BP0HqpcgVSy/8Vj8wLcypIRef6o.TO6', 'Hồ Câu Bảo Ngân', 'hocaubaongan@gmail.com', 'chuho', 'Lê Hoài Giang', '44534047', '970416-ACB', NULL, '0101020020', 21794500.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 3, 'note', 'yes', '2025-05-24 20:06:33', 'Đã xác minh'),
(5, '0900000001', 'hashed1', 'canthu_01', '01@example.com', 'canthu', 'Cần thủ 01', NULL, NULL, NULL, NULL, 100000.00, 50000.00, NULL, 10, 0, 0, 0, 0, 1, NULL, 'no', '2025-05-24 20:27:12', 'Đã xác minh'),
(9, '0900000005', 'hashed5', 'canthu_05', '05@example.com', 'canthu', 'Cần thủ 05', NULL, NULL, NULL, NULL, 100000.00, 50000.00, NULL, 10, 0, 0, 0, 0, 3, NULL, 'yes', '2025-05-24 20:27:12', 'Đã xác minh'),
(10, '0935192079', '$2y$10$I01H2.Eabf56vDvw.IHSsODTQgnOX81Lll3uineMajd4GIt2/ZdOq', 'Đài Sư Tập Sự', 'admin@example.com', 'admin', 'Nguyen Ngoc Tan Thanh', '5512345678', NULL, NULL, '024217481', 100000.00, 50000.00, '0935192079', 10000, 0, 0, 0, 0, 6, 'Đài Sư Tập Sự', 'yes', '2025-05-24 20:30:48', 'Đã xác minh'),
(11, '0888888888', 'hashed_mod_pass', 'MOD - Quản Lý', 'mod@example.com', 'moderator', 'Nguyễn MOD', '5512345678', NULL, NULL, '0101020020', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 3, 'Quản lý', 'yes', '2025-05-24 20:33:26', 'Đã xác minh'),
(16, '0922222224', '$2y$10$GuGQ5Vh5Fn4VBOARgCWjHuyrY9IYZ6JvMfjC/O.QfMQWIXCyca31u', 'Chim se sẽ', 'alibaba@gmail.com', 'canthu', 'Nguyễn Phan', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, NULL, 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'yes', '2025-06-03 17:30:27', 'Chưa xác minh'),
(17, '0911111112', '$2y$10$rvlBX.SwrIo8gUFnJ8RDDu35cBKWgcmd7SuAb26EljVrx/bCPECae', 'Hồ Câu Hoàng Hải', 'idontwant2010@gmail.com', 'chuho', 'Nguyễn Thanh A', '0071005344694', '970436-Vietcombank', NULL, '0101020020', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'user giàu có', 'yes', '2025-06-03 17:34:14', 'Đã xác minh'),
(18, '0902222225', '$2y$10$l66oRNf.jWg2j3INEbQnWOcy51A8Do/JJXqNNIZTlmalI5vdFth7q', 'Lưu Chí Cường', 'idontwant@gmail.com', 'canthu', 'Nguyễn Phan 2', '0123456789', NULL, NULL, '0101020020', 1500000.00, 50000.00, '0935192079', 100, 0, 0, 0, 0, 0, 'Cần thủ VIP', 'yes', '2025-06-03 17:46:52', 'Đã xác minh'),
(19, '09012345001', '$2y$10$g3Zm/RIgdfwCuqalg6ExMe89y/DROLueRlXRyLLZ9hCFIGvPJaLx.', 'guest_9057', 'Email...', 'canthu', 'Nguyễn Văn 001', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 11:21:25', 'Chưa xác minh'),
(20, '09012345002', '$2y$10$srdOQB8a3d8Kqgbk/DiEmusJ2XxifXalYbv.qFAHkK5AUdg7FetMa', 'guest_8310', 'Email...', 'canthu', 'Nguyễn Văn 002', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 11:24:17', 'Chưa xác minh'),
(21, '09012345003', '$2y$10$8ayQeKzVm.8ZRZqbONINz.Kvoa5rWsXWWRx70Ro5iznQthL/oKph6', 'guest_35336', 'Email...', 'canthu', 'Nguyễn Văn 003', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 11:33:13', 'Chưa xác minh'),
(22, '09012345004', '$2y$10$gbpDz5TT7LxaSfJfI/KfseZGymEf0dl.6iqT3DZSpcXlLSX6d6GW2', 'Khach_63584', 'Email...', 'canthu', 'Nguyễn Văn 004', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 11:34:14', 'Chưa xác minh'),
(23, '09012345005', '$2y$10$si4vW491OzPw0kvTYG/PSO9iA4M6o1L8UR6dnHar2Qfkn5dkLS26m', 'Khach_46452', 'Email...', 'canthu', 'Nguyễn Văn 005', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 13:51:06', 'Chưa xác minh'),
(24, '09012345006', '$2y$10$eR7q0UjHv8gpn509572qTO595QxCw4P5bv.9glnB6WLbX0MHViE46', 'Khach_29691', 'Email...', 'canthu', 'Nguyễn Văn 006', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 13:54:10', 'Chưa xác minh'),
(26, '09012345008', '$2y$10$W7qmfvwiFfWRxBlzJ1v5.u2MoDic6nLJQyVuNWhGs1VutLNbD/Rpu', 'guest_4770', 'Email...', 'canthu', 'Nguyễn Văn 008', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:24:45', 'Chưa xác minh'),
(27, '09012345009', '$2y$10$BWhnya9Tut7uISrFMBrsJ.ojd8urzQMDyw0VC6Cjn8n9fV82zZy.i', 'guest_6973', 'Email...', 'canthu', 'Nguyễn Văn 009', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:24:52', 'Chưa xác minh'),
(28, '09012345010', '$2y$10$7HSrq1k/UywEDQ3DA86G4Oxjq2qHk0DEU4tsIZmzG2VOKdyp6SPHa', 'guest_4779', 'Email...', 'canthu', 'Nguyễn Văn 010', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:25:03', 'Chưa xác minh'),
(29, '09012345011', '$2y$10$zvnO9xt66QO2fCO6GJkCmOaAHZ03.8LDv/Z/l4s33EkINMAD2a1AS', 'guest_842191', 'Email...', 'canthu', 'Nguyễn Văn 011', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:25:57', 'Chưa xác minh'),
(30, '09012345012', '$2y$10$Yc4DtG/YSCazW6hoSaEOieRakoUfEzZsIYzrVdIFEL72l8Rk.wAbi', 'guest_112921', 'Email...', 'canthu', 'Nguyễn Văn 012', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:26:07', 'Chưa xác minh'),
(31, '09012345013', '$2y$10$5GOMLbBgJpOzcunjVdde.OngCfmzKf5J1bcm3lBVhxd.Cpa2Dj0Jm', 'nickname_713873', 'Email...', 'canthu', 'Nguyễn Văn 013', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:26:42', 'Chưa xác minh'),
(32, '09012345014', '$2y$10$DCzFKOrETYU1.TOhwSCjQuHdo3qVttuMDS8KDSYkuHq6XgRGwRZ0K', 'nickname_272808', 'Email...', 'canthu', 'Nguyễn Văn 014', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:26:54', 'Chưa xác minh'),
(33, '09012345015', '$2y$10$6VRccNLFPMlQCFK54htKOuqN/YeVMIgoME8sp8D8AcixXxvxOCJLq', 'nickname_965746', 'Email...', 'canthu', 'Nguyễn Văn 015', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:29:06', 'Chưa xác minh'),
(34, '09012345016', '$2y$10$YL1qYMp.o1y9G5c2xeB/vu070P35Laq7.Z3YNGTi8ofAtqVBZXmlW', 'nickname_922320', 'Email...', 'canthu', 'Nguyễn Văn 016', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:29:15', 'Chưa xác minh'),
(35, '09012345017', '$2y$10$7Sz4qkciQVKJkgyF7n6MlOKZZpIQRW6x0pzVbGOBvs9cAKam73/jK', 'nickname_258675', 'Email...', 'canthu', 'Nguyễn Văn 017', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:29:28', 'Chưa xác minh'),
(36, '09012345018', '$2y$10$DR4x9UtzrpHfZM.upcomzOIrj8.QX11GhhiPbbpAHr6XB/rmVhEkC', 'nickname_942731', 'Email...', 'canthu', 'Nguyễn Văn 018', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:29:40', 'Chưa xác minh'),
(37, '09012345019', '$2y$10$eHOHSwaVSqjwZEkblJu8qeaLzLJQ/a7biU4u3k3ttkNyh0Ntv51aK', 'nickname_214320', 'Email...', 'canthu', 'Nguyễn Văn 019', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:29:50', 'Chưa xác minh'),
(38, '09012345020', '$2y$10$PCHDCNqwQ4JAmIYAQalx0.xhkNjSS.2kOYvq2un1N1.myLi74aw76', 'nickname_450234', 'Email...', 'canthu', 'Nguyễn Văn 020', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:29:58', 'Chưa xác minh'),
(39, '09012345021', '$2y$10$JLYUVIJaJb6y9dKFkRLw/uKZYOmsRUCBciNOs4axloD2aoaGznLbS', 'nickname_757272', 'Email...', 'canthu', 'Nguyễn Văn 021', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:39:51', 'Chưa xác minh'),
(40, '09012345022', '$2y$10$t3/p3oko6Ce500n4zv0h7ueXyw7Bssv.xp4ljDJvoJ5e2538w3G4O', 'nickname_728666', 'Email...', 'canthu', 'Nguyễn Văn 022', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-08 14:52:40', 'Chưa xác minh'),
(41, '09012345030', '$2y$10$3aKHn0muOJUY01IF5LQWf.OepRZlKRzdtlc8Y8AmAvAEnm9j/.IYu', 'nickname_565888', 'Email...', 'canthu', 'Nguyễn Văn 030', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-09 21:43:46', 'Chưa xác minh'),
(42, '0901234001', '$2y$10$1bPx/I/YpLicQthwrje/hOAfZ2.AYRlTknamrjATOEPyCtf/SjKUq', 'nickname_797535', 'Email...', 'canthu', 'Tran Van 001', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 16:33:33', 'Chưa xác minh'),
(43, '0901234002', '$2y$10$8djla7qiRLiByAoS6LvvGOX4TZ1.RzZGXNEuNTXcjc..JE15sCfV.', 'nickname_361014', 'Email...', 'canthu', 'Tran Van 002', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 16:53:55', 'Chưa xác minh'),
(44, '0901234005', '$2y$10$/uYjvlniERHvYSLCD7J/leKCANJkdffxJQjqwqlmXS4glpy1RhbNK', 'nickname_870102', 'Email...', 'canthu', 'Tran Van 005', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 16:58:14', 'Chưa xác minh'),
(45, '0909123123', '$2y$10$L49bitlp43q8w9m4t.C6..UvPkJYRIt/H4ERUV2y83lz8Fv50LKS6', 'nickname_741150', 'Email...', 'canthu', 'Tran Van 123', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:00:13', 'Chưa xác minh'),
(46, '0909123124', '$2y$10$/.I/0hSmATs1jLfRkElU2.TfQhRMABSTDW3f8SMmpy5zZu96nPINe', 'nickname_530236', 'Email...', 'canthu', 'Tran Van 124', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:00:50', 'Chưa xác minh'),
(47, '0909123125', '$2y$10$m/z7/TTCxPACuhUalrOQBuB7fcXYBtbx9afKR1QwV/xRnpGgKzKXG', 'nickname_589614', 'Email...', 'canthu', 'Tran Van 125', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:00:57', 'Chưa xác minh'),
(48, '0909123126', '$2y$10$rjGVvckDfitZQaM0a9u.geYjuXyVPa6kvQAEopoGP3fVGTjZGpYs2', 'nickname_145652', 'Email...', 'canthu', 'Tran Van 126', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:05', 'Chưa xác minh'),
(49, '0909123127', '$2y$10$MLQ8GA/WXTn3IxsRKCLMeOxnr6kL9mI83coYq5hbuPB7QBfManPYi', 'nickname_818740', 'Email...', 'canthu', 'Tran Van 127', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:12', 'Chưa xác minh'),
(50, '0909123128', '$2y$10$Iu.JakgCMz7SmOL6FKekCeVTId8BHdnPlPGD11aXd.wFJW6EZnahC', 'nickname_285632', 'Email...', 'canthu', 'Tran Van 128', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:19', 'Chưa xác minh'),
(51, '0909123129', '$2y$10$C2nAXr0y98VHPkvOTPxVE.wOfePKMtDWVrUk0gPWysnrwcD7hymaW', 'nickname_335647', 'Email...', 'canthu', 'Tran Van 129', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:27', 'Chưa xác minh'),
(52, '0909123130', '$2y$10$/ZKzp1IJXtqHz8QDmAthm.Pz6OgLuwpRBWzOvNFE2DWOCK1wOhEhC', 'nickname_854088', 'Email...', 'canthu', 'Tran Van 130', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:36', 'Chưa xác minh'),
(53, '0909123131', '$2y$10$AgrLXMkLagH0enerwuR/v.AEoAilcoxFrFZu4.STh7bqu2tjxaJsW', 'nickname_331951', 'Email...', 'canthu', 'Tran Van 131', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:46', 'Chưa xác minh'),
(54, '0909123132', '$2y$10$aBusBow1as7OQUG5jPix8OLqHqbzJQIDsiHmKF/aqrOe7/I8QOrhq', 'nickname_494537', 'Email...', 'canthu', 'Tran Van 132', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:52', 'Chưa xác minh'),
(55, '0909123133', '$2y$10$ZCufC8FSZH6XM27rDY4NkuCUMaqFsmeOjpifwW8GIfeZ5JTmla9pm', 'nickname_651649', 'Email...', 'canthu', 'Tran Van 133', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:01:58', 'Chưa xác minh'),
(56, '0909123134', '$2y$10$v0zRhOUyZjeNpSMzJJ0AGuTDFo7CzVZk/.n7.9IelTNFwnlOoxgYG', 'nickname_824025', 'Email...', 'canthu', 'Tran Van 134', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:02:04', 'Chưa xác minh'),
(57, '0909123135', '$2y$10$..36K8Wd8uhQTb1sCpYVdepu0AlqlRxmqs6OGXrIIAVZhZTH5pee2', 'nickname_689626', 'Email...', 'canthu', 'Tran Van 135', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:02:15', 'Chưa xác minh'),
(58, '0909123136', '$2y$10$R8a9bBef1H6lmuXHIiQO2eEfRuKHjwZueJc7B2H5w6oGgbCXeyGiS', 'nickname_580294', 'Email...', 'canthu', 'Tran Van 136', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-10 17:02:23', 'Chưa xác minh'),
(59, '0901234006', '$2y$10$V0BNZ1hZJ.Q2YLMV8/A6F.7r.4A9JJEKZ9xMQwSMv4kgkpMFypfxS', 'nickname_338328', 'Email...', 'canthu', 'Nguyễn Văn 006', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 17:16:35', 'Chưa xác minh'),
(60, '0901234008', '$2y$10$suYgn7NQfyv7XTx/7GDfIOn5eu2dAXMYz2a2.E2V.8ZCXcLfxzzzK', 'nickname_541175', 'Email...', 'canthu', 'Nguyễn Văn 008', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 17:16:48', 'Chưa xác minh'),
(61, '0901234009', '$2y$10$qUxQmYO86KAvrJGp8eZbreZZK5JX0DU/VRm8o64k6iAhoYT6Q8qZa', 'nickname_221916', 'Email...', 'canthu', 'Nguyễn Văn 009', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 17:16:59', 'Chưa xác minh'),
(62, '0901234003', '$2y$10$mirtomS6imupu/aKD5Ar2.jvNq6UhRR57dc0R7xGfSjsCOWT2HTBW', 'nickname_518474', 'Email...', 'canthu', 'Lê thị 003', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 17:59:51', 'Chưa xác minh'),
(63, '0901234004', '$2y$10$vvZ8OczHhPrDblhyIdqcNuynFvpHl3ER8hkeyC/5jVTSgQKiDqTjO', 'nickname_373589', 'Email...', 'canthu', 'Lê thị 004', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 17:59:56', 'Chưa xác minh'),
(64, '0901234007', '$2y$10$p1q6pKH9fWzglOffXwfCfe6VE9XYDXWpIQFUO1wI9nd6RduoLu/tC', 'nickname_487742', 'Email...', 'canthu', 'Lê thị 007', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:00:22', 'Chưa xác minh'),
(65, '0901234010', '$2y$10$8fPmn/kzS6N5yQopKrhJSe2yHRHSqgjC/Nhf0CYbYWkar2VWyqF8m', 'nickname_878636', 'Email...', 'canthu', 'Lê thị 010', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:00:46', 'Chưa xác minh'),
(66, '0901234011', '$2y$10$umHcxyYf3mbaLtqGwxWA2enMxcUN6sLm9TUBznR4/ppt4lIOqot2i', 'nickname_401478', 'Email...', 'canthu', 'Lê thị 011', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:00:52', 'Chưa xác minh'),
(67, '0901234012', '$2y$10$aZO6FRHyWQAxd.qWodihdueUU3/1WmweU/dNOa1l58AbZc2onvMtS', 'nickname_169129', 'Email...', 'canthu', 'Lê thị 012', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:00:58', 'Chưa xác minh'),
(68, '0901234013', '$2y$10$PRVcMOnmC7Pr1mEtL/2iV.xrQ.5KeJMC8NczQOB.To0d/Iv00U/Vm', 'nickname_233998', 'Email...', 'canthu', 'Lê thị 013', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:01:03', 'Chưa xác minh'),
(69, '0901234014', '$2y$10$8Y7Sk84ZcIRQ473hts8QSeltzXj978p43gHwbETmoPTeDkcnhAnsW', 'nickname_498849', 'Email...', 'canthu', 'Lê thị 014', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:01:11', 'Chưa xác minh'),
(70, '0901234015', '$2y$10$aAWLV/bpMIqY5RCKuyXY.uiCSKfI/mRcGKS3lM.H1CTZdGUrcnPX2', 'nickname_598964', 'Email...', 'canthu', 'Lê thị 015', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:01:17', 'Chưa xác minh'),
(71, '0901234016', '$2y$10$Z9wB5JVMISevuCXoIYMvT.iQUNbB/DhYsZhbH0CvwzzDfWb2VLpo2', 'nickname_102507', 'Email...', 'canthu', 'Lê thị 016', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:01:23', 'Chưa xác minh'),
(72, '0901234017', '$2y$10$2DePjp/rUqs38WlftJiuh.xiuaY1weLcI2bKkcUM/hxDKd7QvCOtm', 'nickname_441653', 'Email...', 'canthu', 'Lê thị 017', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:01:31', 'Chưa xác minh'),
(73, '0901234018', '$2y$10$mcSFI3uqQww4LKF6I1G8XeTPGoQUkDpNwV86JvTfjMRXLkU.12o1a', 'nickname_531884', 'Email...', 'canthu', 'Lê thị 018', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-12 18:01:38', 'Chưa xác minh'),
(74, '0901234050', '$2y$10$CdAH.xGEijQYZr02aAd2puERZpREhTtdygOqFb4xk7zyCqc/H358u', 'nickname_117115', 'Email...', 'canthu', 'Nguyễn Văn 050', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:16:32', 'Chưa xác minh'),
(75, '0901234033', '$2y$10$9LKIjEgDP25QTYs7N3aEhOdJ5Zxx2LMjCXGBz/VImVf6CAEcEQypi', 'nickname_173290', 'Email...', 'canthu', 'Nguyễn Văn 033', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:02', 'Chưa xác minh'),
(76, '0901234034', '$2y$10$ir.QhjIsrVA7IKYp48Qw1OwJWIz36pvP2jmSGxiq6j/.9a8RoYxcC', 'nickname_983942', 'Email...', 'canthu', 'Nguyễn Văn 034', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:08', 'Chưa xác minh'),
(77, '0901234035', '$2y$10$ZUr5Agaoohb/y2nQdRZ7L.u4YUU5N4cn51n5Ot4IJ51BbqYPEm.FO', 'nickname_173796', 'Email...', 'canthu', 'Nguyễn Văn 035', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:14', 'Chưa xác minh'),
(78, '0901234036', '$2y$10$GdVnEr6YfCTvVhbblcyC8.BX2c/rnuR2PgTKDR.RO4ieuO68NV8xC', 'nickname_667204', 'Email...', 'canthu', 'Nguyễn Văn 036', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:19', 'Chưa xác minh'),
(79, '0901234037', '$2y$10$c8uh6qYWOOkM5iEEFBwKn.0JM0OY6Bl.FXhaRCetnFQXUVWFx..Iu', 'nickname_338606', 'Email...', 'canthu', 'Nguyễn Văn 037', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:25', 'Chưa xác minh'),
(80, '0901234038', '$2y$10$sn7W2dYLx3KE1DRbKA.uEuWjpfe4KzUCGXneSRXVy3IrKw2ZkI/JC', 'nickname_131513', 'Email...', 'canthu', 'Nguyễn Văn 038', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:31', 'Chưa xác minh'),
(81, '0901234039', '$2y$10$9HVEFy3NOFzz.ddGSRKm6OpeT2nJPii.mVICx9mnSTXpCzMoKOJwa', 'nickname_358403', 'Email...', 'canthu', 'Nguyễn Văn 039', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:37', 'Chưa xác minh'),
(82, '0901234040', '$2y$10$Xe1PKmq.R13OXqCbIp3KBOvyZeAGuy4S6CzZNujUFXCxsMil/a7oq', 'nickname_784051', 'Email...', 'canthu', 'Nguyễn Văn 040', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:43', 'Chưa xác minh'),
(83, '0901234041', '$2y$10$k0YOEzhp7kwsDYRei//AZ.N74wM7U9d8pjquqqEK0966VH3WtLNrq', 'nickname_724435', 'Email...', 'canthu', 'Nguyễn Văn 041', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:48', 'Chưa xác minh'),
(84, '0901234042', '$2y$10$3U5l4NkSoGj5H3Np/B5jjeEjc4uvHqIU40fn1us3alryCcIi0BXSW', 'nickname_725717', 'Email...', 'canthu', 'Nguyễn Văn 042', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:18:59', 'Chưa xác minh'),
(85, '0901234043', '$2y$10$XXJ80gZNJbXRBcVq9cmSjuILA1xRd68355E8iBP6aRG7h4VFdrfG2', 'nickname_963789', 'Email...', 'canthu', 'Nguyễn Văn 043', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:19:21', 'Chưa xác minh'),
(86, '0901234044', '$2y$10$Zy330Wo7/fal0DP5pQwzm.g9QVTtfr7JN3LpY0vW224GJwDn2/W9C', 'nickname_217520', 'Email...', 'canthu', 'Nguyễn Văn 044', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:19:32', 'Chưa xác minh'),
(87, '0901234045', '$2y$10$Q0XZ4TdK7XOXn03voI8mN.I0txxToXIANvqnCgV9jqnH0cdks437q', 'nickname_329120', 'Email...', 'canthu', 'Nguyễn Văn 045', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:31:03', 'Chưa xác minh'),
(88, '0901234046', '$2y$10$a0tBtDj.Nd77.A8fKUAwJe9rOIuqIq.tn/jNfCbLiJ0pdHK7LbECW', 'nickname_804701', 'Email...', 'canthu', 'Nguyễn Văn 046', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:34:00', 'Chưa xác minh'),
(89, '0901234047', '$2y$10$.qRg2cRGNHOpzAO/hVTTruIWG0IL2NajNT7mTCQIiwPKcAb/Gxan6', 'nickname_843581', 'Email...', 'canthu', 'Nguyễn Văn 047', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:45:22', 'Chưa xác minh'),
(90, '0901234111', '$2y$10$SBVLG8dR4soXVQE9Tu8hx.1QT9HFH3VwA.Z8pM6JmLRO.elOILZ66', 'nickname_281327', 'Email...', 'canthu', 'Nguyễn Văn 111', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:47:21', 'Chưa xác minh'),
(91, '0901234112', '$2y$10$8ttvkMSebFQxsZcFZ88do.dHaOTc83WKwPIooMvLm.j1tNFHcY4bq', 'nickname_552350', 'Email...', 'canthu', 'Nguyễn Văn 112', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:47:27', 'Chưa xác minh'),
(92, '0901234113', '$2y$10$ukptQLWOIyP9paB8Kj3c0ut7lKT04qFquTaKoMgU.caYxtULdUXna', 'nickname_980987', 'Email...', 'canthu', 'Nguyễn Văn 113', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:47:38', 'Chưa xác minh'),
(93, '0901234114', '$2y$10$rZz1EzIx3giHXjmjiU8/e.1JlFZGJfKN74TlSPmRgn5OwhiygaZzi', 'nickname_227460', 'Email...', 'canthu', 'Nguyễn Văn 114', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:47:44', 'Chưa xác minh'),
(94, '0901234115', '$2y$10$AsNQAfnbP6XYN5hHgrANKOzASHRW077pR.o0IfyxXGCIrA5RsaT2m', 'nickname_381819', 'Email...', 'canthu', 'Nguyễn Văn 116', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:47:50', 'Chưa xác minh'),
(95, '0901234117', '$2y$10$zIacvW7RBMeJJbGSIddTb..zHwwYf4pXjFASXm99LqAHQzX3N58zG', 'nickname_437433', 'Email...', 'canthu', 'Nguyễn Văn 117', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:47:59', 'Chưa xác minh'),
(96, '0901234118', '$2y$10$r6hDO3oT2mlaIABrk1xbfunx7O67xRM1x0le/tmtvpbthRNtrDKVS', 'nickname_484535', 'Email...', 'canthu', 'Nguyễn Văn 118', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:48:08', 'Chưa xác minh'),
(97, '0901234119', '$2y$10$7rq6oYqlbtH7HPGrvcY15uddW7GI0R/6t9bJpwuxhVvI8fC9Ya2c2', 'nickname_121039', 'Email...', 'canthu', 'Nguyễn Văn 119', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:48:15', 'Chưa xác minh'),
(98, '0901234221', '$2y$10$J2AvL5U47E7pLL9bnoILfeq6k8nNSzYbTTxX46sCh0CVBtNVhkD26', 'nickname_824025', 'Email...', 'canthu', 'Lê Văn 221', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:50:44', 'Chưa xác minh'),
(99, '0901234222', '$2y$10$K8sNcUBdGPpeiyVE8wfoB.TMmSHO7FJw3EGpboGN7UhVTavf01WrC', 'nickname_574752', 'Email...', 'canthu', 'Lê Văn 222', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:50:49', 'Chưa xác minh'),
(100, '0901234223', '$2y$10$4mn4S7YUkbZUPP/tl9zZ5ussvAbh2fKXZCK4./1mEJ08j562EVbBK', 'nickname_722577', 'Email...', 'canthu', 'Lê Văn 223', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:50:53', 'Chưa xác minh'),
(101, '0901234224', '$2y$10$xxb8BEm2MVPZ3x04VqD12O7kDpEk.RtdIeGIBHnlrRSIEReVoHpTS', 'nickname_363844', 'Email...', 'canthu', 'Lê Văn 224', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:50:59', 'Chưa xác minh'),
(102, '0901234225', '$2y$10$GI89NaGY26Fud/q24BYroOw8ka8wK3C5lDP9pxURALfrUz07MNCE6', 'nickname_600099', 'Email...', 'canthu', 'Lê Văn 225', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:04', 'Chưa xác minh'),
(103, '0901234226', '$2y$10$Dp3i7Vn4RtGEm1xmTYUsC.DSDvzb0U.Jsnm5jkL9.QhfQySskdTD2', 'nickname_925721', 'Email...', 'canthu', 'Lê Văn 226', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:10', 'Chưa xác minh'),
(104, '0901234227', '$2y$10$mMJrZ.hf8weLIzxtsLI1h.ETzCGhvAKCDqE3h23qGmlZQSBUbs4iy', 'nickname_952798', 'Email...', 'canthu', 'Lê Văn 227', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:14', 'Chưa xác minh'),
(105, '0901234228', '$2y$10$Ti4onLArC63gQrPE6Cs.MugxUxvlvVhdbuEA99TCmoCS37vd25OzS', 'nickname_867163', 'Email...', 'canthu', 'Lê Văn 228', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:20', 'Chưa xác minh'),
(106, '0901234229', '$2y$10$k6VZmnkXOOBMqyIccGQMp.ZBrU7wPoC36goT6d0iAPF5U9YSrp9z.', 'nickname_726978', 'Email...', 'canthu', 'Lê Văn 229', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:25', 'Chưa xác minh'),
(107, '0901234230', '$2y$10$dksSrxgjsMT8nMjiBgmSBeM0CLTsfBKpuAZ0LHIlVqdR.KJXVhvK2', 'nickname_812351', 'Email...', 'canthu', 'Lê Văn 230', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:32', 'Chưa xác minh'),
(108, '0901234231', '$2y$10$XiNjiPeOFxtvNXm2fXeZsOZAHeF.3CgC2vhVWa1Ir6DGJVvmcNUyi', 'nickname_195071', 'Email...', 'canthu', 'Lê Văn 231', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:39', 'Chưa xác minh'),
(109, '0901234233', '$2y$10$cRMVTfWf6fWUTuHT//fB5OmznVep21J3ij1SinSID56nNHxPkqLuG', 'nickname_307406', 'Email...', 'canthu', 'Lê Văn 233', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:46', 'Chưa xác minh'),
(110, '0901234234', '$2y$10$BR6BPldMiIX0k.3qrZwu/.cNmXufRBIidnOvbukxCbaLJ1TpGYyZq', 'nickname_216344', 'Email...', 'canthu', 'Lê Văn 234', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:51', 'Chưa xác minh'),
(111, '0901234235', '$2y$10$F7PQg7MBLZ4PVbUz4h30d.FBK1K1abXuyiUS6CXY36b1YM80wBuO.', 'nickname_376847', 'Email...', 'canthu', 'Lê Văn 235', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:51:56', 'Chưa xác minh'),
(112, '0901234236', '$2y$10$WrUPio9ZXRUmIn4v7t6e7.OgMGZyNbZBjxN.aTNSedRZhLm2.Jbt.', 'nickname_702508', 'Email...', 'canthu', 'Lê Văn 236', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:52:02', 'Chưa xác minh'),
(113, '0901234237', '$2y$10$ouVnZsfCqM0XBG/NxqcniOg.HDSz/mzdVY6.Qp7bIERnnhYirmh/m', 'nickname_727708', 'Email...', 'canthu', 'Lê Văn 237', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:52:07', 'Chưa xác minh'),
(114, '0901234238', '$2y$10$6mvhgS0HYUls7t7BAW2k5elL6sY8VRqlw66z5LXqfHF5OqBvLvKrm', 'nickname_954316', 'Email...', 'canthu', 'Lê Văn 238', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:52:13', 'Chưa xác minh'),
(115, '0901234239', '$2y$10$zC9hoVZT70C1RvkcWwCTnuww7vYnblvOpj/b/amuwln86L5vLKbpK', 'nickname_420521', 'Email...', 'canthu', 'Lê Văn 239', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-13 14:52:19', 'Chưa xác minh'),
(116, '0901235331', '$2y$10$A8UCVHOF11NK46wFrAaivuqpHJa4mo5W3mS39AXpYirumkl/4Aur6', 'nickname_199339', 'Email...', 'canthu', 'Nguyễn Văn 331', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-14 19:05:19', 'Chưa xác minh'),
(117, '0901235332', '$2y$10$MW/F.EWm5d7TdAQsMnHlhu6CSQWzS6yzfWUoSAfBH4NB4HWkIeiny', 'nickname_349653', 'Email...', 'canthu', 'Nguyễn Văn 332', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-14 19:05:25', 'Chưa xác minh'),
(118, '0901235334', '$2y$10$x9OEWERenpVruSALskt0neaDaVoB5PUFt3HMrz5h9WP.ngNmUbZme', 'nickname_911301', 'Email...', 'canthu', 'Nguyễn Văn 334', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-14 19:05:35', 'Chưa xác minh'),
(119, '0901235335', '$2y$10$rlzdG8TnjCpUqyW5BvYKoOmtTrMJwcAq1EHNI3R9WwjIL3l2ZAKbO', 'nickname_342470', 'Email...', 'canthu', 'Nguyễn Văn 335', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-14 19:05:54', 'Chưa xác minh'),
(120, '0901235336', '$2y$10$Y9gT7HecdbKHt7k1YCAM5OtDIVe/kli06sQATgmqlTPT33Uiw1bWG', 'nickname_325790', 'Email...', 'canthu', 'Nguyễn Văn 336', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-14 19:05:59', 'Chưa xác minh'),
(121, '0901234441', '$2y$10$KjewQ.0Wr35Zed80NehPp.lmBioSVtN7tt2E5yj.mKoRzx8sc..Em', 'nickname_234997', 'Email...', 'canthu', 'Bùi Văn 441', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:34:29', 'Chưa xác minh'),
(122, '0901234442', '$2y$10$eQXuJ1fNwmMerYAQcB3rfO9O91KVqq2e6uodogqivktD3cAGlmWUK', 'nickname_443647', 'Email...', 'canthu', 'Bùi Văn 442', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:34:35', 'Chưa xác minh'),
(123, '0901234443', '$2y$10$btUwkW2UlP04e.yqcj2aZepRuGg/ANIa56UQA/F6HiT29B/9XNXce', 'nickname_942249', 'Email...', 'canthu', 'Bùi Văn 443', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:34:46', 'Chưa xác minh'),
(124, '0901234444', '$2y$10$WcJjhzvUJ7aiV98aB9Na5OOzzv7410Ywg4Tmrq4ycGAksArVDZW9u', 'nickname_755523', 'Email...', 'canthu', 'Bùi Văn 444', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:34:53', 'Chưa xác minh'),
(125, '0901234445', '$2y$10$I7X88jtbcd5kD.h1j/FiUe4lItNEF7gnV5q9RooOiprID6NK1xQMK', 'nickname_760091', 'Email...', 'canthu', 'Bùi Văn 445', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:00', 'Chưa xác minh'),
(126, '0901234446', '$2y$10$wOfk7fg3GxOJCBQZ.Ff7XuW9OOAJjHvboNsu6uynW5TfctqZ2oufG', 'nickname_574377', 'Email...', 'canthu', 'Bùi Văn 446', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:05', 'Chưa xác minh'),
(127, '0901234447', '$2y$10$rmrGPDIHG4pEQAirDhiTnOggy6RH92sesfYstJqF/EPrD1m.fWSJy', 'nickname_522588', 'Email...', 'canthu', 'Bùi Văn 447', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:10', 'Chưa xác minh'),
(128, '0901234448', '$2y$10$c5ORHmyKdPaQR0t0vAYHeepjO4MX98PK6cCKsQeg8D2tSOHPSOC1K', 'nickname_960184', 'Email...', 'canthu', 'Bùi Văn 448', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:15', 'Chưa xác minh'),
(129, '0901234449', '$2y$10$KSSHxiv.wa1cJh4Cl6xGk.VfX/VM/hCguVls34MS8Uw1xGt48YV42', 'nickname_182280', 'Email...', 'canthu', 'Bùi Văn 449', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:20', 'Chưa xác minh'),
(130, '0901234450', '$2y$10$svHId.o0YQkLgwTDYw7UMuuvmJTyKpQtAiPNkrPTvRX9hYlzP4vj2', 'nickname_386101', 'Email...', 'canthu', 'Bùi Văn 450', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:26', 'Chưa xác minh'),
(131, '0901234451', '$2y$10$ldNFHhch5GTcz8AYlYKiAujeHHg8M4vPYAspGvBWX171jGCoWztfa', 'nickname_833612', 'Email...', 'canthu', 'Bùi Văn 451', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:34', 'Chưa xác minh'),
(132, '0901234452', '$2y$10$v0.T5qI8uGYs5kZ7bmgi9.l5C2jYi2feUFEzI0POUyHy.eSRAMBfq', 'nickname_441984', 'Email...', 'canthu', 'Bùi Văn 452', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:40', 'Chưa xác minh'),
(133, '0901234453', '$2y$10$Pbyq10bU4I/Z6tgzxfitU.HzI.7IC7n0kndr/NAlv9KNn1n2xEbyK', 'nickname_660785', 'Email...', 'canthu', 'Bùi Văn 453', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:45', 'Chưa xác minh'),
(134, '0901234454', '$2y$10$WCclB9IeMVLdvOs/m4scqe/LzuEiVQcS.o2pfkvDGCddOg.pUMT/e', 'nickname_970191', 'Email...', 'canthu', 'Bùi Văn 453', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:48', 'Chưa xác minh'),
(135, '0901234455', '$2y$10$4UaTs0Keuz0Z7QT15YsL9u9WNEWyp1tCjnIhRiGlP7SXwYU.bauNy', 'nickname_790516', 'Email...', 'canthu', 'Bùi Văn 453', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:35:57', 'Chưa xác minh'),
(136, '0901234456', '$2y$10$8qKqOBnif2E9x0JQJXBOoOgU6ETII.CaEnzT2X1Px6FNhJz4HvpcK', 'nickname_525963', 'Email...', 'canthu', 'Bùi Văn 456', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:36:17', 'Chưa xác minh'),
(137, '0901234457', '$2y$10$WMnl066ofv8Val4yGa.Yx.421xT5HOFMUtNM2xG/82W6Z2GNKzvvK', 'nickname_303512', 'Email...', 'canthu', 'Bùi Văn 457', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:36:23', 'Chưa xác minh'),
(138, '0901234459', '$2y$10$wbVSxgdTtBj545j.ttsTgedMyAs1/nk2t4eOdu1q37f4HYpkrEATS', 'nickname_233463', 'Email...', 'canthu', 'Bùi Văn 459', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:36:38', 'Chưa xác minh'),
(139, '0901234460', '$2y$10$vUthi4.wVrFRzvAIeicl9ejzyqzoNycP0mCpbyHz3hMmu4tlxtrS2', 'nickname_891722', 'Email...', 'canthu', 'Bùi Văn 460', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:36:46', 'Chưa xác minh'),
(140, '0901234461', '$2y$10$GoPDSg650ankdXBnDK1WUOkQGrTr8DOoStzYkz/ZJty8gLhDn0A/a', 'nickname_151667', 'Email...', 'canthu', 'Bùi Văn 461', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:36:50', 'Chưa xác minh'),
(141, '0901234462', '$2y$10$2KLspVbxP3RfBBshtJT0VO/6KdZY65jLRSwBaNULXdjmPf9WG1AqW', 'nickname_990389', 'Email...', 'canthu', 'Bùi Văn 462', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:36:55', 'Chưa xác minh'),
(142, '0901234463', '$2y$10$Y1lNip.72xqlJhW7z8iXfe9NrvzFGF4CZDy7A02nc2SU/80VYBvhm', 'nickname_290305', 'Email...', 'canthu', 'Bùi Văn 463', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:37:00', 'Chưa xác minh'),
(143, '0901234464', '$2y$10$p8hJOBv9lGX6jha9yeIafenDoKd8vDDVD7un5GRgxzbtN.zjvCkdy', 'nickname_499969', 'Email...', 'canthu', 'Bùi Văn 464', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:37:05', 'Chưa xác minh'),
(144, '0901234465', '$2y$10$2INwIjTSyRnx4otbxcRoBOYRj3WfWlxD4GXT67XIgvt1brh2rkUcy', 'nickname_735038', 'Email...', 'canthu', 'Bùi Văn 465', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-16 12:37:10', 'Chưa xác minh'),
(145, '0911111111', '$2y$10$58xHhmjSnSrjTkFYya8y6O5QobsA4ywuqhgaBpvOYIMrIAD3a1joe', 'nickname_695551', 'Email...', 'canthu', 'nguyen thanh', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-17 14:29:53', 'Chưa xác minh'),
(146, '0911111432', '$2y$10$GFQGnlm1flHUWErR0pq0Ie99hoR53IJwcK568t5KEHoHhSlvng/BS', 'nickname_142691', 'Email...', 'canthu', 'nguyen thanh', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-17 14:30:15', 'Chưa xác minh'),
(147, '0911111433', '$2y$10$sxOLoezl2RGxkyOUX4.AoeLPKz5hF7SXzLT8DLJdCnruyDH7o6yP2', 'nickname_698804', 'Email...', 'canthu', 'Huynh Thi 434', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-17 14:30:43', 'Chưa xác minh'),
(148, '0911111434', '$2y$10$jGnXPyY7EYGjD9BYpfjN.u0GV0L.P9q8/QGcpR/Gals60WUXIqjze', 'nickname_279433', 'Email...', 'canthu', 'nguyen thanh', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:52:58', 'Chưa xác minh'),
(149, '0911111435', '$2y$10$o41wY9YGbHdbCd7RLFjgN.nuU2Tr71SZisP1rxFo2F1pqeOHjkGF2', 'nickname_600412', 'Email...', 'canthu', 'Tran thi 435', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:53:20', 'Chưa xác minh'),
(150, '0911111436', '$2y$10$n.bGzKvTZFqs7UeB8bEps.zJCXPXFiD26FbiR2H6qBbMOQTA1l/Bq', 'nickname_382053', 'Email...', 'canthu', 'Tran thi 436', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:53:25', 'Chưa xác minh'),
(151, '0911111437', '$2y$10$OoCGgrxR9lvBWQXYcmAAT.JaJ1j3c.5Gvk.Er1aYt0EthJgu6Gb16', 'nickname_233190', 'Email...', 'canthu', 'Tran thi 437', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:53:31', 'Chưa xác minh'),
(152, '0911111439', '$2y$10$VQ6tDSXX5tbuzn0dsyt/Cer6BBDw5TS4580w66S1xiHLxcncDQY/C', 'nickname_235432', 'Email...', 'canthu', 'Tran thi 439', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:53:53', 'Chưa xác minh'),
(153, '0911111440', '$2y$10$6GmVhlVwzZoMwN.daEGmf.LBiaobT5xy7f1.NymgFVlEO7xwuGkfi', 'nickname_583850', 'Email...', 'canthu', 'Tran thi 440', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:21', 'Chưa xác minh'),
(154, '0911111441', '$2y$10$as/mVxUdvlclCuCzfqWrteogbVX7854E3jkhZdmvUUUciQAZZ67l.', 'nickname_618864', 'Email...', 'canthu', 'Tran thi 441', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:31', 'Chưa xác minh'),
(155, '0911111442', '$2y$10$.t1v1LPs/j6BhItiFsaxcO9xz3Qu7wP8sJKLnQ1w40KkphKGipJ/K', 'nickname_664168', 'Email...', 'canthu', 'Tran thi 442', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:36', 'Chưa xác minh'),
(156, '0911111443', '$2y$10$bveKstG8AAIe.n1JQE3MLuuDlXy7rLaWUIXM9vGjWs0Hop6NTpyQG', 'nickname_896248', 'Email...', 'canthu', 'Tran thi 443', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:41', 'Chưa xác minh'),
(157, '0911111444', '$2y$10$5z8YJWtrJcAoB6qeDQuN7.ud7a3B7vXqwV3bjorZPOdzRKrlxyyFa', 'nickname_491880', 'Email...', 'canthu', 'Tran thi 444', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:46', 'Chưa xác minh'),
(158, '0911111445', '$2y$10$UFcy9P0LXx1aNTkc8MoHDeIK3U76utvBEgtvhdK7D22He2UGY9PAe', 'nickname_741818', 'Email...', 'canthu', 'Tran thi 445', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:51', 'Chưa xác minh'),
(159, '0911111446', '$2y$10$ZTJzAu46tIpcQq.lXAlSyuRioutrKgYzuY18Ft7eg4TY6z3EeF1xO', 'nickname_929429', 'Email...', 'canthu', 'Tran thi 446', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:54:58', 'Chưa xác minh'),
(160, '0911111447', '$2y$10$vJO/HPC8.tK5WgaUuz1W3O77tQh8C0azdi7wwv3mIMrrVo7IhS3cO', 'nickname_649536', 'Email...', 'canthu', 'Tran thi 447', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-18 08:55:04', 'Chưa xác minh'),
(161, '0911111113', '$2y$10$TlX1JkgOWyYpu38l4dUmxOKlV4lDJGsZxNaKVWvu1DIYMhQMbEeP6', 'nickname_648506', 'Email...', 'canthu', 'Bui Van 113', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:17:47', 'Chưa xác minh'),
(162, '0911111114', '$2y$10$7YPaX6mU9VdRRwYyKOGbWe/89AFED3.k.oActqQEXIoNntfUrWqQe', 'nickname_198675', 'Email...', 'canthu', 'Bui Van 114', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:17:52', 'Chưa xác minh'),
(163, '0911111115', '$2y$10$h28P4YiRginuV2itNpmUM.GGkWnd7sYh5XjPLB2mXRzgSRKw2ZcNa', 'nickname_171835', 'Email...', 'canthu', 'Bui Van 115', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:17:57', 'Chưa xác minh'),
(164, '0911111116', '$2y$10$uFfYDC5qPPcDVXdXkk5eLecRSDYlqhIKyM87EFr7u03I.GxE4p8ve', 'nickname_264447', 'Email...', 'canthu', 'Bui Van 116', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:02', 'Chưa xác minh'),
(165, '0911111117', '$2y$10$6c/2UdbbroYlgpMIa5upsuLL7/6eNkqghs3aDr0UIgxF0j/5h.mrK', 'nickname_464658', 'Email...', 'canthu', 'Bui Van 117', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:06', 'Chưa xác minh'),
(166, '0911111118', '$2y$10$hirjoRfT256r3bdmUXykwuR4.BcOyr.Zjr/VnyVjezjyOQX9dkM.e', 'nickname_788425', 'Email...', 'canthu', 'Bui Van 118', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:17', 'Chưa xác minh'),
(167, '0911111119', '$2y$10$sIQrCCRo/9Ef1WQoDnIEf.zc.XCoKkzH1XHOFiipmWV6l7Vnu1qYO', 'nickname_953455', 'Email...', 'canthu', 'Bui Van 119', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:30', 'Chưa xác minh'),
(168, '0911111120', '$2y$10$Seuw7jCF.hbM4VBAiJWTzunXKdsswFjKor8FZ1t1uMWAbJ5hUDTH6', 'nickname_894344', 'Email...', 'canthu', 'Bui Van 120', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:44', 'Chưa xác minh'),
(169, '0911111121', '$2y$10$cCPewW.eWVYKTOq5ejtYGOF8qVutSZYA7KiNApwvD.IB2dSWuF/KC', 'nickname_162290', 'Email...', 'canthu', 'Bui Van 121', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:49', 'Chưa xác minh'),
(170, '0911111122', '$2y$10$UlH8dhHjEM17bmG/d22UveHGZ6CM0uXPkSY8AqkedFwfZdHPXmyK2', 'nickname_997796', 'Email...', 'canthu', 'Bui Van 122', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:18:54', 'Chưa xác minh'),
(171, '0911111123', '$2y$10$1THMK9rX8JyfjX6sBt47fuFXBgqEk2SMuTj4JuGp46alUlIuXD5CG', 'nickname_951694', 'Email...', 'canthu', 'Bui Van 123', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:19:00', 'Chưa xác minh'),
(172, '0911111124', '$2y$10$jQ4Ms3x74eG6dR4A1qMJE.7qJGKjyYekguBNf1NzYRY5iAncJTu7S', 'nickname_159255', 'Email...', 'canthu', 'Bui Van 124', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-07-21 13:19:06', 'Chưa xác minh'),
(173, '0901234508', '$2y$10$Tjy28KnimCMokrBZrzAfIOHH5wy4siY2sOG/JlXw5vEWFXSxmCK.W', 'nickname_437062', 'Email...', 'canthu', 'Nguyễn Văn 006', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-08-20 21:04:49', 'Chưa xác minh'),
(174, '0901234020', '$2y$10$JCcmx3.kPYnQAFf55SYP4uZnRW.Udeu4bvlOnQNts53GOAWKYOW4O', 'nickname_866225', 'Email...', 'canthu', 'Nguyễn Văn 20', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-08-20 21:45:47', 'Chưa xác minh');
INSERT INTO `users` (`id`, `phone`, `password`, `nickname`, `email`, `vai_tro`, `full_name`, `bank_account`, `bank_info`, `qr_image_path`, `CCCD_number`, `balance`, `balance_ref`, `ref_code`, `user_exp`, `cnt_ho`, `cnt_xa`, `cnt_giai`, `cnt_game`, `user_lever`, `user_note`, `review_status`, `created_at`, `status`) VALUES
(175, '0922222225', '$2y$10$.unkdNaTSzu5plPI1QJYPuf5GttrRW/aMME6Smj2lu0TqCtOBcE9q', 'nickname_819212', 'Email...', 'canthu', 'Nguyễn Văn 006', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 0, 'Ghi chú user...', 'no', '2025-08-21 18:24:22', 'Chưa xác minh'),
(176, '0343808951', '$2y$10$E72yi0WhZDU2IL5A27JLZ.8iGsGEwjEqUeTsop2HUkMQSnK3LAHry', 'ct_8951_909', 'Email...', 'canthu', '0343808951', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-25 13:31:41', 'Chưa xác minh'),
(178, '0922222242', '$2y$10$Yib0m.GL7kUYl8D8.u5lc.jwE0RbUxzQ0rxaFxEBarvU6chOjfzba', 'ct_2242_861', 'Email...', 'canthu', 'Huynh Thi 434', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-25 14:34:02', 'Chưa xác minh'),
(179, '0922222252', '$2y$10$bVwLshuLMvuW1YyxcfxAEOrLEmBuyfRphQ..OD3Q1qzaVA3r5WYx2', 'ct_2252_683', 'Email...', 'canthu', 'Huynh Thi 434', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-25 14:35:01', 'Chưa xác minh'),
(180, '0922233252', '$2y$10$4KX0O4l1lNQlhXHu5WLiZuf5tasrPJNIUZTBmnSovAwdz9WpdUFr.', 'ct_3252_502', 'Email...', 'canthu', 'Huynh Thi 434', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-25 14:35:12', 'Chưa xác minh'),
(181, '0935192070', '$2y$10$qQlOfMCMkVi4ykhjIVcruue.5q0ekwmNdY17CVhD1Pp7ikQP0.g/e', 'ct_2070_998', 'Email...', 'canthu', 'Huynh Thi 434', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-25 14:35:54', 'Chưa xác minh'),
(182, '0911111140', '$2y$10$VyfKTrQAZUA3Lp4pPpCun.BDauAM.YiohD1C8CoygGNS.7tnICoOK', 'NickPOS_1140_950', 'Email...', 'canthu', 'Huynh Thi 140', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-27 23:18:20', 'Chưa xác minh'),
(184, '0911111143', '$2y$10$Vv4ZZJ.1yOhRHC6T6k/2p.ykBdLKPVLhewEDNTmDICAp1CkQrU3jK', 'NickPOS_1143_631', 'Email...', 'canthu', 'Huynh Thi 143', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-27 23:38:03', 'Chưa xác minh'),
(185, '0911111145', '$2y$10$pB0MhUQj/GeNGythMBIN6OdAJkRRJx6y7dJAX5b7ZAoBiwSFftPzm', 'POS-1145_223', 'Email...', 'canthu', 'Huynh Thi 145', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-27 23:39:41', 'Chưa xác minh'),
(186, '0911111147', '$2y$10$F4AU9hM7hCU7pvBaOXwm9OV0Z.qsvn3oc9IGdbvoXEaPLgD20Tu6.', 'POS-1147-765', 'Email...', 'canthu', 'Huynh Thi 147', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-27 23:40:14', 'Chưa xác minh'),
(187, '0922222299', '$2y$10$d0675xyFvtblWVsmYMYpbuW./iwlPOLSiycHssMvt055dVaJAbjwO', 'POS-2299-871', 'Email...', 'canthu', '0922222299', 'Số tài khoản...', NULL, NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-28 17:10:51', 'Chưa xác minh'),
(188, '0911111149', '$2y$10$aX1gnEQytizpZVWmAoyzr.i6TRv6ISyNV.TkMhgVh1mJlpS0E1D1u', 'POS-1149-605', 'Email...', 'canthu', 'Le van tam', 'Số tài khoản...', '970436-Vietcombank', NULL, 'Số CCCD...', 100000.00, 50000.00, '0935192079', 10, 0, 0, 0, 0, 1, 'Ghi chú user...', 'no', '2025-08-28 17:25:36', 'Chưa xác minh');

-- --------------------------------------------------------

--
-- Table structure for table `user_balance_logs`
--

CREATE TABLE `user_balance_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('nap','rut','booking_pay','booking_refund','booking_hold','booking_received','game_pay','game_refund','game_hold','game_received','giai_pay','giai_refund','giai_hold','giai_received') NOT NULL,
  `amount` int NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `ref_no` varchar(255) NOT NULL,
  `balance_before` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_balance_logs`
--

INSERT INTO `user_balance_logs` (`id`, `user_id`, `type`, `amount`, `note`, `created_at`, `ref_no`, `balance_before`, `balance_after`) VALUES
(1, 1, 'booking_refund', 35000, 'Hoàn tiền giữ chỗ cho booking #45', '2025-06-14 21:38:08', '', 0.00, 0.00),
(2, 1, 'booking_pay', 115000, 'Thanh toán booking #45', '2025-06-14 21:38:08', '', 0.00, 0.00),
(3, 2, 'booking_pay', 115000, 'Nhận thanh toán từ booking #45', '2025-06-14 21:38:08', '', 0.00, 0.00),
(4, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #44', '2025-06-14 21:45:27', '', 0.00, 0.00),
(5, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #45', '2025-06-14 21:46:32', '', 0.00, 0.00),
(6, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #40', '2025-06-14 21:53:37', '', 0.00, 0.00),
(7, 1, 'booking_pay', 90000, 'Thanh toán booking #40', '2025-06-14 21:53:37', '', 0.00, 0.00),
(8, 2, 'booking_pay', 90000, 'Nhận thanh toán từ booking #40', '2025-06-14 21:53:37', '', 0.00, 0.00),
(9, 1, 'booking_refund', 35000, 'Hoàn tiền giữ chỗ cho booking #46', '2025-06-14 22:06:28', '', 0.00, 0.00),
(10, 2, 'booking_pay', 10000, 'Hoàn lại tiền booking #46', '2025-06-14 22:06:28', '', 0.00, 0.00),
(11, 1, 'booking_pay', 10000, 'Nhận lại tiền từ booking #46', '2025-06-14 22:06:28', '', 0.00, 0.00),
(12, 1, 'booking_refund', 35000, 'Hoàn tiền giữ chỗ cho booking #47', '2025-06-14 22:19:38', '', 0.00, 0.00),
(13, 2, 'booking_pay', 830000, 'Hoàn lại tiền booking #47', '2025-06-14 22:19:38', '', 0.00, 0.00),
(14, 1, 'booking_pay', 830000, 'Nhận lại tiền từ booking #47', '2025-06-14 22:19:38', '', 0.00, 0.00),
(15, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #48', '2025-06-14 22:24:19', '', 0.00, 0.00),
(16, 2, 'booking_pay', -510000, 'Hoàn lại tiền booking #48', '2025-06-14 22:24:19', '', 0.00, 0.00),
(17, 1, 'booking_pay', 510000, 'Nhận lại tiền từ booking #48', '2025-06-14 22:24:19', '', 0.00, 0.00),
(18, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #49', '2025-06-14 22:30:03', '', 0.00, 0.00),
(19, 1, 'booking_pay', -240000, 'Thanh toán booking #49', '2025-06-14 22:30:03', '', 0.00, 0.00),
(20, 2, 'booking_pay', 240000, 'Nhận thanh toán từ booking #49', '2025-06-14 22:30:03', '', 0.00, 0.00),
(21, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #50', '2025-06-14 22:32:42', '', 0.00, 0.00),
(22, 2, 'booking_pay', -450000, 'Hoàn lại tiền booking #50', '2025-06-14 22:32:42', '', 0.00, 0.00),
(23, 1, 'booking_pay', 450000, 'Nhận lại tiền từ booking #50', '2025-06-14 22:32:42', '', 0.00, 0.00),
(24, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #60', '2025-06-14 23:27:22', '', 0.00, 0.00),
(25, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #61', '2025-06-14 23:29:56', '', 0.00, 0.00),
(26, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #60', '2025-06-14 23:31:46', '', 0.00, 0.00),
(27, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #62', '2025-06-14 23:32:23', '', 0.00, 0.00),
(28, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #63', '2025-06-14 23:36:39', '', 0.00, 0.00),
(29, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #64', '2025-06-14 23:37:20', '', 0.00, 0.00),
(30, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #61', '2025-06-14 23:40:47', '', 0.00, 0.00),
(31, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #65', '2025-06-14 23:41:07', '', 0.00, 0.00),
(32, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #65', '2025-06-14 23:41:49', '', 0.00, 0.00),
(33, 2, 'booking_pay', -325000, 'Hoàn lại tiền booking #65', '2025-06-14 23:41:49', '', 0.00, 0.00),
(34, 1, 'booking_pay', 325000, 'Nhận lại tiền từ booking #65', '2025-06-14 23:41:49', '', 0.00, 0.00),
(35, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #64', '2025-06-14 23:43:16', '', 0.00, 0.00),
(36, 1, 'booking_pay', -300000, 'Thanh toán booking #64', '2025-06-14 23:43:16', '', 0.00, 0.00),
(37, 2, 'booking_pay', 300000, 'Nhận thanh toán từ booking #64', '2025-06-14 23:43:16', '', 0.00, 0.00),
(38, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #66', '2025-06-14 23:43:35', '', 0.00, 0.00),
(39, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #66', '2025-06-14 23:43:53', '', 0.00, 0.00),
(40, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #67', '2025-06-15 13:49:02', '', 0.00, 0.00),
(41, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #67', '2025-06-15 14:22:42', '', 0.00, 0.00),
(42, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #68', '2025-06-15 14:24:03', '', 0.00, 0.00),
(43, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #68', '2025-06-15 14:26:22', '', 0.00, 0.00),
(44, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #69', '2025-06-15 14:45:32', '', 0.00, 0.00),
(45, 2, 'booking_pay', -330000, 'Hoàn lại tiền booking #69', '2025-06-15 14:46:55', '', 0.00, 0.00),
(46, 1, 'booking_pay', 330000, 'Nhận lại tiền từ booking #69', '2025-06-15 14:46:55', '', 0.00, 0.00),
(47, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #69', '2025-06-15 14:46:55', '', 0.00, 0.00),
(48, 1, 'booking_pay', 50000, 'Thanh toán booking #63', '2025-06-15 16:07:12', '', 0.00, 0.00),
(49, 2, 'booking_pay', 50000, 'Nhận thanh toán từ booking #63', '2025-06-15 16:07:12', '', 0.00, 0.00),
(50, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #63', '2025-06-15 16:07:12', '', 0.00, 0.00),
(51, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #70', '2025-06-15 16:13:36', '', 0.00, 0.00),
(52, 2, 'booking_pay', -200000, 'Hoàn lại tiền booking #70 user #1 ', '2025-06-15 16:14:04', '', 0.00, 0.00),
(53, 1, 'booking_pay', 200000, 'Nhận lại tiền từ booking #70', '2025-06-15 16:14:04', '', 0.00, 0.00),
(54, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #70', '2025-06-15 16:14:04', '', 0.00, 0.00),
(55, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #71', '2025-06-15 16:22:23', '', 0.00, 0.00),
(56, 1, 'booking_pay', 115000, 'Đã trừ tiền cá, thanh toán booking #71', '2025-06-15 16:23:02', '', 0.00, 0.00),
(57, 2, 'booking_pay', 115000, 'Nhận thanh toán từ booking #71', '2025-06-15 16:23:02', '', 0.00, 0.00),
(58, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #71', '2025-06-15 16:23:02', '', 0.00, 0.00),
(59, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #72', '2025-06-15 16:34:18', '', 0.00, 0.00),
(60, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #73', '2025-06-15 16:34:36', '', 0.00, 0.00),
(61, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #74', '2025-06-15 16:34:49', '', 0.00, 0.00),
(62, 2, 'booking_pay', -10000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu #72 user #1', '2025-06-15 16:35:34', '', 0.00, 0.00),
(63, 1, 'booking_pay', 10000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #72', '2025-06-15 16:35:34', '', 0.00, 0.00),
(64, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #72', '2025-06-15 16:35:34', '', 0.00, 0.00),
(65, 2, 'booking_pay', -10000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu #73 user #1', '2025-06-15 16:41:22', '', 0.00, 0.00),
(66, 1, 'booking_pay', 10000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #73', '2025-06-15 16:41:22', '', 0.00, 0.00),
(67, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #73', '2025-06-15 16:41:22', '', 0.00, 0.00),
(68, 1, 'booking_pay', -75000, 'Đã trừ tiền cá, thanh toán booking #74', '2025-06-15 16:46:01', '', 0.00, 0.00),
(69, 2, 'booking_pay', 75000, 'Nhận thanh toán từ booking #74', '2025-06-15 16:46:01', '', 0.00, 0.00),
(70, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #74', '2025-06-15 16:46:01', '', 0.00, 0.00),
(71, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #75', '2025-06-15 16:46:47', '', 0.00, 0.00),
(72, 2, 'booking_pay', -135000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu #75 user #1  Số dư: 4555000', '2025-06-15 16:47:03', '', 0.00, 0.00),
(73, 1, 'booking_pay', 135000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #75', '2025-06-15 16:47:03', '', 0.00, 0.00),
(74, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #75', '2025-06-15 16:47:03', '', 0.00, 0.00),
(75, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #76', '2025-06-15 16:54:03', '', 0.00, 0.00),
(76, 2, 'booking_pay', -10000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #76 | user: #1 || Số dư: 4545000', '2025-06-15 16:54:27', '', 0.00, 0.00),
(77, 1, 'booking_pay', 10000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #76 | số dư: 455000', '2025-06-15 16:54:27', '', 0.00, 0.00),
(78, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #76', '2025-06-15 16:54:27', '', 0.00, 0.00),
(79, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #77', '2025-06-15 16:56:53', '', 0.00, 0.00),
(80, 2, 'booking_pay', -10000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #77 | user: #1 || Số dư: 4535000', '2025-06-15 16:57:27', '', 0.00, 0.00),
(81, 1, 'booking_pay', 10000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #77 | số dư: 513000', '2025-06-15 16:57:27', '', 0.00, 0.00),
(82, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #77', '2025-06-15 16:57:27', '', 0.00, 0.00),
(83, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #78', '2025-06-15 16:58:48', '', 0.00, 0.00),
(84, 2, 'booking_pay', -75000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #78 | user: #1 || Số dư: 4460000', '2025-06-15 16:59:08', '', 0.00, 0.00),
(85, 1, 'booking_pay', 75000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #78 | số dư: 567000', '2025-06-15 16:59:08', '', 0.00, 0.00),
(86, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #78', '2025-06-15 16:59:08', '', 0.00, 0.00),
(87, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #79', '2025-06-15 17:00:27', '', 0.00, 0.00),
(88, 2, 'booking_pay', -260000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #79 | user: #1 || Số dư: 4200000', '2025-06-15 17:00:44', '', 0.00, 0.00),
(89, 1, 'booking_pay', 260000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #79 | số dư: 816000', '2025-06-15 17:00:44', '', 0.00, 0.00),
(90, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #79', '2025-06-15 17:00:44', '', 0.00, 0.00),
(91, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #80', '2025-06-15 17:08:44', '', 0.00, 0.00),
(92, 2, 'booking_pay', -10000, 'Chủ hồ bị bẻ răng, hoàn tiền cá cho vé câu: #80 | user: #1 || Số dư: 4190000', '2025-06-15 17:09:01', '', 0.00, 0.00),
(93, 1, 'booking_pay', 10000, 'Bẻ răng chủ hồ, nhận tiền cá từ vé câu #80 | số dư: 776000', '2025-06-15 17:09:01', '', 0.00, 0.00),
(94, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ cho booking #80 | Balance: 815000', '2025-06-15 17:09:01', '', 0.00, 0.00),
(95, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #81', '2025-06-15 17:59:03', '', 0.00, 0.00),
(96, 2, 'booking_pay', -10000, 'Vé câu #81, Chủ hồ bị bẻ răng | Số dư cuối #81: 4180000', '2025-06-15 18:00:44', '', 0.00, 0.00),
(97, 1, 'booking_pay', 10000, 'Vé câu #81, nhận tiền bẻ răng chủ hồ | Số dư cuối #81: 775000', '2025-06-15 18:00:44', '', 0.00, 0.00),
(98, 1, 'booking_refund', 39000, 'Hoàn tiền giữ chỗ booking #81 sau khi trừ phí & VAT ||  Số dư: 814000', '2025-06-15 18:00:44', '', 0.00, 0.00),
(99, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #82', '2025-06-15 18:03:56', '', 0.00, 0.00),
(100, 1, 'booking_pay', -115000, 'Vé câu #82, bị chủ hồ bẻ răng | Số dư cuối: 649000', '2025-06-15 18:04:16', '', 0.00, 0.00),
(101, 2, 'booking_pay', 115000, 'Nhận thanh toán từ booking #82', '2025-06-15 18:04:16', '', 0.00, 0.00),
(102, 1, 'booking_refund', 39000, 'Vé câu #82, hoàn phí giữ chổ || Số dư cuối: 688000', '2025-06-15 18:04:16', '', 0.00, 0.00),
(103, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #83', '2025-06-15 18:09:36', '', 0.00, 0.00),
(104, 1, 'booking_pay', -115000, 'Vé câu #83, bị chủ hồ bẻ răng | Số dư cuối: 523000', '2025-06-15 18:09:54', '', 0.00, 0.00),
(105, 2, 'booking_pay', 115000, 'Vé câu #83, bẻ răng Cần thủ | Số dư cuối #83: 4410000', '2025-06-15 18:09:54', '', 0.00, 0.00),
(106, 1, 'booking_refund', 39000, 'Vé câu #83, hoàn phí giữ chổ || Số dư cuối: 562000', '2025-06-15 18:09:54', '', 0.00, 0.00),
(107, 1, 'booking_hold', -50000, 'Đặt booking giữ chỗ #84', '2025-06-15 18:12:01', '', 0.00, 0.00),
(108, 1, 'booking_pay', -115000, 'Vé câu #84, bị chủ hồ bẻ răng | Số dư cuối: 397000', '2025-06-15 18:12:17', '', 0.00, 0.00),
(109, 2, 'booking_pay', 115000, 'Vé câu #84, bẻ răng Cần thủ | Số dư cuối #84: 4525000', '2025-06-15 18:12:17', '', 0.00, 0.00),
(110, 1, 'booking_refund', 39000, 'Vé câu #84, hoàn phí giữ chổ || Số dư cuối: 436000', '2025-06-15 18:12:17', '', 0.00, 0.00),
(111, 1, 'booking_hold', -50000, 'Vé câu #85, Đặt booking giữ chỗ | Số dư cuối: 436000', '2025-06-15 18:16:22', '', 0.00, 0.00),
(112, 1, 'booking_hold', -50000, 'Vé câu #86: Đặt booking giữ chỗ | Số dư cuối: 336000', '2025-06-15 18:18:59', '', 0.00, 0.00),
(113, 2, 'booking_pay', -135000, 'Vé câu #85: Chủ hồ bị bẻ răng | Số dư cuối #85: 4390000', '2025-06-15 18:22:25', '', 0.00, 0.00),
(114, 1, 'booking_pay', 135000, 'Vé câu #85: Nhận tiền bẻ răng chủ hồ | Số dư cuối #85: 471000', '2025-06-15 18:22:25', '', 0.00, 0.00),
(115, 1, 'booking_refund', 39000, 'Vé câu #85: Hoàn phí giữ chổ || Số dư cuối: 510000', '2025-06-15 18:22:25', '', 0.00, 0.00),
(116, 1, 'booking_refund', 39000, 'Hoàn tiền huỷ booking #86', '2025-06-15 18:35:18', '', 0.00, 0.00),
(117, 1, 'booking_hold', -50000, 'Vé câu #87: Đặt booking giữ chỗ | Số dư cuối: 538000', '2025-06-15 18:37:58', '', 0.00, 0.00),
(118, 1, 'booking_refund', 39000, 'Vé câu #booking_id: Hoàn phí giữ chổ || Số dư cuối: 577000', '2025-06-15 18:38:33', '', 0.00, 0.00),
(119, 1, 'booking_hold', -50000, 'Vé câu #88: Đặt booking giữ chỗ | Số dư cuối: 527000', '2025-06-15 18:39:41', '', 0.00, 0.00),
(120, 1, 'booking_refund', 39000, 'Vé câu #88: Hoàn phí giữ chổ || Số dư cuối: 566000', '2025-06-15 18:39:56', '', 0.00, 0.00),
(121, 1, 'booking_hold', -50000, 'Vé câu #89: Đặt booking giữ chỗ | Số dư cuối: 516000', '2025-06-15 18:41:10', '', 0.00, 0.00),
(122, 2, 'booking_pay', -260000, 'Vé câu #89: Chủ hồ bị bẻ răng | Số dư cuối #89: 4130000', '2025-06-15 18:41:37', '', 0.00, 0.00),
(123, 1, 'booking_pay', 260000, 'Vé câu #89: Nhận tiền bẻ răng chủ hồ | Số dư cuối #89: 776000', '2025-06-15 18:41:37', '', 0.00, 0.00),
(124, 1, 'booking_refund', 39000, 'Vé câu #89: Hoàn phí giữ chổ || Số dư cuối: 815000', '2025-06-15 18:41:37', '', 0.00, 0.00),
(125, 1, 'booking_hold', -50000, 'Vé câu #90: Đặt booking giữ chỗ | Số dư cuối: 765000', '2025-06-15 19:02:24', '', 0.00, 0.00),
(126, 2, 'booking_pay', -260000, 'Vé câu #90: Chủ hồ bị bẻ răng | Số dư cuối #90: 3870000', '2025-06-15 19:03:03', '', 0.00, 0.00),
(127, 2, 'booking_pay', -260000, 'Vé câu #90: Chủ hồ bị bẻ răng | Số dư cuối #90: 3610000', '2025-06-15 19:05:44', '', 0.00, 0.00),
(128, 1, 'booking_pay', 260000, 'Vé câu #90: Nhận tiền bẻ răng chủ hồ | Số dư cuối #90: 1285000', '2025-06-15 19:05:44', '', 0.00, 0.00),
(129, 1, 'booking_refund', 39000, 'Vé câu #90: Hoàn phí giữ chổ || Số dư cuối: 1324000', '2025-06-15 19:05:44', '', 0.00, 0.00),
(130, 1, 'booking_hold', -50000, 'Vé câu #91: Đặt booking giữ chỗ | Số dư cuối: 1274000', '2025-06-15 19:12:02', '', 0.00, 0.00),
(131, 1, 'booking_refund', 39000, 'Vé câu #91: Hoàn phí giữ chổ || Số dư cuối: 1313000', '2025-06-15 19:12:26', '', 0.00, 0.00),
(132, 1, 'booking_hold', -50000, 'Vé câu #92: Đặt booking giữ chỗ | Số dư cuối: 1263000', '2025-06-15 19:17:54', '', 0.00, 0.00),
(133, 1, 'booking_refund', 39000, 'Vé câu #92: Hoàn phí giữ chổ || Số dư cuối: 1302000', '2025-06-15 19:18:14', '', 0.00, 0.00),
(134, 1, 'booking_hold', -50000, 'Vé câu #93: Đặt booking giữ chỗ | Số dư cuối: 1252000', '2025-06-15 19:20:01', '', 0.00, 0.00),
(135, 2, 'booking_pay', -135000, 'Vé câu #93: Chủ hồ bị bẻ răng | Số dư cuối #93: 3475000', '2025-06-15 19:20:17', '', 0.00, 0.00),
(136, 1, 'booking_received', 135000, 'Vé câu #93: Nhận tiền bẻ răng chủ hồ | Số dư cuối #93: 1387000', '2025-06-15 19:20:17', '', 0.00, 0.00),
(137, 1, 'booking_refund', 39000, 'Vé câu #93: Hoàn phí giữ chổ || Số dư cuối: 1426000', '2025-06-15 19:20:17', '', 0.00, 0.00),
(138, 1, 'booking_hold', -50000, 'Vé câu #94: Đặt booking giữ chỗ | Số dư cuối: 1376000', '2025-06-15 19:21:53', '', 0.00, 0.00),
(139, 1, 'booking_refund', 39000, 'Vé câu #94: Hoàn phí giữ chổ || Số dư cuối: 1415000', '2025-06-15 19:22:22', '', 0.00, 0.00),
(140, 1, 'booking_hold', -50000, 'Vé câu #95: Đặt booking giữ chỗ | Số dư cuối: 1365000', '2025-06-15 19:22:38', '', 0.00, 0.00),
(141, 1, 'booking_pay', -230000, 'Vé câu #95: Bị chủ hồ bẻ răng | Số dư cuối: 1135000', '2025-06-15 19:22:56', '', 0.00, 0.00),
(142, 2, 'booking_received', 230000, 'Vé câu #95: Bẻ răng Cần thủ | Số dư cuối #95: 3705000', '2025-06-15 19:22:56', '', 0.00, 0.00),
(143, 1, 'booking_refund', 39000, 'Vé câu #95: Hoàn phí giữ chổ || Số dư cuối: 1174000', '2025-06-15 19:22:56', '', 0.00, 0.00),
(144, 1, 'booking_hold', -50000, 'Vé câu #96: Đặt booking giữ chỗ | Số dư cuối: 1124000 vnd', '2025-06-15 19:27:01', '', 0.00, 0.00),
(145, 1, 'booking_pay', -60000, 'Vé câu #96: Bị chủ hồ bẻ răng | Số dư cuối: 1064000 vnd', '2025-06-15 19:27:29', '', 0.00, 0.00),
(146, 2, 'booking_received', 60000, 'Vé câu #96: Bẻ răng Cần thủ | Số dư cuối #96: 3765000 vnd', '2025-06-15 19:27:29', '', 0.00, 0.00),
(147, 1, 'booking_refund', 39000, 'Vé câu #96: Hoàn phí giữ chổ || Số dư cuối: 1103000 vnd', '2025-06-15 19:27:29', '', 0.00, 0.00),
(148, 1, 'booking_hold', -50000, 'Vé câu #97: Đặt booking giữ chỗ | Số dư cuối: 1053000 vnd', '2025-06-15 19:30:59', '', 0.00, 0.00),
(149, 1, 'booking_hold', -50000, 'Vé câu #98: Đặt booking giữ chỗ | Số dư cuối: 1003000 vnd', '2025-06-15 19:32:26', '', 0.00, 0.00),
(150, 1, 'booking_pay', -375000, 'Vé câu #98: Bị chủ hồ bẻ răng | Số dư cuối: 628000 vnd', '2025-06-15 19:35:20', '', 0.00, 0.00),
(151, 2, 'booking_received', 375000, 'Vé câu #98: Bẻ răng Cần thủ | Số dư cuối #98: 4140000 vnd', '2025-06-15 19:35:20', '', 0.00, 0.00),
(152, 1, 'booking_refund', 39000, 'Vé câu #98: Hoàn phí giữ chổ || Số dư cuối: 667000 vnd', '2025-06-15 19:35:20', '', 0.00, 0.00),
(153, 1, 'booking_hold', -50000, 'Vé câu #99: Đặt booking giữ chỗ | Số dư cuối: 617000 vnd', '2025-06-15 19:44:00', '', 0.00, 0.00),
(154, 2, 'booking_pay', -310000, 'Vé câu #99: Chủ hồ bị bẻ răng | Số dư cuối #99: 3830000 vnd', '2025-06-15 19:44:29', '', 0.00, 0.00),
(155, 1, 'booking_received', 310000, 'Vé câu #99: Nhận tiền bẻ răng chủ hồ | Số dư cuối #99: 927000 vnd', '2025-06-15 19:44:29', '', 0.00, 0.00),
(156, 1, 'booking_refund', 39000, 'Vé câu #99: Hoàn phí giữ chổ || Số dư cuối: 966000 vnd', '2025-06-15 19:44:29', '', 0.00, 0.00),
(157, 1, 'booking_hold', -50000, 'Vé câu #100: Đặt booking giữ chỗ | Số dư cuối: 916000 vnd', '2025-06-15 19:46:47', '', 0.00, 0.00),
(158, 2, 'booking_pay', -450000, 'Vé câu #100: Chủ hồ bị bẻ răng | Số dư cuối #100: 3380000 vnd', '2025-06-15 19:47:18', '', 0.00, 0.00),
(159, 1, 'booking_received', 450000, 'Vé câu #100: Nhận tiền bẻ răng chủ hồ | Số dư cuối #100: 1366000 vnd', '2025-06-15 19:47:18', '', 0.00, 0.00),
(160, 1, 'booking_refund', 39000, 'Vé câu #100: Hoàn phí giữ chổ || Số dư cuối: 1405000 vnd', '2025-06-15 19:47:18', '', 0.00, 0.00),
(161, 1, 'booking_hold', -50000, 'Vé câu #101: Đặt booking giữ chỗ | Số dư cuối: 1355000 vnd', '2025-06-15 20:12:26', '', 0.00, 0.00),
(162, 1, 'booking_refund', 39000, 'Vé câu #101: Hoàn phí giữ chổ || Số dư cuối: 1394000 vnd', '2025-06-15 20:14:56', '', 0.00, 0.00),
(163, 1, 'booking_hold', -50000, 'Vé câu #102: Đặt booking giữ chỗ | Số dư cuối: 1344000 vnd', '2025-06-15 20:17:05', '', 0.00, 0.00),
(164, 1, 'booking_hold', -50000, 'Vé câu #103: Đặt booking giữ chỗ | Số dư cuối: 1294000 vnd', '2025-06-15 20:17:14', '', 0.00, 0.00),
(165, 1, 'booking_hold', -50000, 'Vé câu #104: Đặt booking giữ chỗ | Số dư cuối: 1244000 vnd', '2025-06-15 20:17:19', '', 0.00, 0.00),
(166, 1, 'booking_refund', 39000, 'Vé câu #102: Hoàn phí giữ chổ || Số dư cuối: 1283000 vnd', '2025-06-15 20:21:25', '', 0.00, 0.00),
(167, 1, 'booking_refund', 39000, 'Vé câu #103: Hoàn phí giữ chổ || Số dư cuối: 1322000 vnd', '2025-06-15 20:21:32', '', 0.00, 0.00),
(168, 2, 'booking_pay', -60000, 'Vé câu #104: Chủ hồ bị bẻ răng | Số dư cuối #104: 3320000 vnd', '2025-06-15 20:21:49', '', 0.00, 0.00),
(169, 1, 'booking_received', 60000, 'Vé câu #104: Nhận tiền bẻ răng chủ hồ | Số dư cuối #104: 1382000 vnd', '2025-06-15 20:21:49', '', 0.00, 0.00),
(170, 1, 'booking_refund', 39000, 'Vé câu #104: Hoàn phí giữ chổ || Số dư cuối: 1421000 vnd', '2025-06-15 20:21:49', '', 0.00, 0.00),
(171, 1, 'booking_hold', -50000, 'Vé câu #105: Đặt booking giữ chỗ | Số dư cuối: 1371000 vnd', '2025-06-17 21:48:26', '', 0.00, 0.00),
(172, 1, 'booking_pay', -45000, 'Vé câu #105: Bị chủ hồ bẻ răng | Số dư cuối: 1326000 vnd', '2025-06-17 21:50:34', '', 0.00, 0.00),
(173, 2, 'booking_received', 45000, 'Vé câu #105: Bẻ răng Cần thủ | Số dư cuối #105: 3365000 vnd', '2025-06-17 21:50:34', '', 0.00, 0.00),
(174, 1, 'booking_refund', 39000, 'Vé câu #105: Hoàn phí giữ chổ || Số dư cuối: 1365000 vnd', '2025-06-17 21:50:34', '', 0.00, 0.00),
(175, 1, 'booking_hold', -50000, 'Vé câu #106: Đặt booking giữ chỗ | Số dư cuối: 1315000 vnd', '2025-06-23 14:03:32', '', 0.00, 0.00),
(181, 1, 'game_pay', -6050, 'Trừ tiền tạo game ID #10', '2025-06-23 18:26:32', '', 0.00, 0.00),
(182, 1, 'game_pay', -60500, 'Trừ tiền tạo game ID #9', '2025-06-23 18:28:29', '', 0.00, 0.00),
(183, 1, 'game_pay', -110000, 'Trừ tiền tạo game ID #12', '2025-06-23 19:01:25', '', 0.00, 0.00),
(184, 1, 'game_pay', -60500, 'Trừ tiền tạo game ID #12', '2025-06-23 19:04:22', '', 0.00, 0.00),
(185, 1, 'game_pay', -121000, 'Trừ tiền tạo game ID #13', '2025-06-23 19:55:06', '', 0.00, 0.00),
(186, 1, 'game_pay', -77000, 'Trừ tiền tạo game ID #16', '2025-06-24 18:17:48', '', 0.00, 0.00),
(187, 1, 'game_pay', -40700, 'Trừ tiền tạo game ID #14', '2025-06-24 22:12:13', '', 0.00, 0.00),
(188, 1, 'booking_hold', -50000, 'Vé câu #107: Đặt booking giữ chỗ | Số dư cuối: 789250 vnd', '2025-06-27 16:50:26', '', 0.00, 0.00),
(189, 1, 'booking_hold', -50000, 'Vé câu #108: Đặt booking giữ chỗ | Số dư cuối: 739250 vnd', '2025-06-27 19:27:31', '', 0.00, 0.00),
(190, 1, 'booking_pay', -22000, 'Vé câu #108: Bị chủ hồ bẻ răng | Số dư cuối: 717250 vnd', '2025-06-27 19:29:24', '', 0.00, 0.00),
(191, 2, 'booking_received', 22000, 'Vé câu #108: Bẻ răng Cần thủ | Số dư cuối #108: 3387000 vnd', '2025-06-27 19:29:24', '', 0.00, 0.00),
(192, 1, 'booking_refund', 39000, 'Vé câu #108: Hoàn phí giữ chổ || Số dư cuối: 756250 vnd', '2025-06-27 19:29:24', '', 0.00, 0.00),
(193, 1, 'booking_hold', -50000, 'Vé câu #109: Đặt booking giữ chỗ | Số dư cuối: 706250 vnd', '2025-07-01 19:06:54', '', 0.00, 0.00),
(194, 1, 'giai_pay', 1296000, 'Trừ phí tổ chức giải ID #7', '2025-07-05 14:57:31', '', 0.00, 0.00),
(195, 1, 'giai_pay', 1296000, 'Trừ phí tổ chức giải ID #6, số dư hiện tại 2410250 vnd', '2025-07-05 15:02:33', '', 0.00, 0.00),
(196, 1, 'giai_pay', 1296000, 'Trừ phí tổ chức giải ID #7, số dư hiện tại 3818250 vnd', '2025-07-05 15:09:05', '', 0.00, 0.00),
(197, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #8, số dư hiện tại 2498250 vnd', '2025-07-05 20:25:56', '', 0.00, 0.00),
(198, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #10, số dư hiện tại 1178250 vnd', '2025-07-05 22:07:50', '', 0.00, 0.00),
(199, 1, 'giai_pay', 990000, 'Trừ phí tổ chức giải ID #13, số dư hiện tại 188250 vnd', '2025-07-05 22:09:15', '', 0.00, 0.00),
(200, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #14, số dư hiện tại 9868250 vnd', '2025-07-07 10:03:58', '', 0.00, 0.00),
(201, 1, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #15, số dư hiện tại 7228250 vnd', '2025-07-07 12:08:14', '', 0.00, 0.00),
(202, 1, 'giai_pay', 1980000, 'Trừ phí tổ chức giải ID #16, số dư hiện tại 5248250 vnd', '2025-07-07 12:09:16', '', 0.00, 0.00),
(203, 1, 'giai_refund', 2640000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #15)', '2025-07-07 12:59:05', '', 0.00, 0.00),
(204, 1, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #15, số dư hiện tại 7888250 vnd', '2025-07-07 13:17:16', '', 0.00, 0.00),
(205, 1, 'giai_pay', 990000, 'Trừ phí tổ chức giải ID #12, số dư hiện tại 6898250 vnd', '2025-07-07 13:17:52', '', 0.00, 0.00),
(206, 1, 'giai_refund', 1980000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #16)', '2025-07-07 13:18:26', '', 0.00, 0.00),
(207, 1, 'giai_pay', 1980000, 'Trừ phí tổ chức giải ID #16, số dư hiện tại 6898250 vnd', '2025-07-07 13:23:55', '', 0.00, 0.00),
(208, 1, 'giai_refund', 1980000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #16). số dư:8878250vnd', '2025-07-07 13:24:12', '', 0.00, 0.00),
(209, 1, 'giai_pay', 1980000, 'Trừ phí tổ chức giải ID #16, số dư hiện tại 6898250 vnd', '2025-07-07 13:27:07', '', 0.00, 0.00),
(210, 1, 'giai_refund', 1980000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #16). số dư: 8,878,250 vnd', '2025-07-07 13:28:02', '', 0.00, 0.00),
(211, 1, 'giai_pay', 1980000, 'Trừ phí tổ chức giải ID #16, số dư hiện tại number_format(6898250) vnd', '2025-07-07 13:30:20', '', 0.00, 0.00),
(212, 1, 'giai_refund', 1980000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #16). số dư: 8,878,250 vnd', '2025-07-07 13:36:01', '', 0.00, 0.00),
(213, 1, 'giai_pay', 1980000, 'Trừ phí tổ chức giải ID #16, số dư hiện tại 6.898.250 vnd', '2025-07-07 13:36:18', '', 0.00, 0.00),
(214, 1, 'giai_refund', 1980000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #16). số dư: 8.878.250 vnd', '2025-07-07 13:37:42', '', 0.00, 0.00),
(215, 1, 'giai_pay', 1980000, 'Trừ phí tổ chức giải ID #16, số dư hiện tại 6.898.250 vnd', '2025-07-07 13:41:36', '', 0.00, 0.00),
(216, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #2, số dư hiện tại 5.578.250 vnd', '2025-07-07 23:50:23', '', 0.00, 0.00),
(217, 1, 'giai_pay', 1650000, 'Trừ phí tổ chức giải ID #3, số dư hiện tại 3.928.250 vnd', '2025-07-08 15:04:35', '', 0.00, 0.00),
(218, 1, 'giai_refund', 1650000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #3). số dư: 5.578.250 vnd', '2025-07-08 15:21:59', '', 0.00, 0.00),
(219, 1, 'giai_pay', 1650000, 'Trừ phí tổ chức giải ID #3, số dư hiện tại 3.928.250 vnd', '2025-07-08 15:22:32', '', 0.00, 0.00),
(220, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #17, số dư hiện tại 2.608.250 vnd', '2025-07-08 17:31:09', '', 0.00, 0.00),
(221, 1, 'giai_refund', 1320000, 'Hoàn phí giải khi chủ hồ từ chối duyệt (giải #17). số dư: 3.928.250 vnd', '2025-07-08 17:32:25', '', 0.00, 0.00),
(222, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #17, số dư hiện tại 2.608.250 vnd', '2025-07-08 17:37:27', '', 0.00, 0.00),
(223, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #11, số dư hiện tại 1.288.250 vnd', '2025-07-10 00:04:05', '', 0.00, 0.00),
(224, 1, 'giai_pay', 990000, 'Trừ phí tổ chức giải ID #20, số dư hiện tại 298.250 vnd', '2025-07-10 16:04:45', '', 0.00, 0.00),
(225, 1, 'giai_pay', 2475000, 'Trừ phí tổ chức giải ID #19, số dư hiện tại 19.823.250 vnd', '2025-07-10 17:18:43', '', 0.00, 0.00),
(226, 1, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #22, số dư hiện tại 17.183.250 vnd', '2025-07-13 14:09:22', '', 0.00, 0.00),
(227, 1, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #23, số dư hiện tại 14.543.250 vnd', '2025-07-13 14:10:33', '', 0.00, 0.00),
(228, 1, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #24, số dư hiện tại 11.903.250 vnd', '2025-07-13 14:14:53', '', 0.00, 0.00),
(229, 1, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #25, số dư hiện tại 9.263.250 vnd', '2025-07-13 14:49:52', '', 0.00, 0.00),
(230, 2, 'giai_pay', 2640000, 'Trừ phí tổ chức giải ID #29, số dư hiện tại 3.827.000 vnd', '2025-07-17 14:25:46', 'giai_29', 6467000.00, 3827000.00),
(231, 1, 'giai_pay', 1320000, 'Trừ phí tổ chức giải ID #30, số dư hiện tại 7.943.250 vnd', '2025-07-17 14:27:53', 'giai_30', 9263250.00, 7943250.00),
(232, 1, 'giai_pay', 3300000, 'Trừ phí tổ chức giải ID #33, số dư hiện tại 4.643.250 vnd', '2025-07-18 07:47:49', 'giai_33', 7943250.00, 4643250.00),
(233, 1, 'giai_pay', 550000, 'Trừ phí tổ chức giải ID #34, số dư hiện tại 4.093.250 vnd', '2025-07-18 10:35:31', 'giai_34', 4643250.00, 4093250.00),
(234, 2, 'giai_pay', 1650000, 'Trừ phí tổ chức giải ID #36, số dư hiện tại 2.177.000 vnd', '2025-07-21 13:14:55', 'giai_36', 3827000.00, 2177000.00),
(235, 2, 'giai_pay', 2750000, 'Trừ phí tổ chức giải ID #37, số dư hiện tại 19.427.000 vnd', '2025-07-21 13:26:06', 'giai_37', 22177000.00, 19427000.00),
(236, 1, 'giai_pay', 2200000, 'Trừ phí tổ chức giải ID #38, số dư hiện tại 1.893.250 vnd', '2025-07-24 12:51:47', 'giai_38', 4093250.00, 1893250.00),
(237, 1, 'giai_pay', 1100000, 'Trừ phí tổ chức giải ID #39, số dư hiện tại 793.250 vnd', '2025-08-07 12:48:20', 'giai_39', 1893250.00, 793250.00),
(238, 2, 'giai_pay', 1100000, 'Trừ phí tổ chức giải ID #40, số dư hiện tại 18.327.000 vnd', '2025-08-07 12:51:19', 'giai_40', 19427000.00, 18327000.00),
(239, 1, 'giai_pay', 700000, 'Trừ tiền cược tham gia giải ID #40', '2025-08-07 14:40:05', '40', 793250.00, 93250.00),
(240, 1, 'giai_pay', 700000, 'Trừ tiền cược tham gia giải ID #39', '2025-08-07 14:43:32', '39', 1193250.00, 493250.00),
(241, 1, 'giai_pay', 700000, 'Trừ tiền cược tham gia giải ID #39', '2025-08-07 14:49:22', '39', 11493250.00, 10793250.00),
(242, 1, 'giai_pay', 700000, 'Trừ tiền cược tham gia giải ID #40', '2025-08-07 14:50:17', '40', 10793250.00, 10093250.00),
(243, 1, 'giai_pay', 1100000, 'Trừ phí tổ chức giải ID #41, số dư hiện tại 8.943.250 vnd', '2025-08-20 15:34:39', 'giai_41', 10043250.00, 8943250.00),
(244, 1, 'giai_pay', 700000, 'Phí tham gia giải ID #41 - Giải ngày 20-08. Số dư sau: 8.243.250đ', '2025-08-20 16:03:25', 'giai_41', 8943250.00, 8243250.00),
(245, 18, 'giai_pay', 700000, 'Phí tham gia giải ID #41 - Giải ngày 20-08. Số dư sau: 300.000đ', '2025-08-20 20:23:07', 'giai_41', 1000000.00, 300000.00),
(246, 2, 'giai_pay', 1732500, 'Trừ phí tổ chức giải ID #42, số dư hiện tại 16.594.500 vnd', '2025-08-20 22:30:53', 'giai_42', 18327000.00, 16594500.00),
(247, 18, 'giai_pay', 700000, 'Phí tham gia giải ID #42 - Giải Hoà bình 20-08111. Số dư sau: 10.600.000đ', '2025-08-20 22:32:14', 'giai_42', 11300000.00, 10600000.00),
(248, 1, 'giai_pay', 700000, 'Phí tham gia giải ID #42 - Giải Hoà bình 20-08111. Số dư sau: 7.543.250đ', '2025-08-20 22:32:47', 'giai_42', 8243250.00, 7543250.00),
(249, 1, 'giai_pay', 700000, 'Phí tham gia giải ID #42 - Giải Hoà bình 20-08111. Số dư sau: 6.843.250đ', '2025-08-20 22:36:49', 'giai_42', 7543250.00, 6843250.00),
(264, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #42 (Giải Hoà bình 20-08111)', '2025-08-21 17:19:28', 'giai_42', 10600000.00, 9900000.00),
(265, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #42 từ user #18', '2025-08-21 17:19:28', 'giai_42', 16594500.00, 17294500.00),
(266, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #42 (Giải Hoà bình 20-08111)', '2025-08-21 17:37:29', 'giai_42', 9900000.00, 9200000.00),
(267, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #42 từ user #18', '2025-08-21 17:37:29', 'giai_42', 17294500.00, 17994500.00),
(268, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #42 (Giải Hoà bình 20-08111)', '2025-08-21 17:42:52', 'giai_42', 9200000.00, 8500000.00),
(269, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #42 từ user #18', '2025-08-21 17:42:52', 'giai_42', 17994500.00, 18694500.00),
(270, 1, 'giai_pay', 550000, 'Trừ phí tổ chức giải ID #43, số dư hiện tại 6.293.250 vnd', '2025-08-21 17:51:28', 'giai_43', 6843250.00, 6293250.00),
(272, 1, 'giai_pay', 700000, 'Phí tham gia giải ID #43 - Giiải 2108000. Số dư sau: 5.593.250đ', '2025-08-21 17:53:04', 'giai_43', 6293250.00, 5593250.00),
(273, 1, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #43 (Giiải 2108000)', '2025-08-21 17:57:04', 'giai_43', 5593250.00, 4893250.00),
(274, 1, 'giai_received', 700000, 'Nhận phí tham gia giải #43 từ user #1', '2025-08-21 17:57:04', 'giai_43', 4893250.00, 5593250.00),
(275, 18, 'giai_pay', 700000, 'Phí tham gia giải ID #43 - Giiải 2108000. Số dư sau: 7.800.000đ', '2025-08-21 17:57:50', 'giai_43', 8500000.00, 7800000.00),
(276, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #43 (Giiải 2108000)', '2025-08-21 18:05:46', 'giai_43', 7800000.00, 7100000.00),
(277, 1, 'giai_received', 700000, 'Nhận phí tham gia giải #43 từ user #18', '2025-08-21 18:05:46', 'giai_43', 5593250.00, 6293250.00),
(278, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #43 (Giiải 2108000)', '2025-08-21 18:17:07', 'giai_43', 7100000.00, 6400000.00),
(279, 1, 'giai_received', 700000, 'Nhận phí tham gia giải #43 từ user #18', '2025-08-21 18:17:07', 'giai_43', 6293250.00, 6993250.00),
(280, 1, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #43 (Giiải 2108000)', '2025-08-21 18:23:32', 'giai_43', 6993250.00, 6293250.00),
(281, 1, 'giai_received', 700000, 'Nhận phí tham gia giải #43 từ user #1', '2025-08-21 18:23:32', 'giai_43', 6293250.00, 6993250.00),
(282, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #43 (Giiải 2108000)', '2025-08-21 18:25:55', 'giai_43', 6400000.00, 5700000.00),
(283, 1, 'giai_received', 700000, 'Nhận phí tham gia giải #43 từ user #18', '2025-08-21 18:25:55', 'giai_43', 6993250.00, 7693250.00),
(284, 2, 'giai_pay', 1100000, 'Trừ phí tổ chức giải ID #44, số dư hiện tại 17.594.500 vnd', '2025-08-21 21:28:56', 'giai_44', 18694500.00, 17594500.00),
(285, 1, 'giai_pay', 700000, 'Phí tham gia giải ID #44 - Giải Hoà bình 20-08111. Số dư sau: 6.993.250đ', '2025-08-21 21:36:17', 'giai_44', 7693250.00, 6993250.00),
(286, 1, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #44 (Giải Hoà bình 20-08111)', '2025-08-21 21:39:35', 'giai_44', 6993250.00, 6293250.00),
(287, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #44 từ user #1', '2025-08-21 21:39:35', 'giai_44', 17594500.00, 18294500.00),
(288, 18, 'giai_pay', 700000, 'Phí tham gia giải ID #44 - Giải Hoà bình 20-08111. Số dư sau: 5.000.000đ', '2025-08-21 21:40:41', 'giai_44', 5700000.00, 5000000.00),
(289, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #44 (Giải Hoà bình 20-08111)', '2025-08-21 21:46:06', 'giai_44', 5000000.00, 4300000.00),
(290, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #44 từ user #18', '2025-08-21 21:46:06', 'giai_44', 18294500.00, 18994500.00),
(291, 18, 'giai_pay', 700000, 'Phí tham gia giải ID #44 - Giải Hoà bình 20-08111. Số dư sau: 3.600.000đ', '2025-08-21 21:46:51', 'giai_44', 4300000.00, 3600000.00),
(292, 1, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #44 (Giải Hoà bình 20-08111)', '2025-08-21 21:53:03', 'giai_44', 6293250.00, 5593250.00),
(293, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #44 từ user #1', '2025-08-21 21:53:03', 'giai_44', 18994500.00, 19694500.00),
(294, 1, 'giai_pay', 700000, 'Đăng ký online tham gia giải ID #44 - Giải Hoà bình 20-08111. Số dư sau: 4.893.250đ', '2025-08-21 22:05:39', 'giai_44', 5593250.00, 4893250.00),
(295, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #44 (Giải Hoà bình 20-08111) - Số dư sau: 0 đ', '2025-08-21 22:07:19', 'giai_44', 3600000.00, 2900000.00),
(296, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #44 từ user #18', '2025-08-21 22:07:19', 'giai_44', 19694500.00, 20394500.00),
(297, 1, 'giai_pay', 700000, 'Đăng ký online tham gia giải ID #44 - Giải Hoà bình 20-08111. Số dư sau: 4.193.250đ', '2025-08-21 22:12:51', 'giai_44', 4893250.00, 4193250.00),
(298, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #44 (Giải Hoà bình 20-08111) - Số dư sau: 0 đ', '2025-08-21 22:13:54', 'giai_44', 2900000.00, 2200000.00),
(299, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #44 từ user #18', '2025-08-21 22:13:54', 'giai_44', 20394500.00, 21094500.00),
(300, 18, 'giai_pay', 700000, 'Chấp nhận mời & thanh toán giải #44 (Giải Hoà bình 20-08111) - Số dư sau: 1.500.000 đ', '2025-08-21 22:18:18', 'giai_44', 2200000.00, 1500000.00),
(301, 2, 'giai_received', 700000, 'Nhận phí tham gia giải #44 từ user #18', '2025-08-21 22:18:18', 'giai_44', 21094500.00, 21794500.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_exp_log`
--

CREATE TABLE `user_exp_log` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `action_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'key...',
  `exp_change` int DEFAULT '0',
  `note` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'note exp logs...',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_game_visited`
--

CREATE TABLE `user_game_visited` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `game_id` int NOT NULL,
  `visited_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_giai_visited`
--

CREATE TABLE `user_giai_visited` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `giai_id` int NOT NULL,
  `visited_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_ho_visited`
--

CREATE TABLE `user_ho_visited` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `ho_cau_id` int NOT NULL,
  `visited_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_lever_rules`
--

CREATE TABLE `user_lever_rules` (
  `id` int NOT NULL,
  `lever` int NOT NULL,
  `ten_lever` varchar(100) NOT NULL,
  `mo_ta` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Mô tả các cấp bậc...',
  `user_exp_toi_thieu` int DEFAULT '0',
  `so_ho_toi_thieu` int DEFAULT '0',
  `so_game_toi_thieu` int DEFAULT '0',
  `so_tinh_toi_thieu` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_lever_rules`
--

INSERT INTO `user_lever_rules` (`id`, `lever`, `ten_lever`, `mo_ta`, `user_exp_toi_thieu`, `so_ho_toi_thieu`, `so_game_toi_thieu`, `so_tinh_toi_thieu`, `created_at`) VALUES
(1, 1, 'Câu cá cấp 1', 'Điều kiện: >= 3 hồ', 30, 3, 0, 0, '2025-05-24 20:36:42'),
(2, 2, 'Câu cá cấp 2', '>= 10 hồ ở 3 xã', 100, 10, 0, 0, '2025-05-24 20:36:42'),
(3, 3, 'Chuẩn Đài Sư', '>= 50 hồ ở 3 tỉnh', 300, 50, 5, 3, '2025-05-24 20:36:42'),
(4, 4, 'Trung Cấp Đài Sư', '>= 100 hồ ở 10 tỉnh', 600, 100, 10, 10, '2025-05-24 20:36:42'),
(5, 5, 'Thượng Cấp Đài Sư', '>= 200 hồ ở 20 tỉnh', 1000, 200, 20, 20, '2025-05-24 20:36:42'),
(6, 6, 'Đặc Cấp Đài Sư', '>= 300 hồ ở 30 tỉnh', 1500, 300, 30, 30, '2025-05-24 20:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_stats`
--

CREATE TABLE `user_stats` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `tong_booking` int DEFAULT '0',
  `so_ho_khac_nhau` int DEFAULT '0',
  `so_xa_khac_nhau` int DEFAULT '0',
  `so_tinh_khac_nhau` int DEFAULT '0',
  `tong_gio_cau` float DEFAULT '0',
  `tong_kg_ca` float DEFAULT '0',
  `so_game` int DEFAULT '0',
  `tong_exp` int DEFAULT '0',
  `current_lever` int DEFAULT '0',
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_xa_visited`
--

CREATE TABLE `user_xa_visited` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `xa_phuong_id` int NOT NULL,
  `visited_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_config_keys`
--
ALTER TABLE `admin_config_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config_key` (`config_key`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoi_tao_id` (`nguoi_tao_id`),
  ADD KEY `can_thu_id` (`can_thu_id`),
  ADD KEY `chu_ho_id` (`chu_ho_id`),
  ADD KEY `ho_cau_id` (`ho_cau_id`),
  ADD KEY `gia_id` (`gia_id`),
  ADD KEY `ref_by_user_id` (`ref_by_user_id`);

--
-- Indexes for table `booking_logs`
--
ALTER TABLE `booking_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `booking_payment_logs`
--
ALTER TABLE `booking_payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_prize_awards`
--
ALTER TABLE `booking_prize_awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `awarded_by` (`awarded_by`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_ho` (`ho_cau_id`),
  ADD KEY `idx_type_time` (`prize_type`,`created_at`);

--
-- Indexes for table `booking_service_fee`
--
ALTER TABLE `booking_service_fee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_ho` (`ho_cau_id`),
  ADD KEY `idx_type_time` (`service_type`,`created_at`);

--
-- Indexes for table `cum_ho`
--
ALTER TABLE `cum_ho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `xa_id` (`xa_id`),
  ADD KEY `chu_ho_id` (`chu_ho_id`);

--
-- Indexes for table `cum_ho_logs`
--
ALTER TABLE `cum_ho_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cum_ho_id` (`cum_ho_id`),
  ADD KEY `old_chu_ho_id` (`old_chu_ho_id`),
  ADD KEY `new_chu_ho_id` (`new_chu_ho_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `cum_ho_review`
--
ALTER TABLE `cum_ho_review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by_user_id` (`added_by_user_id`);

--
-- Indexes for table `cum_ho_review_loai_ca`
--
ALTER TABLE `cum_ho_review_loai_ca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_review_ca` (`cum_ho_review_id`,`loai_ca_id`),
  ADD KEY `loai_ca_id` (`loai_ca_id`);

--
-- Indexes for table `dm_tinh`
--
ALTER TABLE `dm_tinh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_tinh` (`ten_tinh`);

--
-- Indexes for table `dm_xa_phuong`
--
ALTER TABLE `dm_xa_phuong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tinh_id` (`tinh_id`);

--
-- Indexes for table `game_ket_qua`
--
ALTER TABLE `game_ket_qua`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `game_list`
--
ALTER TABLE `game_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ho_cau_id` (`ho_cau_id`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `hinh_thuc_id` (`hinh_thuc_id`);

--
-- Indexes for table `game_log`
--
ALTER TABLE `game_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `game_schedule`
--
ALTER TABLE `game_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `game_tong_hop`
--
ALTER TABLE `game_tong_hop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `game_user`
--
ALTER TABLE `game_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `giai_game_hinh_thuc`
--
ALTER TABLE `giai_game_hinh_thuc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `giai_list`
--
ALTER TABLE `giai_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `giai_log`
--
ALTER TABLE `giai_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giai_id` (`giai_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `giai_schedule`
--
ALTER TABLE `giai_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giai_id` (`giai_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `giai_user`
--
ALTER TABLE `giai_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_giai_user` (`giai_id`,`user_id`),
  ADD KEY `giai_id` (`giai_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gia_ca_thit_phut`
--
ALTER TABLE `gia_ca_thit_phut`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ho_banggia` (`ho_cau_id`,`ten_bang_gia`);

--
-- Indexes for table `ho_cau`
--
ALTER TABLE `ho_cau`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ho_cau_loai_ca`
--
ALTER TABLE `ho_cau_loai_ca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ho_ca` (`ho_cau_id`,`loai_ca_id`),
  ADD KEY `loai_ca_id` (`loai_ca_id`);

--
-- Indexes for table `lich_hoat_dong_ho_cau`
--
ALTER TABLE `lich_hoat_dong_ho_cau`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lich_ho_xoa` (`ho_cau_id`);

--
-- Indexes for table `loai_ca`
--
ALTER TABLE `loai_ca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_ca` (`ten_ca`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `qr_code_logs`
--
ALTER TABLE `qr_code_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `referral_logs`
--
ALTER TABLE `referral_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ref` (`ref_user_id`,`new_user_id`),
  ADD KEY `new_user_id` (`new_user_id`);

--
-- Indexes for table `referral_payouts`
--
ALTER TABLE `referral_payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payout_code` (`payout_code`),
  ADD KEY `ref_user_id` (`ref_user_id`);

--
-- Indexes for table `referral_rewards`
--
ALTER TABLE `referral_rewards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref_user_id` (`ref_user_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `paid_by_payout_id` (`paid_by_payout_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `user_balance_logs`
--
ALTER TABLE `user_balance_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_exp_log`
--
ALTER TABLE `user_exp_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_game_visited`
--
ALTER TABLE `user_game_visited`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_game` (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `user_giai_visited`
--
ALTER TABLE `user_giai_visited`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_giai` (`user_id`,`giai_id`),
  ADD KEY `giai_id` (`giai_id`);

--
-- Indexes for table `user_ho_visited`
--
ALTER TABLE `user_ho_visited`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_ho` (`user_id`,`ho_cau_id`),
  ADD KEY `ho_cau_id` (`ho_cau_id`);

--
-- Indexes for table `user_lever_rules`
--
ALTER TABLE `user_lever_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lever` (`lever`);

--
-- Indexes for table `user_stats`
--
ALTER TABLE `user_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `current_lever` (`current_lever`);

--
-- Indexes for table `user_xa_visited`
--
ALTER TABLE `user_xa_visited`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_xa` (`user_id`,`xa_phuong_id`),
  ADD KEY `xa_phuong_id` (`xa_phuong_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `admin_config_keys`
--
ALTER TABLE `admin_config_keys`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `booking_logs`
--
ALTER TABLE `booking_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `booking_payment_logs`
--
ALTER TABLE `booking_payment_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `booking_prize_awards`
--
ALTER TABLE `booking_prize_awards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `booking_service_fee`
--
ALTER TABLE `booking_service_fee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cum_ho`
--
ALTER TABLE `cum_ho`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cum_ho_logs`
--
ALTER TABLE `cum_ho_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cum_ho_review`
--
ALTER TABLE `cum_ho_review`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cum_ho_review_loai_ca`
--
ALTER TABLE `cum_ho_review_loai_ca`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_tinh`
--
ALTER TABLE `dm_tinh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `dm_xa_phuong`
--
ALTER TABLE `dm_xa_phuong`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=595;

--
-- AUTO_INCREMENT for table `game_ket_qua`
--
ALTER TABLE `game_ket_qua`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_list`
--
ALTER TABLE `game_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `game_log`
--
ALTER TABLE `game_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_schedule`
--
ALTER TABLE `game_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `game_tong_hop`
--
ALTER TABLE `game_tong_hop`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_user`
--
ALTER TABLE `game_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `giai_game_hinh_thuc`
--
ALTER TABLE `giai_game_hinh_thuc`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `giai_list`
--
ALTER TABLE `giai_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `giai_log`
--
ALTER TABLE `giai_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `giai_schedule`
--
ALTER TABLE `giai_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2275;

--
-- AUTO_INCREMENT for table `giai_user`
--
ALTER TABLE `giai_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `gia_ca_thit_phut`
--
ALTER TABLE `gia_ca_thit_phut`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `ho_cau`
--
ALTER TABLE `ho_cau`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `ho_cau_loai_ca`
--
ALTER TABLE `ho_cau_loai_ca`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lich_hoat_dong_ho_cau`
--
ALTER TABLE `lich_hoat_dong_ho_cau`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1184;

--
-- AUTO_INCREMENT for table `loai_ca`
--
ALTER TABLE `loai_ca`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_code_logs`
--
ALTER TABLE `qr_code_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_logs`
--
ALTER TABLE `referral_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_payouts`
--
ALTER TABLE `referral_payouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_rewards`
--
ALTER TABLE `referral_rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `user_balance_logs`
--
ALTER TABLE `user_balance_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT for table `user_exp_log`
--
ALTER TABLE `user_exp_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_game_visited`
--
ALTER TABLE `user_game_visited`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_giai_visited`
--
ALTER TABLE `user_giai_visited`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_ho_visited`
--
ALTER TABLE `user_ho_visited`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_lever_rules`
--
ALTER TABLE `user_lever_rules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_stats`
--
ALTER TABLE `user_stats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_xa_visited`
--
ALTER TABLE `user_xa_visited`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`can_thu_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`chu_ho_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`),
  ADD CONSTRAINT `booking_ibfk_5` FOREIGN KEY (`gia_id`) REFERENCES `gia_ca_thit_phut` (`id`),
  ADD CONSTRAINT `booking_ibfk_6` FOREIGN KEY (`ref_by_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_logs`
--
ALTER TABLE `booking_logs`
  ADD CONSTRAINT `booking_logs_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_prize_awards`
--
ALTER TABLE `booking_prize_awards`
  ADD CONSTRAINT `booking_prize_awards_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_prize_awards_ibfk_2` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_prize_awards_ibfk_3` FOREIGN KEY (`awarded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `booking_service_fee`
--
ALTER TABLE `booking_service_fee`
  ADD CONSTRAINT `booking_service_fee_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_service_fee_ibfk_2` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_service_fee_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `cum_ho`
--
ALTER TABLE `cum_ho`
  ADD CONSTRAINT `cum_ho_ibfk_1` FOREIGN KEY (`xa_id`) REFERENCES `dm_xa_phuong` (`id`),
  ADD CONSTRAINT `cum_ho_ibfk_2` FOREIGN KEY (`chu_ho_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cum_ho_logs`
--
ALTER TABLE `cum_ho_logs`
  ADD CONSTRAINT `cum_ho_logs_ibfk_1` FOREIGN KEY (`cum_ho_id`) REFERENCES `cum_ho` (`id`),
  ADD CONSTRAINT `cum_ho_logs_ibfk_2` FOREIGN KEY (`old_chu_ho_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cum_ho_logs_ibfk_3` FOREIGN KEY (`new_chu_ho_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cum_ho_logs_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `cum_ho_review`
--
ALTER TABLE `cum_ho_review`
  ADD CONSTRAINT `cum_ho_review_ibfk_1` FOREIGN KEY (`added_by_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cum_ho_review_loai_ca`
--
ALTER TABLE `cum_ho_review_loai_ca`
  ADD CONSTRAINT `cum_ho_review_loai_ca_ibfk_1` FOREIGN KEY (`cum_ho_review_id`) REFERENCES `cum_ho_review` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cum_ho_review_loai_ca_ibfk_2` FOREIGN KEY (`loai_ca_id`) REFERENCES `loai_ca` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dm_xa_phuong`
--
ALTER TABLE `dm_xa_phuong`
  ADD CONSTRAINT `dm_xa_phuong_ibfk_1` FOREIGN KEY (`tinh_id`) REFERENCES `dm_tinh` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `game_ket_qua`
--
ALTER TABLE `game_ket_qua`
  ADD CONSTRAINT `game_ket_qua_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game_list` (`id`),
  ADD CONSTRAINT `game_ket_qua_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `game_list`
--
ALTER TABLE `game_list`
  ADD CONSTRAINT `game_list_ibfk_1` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`),
  ADD CONSTRAINT `game_list_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `game_list_ibfk_3` FOREIGN KEY (`hinh_thuc_id`) REFERENCES `giai_game_hinh_thuc` (`id`);

--
-- Constraints for table `game_log`
--
ALTER TABLE `game_log`
  ADD CONSTRAINT `game_log_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game_list` (`id`),
  ADD CONSTRAINT `game_log_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `game_schedule`
--
ALTER TABLE `game_schedule`
  ADD CONSTRAINT `game_schedule_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game_list` (`id`),
  ADD CONSTRAINT `game_schedule_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `game_tong_hop`
--
ALTER TABLE `game_tong_hop`
  ADD CONSTRAINT `game_tong_hop_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game_list` (`id`),
  ADD CONSTRAINT `game_tong_hop_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `game_user`
--
ALTER TABLE `game_user`
  ADD CONSTRAINT `game_user_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game_list` (`id`),
  ADD CONSTRAINT `game_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `giai_log`
--
ALTER TABLE `giai_log`
  ADD CONSTRAINT `fk_giai_log_giai` FOREIGN KEY (`giai_id`) REFERENCES `giai_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_giai_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `giai_schedule`
--
ALTER TABLE `giai_schedule`
  ADD CONSTRAINT `giai_schedule_ibfk_1` FOREIGN KEY (`giai_id`) REFERENCES `giai_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `giai_schedule_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `giai_user`
--
ALTER TABLE `giai_user`
  ADD CONSTRAINT `giai_user_ibfk_1` FOREIGN KEY (`giai_id`) REFERENCES `giai_list` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `giai_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gia_ca_thit_phut`
--
ALTER TABLE `gia_ca_thit_phut`
  ADD CONSTRAINT `fk_gia_xoa` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ho_cau_loai_ca`
--
ALTER TABLE `ho_cau_loai_ca`
  ADD CONSTRAINT `ho_cau_loai_ca_ibfk_1` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ho_cau_loai_ca_ibfk_2` FOREIGN KEY (`loai_ca_id`) REFERENCES `loai_ca` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_hoat_dong_ho_cau`
--
ALTER TABLE `lich_hoat_dong_ho_cau`
  ADD CONSTRAINT `fk_lich_ho_xoa` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD CONSTRAINT `payment_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payment_logs_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `qr_code_logs`
--
ALTER TABLE `qr_code_logs`
  ADD CONSTRAINT `qr_code_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `qr_code_logs_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `referral_logs`
--
ALTER TABLE `referral_logs`
  ADD CONSTRAINT `referral_logs_ibfk_1` FOREIGN KEY (`ref_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `referral_logs_ibfk_2` FOREIGN KEY (`new_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `referral_payouts`
--
ALTER TABLE `referral_payouts`
  ADD CONSTRAINT `referral_payouts_ibfk_1` FOREIGN KEY (`ref_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `referral_rewards`
--
ALTER TABLE `referral_rewards`
  ADD CONSTRAINT `referral_rewards_ibfk_1` FOREIGN KEY (`ref_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `referral_rewards_ibfk_2` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `referral_rewards_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `referral_rewards_ibfk_4` FOREIGN KEY (`paid_by_payout_id`) REFERENCES `referral_payouts` (`id`);

--
-- Constraints for table `user_exp_log`
--
ALTER TABLE `user_exp_log`
  ADD CONSTRAINT `user_exp_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_game_visited`
--
ALTER TABLE `user_game_visited`
  ADD CONSTRAINT `user_game_visited_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_game_visited_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `game_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_giai_visited`
--
ALTER TABLE `user_giai_visited`
  ADD CONSTRAINT `user_giai_visited_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_giai_visited_ibfk_2` FOREIGN KEY (`giai_id`) REFERENCES `giai_list` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_ho_visited`
--
ALTER TABLE `user_ho_visited`
  ADD CONSTRAINT `user_ho_visited_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_ho_visited_ibfk_2` FOREIGN KEY (`ho_cau_id`) REFERENCES `ho_cau` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_stats`
--
ALTER TABLE `user_stats`
  ADD CONSTRAINT `user_stats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_stats_ibfk_2` FOREIGN KEY (`current_lever`) REFERENCES `user_lever_rules` (`lever`);

--
-- Constraints for table `user_xa_visited`
--
ALTER TABLE `user_xa_visited`
  ADD CONSTRAINT `user_xa_visited_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_xa_visited_ibfk_2` FOREIGN KEY (`xa_phuong_id`) REFERENCES `dm_xa_phuong` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
