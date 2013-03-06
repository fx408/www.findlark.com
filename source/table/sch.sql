-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 03 月 06 日 10:03
-- 服务器版本: 5.5.20
-- PHP 版本: 5.2.9-1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `findlark`
--

-- --------------------------------------------------------

--
-- 表的结构 `sch_school`
--

CREATE TABLE IF NOT EXISTS `sch_school` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '学校ID',
  `name` varchar(30) NOT NULL COMMENT '名称',
  `desc` varchar(200) NOT NULL COMMENT '描述',
  `type` tinyint(4) NOT NULL COMMENT '学校类型',
  `address` varchar(200) DEFAULT NULL COMMENT '学校详细地址',
  `latitude` float DEFAULT '0' COMMENT '纬度',
  `longitude` float DEFAULT '0' COMMENT '经度',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态，1 为正常，0 审核中， 2..',
  `create_user` int(11) DEFAULT '0' COMMENT '创建人, 0 为匿名',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `provinces` smallint(6) NOT NULL COMMENT '省代码',
  `city` smallint(6) NOT NULL COMMENT '市代码',
  `county` smallint(6) NOT NULL COMMENT '县代码',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sch_school_link`
--

CREATE TABLE IF NOT EXISTS `sch_school_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL COMMENT '学校ID',
  `title` varchar(20) NOT NULL COMMENT '链接标题',
  `url` varchar(100) NOT NULL COMMENT '连接地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学校的相关链接' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sch_school_pic`
--

CREATE TABLE IF NOT EXISTS `sch_school_pic` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL COMMENT '学校ID',
  `title` int(11) DEFAULT NULL COMMENT '图片标题',
  `name` varchar(20) NOT NULL COMMENT '图片存放在磁盘上的名称',
  `path` varchar(100) NOT NULL COMMENT '图片存放路径',
  PRIMARY KEY (`id`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
