-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 05, 2024 at 03:05 PM
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
-- Database: `ChicHub`
--

-- --------------------------------------------------------

--
-- Table structure for table `Banner`
--

CREATE TABLE `Banner` (
  `Bid` int(255) NOT NULL,
  `B_Name` varchar(255) NOT NULL,
  `B_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Banner`
--

INSERT INTO `Banner` (`Bid`, `B_Name`, `B_img`) VALUES
(1, 'โปรโมชั่นลดสูงสุด 75%', 'http://localhost/project/ChichHub/Component/img/Banner/Banner.png');

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `C_ID` int(5) NOT NULL,
  `C_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`C_ID`, `C_Name`) VALUES
(1001, 'T-shirt'),
(1002, 'Pants'),
(1003, 'Promotion');

-- --------------------------------------------------------

--
-- Table structure for table `Images`
--

CREATE TABLE `Images` (
  `IMG_ID` int(10) NOT NULL,
  `File_name` varchar(20) NOT NULL,
  `Upload_date` datetime NOT NULL,
  `IMG_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Images`
--

INSERT INTO `Images` (`IMG_ID`, `File_name`, `Upload_date`, `IMG_path`) VALUES
(43, 'kiddo.png', '2024-11-04 14:03:17', 'http://localhost/project/ChichHub/Component/img/Pants/kiddo.png'),
(44, 'pants1.png', '2024-11-04 14:04:26', 'http://localhost/project/ChichHub/Component/img/Pants/pants1.png'),
(45, 'pink-floyd.png', '2024-11-04 14:04:59', 'http://localhost/project/ChichHub/Component/img/Pants/pink-floyd.png'),
(46, 'soldier prang.png', '2024-11-04 14:05:38', 'http://localhost/project/ChichHub/Component/img/Pants/soldier prang.png'),
(47, 'Hooded T-Shirt.png', '2024-11-04 14:06:17', 'http://localhost/project/ChichHub/Component/img/T-shirt/Hooded T-Shirt.png'),
(48, 'Mercenary Tao.png', '2024-11-04 14:07:02', 'http://localhost/project/ChichHub/Component/img/T-shirt/Mercenary.png'),
(49, 'Nautical-Print.png', '2024-11-04 14:07:39', 'http://localhost/project/ChichHub/Component/img/T-shirt/Nautical.png'),
(50, 'Polo Bear.png', '2024-11-04 14:08:13', 'http://localhost/project/ChichHub/Component/img/T-shirt/Polo Bear.png'),
(51, 'V-Neck.png', '2024-11-04 14:09:02', 'http://localhost/project/ChichHub/Component/img/T-shirt/V-Neck.png'),
(52, 'Sherlock set 3.png', '2024-11-04 14:14:01', 'http://localhost/project/ChichHub/Component/img/Promotion/Sherlock set 3.png'),
(55, 'Double-Knit.png', '2024-11-04 15:23:10', 'http://localhost/project/ChichHub/Component/img/Promotion/Double-Knit.png'),
(56, 'Doublebronze.png', '2024-11-04 15:23:39', 'http://localhost/project/ChichHub/Component/img/Promotion/Doublebronze.png'),
(57, 'Doublekniyblue.png', '2024-11-04 15:24:10', 'http://localhost/project/ChichHub/Component/img/Promotion/Doublekniyblue.png'),
(58, 'Doublered.png', '2024-11-04 15:24:49', 'http://localhost/project/ChichHub/Component/img/Promotion/Doublered.png'),
(59, 'RL set gray.png', '2024-11-04 15:25:20', 'http://localhost/project/ChichHub/Component/img/Promotion/RL set gray.png'),
(60, 'Classicb.png', '2024-11-04 15:28:02', 'http://localhost/project/ChichHub/Component/img/T-shirt/Classicb.png'),
(61, 'ClassicOr.png', '2024-11-04 15:28:34', 'http://localhost/project/ChichHub/Component/img/T-shirt/ClassicOr.png'),
(62, 'ClassicPurple.png', '2024-11-04 15:29:01', 'http://localhost/project/ChichHub/Component/img/T-shirt/ClassicPurple.png'),
(63, 'Classigreen.png', '2024-11-04 15:29:28', 'http://localhost/project/ChichHub/Component/img/T-shirt/Classigreen.png'),
(64, 'Clsssicgray.png', '2024-11-04 15:30:04', 'http://localhost/project/ChichHub/Component/img/T-shirt/Clsssicgray.png'),
(65, 'PoloRed.png', '2024-11-04 15:30:25', 'http://localhost/project/ChichHub/Component/img/T-shirt/PoloRed.png'),
(66, 'HeritageWhite.png', '2024-11-04 15:32:01', 'http://localhost/project/ChichHub/Component/img/Pants/HeritageWhite.png'),
(67, 'jeanChestnut.png', '2024-11-04 15:32:29', 'http://localhost/project/ChichHub/Component/img/Pants/jeanChestnut.png'),
(68, 'Metallicb.png', '2024-11-04 15:32:54', 'http://localhost/project/ChichHub/Component/img/Pants/Metallicb.png'),
(69, 'Slimdark.png', '2024-11-04 15:33:21', 'http://localhost/project/ChichHub/Component/img/Pants/Slimdark.png'),
(70, 'Slimsand.png', '2024-11-04 15:33:53', 'http://localhost/project/ChichHub/Component/img/Pants/Slimsand.png');

-- --------------------------------------------------------

--
-- Table structure for table `Member`
--

CREATE TABLE `Member` (
  `ID` int(5) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `MD_ID` int(5) NOT NULL,
  `Role` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Member`
--

INSERT INTO `Member` (`ID`, `Username`, `Password`, `MD_ID`, `Role`) VALUES
(2010, 'meen', '$2y$10$/EJ2.WsYYsx0bOsEhhyjKeR1XCZBDFDS52/TcMTjAoYleoW1bhChS', 2014, 0),
(2011, 'Nuttapat1', '$2y$10$eZXtAglP2a.8IpmNCBomCe7YZjCkBFDjiZIPRswa.U/3vzLR7YwcO', 2015, 1),
(2012, 'Volkk', '$2y$10$rbu3dfjXksMGASSUGfRleu3wYgoiZ6/kan7jbpiec1hdmmt/qjSGK', 2016, 0),
(2013, 'Volkk1', '$2y$10$KD08Rwmo45L6TzTKTtbkf.sLowxEkbpZAOFFtd8HhI.CkMLnv6Gum', 2017, 0),
(2014, 'Folk12', '$2y$10$zbCwFVOGtNU0lnyayy9zR.KatV1da5iY6Wlb3s8cBEbcvkf0H4wcy', 2018, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Member_detail`
--

CREATE TABLE `Member_detail` (
  `MD_ID` int(5) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Tel` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Member_detail`
--

INSERT INTO `Member_detail` (`MD_ID`, `Name`, `Surname`, `Email`, `Tel`, `Address`) VALUES
(2001, 'Cristiano', 'Ronaldo', 'Ronaldo@gmail.com', '192929292', 'Mars'),
(2002, 'Leonel', 'Messi', 'Messiloveyou@gmail.com', '999999999', 'Jupiter'),
(2004, 'พลับ', 'สุดหล่อ', 'Nuttpat@email.com', '0918098123', 'อยู่ไหนก็ได้'),
(2005, 'สาฟหก', 'สุดหล่อdasd', 'wflkjk123jf@lksdj.com', '928304123', 'จังหวัด: พระนครศรีอยุธยา, อำเภอ: นครหลวง, ตำบล: ท่าช้าง, รหัสไปรษณีย์: 13260'),
(2006, 'volk', 'autistic', 'wflkjkjfasd@lksdj.com', '928304099', 'จังหวัด: ปทุมธานี, อำเภอ: ธัญบุรี, ตำบล: รังสิต, รหัสไปรษณีย์: 12130'),
(2007, 'zafe', 'naheee', 'zafe@email.com', '198230981', 'จังหวัด: นนทบุรี, อำเภอ: บางใหญ่, ตำบล: , รหัสไปรษณีย์: '),
(2008, 'สาฟหก', 'สุดหล่อdasd', 'wflkjkjf@lksdj.com', '0192309109', 'จังหวัด: นนทบุรี, อำเภอ: บางใหญ่, ตำบล: , รหัสไปรษณีย์: 11000'),
(2009, 'sdf', 'sdf', 'wflkjk12jf@lksdj.com', '0928304099', 'จังหวัด: นนทบุรี, อำเภอ: บางบัวทอง, ตำบล: , รหัสไปรษณีย์: '),
(2014, 'meen', 'sudsuay', 'meen@mail.com', '0928304099', 'จังหวัด: กรุงเทพมหานคร, อำเภอ: เขตหนองจอก, ตำบล: , รหัสไปรษณีย์: '),
(2015, 'Nuttapat', 'Admin', 'Admin@mail.com', '0911111111', 'ดาวอังคาร123'),
(2016, 'Volkk', 'Pny', 'vkk1234@gmail.com', '0774352122', 'จังหวัด: สมุทรปราการ, อำเภอ: บางพลี, ตำบล: บางปลา, รหัสไปรษณีย์: 10540'),
(2017, 'Volkk', 'Vkk', 'user.name@domain.co', '0534212431', 'จังหวัด: สมุทรปราการ, อำเภอ: , ตำบล: , รหัสไปรษณีย์: '),
(2018, 'Folk', 'Pny', 'sw1@123e.com', '0345346535', 'จังหวัด: กรุงเทพมหานคร, อำเภอ: เขตพระนคร, ตำบล: วังบูรพาภิรมย์, รหัสไปรษณีย์: 10200');

-- --------------------------------------------------------

--
-- Table structure for table `Orders`
--

CREATE TABLE `Orders` (
  `Ord_id` int(5) NOT NULL,
  `Date` date NOT NULL,
  `Time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ID` int(5) NOT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Orders`
--

INSERT INTO `Orders` (`Ord_id`, `Date`, `Time`, `ID`, `shipping_address`, `payment_method`) VALUES
(4165, '2024-11-04', '2024-11-04 09:07:20', 2011, 'ดาวอังคาร123', 'cash'),
(4166, '2024-11-04', '2024-11-04 09:07:50', 2011, 'ดาวอังคาร123ๆไา่หสกา่ดทหกเอห เหกด', 'cash'),
(4167, '2024-11-04', '2024-11-04 09:15:32', 2011, 'Los Angelis', 'credit_card'),
(4168, '2024-11-04', '2024-11-04 09:47:54', 2011, 'ดาวอังคาร123', 'credit_card'),
(4169, '2024-11-04', '2024-11-04 10:16:38', 2012, 'จังหวัด: สมุทรปราการ, อำเภอ: บางพลี, ตำบล: บางปลา, รหัสไปรษณีย์: 10540', 'credit_card'),
(4170, '2024-11-04', '2024-11-04 10:22:50', 2012, 'จังหวัด: สมุทรปราการ, อำเภอ: บางพลี, ตำบล: บางปลา, รหัสไปรษณีย์: 10540', 'mobile_banking'),
(4171, '2024-11-04', '2024-11-04 12:47:14', 2012, 'จังหวัด: สมุทรปราการ, อำเภอ: บางพลี, ตำบล: บางปลา, รหัสไปรษณีย์: 10540', 'mobile_banking'),
(4172, '2024-11-05', '2024-11-04 18:12:57', 2012, 'จังหวัด: สมุทรปราการ, อำเภอ: บางพลี, ตำบล: บางปลา, รหัสไปรษณีย์: 10540', 'mobile_banking');

-- --------------------------------------------------------

--
-- Table structure for table `Ord_detail`
--

CREATE TABLE `Ord_detail` (
  `OD_ID` int(5) NOT NULL,
  `P_ID` int(5) NOT NULL,
  `Ord_id` int(5) NOT NULL,
  `Amount` int(255) NOT NULL,
  `Payment_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Ord_detail`
--

INSERT INTO `Ord_detail` (`OD_ID`, `P_ID`, `Ord_id`, `Amount`, `Payment_status`) VALUES
(48, 6045, 4165, 1, 'paid'),
(49, 6043, 4166, 1, 'paid'),
(50, 6061, 4167, 1, 'paid'),
(51, 6042, 4167, 1, 'paid'),
(52, 6050, 4167, 1, 'paid'),
(53, 6041, 4168, 1, 'paid'),
(54, 6042, 4168, 1, 'paid'),
(55, 6044, 4168, 1, 'paid'),
(56, 6067, 4169, 1, 'paid'),
(57, 6041, 4170, 1, 'paid'),
(58, 6044, 4170, 1, 'paid'),
(59, 6048, 4170, 1, 'paid'),
(60, 6041, 4171, 1, 'paid'),
(61, 6041, 4172, 1, 'paid'),
(62, 6042, 4172, 1, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `P_ID` int(5) NOT NULL,
  `P_name` varchar(255) NOT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Amount` int(10) NOT NULL,
  `C_ID` int(5) NOT NULL,
  `IMG_ID` int(5) NOT NULL,
  `Color` varchar(255) NOT NULL,
  `Detail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`P_ID`, `P_name`, `Price`, `Amount`, `C_ID`, `IMG_ID`, `Color`, `Detail`) VALUES
(6041, 'Blue kids Jeans', 999.00, 200, 1002, 43, 'Blue', 'เกงยีนสีฟ้าอ่อนสำหรับเด็ก'),
(6042, 'เกงเทา', 699.00, 200, 1002, 44, 'Grey', 'เกงเทาราขึ้นไม่ต้องซัก ถ้าซักสีเปลี่ยน'),
(6043, 'เกงชมพู', 299.00, 1000, 1002, 45, 'Pink', 'เกงใส่นอน เข้ากับทุกบรรยากาศ'),
(6044, 'เกงลายทหาร', 999.00, 200, 1002, 46, 'Green', 'เกงลายทหารเหมาะสำหรับไปรบ'),
(6045, 'เสื้อเขียวลายทาง', 999.00, 1222, 1001, 47, 'green', 'เสื้อเขียวลายทาง เหลืองขาวใส่แล้วดูสปอร์ตใจดี'),
(6046, 'เสื้อขาวสาวกรี้ด', 199.00, 1000, 1001, 48, 'white', 'เสื้อสีขาวสะอาด แต่อย่าใส่ไปกินก๋วยเตี๋ยว'),
(6047, 'เสื้อขาวมีลาย', 499.00, 100, 1001, 49, 'White', 'เสื้อขาวลายไรไม่รู้มองไม่เห็น'),
(6048, 'เสื้อดำมีลาย', 99.00, 2000, 1001, 50, 'Black', 'เสื้อ polo ลายหมี'),
(6049, 'เสื้อดำ', 299.00, 1231, 1001, 51, 'Black', 'เสื้อดำโง่ๆ แต่ดูแพง'),
(6050, 'Promotion เสื้อ+กางเกง ลด 50%', 1599.00, 90, 1003, 52, 'Black\r\n', 'Promotion เสื้อ+กางเกง ลด 50% จากเดิม 3199'),
(6053, 'Promotion Doubleknit', 1299.00, 30, 1003, 55, 'Green', 'Promotion off 50%'),
(6054, 'Promotion Double knit Bronze', 799.00, 30, 1003, 56, 'Bronze', 'Promotion off 50%'),
(6055, 'Promotion Double knit blue', 199.00, 200, 1003, 57, 'Blue', 'PRomot'),
(6056, 'Promotion Red Double Knit', 499.00, 200, 1003, 58, 'Red', 'Promotion off 50%'),
(6057, 'Promotion Grey Doubleknit', 799.00, 300, 1003, 59, 'Grey', 'Promotion off 50%'),
(6058, 'Classic blue t-shirt', 299.00, 200, 1001, 60, 'Blue', 'Blue t-shirt'),
(6059, 'Classic Orange t-shirt', 899.00, 80, 1001, 61, 'Orange', 'Classic Orange t-shirt'),
(6060, 'Purple Classic t-shirt ', 899.00, 200, 1001, 62, 'Purple', 'Classi t-shirt'),
(6061, 'Classic Green t-shirt', 599.00, 900, 1001, 63, 'Green', 'Classic Green t-shirt'),
(6062, 'Classic Grey t-shirt', 200.00, 200, 1001, 64, 'Grey', 'Grey t-shirt'),
(6063, 'Polo Red', 1299.00, 200, 1001, 65, 'Red', 'Polo Red'),
(6064, 'Heritage jeans White pants', 799.00, 200, 1002, 66, 'White', 'White pants'),
(6065, 'slim Jeans chestnut pants ', 899.00, 90, 1002, 67, 'Brown', 'Brown pants'),
(6066, 'Slim Metallic Black pants', 1299.00, 90, 1002, 68, 'Black', 'Black pants'),
(6067, 'Slim dark pants', 699.00, 900, 1002, 69, 'black', 'dark pants'),
(6068, 'Sand color pants', 399.00, 200, 1002, 70, 'Yellow', 'Yellow pants');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Banner`
--
ALTER TABLE `Banner`
  ADD PRIMARY KEY (`Bid`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`C_ID`);

--
-- Indexes for table `Images`
--
ALTER TABLE `Images`
  ADD PRIMARY KEY (`IMG_ID`);

--
-- Indexes for table `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Member_detail`
--
ALTER TABLE `Member_detail`
  ADD PRIMARY KEY (`MD_ID`);

--
-- Indexes for table `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`Ord_id`);

--
-- Indexes for table `Ord_detail`
--
ALTER TABLE `Ord_detail`
  ADD PRIMARY KEY (`OD_ID`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`P_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Banner`
--
ALTER TABLE `Banner`
  MODIFY `Bid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `C_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT for table `Images`
--
ALTER TABLE `Images`
  MODIFY `IMG_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `Member`
--
ALTER TABLE `Member`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2015;

--
-- AUTO_INCREMENT for table `Member_detail`
--
ALTER TABLE `Member_detail`
  MODIFY `MD_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2019;

--
-- AUTO_INCREMENT for table `Orders`
--
ALTER TABLE `Orders`
  MODIFY `Ord_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4173;

--
-- AUTO_INCREMENT for table `Ord_detail`
--
ALTER TABLE `Ord_detail`
  MODIFY `OD_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `P_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6069;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
