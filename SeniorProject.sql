-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2018 at 04:36 PM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SeniorProject`
--

-- --------------------------------------------------------

--
-- Table structure for table `Addon`
--

CREATE TABLE `Addon` (
  `Addon_ID` int(5) NOT NULL,
  `Addon_Room_ID` int(5) NOT NULL,
  `Addon_Host_ID` int(11) NOT NULL DEFAULT '1',
  `Addon_Name` varchar(20) NOT NULL,
  `Addon_Description` varchar(40) NOT NULL,
  `Addon_Pin` int(2) NOT NULL,
  `Addon_State` decimal(3,2) NOT NULL DEFAULT '0.00',
  `Addon_Type` varchar(2) NOT NULL,
  `Addon_Order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Addon`
--

INSERT INTO `Addon` (`Addon_ID`, `Addon_Room_ID`, `Addon_Host_ID`, `Addon_Name`, `Addon_Description`, `Addon_Pin`, `Addon_State`, `Addon_Type`, `Addon_Order`) VALUES
(1, 1, 1, 'Light #1', 'Testing it out', 1, '0.00', 'L', 0),
(2, 2, 1, 'Light #2', 'The hot lamp', 2, '0.00', 'L', 0),
(5, 1, 1, 'Slider #1', 'By the chair', 4, '0.00', 'S', 0),
(7, 2, 1, 'Fan #1', 'Above bed', 5, '0.00', 'F', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE `Groups` (
  `Groups_gID` int(5) NOT NULL,
  `Groups_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Groups`
--

INSERT INTO `Groups` (`Groups_gID`, `Groups_Name`) VALUES
(1, 'Admin'),
(2, 'Default');

-- --------------------------------------------------------

--
-- Table structure for table `Hosts`
--

CREATE TABLE `Hosts` (
  `Host_ID` int(11) NOT NULL,
  `Host_IP` varchar(50) NOT NULL,
  `Host_Mac` varchar(50) DEFAULT NULL,
  `Host_Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Hosts`
--

INSERT INTO `Hosts` (`Host_ID`, `Host_IP`, `Host_Mac`, `Host_Name`) VALUES
(1, '127.0.0.1', NULL, 'Pi');

-- --------------------------------------------------------

--
-- Table structure for table `House`
--

CREATE TABLE `House` (
  `House_ID` int(5) NOT NULL,
  `House_Name` varchar(20) NOT NULL,
  `House_Code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `House`
--

