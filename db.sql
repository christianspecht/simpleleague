-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2013 at 02:05 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpsimpleleague`
--
CREATE DATABASE IF NOT EXISTS `phpsimpleleague` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `phpsimpleleague`;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int(11) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(50) NOT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE IF NOT EXISTS `seasons` (
  `season_id` int(11) NOT NULL AUTO_INCREMENT,
  `season_name` varchar(50) NOT NULL,
  PRIMARY KEY (`season_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `seasons_players`
--

CREATE TABLE IF NOT EXISTS `seasons_players` (
  `season_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`season_id`,`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(50) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
