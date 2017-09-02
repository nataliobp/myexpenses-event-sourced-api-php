CREATE DATABASE myexpenses;

use myexpenses;

DROP TABLE IF EXISTS `expenses`;

CREATE TABLE `expenses` (
  `expense_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `spender_id` varchar(255) NOT NULL,
  `expense_list_id` varchar(255) NOT NULL,
  `occurred_on` datetime NOT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `expense_list_overviews`;

CREATE TABLE `expense_list_overviews` (
  `expense_list_id` varchar(255) NOT NULL,
  `overview` TEXT,
  PRIMARY KEY (`expense_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `expense_lists`;

CREATE TABLE `expense_lists` (
  `expense_list_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`expense_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `spenders`;

CREATE TABLE `spenders` (
  `spender_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`spender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `expense_list_id` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;