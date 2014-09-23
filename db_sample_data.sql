-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2014 at 07:08 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `simpleleague_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT,
  `round_id` int(11) NOT NULL,
  `player1_id` int(11) NOT NULL,
  `player2_id` int(11) NOT NULL,
  `player1_points` int(11) NOT NULL DEFAULT '0',
  `player2_points` int(11) NOT NULL DEFAULT '0',
  `player1_victorypoints` int(11) NOT NULL DEFAULT '0',
  `player2_victorypoints` int(11) NOT NULL DEFAULT '0',
  `player1_result_id` int(11) NOT NULL DEFAULT '0',
  `player2_result_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `round_id`, `player1_id`, `player2_id`, `player1_points`, `player2_points`, `player1_victorypoints`, `player2_victorypoints`, `player1_result_id`, `player2_result_id`) VALUES
(1, 1, 1, 2, 1, 1, 50, 50, 1, 1),
(2, 1, 3, 0, 0, 0, 0, 0, 0, 0),
(3, 2, 2, 3, 3, 0, 80, 10, 3, 0),
(4, 2, 1, 0, 0, 0, 0, 0, 0, 0),
(5, 3, 3, 1, 0, 0, 0, 0, 0, 0),
(6, 3, 2, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int(11) NOT NULL AUTO_INCREMENT,
  `player_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `player_name`) VALUES
(1, 'Bill'),
(2, 'Steve'),
(3, 'John');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description_short` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`result_id`, `description`, `description_short`, `sort`) VALUES
(0, 'Loss', 'L', 0),
(1, 'Draw', 'D', 1),
(2, 'Win', 'W', 2),
(3, 'Major Win', 'M', 3);

-- --------------------------------------------------------

--
-- Table structure for table `rounds`
--

CREATE TABLE IF NOT EXISTS `rounds` (
  `round_id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `round_number` int(11) NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`round_id`),
  UNIQUE KEY `season_id` (`season_id`,`round_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `rounds`
--

INSERT INTO `rounds` (`round_id`, `season_id`, `round_number`, `description`, `finished`) VALUES
(1, 1, 1, 'Spring 2013', 1),
(2, 1, 2, 'Summer 2013', 1),
(3, 1, 3, 'Fall 2013', 0);

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE IF NOT EXISTS `seasons` (
  `season_id` int(11) NOT NULL AUTO_INCREMENT,
  `season_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `no_statistics` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`season_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `seasons`
--

INSERT INTO `seasons` (`season_id`, `season_name`, `no_statistics`) VALUES
(1, '2013', 0);

-- --------------------------------------------------------

--
-- Table structure for table `seasons_players`
--

CREATE TABLE IF NOT EXISTS `seasons_players` (
  `season_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`season_id`,`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `seasons_players`
--

INSERT INTO `seasons_players` (`season_id`, `player_id`, `team_id`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`) VALUES
(1, 'Humans'),
(2, 'Orcs'),
(3, 'Elves');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
