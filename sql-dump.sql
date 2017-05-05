-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2015 at 07:31 PM
-- Server version: 5.5.41-cll-lve
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `petstapost`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `ad_id` int(100) NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `ad_url` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ad_photo_url` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`ad_id`, `ad_name`, `ad_url`, `ad_photo_url`) VALUES
(1, 'Baby Bow Tie', 'https://www.babybowtie.com/pets', 'http://s3-us-west-1.amazonaws.com/petstapost/ads/babybowtie/babybowtie.jpg'),
(2, 'The Distillery', 'http://thedistillerymarket.com/', 'http://s3-us-west-1.amazonaws.com/petstapost/ads/distillery/distillery.jpg'),
(3, 'The Distillery', 'http://thedistillerymarket.com/', 'http://s3-us-west-1.amazonaws.com/petstapost/ads/distillery/distillery2.jpg'),
(4, 'Baby Bow Tie', 'https://www.babybowtie.com/pets', 'http://s3-us-west-1.amazonaws.com/petstapost/ads/babybowtie/babybowtie2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(10) NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `hashtag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mention` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=154 ;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `favorite_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=478 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `friendship_id` int(10) NOT NULL AUTO_INCREMENT,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `user1_name` varchar(100) NOT NULL,
  `user2_name` varchar(100) NOT NULL,
  `confirmed` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`friendship_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=173 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(100) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `cdn_id` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `mime` varchar(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ssl_url` varchar(255) NOT NULL,
  `width` int(4) DEFAULT NULL,
  `height` int(4) DEFAULT NULL,
  `filter` varchar(20) NOT NULL,
  `date_recorded` varchar(20) DEFAULT NULL,
  `date_file_created` varchar(20) DEFAULT NULL,
  `date_file_modified` varchar(30) DEFAULT NULL,
  `aspect_ratio` decimal(20,0) DEFAULT NULL,
  `city` varchar(15) DEFAULT NULL,
  `state` varchar(15) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `device_name` varchar(80) DEFAULT NULL,
  `latitude` decimal(20,0) DEFAULT NULL,
  `longitude` decimal(20,0) DEFAULT NULL,
  `orientation` varchar(40) DEFAULT NULL,
  `colorspace` varchar(15) DEFAULT NULL,
  `average_color` varchar(10) DEFAULT NULL,
  `caption` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `hashtag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mention` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `video_url` varchar(250) DEFAULT NULL,
  `video_ssl_url` varchar(250) DEFAULT NULL,
  `video_name` varchar(250) DEFAULT NULL,
  `video_mime` varchar(15) DEFAULT NULL,
  `video_width` int(5) DEFAULT NULL,
  `video_height` int(5) DEFAULT NULL,
  `video_duration` decimal(10,10) DEFAULT NULL,
  `video_framerate` int(5) DEFAULT NULL,
  `video_bitrate` int(10) DEFAULT NULL,
  `video_codec` varchar(50) DEFAULT NULL,
  `audio_codec` varchar(50) DEFAULT NULL,
  `video_date_file_created` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=300 ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `notify_id` int(100) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `friend_request_user` int(11) DEFAULT NULL,
  `friend_request_name` varchar(100) DEFAULT NULL,
  `accepted_user` int(11) DEFAULT NULL,
  `accepted_name` varchar(100) DEFAULT NULL,
  `liked_user` int(11) DEFAULT NULL,
  `liked_item` int(100) DEFAULT NULL,
  `commented_user` int(11) DEFAULT NULL,
  `commented_item` int(100) DEFAULT NULL,
  `comment` text,
  `caption` text,
  `hashtag` varchar(255) DEFAULT NULL,
  `mention` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mention_user` int(11) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `viewed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notify_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=747 ;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE IF NOT EXISTS `pets` (
  `pet_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `pet_name` varchar(50) NOT NULL DEFAULT 'No Name Yet',
  `past_present` int(1) NOT NULL DEFAULT '1',
  `pet_avatar_url` varchar(250) NOT NULL DEFAULT 'http://petstapost.com/img/pet-avatar-placeholder.jpg',
  `pet_filter` varchar(20) DEFAULT NULL,
  `type` varchar(25) NOT NULL DEFAULT 'Unknown',
  `breed` varchar(75) NOT NULL DEFAULT 'Unknown',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`pet_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_item`
--

CREATE TABLE IF NOT EXISTS `report_item` (
  `report_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(100) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_user`
--

CREATE TABLE IF NOT EXISTS `report_user` (
  `report_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(18) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `password` varchar(512) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `email_code` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `profile_picture_filter` varchar(20) DEFAULT NULL,
  `cover_picture` varchar(255) DEFAULT NULL,
  `cover_picture_filter` varchar(20) DEFAULT NULL,
  `cover_color` varchar(10) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '0',
  `generated_string` varchar(35) NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL,
  `secured` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
