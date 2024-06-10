-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2024 at 07:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dormitorysystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `age` int(20) NOT NULL,
  `address` varchar(300) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `contact` int(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `gender`, `age`, `address`, `username`, `password`, `contact`) VALUES
(1, 'Adie Cariaga', 'Female', 0, '23', 'Admin', '12345', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `roomnumber` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `balance` int(11) NOT NULL,
  `paymentprice` int(11) NOT NULL,
  `paymentstatus` varchar(20) NOT NULL,
  `paymentdate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `roomnumber`, `name`, `balance`, `paymentprice`, `paymentstatus`, `paymentdate`) VALUES
(1, 1, 'Ahl Kenneth E. Leydo', 0, 500, 'Fully Paid', '2024-05-21'),
(2, 1, 'Maremie Jane J. Beloy', 0, 500, 'Fully Paid', '2024-05-21'),
(3, 1, 'Maremie Jane J. Beloy', 100, 400, 'Partially Paid', '2024-05-21');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `roomnumber` int(20) NOT NULL,
  `price` int(20) NOT NULL,
  `tenantmax` int(20) NOT NULL,
  `tenantoccupied` int(20) NOT NULL,
  `roomstatus` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `roomnumber`, `price`, `tenantmax`, `tenantoccupied`, `roomstatus`) VALUES
(1, 1, 500, 5, 2, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `roomnum` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(2) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` varchar(300) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` varchar(3) NOT NULL,
  `section` varchar(1) NOT NULL,
  `balance` int(10) NOT NULL,
  `paymentstatus` varchar(20) NOT NULL,
  `paymentprice` int(20) NOT NULL,
  `roomprice` int(10) NOT NULL,
  `paymentdate` date NOT NULL DEFAULT current_timestamp(),
  `startingdate` date NOT NULL DEFAULT current_timestamp(),
  `enddate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `roomnum`, `name`, `age`, `gender`, `address`, `course`, `year`, `section`, `balance`, `paymentstatus`, `paymentprice`, `roomprice`, `paymentdate`, `startingdate`, `enddate`) VALUES
(1, 1, 'Ahl Kenneth E. Leydo', 26, 'Male', 'Bangcas, Hinunangan, Southern Leyte', 'BSIT', '4', 'A', 0, 'Fully Paid', 500, 500, '2024-05-21', '2024-05-21', '0000-00-00'),
(3, 1, 'Maremie Jane J. Beloy', 26, 'Female', 'Davao City', 'BSED ', '4', 'A', 100, 'Partially Paid', 400, 500, '2024-05-21', '2024-05-21', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
