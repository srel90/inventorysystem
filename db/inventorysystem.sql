-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 10, 2013 at 12:14 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inventorysystem`
--
CREATE DATABASE IF NOT EXISTS `inventorysystem` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE inventorysystem;

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

DROP TABLE IF EXISTS `productcategory`;
CREATE TABLE IF NOT EXISTS `productcategory` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`categoryID`)
) TYPE=InnoDB  AUTO_INCREMENT=5 ;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`categoryID`, `category`, `status`) VALUES
(1, 'Product', 1),
(2, 'Material', 1),
(4, 'test2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `code` varchar(7) NOT NULL COMMENT 'รหัสสินค้า',
  `categoryID` int(11) NOT NULL COMMENT 'หมวดหมู่สินค้า product / material',
  `typeID` int(11) NOT NULL COMMENT 'ประเภทสินค้า',
  `name` varchar(255) NOT NULL COMMENT 'ชื่อสินค้า',
  `description` text NOT NULL COMMENT 'รายละเอียด',
  `image` varchar(255) NOT NULL COMMENT 'รูปสินค้า',
  `color` varchar(50) NOT NULL COMMENT 'สี',
  `size` varchar(255) NOT NULL COMMENT 'ขนาด',
  `listOfMaterial` text NOT NULL COMMENT 'รายการวัตถุดิบ',
  `price` double NOT NULL COMMENT 'ราคา',
  `cost` double NOT NULL COMMENT 'ต้นทุน',
  `unit` varchar(100) NOT NULL COMMENT 'หน่วยนับ',
  `quantity` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `pointOfOrder` int(11) NOT NULL COMMENT 'จุดสั่งซื้อ',
  `supplier` varchar(255) NOT NULL COMMENT 'ผู้จำหน่าย',
  `contactPerson` varchar(255) NOT NULL COMMENT 'ชื่อผู้ติดต่อ',
  `contactNumber` varchar(100) NOT NULL COMMENT 'หมายเลขโทรศัพท์',
  `lastUpdate` datetime NOT NULL COMMENT 'ปรับปรุงล่าสุด',
  `updateBy` int(11) NOT NULL COMMENT 'ปรับปรุงโดย',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`productID`)
) TYPE=InnoDB  AUTO_INCREMENT=4 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `code`, `categoryID`, `typeID`, `name`, `description`, `image`, `color`, `size`, `listOfMaterial`, `price`, `cost`, `unit`, `quantity`, `pointOfOrder`, `supplier`, `contactPerson`, `contactNumber`, `lastUpdate`, `updateBy`, `status`) VALUES
(1, '0000001', 1, 1, 'แผ่นกรองอากาศ amway', 'แผ่นกรองอากาศ amway', '1_9rvku6oIXa546_521_climate@risk.jpg', 'test', 'test', 'test', 5.5, 5, 'แผ่น', 110, 100, 'test', 'test', 'test', '2013-08-06 12:00:46', 1, 1),
(2, '0000002', 1, 1, 'ทดสอบ', 'ทดสอบ', '', 'test', 'test', 'test', 0, 10999, 'test', 96, 10, 'test', 'test', 'test', '2013-08-05 11:40:50', 1, 1),
(3, '0000003', 2, 3, 'ฝาน้ำ ยี้ห้อทดสอบ', 'ฟหกดฟหกด หฟกดฟห ฟดฟหกด', '3_Pn5tXxSfHK546_521_climate@risk.jpg', 'test', 'test', 'test', 1111, 11, 'test', 95, 100, 'test', 'test', 'test', '2013-08-05 10:10:10', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

DROP TABLE IF EXISTS `producttype`;
CREATE TABLE IF NOT EXISTS `producttype` (
  `typeID` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL COMMENT 'รหัสหมวดหมูสินค้า',
  PRIMARY KEY (`typeID`)
) TYPE=InnoDB  AUTO_INCREMENT=4 ;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`typeID`, `type`, `status`, `categoryID`) VALUES
(1, 'แผ่นกรองอากาศ', 1, 1),
(2, 'ผ้ากรอง', 1, 2),
(3, 'ฝา', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder`
--

