-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 16, 2011 at 12:45 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `ickle`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `current_version_id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) CHARACTER SET latin1 NOT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `menu` tinyint(1) NOT NULL DEFAULT '1',
  `requires_authentication` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `DEPRscope` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `current_version_id` (`current_version_id`),
  KEY `creator` (`creator`),
  KEY `type` (`type`),
  KEY `deleted` (`deleted`),
  KEY `order` (`order`),
  KEY `scope` (`DEPRscope`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`ID`, `current_version_id`, `type`, `order`, `parent`, `path`, `creator`, `created`, `active`, `menu`, `requires_authentication`, `deleted`, `DEPRscope`) VALUES
(1, 1, 2, 0, 0, '', NULL, '2011-01-02 19:54:56', 1, 1, 0, 0, 1),
(2, 3, 1, 0, 0, 'home', 1, '2011-01-07 20:45:25', 1, 1, 0, 0, 1),
(3, 4, 1, 2, 0, 'approach', 1, '2011-01-09 10:11:21', 1, 1, 0, 0, 1),
(4, 5, 1, 3, 0, 'services', 1, '2011-01-09 10:11:21', 1, 1, 0, 0, 1),
(5, 6, 1, 4, 0, 'about', 1, '2011-01-09 10:11:46', 1, 1, 0, 0, 1),
(6, 7, 1, 5, 0, 'contact', 1, '2011-01-09 10:11:46', 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `content_group_authorisations`
--

CREATE TABLE IF NOT EXISTS `content_group_authorisations` (
  `content_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `content_group_authorisations`
--


-- --------------------------------------------------------

--
-- Table structure for table `content_scope_associations`
--

