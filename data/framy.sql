-- phpMyAdmin SQL Dump
-- version 4.0.10.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Lug 02, 2019 alle 13:22
-- Versione del server: 5.1.73
-- Versione PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fn_silvia`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `fe`
--

CREATE TABLE IF NOT EXISTS `fe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame` int(11) NOT NULL,
  `coreType` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `abbrev` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fe_id` int(11) NOT NULL,
  `bgColor` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `fgColor` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `coreType` (`coreType`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11429 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `frames`
--

CREATE TABLE IF NOT EXISTS `frames` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1222 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `lu`
--

CREATE TABLE IF NOT EXISTS `lu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame` int(11) NOT NULL,
  `lemma` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `results_argument`
--

CREATE TABLE IF NOT EXISTS `results_argument` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `results_sentence_id` int(11) NOT NULL,
  `id_token` int(11) NOT NULL,
  `argument` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `results_frame`
--

CREATE TABLE IF NOT EXISTS `results_frame` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frame` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `results_sentence`
--

CREATE TABLE IF NOT EXISTS `results_sentence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `results_synset_id` int(11) NOT NULL,
  `filename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sentence` int(11) NOT NULL,
  `id_token` int(11) NOT NULL,
  `notsure` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `results_synset`
--

CREATE TABLE IF NOT EXISTS `results_synset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `results_frame_id` int(11) NOT NULL,
  `synset` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `sentence_tokens`
--

CREATE TABLE IF NOT EXISTS `sentence_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sentence` int(11) DEFAULT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `pos` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cmd` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ot` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lemma` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wnsn` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `segnum` int(11) DEFAULT NULL,
  `segst` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `frame_done` tinyint(1) NOT NULL DEFAULT '0',
  `frame_id` int(11) DEFAULT NULL,
  `frame_element` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wnsn` (`wnsn`),
  KEY `filename` (`filename`,`sentence`,`position`),
  KEY `id` (`id`,`filename`,`sentence`,`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=172981 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