DROP TABLE IF EXISTS `purchaseorder`;
CREATE TABLE IF NOT EXISTS `purchaseorder` (
  `purchaseOrderID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `code` varchar(7) NOT NULL COMMENT 'รหัสใบสั่งซื้อ',
  `orderDate` datetime NOT NULL COMMENT 'วันที่สั่ง',
  `orderBy` varchar(100) NOT NULL COMMENT 'สั่งโดย',
  `amount` int(11) NOT NULL COMMENT 'จำนวน',
  `grandTotal` double NOT NULL COMMENT 'ราคาต้นทุนรวม',
  `receiveDate` datetime NOT NULL COMMENT 'จัดส่งสินค้าวันที่',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`purchaseOrderID`)
) TYPE=InnoDB;

--
-- Dumping data for table `purchaseorder`
--

INSERT INTO `purchaseorder` (`purchaseOrderID`, `code`, `orderDate`, `orderBy`, `amount`, `grandTotal`, `receiveDate`, `status`) VALUES
(1, '0000001', '2013-08-05 00:00:00', '1', 1, 50, '0000-00-00 00:00:00', 2),
(2, '0000002', '2013-08-05 00:00:00', '1', 2, 11, '0000-00-00 00:00:00', 2),
(3, '0000003', '2013-08-05 00:00:00', '1', 1, 10999, '0000-00-00 00:00:00', 2),
(4, '0000004', '2013-08-06 00:00:00', '1', 3, 11015, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorderdetail`
--

DROP TABLE IF EXISTS `purchaseorderdetail`;
CREATE TABLE IF NOT EXISTS `purchaseorderdetail` (
  `purchaseOrderDetailID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `purchaseOrderID` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อ',
  `productID` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `quantity` int(11) NOT NULL COMMENT 'จำนวน',
  `cost` double NOT NULL COMMENT 'ราคาทุน',
  `total` double NOT NULL COMMENT 'ราคารวม',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`purchaseOrderDetailID`)
) TYPE=InnoDB  AUTO_INCREMENT=8 ;

--
-- Dumping data for table `purchaseorderdetail`
--

INSERT INTO `purchaseorderdetail` (`purchaseOrderDetailID`, `purchaseOrderID`, `productID`, `quantity`, `cost`, `total`, `status`) VALUES
(1, 1, 1, 10, 5, 50, 2),
(2, 2, 2, 1, 0, 0, 2),
(3, 2, 3, 1, 11, 11, 2),
(4, 3, 2, 1, 10999, 10999, 2),
(5, 4, 1, 1, 5, 5, 1),
(6, 4, 2, 1, 10999, 10999, 1),
(7, 4, 3, 1, 11, 11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `requisition`
--

DROP TABLE IF EXISTS `requisition`;
CREATE TABLE IF NOT EXISTS `requisition` (
  `requisitionID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `code` varchar(10) NOT NULL COMMENT 'รหัสใบเบิก',
  `requester` varchar(100) NOT NULL COMMENT 'ผู้ขอเบิก',
  `accounttingDate` datetime NOT NULL COMMENT 'วันที่ลงบันทึก',
  `amount` int(11) NOT NULL COMMENT 'จำนวนชิ้น',
  `recordBy` int(11) NOT NULL COMMENT 'บันทึกโดย',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`requisitionID`)
) TYPE=InnoDB  AUTO_INCREMENT=10 ;

--
-- Dumping data for table `requisition`
--

