-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 05, 2025 at 06:52 AM
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
-- Database: `shoe_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locked` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `role` enum('admin','guest','shipper') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guest',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `name`, `phone`, `address`, `token`, `locked`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$Nl0IqopilF6cxZ1auaHZE.8K.tGIeeNHOZkqYC8iXi5v4LWGA5y/O', 'Admin', '0123456789', 'Yên Nghĩa, Hà Đông, Hà Nội', 'XQzERLrp4mNqReNbrtpMmLbBYzCW0u22dvw1mmD7uSLTBGq84U', '0', 'admin', '2025-02-05 16:43:02', '2025-02-05 16:44:29'),
(2, 'shipper', '$2y$10$YYdU9jnJ9qEOt6lDDu8YpOgsjON15WgUc8L60y8mhnZSBmfaIgSJe', 'Shipper', '0123456789', 'Yên Nghĩa, Hà Đông, Hà Nội', 'rpsN3MQgcxAcuht7SFzi3zAa5z7kGFALk3VNZukbTMZsUd7vee', '0', 'shipper', '2025-02-05 16:43:02', '2025-05-14 21:56:10'),
(3, 'user', '$2y$10$mwq1BRpgI8BFJeHstm3ahul41u0Se8IwXft.vHJKwpJI9F0EuG2VO', 'Khách hàng', '0123456789', 'Yên Nghĩa, Hà Đông, Hà Nội', 'GBxVhlYbTiXcH0mdDefUC17NQ3ZTssANraIg2X1Lc8XMwP8TKh', '0', 'guest', '2025-02-05 16:43:02', '2025-05-14 21:55:55');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Giày Adidas', 'giay-adidas', '2025-02-05 16:43:02', '2025-07-03 11:53:58'),
(2, 'Giày  Nike', 'giay-nike', '2025-02-05 16:43:02', '2025-07-03 11:54:18'),
(3, 'Giày Jordan', 'giay-jordan', '2025-02-05 16:43:02', '2025-07-03 11:54:32'),
(4, 'Giày Vans', 'giay-vans', '2025-02-05 16:43:02', '2025-07-03 11:59:42'),
(5, 'Giày Puma', 'giay-puma', '2025-02-05 16:43:02', '2025-07-03 11:59:11'),
(6, 'Giày MLB', 'giay-mlb', '2025-02-05 16:43:02', '2025-07-03 11:59:24'),
(7, 'Giày Converse', 'giay-converse', '2025-02-05 16:43:02', '2025-07-03 12:00:08'),
(8, 'Giày Balenciaga', 'giay-balenciaga', '2025-02-05 16:43:02', '2025-07-03 12:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `percent` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `name`, `code`, `start_at`, `end_at`, `percent`, `created_at`, `updated_at`) VALUES
(1, 'Tưng bừng khai trương', 'giam10', '2025-05-31 17:00:00', '2025-08-30 16:59:59', 10, '2025-06-04 17:10:33', '2025-06-04 17:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_01_26_062044_create_accounts_table', 1),
(2, '2025_01_26_062240_create_categories_table', 1),
(3, '2025_01_26_062316_create_products_table', 1),
(4, '2025_01_26_062357_create_discounts_table', 1),
(5, '2025_01_26_172503_create_orders_table', 1),
(6, '2025_01_26_172913_create_order_product_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` bigint UNSIGNED DEFAULT NULL,
  `shipper_id` bigint UNSIGNED DEFAULT NULL,
  `discount_id` bigint UNSIGNED DEFAULT NULL,
  `discount_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_percent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay` enum('unpaid','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct',
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('cart','wait','shipping','complete','cancel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cart',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` int DEFAULT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL,
  `vote` double(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` int NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `avatar`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'UltraBoost 22 ‘Black White’', 'Mã sản phẩm: GY6087\r\n\r\nThương hiệu: adidas\r\n\r\nMàu sắc chính: Đen (Black)\r\n\r\nDanh mục sản phẩm: Giày chạy bộ (Running Shoes)\r\n\r\nTình trạng: Mới 100%\r\n\r\nNgày phát hành: 8 tháng 3 năm 2022\r\n\r\nMô tả sản phẩm:\r\n\r\nNâng tầm phong cách athleisure của bạn với mẫu giày Stella McCartney x adidas UltraBoost 22 ‘Black White’ (GY6087) dành cho nữ. Đôi giày thể thao này được thiết kế với sự kết hợp hoàn hảo giữa hiệu suất cao và thiết kế hiện đại. Phần trên được làm từ chất liệu dệt knit màu đen và trắng không chỉ mang lại sự thoải mái mà còn hỗ trợ sự thoáng khí.\r\n\r\nCông nghệ đế giữa Boost giúp tối ưu hóa độ đàn hồi, mang đến cảm giác năng lượng dồi dào cho mỗi bước chân của bạn. Chi tiết gót giày được đồng thương hiệu phối hợp khéo léo, tạo nên vẻ ngoài sang trọng, giúp bạn dễ dàng phối đồ cho cả những buổi tập luyện và những dịp đi chơi.\r\n\r\nTrải nghiệm sự hòa quyện hoàn hảo giữa thời trang và chức năng với đôi giày này, một lựa chọn tuyệt vời cho những ai yêu thích sự nổi bật và thoải mái trong từng hoạt động!', 3900000, 1, 'Shoe/1.png', 'ultraboost-22-black-white', '2025-02-05 16:43:02', '2025-07-03 12:05:10'),
(2, 'NMD Human Race Trail ‘Blank Canvas’', 'Mã sản phẩm: AC7031\r\n\r\nMàu sắc chính: Trắng (White)\r\n\r\nDanh mục sản phẩm: Giày thể thao phong cách (Lifestyle Casual Shoes)\r\n\r\nTình trạng: Mới 100%\r\n\r\nNgày phát hành: 23 tháng 2 năm 2018\r\n\r\nMô tả sản phẩm:\r\n\r\nGiới thiệu mẫu giày Pharrell Williams x adidas NMD Human Race Trail Blank Canvas. Được thiết kế hợp tác với adidas, mẫu giày phiên bản giới hạn này mang lại màu trắng tinh khiết, tạo điều kiện cho bạn thể hiện phong cách cá nhân của mình.\r\n\r\nVới kiểu dáng NMD huyền thoại và dấu ấn đặc trưng của Pharrell, những đôi giày sneaker này trở thành món “phải có” cho các tín đồ thời trang và sneaker. Đặc điểm nổi bật bao gồm phần trên bằng chất liệu Primeknit co giãn, cùng với đế giữa công nghệ Boost mang đến sự thoải mái tối ưu.\r\n\r\nĐừng bỏ lỡ cơ hội sở hữu một sản phẩm độc đáo này—hãy mua ngay hôm nay và nâng cao phong cách sneaker của bạn với Pharrell Williams x adidas NMD Human Race Trail Blank Canvas!', 3500000, 1, 'Shoe/f7e42efee49bfde6e1286df388b0deb7_bed8fd43f8c7ea9ae2b932f11c978c64_1751544945.png', 'nmd-human-race-trail-blank-canvas', '2025-02-05 16:43:02', '2025-07-03 12:15:45'),
(3, 'UltraBoost 22 ‘Black Turbo Mint’', 'Mã sản phẩm: GX5497\r\n\r\nMàu chính: Đen (Black)\r\n\r\nDanh mục sản phẩm: Giày chạy (Running Shoes)\r\n\r\nTình trạng: Mới 100%\r\n\r\nNgày phát hành: 16 tháng 2 năm 2022\r\n\r\nMô tả sản phẩm:\r\n\r\nKhám phá sự kết hợp hoàn hảo giữa hiệu suất và phong cách với adidas UltraBoost 22 ‘Black Turbo Mint Rust’. Đôi giày chạy này sở hữu phần trên màu đen thanh lịch, kèm theo những điểm nhấn nổi bật từ màu turbo mint và rust, mang đến một diện mạo đầy mạnh mẽ và cá tính.\r\n\r\nVới công nghệ UltraBoost tiên tiến, đế giữa cung cấp sự đệm nhẹ nhàng cho bước chạy của bạn, trong khi phần trên Primeknit+ tạo nên một độ vừa vặn thích ứng, ôm sát bàn chân nhưng vẫn thông thoáng. Điều này giúp bạn cảm nhận được sự thoải mái tối đa, dù bạn là một vận động viên nghiêm túc hay chỉ đơn giản là muốn giữ phong cách hàng ngày.\r\n\r\nBên cạnh đó, đế ngoài Continental™ Rubber giúp tăng cường độ bám và ổn định cho từng bước đi, ngay cả trên những bề mặt khác nhau. Hãy nâng tầm hiệu suất và phong cách đường phố của bạn với adidas UltraBoost 22 ‘', 5500000, 1, 'Shoe/b70d3125c133da03f33762bc5441230b_220c921c7d8b100f85616acd0c73d6a6_1751545072.png', 'ultraboost-22-black-turbo-mint', '2025-02-05 16:43:02', '2025-07-03 12:17:52'),
(4, 'Nike Air Max 1 Leather PA ‘White Gum’ 807602-113', 'Mã sản phẩm: 807602-113\r\nThương hiệu: Nike\r\nNhà thiết kế: Tinker Hatfield\r\nCông nghệ: Air\r\nMàu sắc: Trắng\r\nKiểu dáng: Lifestyle', 1900000, 2, 'Shoe/b998cbf1ac491bd97bebe58e4baf1803_0b999d5f71acf217594ca4c3105bc59d_1751545300.png', 'nike-air-max-1-leather-pa-white-gum-807602-113', '2025-02-05 16:43:02', '2025-07-03 12:21:40'),
(5, 'WMNS Air Max 1 Lux’ Just Do It’', 'Mã sản phẩm:917691-100\r\nThương hiệu: Nike\r\nNhà thiết kế: Tinker Hatfield\r\nCông nghệ: Air\r\nMàu sắc: Trắng\r\nKiểu dáng: Lifestyle\r\nFit size: True size (Size chuẩn)', 3900000, 2, 'Shoe/495e5f73b5d8e052f7fbc5a15fb3c15b_eaf0a4d9dbb995db0bb72c5b819fab90_1751545377.png', 'wmns-air-max-1-lux-just-do-it', '2025-02-05 16:43:02', '2025-07-03 12:22:57'),
(6, 'Air Max 1 Atmos We Love Nike ‘Game Royal’', 'Mô tả\r\nMã giày: AQ0927-100\r\nPhối màu: White/Game Royal/Neutral Grey\r\nPhát hành: 27/05/2018\r\nNhà thiết kế: Tinker Hatfield\r\nDanh mục: Air Max', 7500000, 2, 'Shoe/39b76cd97c71edd2609611503f52c465_3e6405450e9c99f803d80b19563cbe46_1751545424.png', 'air-max-1-atmos-we-love-nike-game-royal', '2025-02-05 16:43:02', '2025-07-03 12:23:44'),
(7, 'Air Jordan 1 Retro High OG ‘Hyper Royal’', 'Mã giày: 555088-402\r\nPhối màu: Blue/Grey\r\nNgày phát hành: 17 tháng 4 năm 2021\r\nNhà thiết kế: Peter Moore\r\nDanh mục Air Jordan 1\r\nThương hiệu: Nike', 9900000, 3, 'Shoe/44f7ef74c9651e35f11ae17911c6ced0_27c09816a7572d89534ce3409986b20a_1751545485.png', 'air-jordan-1-retro-high-og-hyper-royal', '2025-02-05 16:43:02', '2025-07-03 12:24:45'),
(8, 'Air Jordan 3 Retro Denim SE Fire Red', 'Mã giày: CZ6431-100\r\n\r\nPhối màu: White/Fire Red/Black\r\n\r\nGiá Retail: $219\r\n\r\nPhát hành: Ngày 28 tháng 08 năm 2020\r\n\r\nThương hiệu: Air Jordan\r\n\r\nNhà thiết kế: Tinker Hatfiel\r\n\r\nDanh mục: Air Jordan 3', 8900000, 3, 'Shoe/235014599a8fd76ed51b99d557e8878b_425b0b4afa6e2f763146fd7002e9389b_1751545554.png', 'air-jordan-3-retro-denim-se-fire-red', '2025-02-05 16:43:02', '2025-07-03 12:25:54'),
(9, 'Nike Air Jordan 3 Retro ‘Racer Blue’', 'Mã sản phẩm: CT8532-145\r\n\r\nPhối màu: WHITE/BLACK-CEMENT GREY-RACER BLUE\r\n\r\nNgày phát hành: 07/10/2021\r\n\r\nThương hiệu: Nike\r\n\r\nNhà thiết kế: Tinker Hatfield\r\n\r\nThiết kế: Air Jordan 3', 8900000, 3, 'Shoe/136303e79944bb6f68b88b07857ba4d6_41a660b7293de244a924e37420d78a87_1751545609.png', 'nike-air-jordan-3-retro-racer-blue', '2025-02-05 16:43:02', '2025-07-03 12:26:49'),
(10, 'Old Skool ‘Navy’', 'Mã giày: VN000D3HNVY\r\n\r\nPhối màu: Navy/White/Blue\r\n\r\nPhát hành: TBC\r\n\r\nNhà thiết kế: Paul Van Doren\r\n\r\nThương hiệu: Vans\r\n\r\nDanh mục: Old Skool', 2100000, 4, 'Shoe/6977991ed4d889bf7f44d1ca19819f46_f9015e95486f123c2aefd02b9a087334_1751545918.png', 'old-skool-navy', '2025-02-05 16:43:02', '2025-07-03 12:31:58'),
(11, 'Old Skool ‘Black Checkerboard’', 'Mã sản phẩm:  VN0A38G1P0S\r\n\r\nPhối màu: Black/White\r\n\r\nNgày phát hành: TBC\r\n\r\nThương hiệu:  Vans\r\n\r\nNhà thiết kế: Paul Van Doren\r\n\r\nDanh mục: Old Skool', 2800000, 4, 'Shoe/5ef6981c1f67a60d57bcc0dd50286a56_a7bd6f579254da6a0d575ebd13428f90_1751546003.png', 'old-skool-black-checkerboard', '2025-02-05 16:43:02', '2025-07-03 12:33:23'),
(12, 'Sk8-Hi ‘Black White’', 'Mã giày: VN000D5IB8C\r\n\r\nPhối màu: Black/ White\r\n\r\nPhát hành: Ngày 29 tháng 09 năm 2019\r\n\r\nNhà thiết kế: Paul Van Doren\r\n\r\nThương hiệu: Vans\r\n\r\nDanh mục: Sk8', 2300000, 4, 'Shoe/0ee4d9829541ed981260f3601122eb11_3fd559d912a53599478afc9746a581ac_1751546096.png', 'sk8-hi-black-white', '2025-02-05 16:43:02', '2025-07-03 12:34:56'),
(13, 'RSX Hard Drive Jr Galaxy Blue', 'Mua giày Puma RSX Hard Drive Jr Galaxy Blue 370644-02 chính hãng 100% có sẵn tại Authentic Shoes. Giao hàng miễn phí trong 1 ngày. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. FREE vệ sinh giày trọn đời. MUA NGAY !', 3500000, 5, 'Shoe/b8b13aabbfdf88dffe3d8c7530f0777c_42dca4e64687cba51371749d621c2f10_1751546175.png', 'rsx-hard-drive-jr-galaxy-blue', '2025-02-05 16:43:02', '2025-07-03 12:36:15'),
(14, 'RS-X Tracks ‘Whisper White Volt’', 'Mua Giày Puma RS-X Tracks ‘Whisper White Volt’ 369332-04 chính hãng 100% có sẵn tại Authentic Shoes. Giao hàng miễn phí trong 1 ngày. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. FREE vệ sinh trọn đời. MUA NGAY!', 5000000, 5, 'Shoe/f33a0ad508ca0189a9ea725911666366_7e0c8c158c942a2e5da0ecfa3e51b930_1751546213.png', 'rs-x-tracks-whisper-white-volt', '2025-02-05 16:43:02', '2025-07-03 12:36:53'),
(15, 'CA Pro Suede ‘White Navy’', 'Mua Giày Puma CA Pro Suede ‘White Navy’ 387327-04 chính hãng 100% có sẵn tại Authentic Shoes. Giao hàng miễn phí trong 1 ngày. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. Dịch vụ vệ sinh trọn đời. MUA NGAY!', 4100000, 5, 'Shoe/9d58644f019f06649160ed404f416bb2_7ab2738cd943487cef506c7f1a585067_1751546282.png', 'ca-pro-suede-white-navy', '2025-02-05 16:43:02', '2025-07-03 12:38:02'),
(16, 'MLB Big Ball Chunky Emboss Boston Red Sox', 'Mã sản phẩm: 32SHC2011-43I\r\n\r\nThương hiệu: MLB\r\n\r\nMàu sắc chính: Trắng (Ivory White)\r\n\r\nDanh mục sản phẩm: Giày Dad Shoes\r\n\r\nMô tả sản phẩm:\r\n\r\nGiới thiệu mẫu giày MLB Big Ball Chunky ‘Ivory White’ 32SHC2011‑43I – đôi giày hoàn hảo cho những người đam mê bóng chày cũng như những tín đồ thời trang. Được chế tác từ các vật liệu cao cấp, những đôi sneaker chunky này mang vẻ đẹp ấn tượng với tông màu ‘Ivory White’ dễ dàng phối hợp với nhiều trang phục.\r\n\r\nThiết kế oversized độc đáo cùng các điểm nhấn tương phản tạo nên sự nổi bật và phong cách. Bên cạnh đó, sự chắc chắn trong cấu trúc giúp đảm bảo cảm giác thoải mái lâu dài.\r\n\r\nHãy nâng tầm phong cách của bạn với MLB Big Ball Chunky ‘Ivory White’ – nơi hội tụ hoàn hảo giữa thời trang và chức năng!', 2500000, 6, 'Shoe/aafe681d67987166bebba5cd7ee168ae_5fcd97ac29ff3f9fb0a5d4cd85e7fb2b_1751546381.png', 'mlb-big-ball-chunky-emboss-boston-red-sox', '2025-02-05 16:43:02', '2025-07-03 12:39:41'),
(17, 'PlayBall Origin Mule NY New York Yankees ‘Mint’', 'Mã sản phẩm: 32SHS1111-50T\r\n\r\nThương hiệu: MLB\r\n\r\nKiểu dáng: Slip-On\r\n\r\nMàu sắc chính: Xanh lá cây\r\n\r\nDanh mục sản phẩm: Giày canvas\r\n\r\nTình trạng: Mới 100%\r\n\r\nMô tả sản phẩm:\r\n\r\nHãy nhanh tay sở hữu đôi giày MLB x Vans Slip-On Low ‘Play Ball Green’ và nâng tầm phong cách của bạn! Những đôi slip-on biểu tượng này có phần trên màu xanh lá cây rực rỡ với logo “MLB” thêu, mang đến phong cách thể thao đầy cuốn hút.\r\n\r\nVới kiểu dáng low-top cổ điển và đế ngoài waffle thoải mái, đôi giày này đảm bảo mang đến sự thoải mái và hỗ trợ cả ngày dài. Hãy thể hiện tình yêu với môn bóng chày trong khi vẫn giữ được vẻ ngoài thời thượng và bất diệt. Đặt hàng ngay hôm nay để nâng cấp bộ sưu tập giày sneaker của bạn!', 2200000, 6, 'Shoe/4b4e02ef1cf5c2a35ea84151d9665290_8ff48c159f0c90d4b426d84bd5c6b548_1751546433.png', 'playball-origin-mule-ny-new-york-yankees-mint', '2025-02-05 16:43:02', '2025-07-03 12:40:33'),
(18, 'PLayBall Origin Mule ‘Black’', 'Mã sản phẩm: 32SHS1111-50L\r\n\r\nNgày phát hành: TBC\r\n\r\nThương hiệu: MLB\r\n\r\nNgày phát hành: TBC\r\n\r\nDanh mục: Playball\r\n\r\nMLB PLayBall Origin MULE BLACK được thiết kế từ chất liệu vải cao cấp, thêm vào đó là các đường nét độc đáo. Điểm nhấn của đôi giày chính là logo thương hiệu MLB.\r\n\r\nĐôi MLB này được thiết kế với kiểu dáng hở gót tiện lợi cho bạn khi mang theo nó mỗi ngày. Giày có gam màu cơ bản giúp dễ phối với nhiều loại quần áo khác nhau. Nếu bạn đã có quá nhiều giày với dây buộc thắt, bạn nên thử một đôi giày mang vào nhanh chóng như một đôi dép mà vẫn lịch sự.\r\n\r\nMLB PLayBall Origin MULE BLAC vẫn giữ nguyên phần upper mặt trước giày nhưng phần sau gót được các nhà thiết kế gọt đi trở thành một đôi giày đạp gót đáng yêu. Với thiết kế độc đáo, trẻ trung, có thể kết hợp được với nhiều trang phục khác nhau, đôi giày này xứng đáng được có trong tủ giày của bạn!', 1900000, 6, 'Shoe/2f84be5df441f87a40faa9cd54c898d8_25cd4adf47a5ac0295887f808c39a3a2_1751546498.png', 'playball-origin-mule-black', '2025-02-05 16:43:02', '2025-07-03 12:41:38'),
(19, 'Converse Chuck 70 High ‘Final Club Obsidian’', 'Mua Giày Converse Chuck 70 High ‘Final Club Obsidian’ 168604C chính hãng 100% có sẵn tại Authentic Shoes. Giao hàng miễn phí trong 1 ngày. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. FREE vệ sinh giày  trọn đời. MUA NGAY!', 2900000, 7, 'Shoe/72b40339e4247e0e56f5d208b269098a_41c17292e6ae5a8727a386d4538ed1a0_1751546555.png', 'converse-chuck-70-high-final-club-obsidian', '2025-02-05 16:43:02', '2025-07-03 12:42:35'),
(20, 'Converse Chuck 70 High ‘Paint Splatter Egret’', 'Mua Giày Converse Chuck 70 High ‘White Blue’ 173100C chính hãng 100% có sẵn tại Authentic Shoes. Giao hàng miễn phí trong 1 ngày. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. FREE vệ sinh giày  trọn đời. MUA NGAY!', 2900000, 7, 'Shoe/742ec67b83daec70ec15b8ff9c623cb6_30ca4aad295e5a78432a630f25c901f4_1751546628.png', 'converse-chuck-70-high-paint-splatter-egret', '2025-02-05 16:43:02', '2025-07-03 12:44:13'),
(21, 'Converse Chuck 70 High ‘Seam Tape Serenity’', 'Mua Giày Converse Chuck 70 High ‘Seam Tape Serenity’ 169525C chính hãng 100% có sẵn tại Authentic Shoes. Giao hàng miễn phí trong 1 ngày. Cam kết đền tiền X5 nếu phát hiện Fake. Đổi trả miễn phí size. FREE vệ sinh giày  trọn đời. MUA NGAY!', 3000000, 7, 'Shoe/8b7d8acf981ebd0853c8362a57b499d1_bf63adfa0829a1ec6279a2b8555bfb58_1751546703.png', 'converse-chuck-70-high-seam-tape-serenity', '2025-02-05 16:43:02', '2025-07-03 12:45:03'),
(22, 'Balenciaga x adidas Triple S White', 'Mô tả\r\nMã sản phẩm: 645056-W2DBQ-1015\r\n\r\nThương hiệu: Balenciaga\r\n\r\nMàu sắc chính: Trắng (White)\r\n\r\nDanh mục sản phẩm: Giày casual lifestyle\r\n\r\nTình trạng: Mới 100%\r\n\r\nNgày phát hành: 21 tháng 3 năm 2022\r\n\r\nMô tả sản phẩm:\r\n\r\nGiới thiệu mẫu giày Balenciaga Speed Sneaker màu Đen – món đồ hoàn hảo cho tủ đồ của bạn từ thương hiệu thiết kế Balenciaga. Với thiết kế trang nhã và hiện đại, những đôi giày này là sự kết hợp hoàn hảo giữa sự thoải mái và xu hướng thời trang.\r\n\r\nVới chất liệu nylon nhẹ và bền bỉ cùng kiểu dáng thời thượng, Balenciaga Speed Sneaker sẽ giúp bạn khẳng định phong cách cá nhân một cách tinh tế.\r\n\r\nHãy mua ngay và nâng cấp bộ sưu tập giày của bạn với đôi giày này – một món phụ kiện không thể thiếu cho những ai yêu thích sự sang trọng và đẳng cấp!', 1900000, 8, 'Shoe/7f6b970d1e367734f5499bc5bed96983_222444a4f0c6110345cc718c2676e5af_1751546818.png', 'balenciaga-x-adidas-triple-s-white', '2025-02-05 16:43:02', '2025-07-03 12:49:47'),
(23, 'Balenciaga Track Sneaker ‘White Orange’', 'Mô tả\r\nMã sản phẩm: 645056-W2DBQ-1015\r\n\r\nThương hiệu: Balenciaga\r\n\r\nMàu sắc chính: Trắng - Cam\r\n\r\nDanh mục sản phẩm: Giày casual lifestyle\r\n\r\nTình trạng: Mới 100%\r\n\r\nNgày phát hành: 21 tháng 3 năm 2022\r\n\r\nMô tả sản phẩm:\r\n\r\nGiới thiệu mẫu giày Balenciaga Speed Sneaker màu Đen – món đồ hoàn hảo cho tủ đồ của bạn từ thương hiệu thiết kế Balenciaga. Với thiết kế trang nhã và hiện đại, những đôi giày này là sự kết hợp hoàn hảo giữa sự thoải mái và xu hướng thời trang.\r\n\r\nVới chất liệu nylon nhẹ và bền bỉ cùng kiểu dáng thời thượng, Balenciaga Speed Sneaker sẽ giúp bạn khẳng định phong cách cá nhân một cách tinh tế.\r\n\r\nHãy mua ngay và nâng cấp bộ sưu tập giày của bạn với đôi giày này – một món phụ kiện không thể thiếu cho những ai yêu thích sự sang trọng và đẳng cấp!', 290000, 8, 'Shoe/3f1df854ab234ad1046bf3bc3c099de2_be1aeaf2eda6adfa312db650c67ae6a9_1751546858.png', 'balenciaga-track-sneaker-white-orange', '2025-02-05 16:43:02', '2025-07-03 12:50:06'),
(24, 'Balenciaga Speed Sneaker ‘Black’', 'Mô tả\r\nMã sản phẩm: 645056-W2DBQ-1015\r\n\r\nThương hiệu: Balenciaga\r\n\r\nMàu sắc chính: Đen (Black)\r\n\r\nDanh mục sản phẩm: Giày casual lifestyle\r\n\r\nTình trạng: Mới 100%\r\n\r\nNgày phát hành: 21 tháng 3 năm 2022\r\n\r\nMô tả sản phẩm:\r\n\r\nGiới thiệu mẫu giày Balenciaga Speed Sneaker màu Đen – món đồ hoàn hảo cho tủ đồ của bạn từ thương hiệu thiết kế Balenciaga. Với thiết kế trang nhã và hiện đại, những đôi giày này là sự kết hợp hoàn hảo giữa sự thoải mái và xu hướng thời trang.\r\n\r\nVới chất liệu nylon nhẹ và bền bỉ cùng kiểu dáng thời thượng, Balenciaga Speed Sneaker sẽ giúp bạn khẳng định phong cách cá nhân một cách tinh tế.\r\n\r\nHãy mua ngay và nâng cấp bộ sưu tập giày của bạn với đôi giày này – một món phụ kiện không thể thiếu cho những ai yêu thích sự sang trọng và đẳng cấp!', 5700000, 8, 'Shoe/58556e7579c10a645459849fe337ee37_27ef16f22b75f0c99be0ff0c345732c1_1751546935.png', 'balenciaga-speed-sneaker-black', '2025-02-05 16:43:02', '2025-07-03 12:48:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_account_id_foreign` (`account_id`),
  ADD KEY `orders_shipper_id_foreign` (`shipper_id`),
  ADD KEY `orders_discount_id_foreign` (`discount_id`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_order_id_foreign` (`order_id`),
  ADD KEY `order_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product`
--
ALTER TABLE `order_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_shipper_id_foreign` FOREIGN KEY (`shipper_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
