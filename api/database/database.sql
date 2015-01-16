-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 16, 2015 at 11:01 AM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `vishu_uploader`
--

CREATE DATABASE IF NOT EXISTS `vishu_uploader`;
USE `vishu_uploader`;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_name` varchar(255) NOT NULL,
  `album_description` text NOT NULL,
  `album_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_album_id` int(11) NOT NULL,
  `photo_filename` varchar(255) NOT NULL,
  `photo_camera` varchar(255) NOT NULL,
  `photo_lens` varchar(255) NOT NULL,
  `photo_filetype` varchar(255) NOT NULL,
  `photo_date` date NOT NULL,
  `photo_fstop` decimal(10,0) NOT NULL,
  `photo_flash` varchar(255) NOT NULL,
  `photo_iso` int(11) NOT NULL,
  `photo_focal_length` int(11) NOT NULL,
  `photo_shutter` int(11) NOT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `photo_album_id` (`photo_album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`photo_album_id`) REFERENCES `albums` (`album_id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;