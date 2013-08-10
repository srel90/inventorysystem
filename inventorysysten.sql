-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2013 at 06:06 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inventorysysten`
--
CREATE DATABASE IF NOT EXISTS `inventorysysten` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE inventorysysten;

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

CREATE TABLE IF NOT EXISTS `productcategory` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`categoryID`)
) TYPE=InnoDB  AUTO_INCREMENT=3 ;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`categoryID`, `category`, `status`) VALUES
(1, 'Product', 1),
(2, 'Material', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

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
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE IF NOT EXISTS `producttype` (
  `typeID` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`typeID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder`
--

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
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorderdetail`
--

CREATE TABLE IF NOT EXISTS `purchaseorderdetail` (
  `purchaseOrderDetailID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `purchaseOrderID` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อ',
  `productID` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `quantity` int(11) NOT NULL COMMENT 'จำนวน',
  `cost` double NOT NULL COMMENT 'ราคาทุน',
  `total` double NOT NULL COMMENT 'ราคารวม',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`purchaseOrderDetailID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `requisition`
--

CREATE TABLE IF NOT EXISTS `requisition` (
  `requisitionID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `code` varchar(10) NOT NULL COMMENT 'รหัสใบเบิก',
  `requester` varchar(100) NOT NULL COMMENT 'ผู้ขอเบิก',
  `accounttingDate` datetime NOT NULL COMMENT 'วันที่ลงบันทึก',
  `amount` int(11) NOT NULL COMMENT 'จำนวนชิ้น',
  `recordBy` int(11) NOT NULL COMMENT 'บันทึกโดย',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`requisitionID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `requisitiondetail`
--

CREATE TABLE IF NOT EXISTS `requisitiondetail` (
  `requisitionDetailID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `requisitionID` int(11) NOT NULL COMMENT 'รหัสใบเบิก',
  `productID` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `quantity` int(11) NOT NULL COMMENT 'จำนวน',
  PRIMARY KEY (`requisitionDetailID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

CREATE TABLE IF NOT EXISTS `userrole` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `typeID` int(11) NOT NULL COMMENT 'รหัสประเภทผู้ใช้',
  `path` varchar(255) NOT NULL COMMENT 'ไฟล์ที่สามารถเข้าถึง',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`roleID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  `registerDate` datetime NOT NULL COMMENT 'วันที่ลงทะเบียน',
  `lastAccess` datetime NOT NULL COMMENT 'วันที่เข้าใช้งานล่าสุด',
  `status` int(1) NOT NULL COMMENT 'สถานะ',
  `username` varchar(100) NOT NULL COMMENT 'ชื่อผู้ใช้',
  `password` varchar(32) NOT NULL COMMENT 'รหัสผ่าน',
  `typeID` int(11) NOT NULL COMMENT 'ประเภทผู้ใช้งาน',
  PRIMARY KEY (`userID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE IF NOT EXISTS `usertype` (
  `typeID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับ',
  `type` varchar(100) NOT NULL COMMENT 'ประเภทผู้ใช้',
  `status` int(11) NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`typeID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
