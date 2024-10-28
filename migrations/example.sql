-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 16 2023 г., 09:40
-- Версия сервера: 10.4.24-MariaDB
-- Версия PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Удаляем существующие таблицы в правильном порядке
DROP TABLE IF EXISTS `additional_goods_field_values`;
DROP TABLE IF EXISTS `additional_field_values`;
DROP TABLE IF EXISTS `additional_fields`;
DROP TABLE IF EXISTS `goods`;
DROP TABLE IF EXISTS `users`;

-- Создание таблицы пользователей
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `position` enum('программист','менеджер','тестировщик') NOT NULL,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Создание таблицы товаров
CREATE TABLE `goods` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `article` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `price` double(12,2) NOT NULL,
  `ean` varchar(13) COLLATE utf8_bin NOT NULL DEFAULT '',
  `vat` float(5,2) UNSIGNED NOT NULL DEFAULT 19.00,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ean` (`ean`),
  KEY `article` (`article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Создание таблицы дополнительных полей
CREATE TABLE `additional_fields` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `sort_no` int(11) NOT NULL,
  `alias` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Создание таблицы значений дополнительных полей
CREATE TABLE `additional_field_values` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `additional_field_id` int(10) UNSIGNED NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `additional_field_id` (`additional_field_id`),
  CONSTRAINT `fk_additional_field_values_field_id` FOREIGN KEY (`additional_field_id`) REFERENCES `additional_fields` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Создание таблицы связей товаров и значений дополнительных полей
CREATE TABLE `additional_goods_field_values` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `good_id` int(10) UNSIGNED NOT NULL,
  `additional_field_id` int(10) UNSIGNED NOT NULL,
  `additional_field_value_id` int(10) UNSIGNED NOT NULL,
  `is_deleted` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `good_id_2` (`good_id`,`additional_field_id`),
  KEY `good_id` (`good_id`),
  KEY `additional_field_id` (`additional_field_id`),
  KEY `additional_field_value_id` (`additional_field_value_id`),
  CONSTRAINT `fk_goods_field_values_good_id` FOREIGN KEY (`good_id`) REFERENCES `goods` (`id`),
  CONSTRAINT `fk_goods_field_values_field_id` FOREIGN KEY (`additional_field_id`) REFERENCES `additional_fields` (`id`),
  CONSTRAINT `fk_goods_field_values_value_id` FOREIGN KEY (`additional_field_value_id`) REFERENCES `additional_field_values` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Вставка тестовых данных
INSERT INTO `goods` (`article`, `name`, `price`, `ean`, `vat`) VALUES
('A123', 'test good 1', 45.00, '4041388630126', 19.00),
('A124', 'test good 2', 45.00, '9002859043062', 19.00),
('A125', 'test good 3', 5.50, '4041388630126', 19.00);

INSERT INTO `additional_fields` (`name`, `sort_no`, `alias`) VALUES
('Color', 1, 'color'),
('Size', 2, 'size');

INSERT INTO `additional_field_values` (`additional_field_id`, `name`) VALUES
(1, 'Red'),
(1, 'Green'),
(2, 'S'),
(2, 'M'),
(2, 'L');

INSERT INTO `additional_goods_field_values` (`good_id`, `additional_field_id`, `additional_field_value_id`) VALUES
(1, 1, 1),
(1, 2, 4),
(2, 1, 2),
(2, 2, 3);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