INSERT INTO `requisition` (`requisitionID`, `code`, `requester`, `accounttingDate`, `amount`, `recordBy`, `status`) VALUES
(1, '0000001', 'test', '2013-08-04 14:09:29', 1, 1, 1),
(2, '0000002', 'test', '2013-08-04 14:12:04', 1, 1, 1),
(3, '0000003', 'ทดสอบ', '2013-08-04 14:26:03', 1, 1, 1),
(4, '0000004', 'ทดสอบอีก', '2013-08-04 14:37:30', 2, 1, 1),
(5, '0000005', 'test', '2013-08-04 15:38:54', 2, 1, 1),
(6, '0000006', 'test', '2013-08-05 10:11:10', 3, 1, 1),
(7, '0000007', 'test', '2013-08-05 10:12:27', 1, 1, 1),
(8, '0000008', 'test', '2013-08-05 10:12:45', 1, 1, 1),
(9, '0000009', 'test', '2013-08-05 10:29:43', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `requisitiondetail`
--

DROP TABLE IF EXISTS `requisitiondetail`;
CREATE TABLE IF NOT EXISTS `requisitiondetail` (
  `requisitionDetailID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `requisitionID` int(11) NOT NULL COMMENT 'รหัสใบเบิก',
  `productID` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `quantity` int(11) NOT NULL COMMENT 'จำนวน',
  PRIMARY KEY (`requisitionDetailID`)
) TYPE=InnoDB  AUTO_INCREMENT=14 ;

--
-- Dumping data for table `requisitiondetail`
--

INSERT INTO `requisitiondetail` (`requisitionDetailID`, `requisitionID`, `productID`, `quantity`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 3, 1, 10),
(4, 4, 1, 10),
(5, 4, 2, 10),
(6, 5, 1, 10),
(7, 5, 2, 10),
(8, 6, 1, 10),
(9, 6, 2, 10),
(10, 6, 3, 910),
(11, 7, 1, 1),
(12, 8, 2, 1),
(13, 9, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

DROP TABLE IF EXISTS `userrole`;
CREATE TABLE IF NOT EXISTS `userrole` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `typeID` int(11) NOT NULL COMMENT 'รหัสประเภทผู้ใช้',
  `path` varchar(255) NOT NULL COMMENT 'ไฟล์ที่สามารถเข้าถึง',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`roleID`)
) TYPE=InnoDB  AUTO_INCREMENT=5 ;

--
-- Dumping data for table `userrole`
--

INSERT INTO `userrole` (`roleID`, `typeID`, `path`, `status`) VALUES
(1, 1, '*', 1),
(2, 2, 'producttype.php', 1),
(3, 2, 'product.php', 1),
(4, 2, 'productcategory.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `code` varchar(7) NOT NULL COMMENT 'รหัสพนักงาน',
  `IDCard` varchar(13) NOT NULL COMMENT 'รหัสประจำตัวประชาชน',
  `firstName` varchar(100) NOT NULL COMMENT 'ชื่อ',
  `lastName` varchar(100) NOT NULL COMMENT 'นามสกุล',
  `address` text NOT NULL COMMENT 'ที่อยู่',
  `phone` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `mobile` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์เคลื่อนที่',
  `email` varchar(100) NOT NULL COMMENT 'อีเมล์',
  `position` varchar(255) NOT NULL COMMENT 'ตำแหน่ง',
  `registerDate` date NOT NULL COMMENT 'วันที่ลงทะเบียน',
  `lastAccess` datetime NOT NULL COMMENT 'วันที่เข้าใช้งานล่าสุด',
  `status` int(1) NOT NULL COMMENT 'สถานะ',
  `username` varchar(100) NOT NULL COMMENT 'ชื่อผู้ใช้',
  `password` varchar(32) NOT NULL COMMENT 'รหัสผ่าน',
  `typeID` int(11) NOT NULL COMMENT 'ประเภทผู้ใช้งาน',
  PRIMARY KEY (`userID`)
) TYPE=InnoDB  AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `code`, `IDCard`, `firstName`, `lastName`, `address`, `phone`, `mobile`, `email`, `position`, `registerDate`, `lastAccess`, `status`, `username`, `password`, `typeID`) VALUES
(1, '0000000', '3929800021686', 'Vilerswat', 'Noosaeng', 'test', '0840900050', '0840900050', 'srel90@gmail.com', 'test', '0000-00-00', '2013-08-10 13:00:21', 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),
(3, '0000003', '3929800021686', 'วิเลิศวัฒน์', 'หนูแสง', 'test', '66840900050', '66840900050', 'srel90@gmail.com', 'test', '2013-07-30', '2013-08-05 09:42:54', 1, 'test', '098f6bcd4621d373cade4e832627b4f6', 2);

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

DROP TABLE IF EXISTS `usertype`;
CREATE TABLE IF NOT EXISTS `usertype` (
  `typeID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `type` varchar(100) NOT NULL COMMENT 'ประเภทผู้ใช้',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`typeID`)
) TYPE=InnoDB  AUTO_INCREMENT=4 ;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`typeID`, `type`, `status`) VALUES
(1, 'Administrator', 1),
(2, 'user', 1),
(3, 'Manager', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