INSERT INTO `House` (`House_ID`, `House_Name`, `House_Code`) VALUES
(1, 'Joe\'s House', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `House_Assignment`
--

CREATE TABLE `House_Assignment` (
  `Assign_House_ID` int(5) NOT NULL,
  `Assign_User_ID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `House_Assignment`
--

INSERT INTO `House_Assignment` (`Assign_House_ID`, `Assign_User_ID`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Room`
--

CREATE TABLE `Room` (
  `Room_ID` int(5) NOT NULL,
  `House_ID` int(5) NOT NULL,
  `Room_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Room`
--

INSERT INTO `Room` (`Room_ID`, `House_ID`, `Room_Name`) VALUES
(1, 1, 'Living Room'),
(2, 1, 'Bedroom'),
(4, 1, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `Scenes`
--

CREATE TABLE `Scenes` (
  `Scene_ID` int(11) NOT NULL,
  `House_ID` int(11) NOT NULL,
  `Scene_Name` int(11) NOT NULL,
  `Start_Time` time NOT NULL,
  `End_Time` time NOT NULL,
  `Is_Automated` int(11) NOT NULL,
  `Scene_Order` int(11) NOT NULL,
  `Scene_Color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Scene_Assignment`
--

CREATE TABLE `Scene_Assignment` (
  `Scene_ID` int(11) NOT NULL,
  `Addon_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Temp`
--

CREATE TABLE `Temp` (
  `Temp_ID` int(11) NOT NULL,
  `House_ID` int(11) NOT NULL,
  `C` int(11) NOT NULL,
  `F` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Temp`
--

INSERT INTO `Temp` (`Temp_ID`, `House_ID`, `C`, `F`) VALUES
(1, 1, 10, 50);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `User_ID` int(5) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `User_gID` int(5) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`User_ID`, `Username`, `Password`, `User_gID`) VALUES
(2, 'danzerdude10', 'password', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Addon`
--
ALTER TABLE `Addon`
  ADD PRIMARY KEY (`Addon_ID`),
  ADD KEY `Addon_Room_ID` (`Addon_Room_ID`),
  ADD KEY `Addon_Host_ID` (`Addon_Host_ID`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`Groups_gID`);

--
-- Indexes for table `Hosts`
--
ALTER TABLE `Hosts`
  ADD PRIMARY KEY (`Host_ID`);

--
-- Indexes for table `House`
--
ALTER TABLE `House`
  ADD PRIMARY KEY (`House_ID`);

--
-- Indexes for table `House_Assignment`
--
ALTER TABLE `House_Assignment`
  ADD PRIMARY KEY (`Assign_House_ID`,`Assign_User_ID`),
  ADD KEY `Assign_User_ID` (`Assign_User_ID`);

--
-- Indexes for table `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`Room_ID`),
  ADD KEY `House_ID` (`House_ID`);

--
-- Indexes for table `Scenes`
--
ALTER TABLE `Scenes`
  ADD PRIMARY KEY (`Scene_ID`),
  ADD KEY `House_ID` (`House_ID`);

--
-- Indexes for table `Scene_Assignment`
--
ALTER TABLE `Scene_Assignment`
  ADD KEY `Scene_ID` (`Scene_ID`),
  ADD KEY `Addon_ID` (`Addon_ID`);

--
-- Indexes for table `Temp`
--
ALTER TABLE `Temp`
  ADD PRIMARY KEY (`Temp_ID`),
  ADD KEY `House_ID` (`House_ID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`User_ID`),
  ADD KEY `User_gID` (`User_gID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Addon`
--
ALTER TABLE `Addon`
  MODIFY `Addon_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Groups`
--
ALTER TABLE `Groups`
  MODIFY `Groups_gID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Hosts`
--
ALTER TABLE `Hosts`
  MODIFY `Host_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `House`
--
ALTER TABLE `House`
  MODIFY `House_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Room`
--
ALTER TABLE `Room`
  MODIFY `Room_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Scenes`
--
ALTER TABLE `Scenes`
  MODIFY `Scene_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Temp`
--
ALTER TABLE `Temp`
  MODIFY `Temp_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `User_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Addon`
--
ALTER TABLE `Addon`
  ADD CONSTRAINT `addon_ibfk_1` FOREIGN KEY (`Addon_Room_ID`) REFERENCES `Room` (`Room_ID`),
  ADD CONSTRAINT `addon_ibfk_2` FOREIGN KEY (`Addon_Host_ID`) REFERENCES `Hosts` (`Host_ID`);

--
-- Constraints for table `House_Assignment`
--
ALTER TABLE `House_Assignment`
  ADD CONSTRAINT `house_assignment_ibfk_1` FOREIGN KEY (`Assign_House_ID`) REFERENCES `House` (`House_ID`),
  ADD CONSTRAINT `house_assignment_ibfk_2` FOREIGN KEY (`Assign_User_ID`) REFERENCES `User` (`User_ID`);

--
-- Constraints for table `Room`
--
ALTER TABLE `Room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`House_ID`) REFERENCES `House` (`House_ID`);

--
-- Constraints for table `Scenes`
--
ALTER TABLE `Scenes`
  ADD CONSTRAINT `scenes_ibfk_1` FOREIGN KEY (`House_ID`) REFERENCES `HOUSE` (`House_ID`);

--
-- Constraints for table `Scene_Assignment`
--
ALTER TABLE `Scene_Assignment`
  ADD CONSTRAINT `scene_assignment_ibfk_1` FOREIGN KEY (`Scene_ID`) REFERENCES `Scenes` (`Scene_ID`),
  ADD CONSTRAINT `scene_assignment_ibfk_2` FOREIGN KEY (`Addon_ID`) REFERENCES `Addon` (`Addon_ID`);

--
-- Constraints for table `Temp`
--
ALTER TABLE `Temp`
  ADD CONSTRAINT `temp_ibfk_1` FOREIGN KEY (`House_ID`) REFERENCES `HOUSE` (`House_ID`);

--
-- Constraints for table `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`User_gID`) REFERENCES `Groups` (`Groups_gID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
