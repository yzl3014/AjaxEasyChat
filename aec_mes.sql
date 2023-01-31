-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- 主机： localhost:3306
-- 生成日期： 2023-01-31 10:59:33
-- 服务器版本： 5.6.51
-- PHP 版本： 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `430860`
--

-- --------------------------------------------------------

--
-- 表的结构 `aec_mes`
--

CREATE TABLE `aec_mes` (
  `id` int(2) NOT NULL COMMENT '信件ID',
  `name` varchar(64) DEFAULT NULL COMMENT '发件人昵称',
  `user_id` varchar(32) DEFAULT NULL COMMENT '发件人ID',
  `content` longtext COMMENT '消息内容',
  `time` varchar(64) DEFAULT NULL COMMENT '发件时间',
  `type` varchar(16) DEFAULT NULL COMMENT '内容属性',
  `recipient` varchar(128) DEFAULT NULL COMMENT '消息At人',
  `ip` varchar(64) NOT NULL COMMENT '发件IP'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='AjaxEasyChat消息池';

--
-- 转存表中的数据 `aec_mes`
--

INSERT INTO `aec_mes` (`id`, `name`, `user_id`, `content`, `time`, `type`, `recipient`, `ip`) VALUES
(1, 'yuanzj', '1', '见到此消息，证明你的AEC已搭建完成。若无法发送消息，请查看后端recaptcha配置。', '2000-01-01 12:00:00', 'mes', '#', '127.0.0.1');

--
-- 转储表的索引
--

--
-- 表的索引 `aec_mes`
--
ALTER TABLE `aec_mes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `aec_mes`
--
ALTER TABLE `aec_mes`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT COMMENT '信件ID', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