CREATE TABLE IF NOT EXISTS `content_scope_associations` (
  `content_id` int(11) NOT NULL,
  `scope_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`scope_id`),
  KEY `scope_id` (`scope_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `content_scope_associations`
--

INSERT INTO `content_scope_associations` (`content_id`, `scope_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `content_types`
--

CREATE TABLE IF NOT EXISTS `content_types` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `controller` varchar(100) COLLATE utf8_bin NOT NULL,
  `view_path_prepend` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dumping data for table `content_types`
--

INSERT INTO `content_types` (`ID`, `reference`, `name`, `controller`, `view_path_prepend`) VALUES
(1, 'page', 'CMS Page', '', ''),
(2, 'product', 'E-Commerce Product', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `content_versions`
--

CREATE TABLE IF NOT EXISTS `content_versions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `title` varchar(150) COLLATE utf8_bin NOT NULL,
  `heading` varchar(150) COLLATE utf8_bin NOT NULL,
  `content` longtext COLLATE utf8_bin NOT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_bin NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_bin NOT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publication_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiry_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `creator` (`creator`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `content_versions`
--

INSERT INTO `content_versions` (`ID`, `name`, `title`, `heading`, `content`, `meta_keywords`, `meta_description`, `creator`, `created`, `publication_timestamp`, `expiry_timestamp`) VALUES
(1, 'test', '', '', '', '', '', NULL, '2011-01-03 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Home', 'Welcome to ShatterProof Digital', '', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eleifend dolor et ante pharetra eu luctus turpis fringilla. Proin id adipiscing diam. Vestibulum scelerisque metus sit amet odio volutpat vel fermentum mi faucibus. Mauris id augue leo, vitae hendrerit leo. Nulla facilisi. Sed pulvinar quam id leo consequat vel dignissim eros aliquet. Nulla vel ipsum libero. Vivamus risus lectus, vestibulum at ultricies ac, laoreet sed lectus. Vestibulum sodales risus quis tortor aliquet sed fringilla justo pellentesque. Ut at condimentum turpis. Morbi vitae odio turpis.\r\n</p><p>\r\nDonec lobortis laoreet erat sed commodo. Fusce ut suscipit mauris. Morbi commodo venenatis euismod. Sed lobortis tincidunt justo sit amet faucibus. Sed tincidunt ante vitae lacus eleifend ut tempus urna volutpat. Nulla lacus elit, pulvinar quis euismod et, sollicitudin sit amet nibh. Sed volutpat, est in ultrices aliquam, felis ipsum varius quam, in varius ipsum libero quis odio. Sed rutrum tincidunt mauris, eu bibendum urna viverra vel. Pellentesque elit tellus, malesuada a porttitor at, egestas a elit. Phasellus in mi nec mi semper pulvinar. Etiam id semper mauris. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\r\n</p><p>\r\nIn consequat pulvinar dignissim. In et odio neque. Donec cursus metus condimentum nisl commodo non vehicula est varius. Ut pretium tempus arcu nec mattis. Sed posuere facilisis nisl, ac placerat diam pretium ut. Phasellus et dui dolor, sed porta nulla. Cras quis justo erat, eget tincidunt augue. Ut ut luctus ligula. Vivamus sed nunc dolor, nec semper turpis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque in diam ac enim pellentesque viverra sed id tortor. In tempus, purus eget auctor ornare, felis neque interdum magna, ac vulputate est tellus et nisl. Mauris vel ligula sollicitudin erat fermentum dictum. Nulla pulvinar suscipit dolor vel consequat. Phasellus augue turpis, consectetur nec tincidunt id, sollicitudin ac purus. Sed ultrices diam eu nisl fermentum venenatis. Nulla urna odio, tempus vitae dapibus sit amet, facilisis in ipsum.</p>', '', '', 1, '2011-01-07 20:45:07', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Approach', '', '', '', '', '', 1, '2011-01-09 10:09:58', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Services', '', '', '', '', '', 1, '2011-01-09 10:09:58', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'About', '', '', '', '', '', 1, '2011-01-09 10:10:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Contact', '', '', '', '', '', 1, '2011-01-09 10:10:09', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `content_versions_pages`
--

CREATE TABLE IF NOT EXISTS `content_versions_pages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `template` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `template` (`template`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `content_versions_pages`
--

INSERT INTO `content_versions_pages` (`ID`, `template`) VALUES
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ec_basket`
--

CREATE TABLE IF NOT EXISTS `ec_basket` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ip_address` varchar(100) COLLATE utf8_bin NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `item_id` int(11) NOT NULL,
  `item_type` varchar(50) COLLATE utf8_bin NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_customised` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `user_id` (`user_id`,`ip_address`),
  KEY `session_id` (`session_id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_basket`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_currencies`
--

CREATE TABLE IF NOT EXISTS `ec_currencies` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_bin NOT NULL,
  `symbol` varchar(10) COLLATE utf8_bin NOT NULL,
  `default_currency` tinyint(1) NOT NULL DEFAULT '0',
  `default_multiplier` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_currencies`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_delivery_locations`
--

CREATE TABLE IF NOT EXISTS `ec_delivery_locations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_delivery_locations`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_discount_rules`
--

CREATE TABLE IF NOT EXISTS `ec_discount_rules` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_discount_rules`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_payment_methods`
--

CREATE TABLE IF NOT EXISTS `ec_payment_methods` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `reference` varchar(50) COLLATE utf8_bin NOT NULL,
  `type` enum('offline','onsite','offsite') COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_payment_methods`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_products`
--

CREATE TABLE IF NOT EXISTS `ec_products` (
  `ID` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `ec_products`
--

INSERT INTO `ec_products` (`ID`, `cost`) VALUES
(1, 50.1);

-- --------------------------------------------------------

--
-- Table structure for table `ec_shipping_costs_product`
--

CREATE TABLE IF NOT EXISTS `ec_shipping_costs_product` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `method` int(11) NOT NULL,
  `cost` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_shipping_costs_product`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_shipping_costs_rules`
--

CREATE TABLE IF NOT EXISTS `ec_shipping_costs_rules` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `method` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_shipping_costs_rules`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_shipping_costs_weight`
--

CREATE TABLE IF NOT EXISTS `ec_shipping_costs_weight` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `method` int(11) NOT NULL,
  `weight_ceiling` float NOT NULL,
  `cost` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_shipping_costs_weight`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_shipping_methods`
--

CREATE TABLE IF NOT EXISTS `ec_shipping_methods` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `default_cost` float NOT NULL,
  `default_method` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_shipping_methods`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_shipping_methods_delivery_associations`
--

CREATE TABLE IF NOT EXISTS `ec_shipping_methods_delivery_associations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_method` int(11) NOT NULL,
  `delivery_location` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_shipping_methods_delivery_associations`
--


-- --------------------------------------------------------

--
-- Table structure for table `ec_taxes`
--

CREATE TABLE IF NOT EXISTS `ec_taxes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `type` enum('product','order') COLLATE utf8_bin NOT NULL,
  `multiplier` float NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ec_taxes`
--


-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `group_memberships`
--

CREATE TABLE IF NOT EXISTS `group_memberships` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `membership_valid_from` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `membership_valid_until` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `group_memberships`
--


-- --------------------------------------------------------

--
-- Table structure for table `scopes`
--

CREATE TABLE IF NOT EXISTS `scopes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `reference` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `scopes`
--

INSERT INTO `scopes` (`ID`, `reference`, `type`) VALUES
(1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `scopes_types`
--

CREATE TABLE IF NOT EXISTS `scopes_types` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `reference` (`reference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

--
-- Dumping data for table `scopes_types`
--

INSERT INTO `scopes_types` (`ID`, `reference`, `name`) VALUES
(1, 'primary', 'Primary Scope'),
(2, 'global', 'Global Scope'),
(3, 'account', 'Account'),
(4, 'subdomain', 'Sub domain');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_bin NOT NULL,
  `data` longtext COLLATE utf8_bin NOT NULL,
  `core` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`ID`, `key`, `data`, `core`) VALUES
(1, 'view', 'default', 1);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `file` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `file` (`file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `templates`
--


-- --------------------------------------------------------

--
-- Table structure for table `templates_files`
--

CREATE TABLE IF NOT EXISTS `templates_files` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `files` varchar(150) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `templates_files`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password_hash` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password_salt` varchar(10) CHARACTER SET latin1 NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `administrator` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `password_hash`, `password_salt`, `email`, `active`, `joined`, `last_logged_in`, `banned`, `deleted`, `administrator`) VALUES
(1, 'Michael', '', '', '', 0, '2011-01-07 20:44:32', '0000-00-00 00:00:00', 0, 0, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`current_version_id`) REFERENCES `content_versions` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `content_ibfk_2` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `content_ibfk_3` FOREIGN KEY (`type`) REFERENCES `content_types` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `content_group_authorisations`
--
ALTER TABLE `content_group_authorisations`
  ADD CONSTRAINT `content_group_authorisations_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `content_group_authorisations_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `content_scope_associations`
--
ALTER TABLE `content_scope_associations`
  ADD CONSTRAINT `content_scope_associations_ibfk_2` FOREIGN KEY (`scope_id`) REFERENCES `scopes` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `content_scope_associations_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `content_versions`
--
ALTER TABLE `content_versions`
  ADD CONSTRAINT `content_versions_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `group_memberships`
--
ALTER TABLE `group_memberships`
  ADD CONSTRAINT `group_memberships_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_memberships_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`file`) REFERENCES `templates_files` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
