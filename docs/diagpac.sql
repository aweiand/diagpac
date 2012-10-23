/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50156
Source Host           : 127.0.0.1:3306
Source Database       : diagpac

Target Server Type    : MYSQL
Target Server Version : 50156
File Encoding         : 65001

Date: 2012-10-23 19:03:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `consultas`
-- ----------------------------
DROP TABLE IF EXISTS `consultas`;
CREATE TABLE `consultas` (
  `conid` int(11) NOT NULL,
  `pacid` int(11) NOT NULL,
  `condata` date NOT NULL,
  `conhora` time NOT NULL,
  PRIMARY KEY (`conid`),
  KEY `pacid` (`pacid`),
  CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`pacid`) REFERENCES `pacientes` (`pacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of consultas
-- ----------------------------

-- ----------------------------
-- Table structure for `consulta_doenca`
-- ----------------------------
DROP TABLE IF EXISTS `consulta_doenca`;
CREATE TABLE `consulta_doenca` (
  `conid` int(11) NOT NULL,
  `doeid` int(11) NOT NULL,
  PRIMARY KEY (`doeid`,`conid`),
  KEY `conid` (`conid`) USING BTREE,
  CONSTRAINT `consulta_doenca_ibfk_1` FOREIGN KEY (`conid`) REFERENCES `consultas` (`conid`),
  CONSTRAINT `consulta_doenca_ibfk_2` FOREIGN KEY (`doeid`) REFERENCES `doencas` (`doeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of consulta_doenca
-- ----------------------------

-- ----------------------------
-- Table structure for `consulta_sintoma`
-- ----------------------------
DROP TABLE IF EXISTS `consulta_sintoma`;
CREATE TABLE `consulta_sintoma` (
  `conid` int(11) NOT NULL,
  `sinid` int(11) NOT NULL,
  PRIMARY KEY (`conid`,`sinid`),
  KEY `sinid` (`sinid`),
  CONSTRAINT `consulta_sintoma_ibfk_1` FOREIGN KEY (`conid`) REFERENCES `consultas` (`conid`),
  CONSTRAINT `consulta_sintoma_ibfk_2` FOREIGN KEY (`sinid`) REFERENCES `sintomas` (`sinid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of consulta_sintoma
-- ----------------------------

-- ----------------------------
-- Table structure for `consulta_tratamento`
-- ----------------------------
DROP TABLE IF EXISTS `consulta_tratamento`;
CREATE TABLE `consulta_tratamento` (
  `conid` int(11) NOT NULL,
  `traid` int(11) NOT NULL,
  PRIMARY KEY (`conid`,`traid`),
  KEY `traid` (`traid`),
  CONSTRAINT `consulta_tratamento_ibfk_1` FOREIGN KEY (`conid`) REFERENCES `consultas` (`conid`),
  CONSTRAINT `consulta_tratamento_ibfk_2` FOREIGN KEY (`traid`) REFERENCES `tratamentos` (`traid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of consulta_tratamento
-- ----------------------------

-- ----------------------------
-- Table structure for `doencas`
-- ----------------------------
DROP TABLE IF EXISTS `doencas`;
CREATE TABLE `doencas` (
  `doeid` int(11) NOT NULL,
  `doenome` varchar(255) NOT NULL,
  PRIMARY KEY (`doeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doencas
-- ----------------------------

-- ----------------------------
-- Table structure for `doenca_sintoma`
-- ----------------------------
DROP TABLE IF EXISTS `doenca_sintoma`;
CREATE TABLE `doenca_sintoma` (
  `doeid` int(11) NOT NULL,
  `doesin` int(11) NOT NULL,
  PRIMARY KEY (`doeid`,`doesin`),
  KEY `doesin` (`doesin`),
  CONSTRAINT `doenca_sintoma_ibfk_1` FOREIGN KEY (`doeid`) REFERENCES `doencas` (`doeid`),
  CONSTRAINT `doenca_sintoma_ibfk_2` FOREIGN KEY (`doesin`) REFERENCES `sintomas` (`sinid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doenca_sintoma
-- ----------------------------

-- ----------------------------
-- Table structure for `doenca_tratamento`
-- ----------------------------
DROP TABLE IF EXISTS `doenca_tratamento`;
CREATE TABLE `doenca_tratamento` (
  `doeid` int(11) NOT NULL,
  `doetraid` int(11) NOT NULL,
  PRIMARY KEY (`doeid`,`doetraid`),
  KEY `doetraid` (`doetraid`),
  CONSTRAINT `doenca_tratamento_ibfk_1` FOREIGN KEY (`doeid`) REFERENCES `doencas` (`doeid`),
  CONSTRAINT `doenca_tratamento_ibfk_2` FOREIGN KEY (`doetraid`) REFERENCES `tratamentos` (`traid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of doenca_tratamento
-- ----------------------------

-- ----------------------------
-- Table structure for `pacientes`
-- ----------------------------
DROP TABLE IF EXISTS `pacientes`;
CREATE TABLE `pacientes` (
  `pacid` int(11) NOT NULL,
  `pacnome` varchar(255) DEFAULT NULL,
  `pactel` varchar(20) DEFAULT NULL,
  `pacend` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pacientes
-- ----------------------------

-- ----------------------------
-- Table structure for `paciente_consulta`
-- ----------------------------
DROP TABLE IF EXISTS `paciente_consulta`;
CREATE TABLE `paciente_consulta` (
  `conid` int(11) NOT NULL,
  `pacid` int(11) NOT NULL,
  PRIMARY KEY (`conid`,`pacid`),
  KEY `pacid` (`pacid`),
  CONSTRAINT `paciente_consulta_ibfk_1` FOREIGN KEY (`conid`) REFERENCES `consultas` (`conid`),
  CONSTRAINT `paciente_consulta_ibfk_2` FOREIGN KEY (`pacid`) REFERENCES `pacientes` (`pacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of paciente_consulta
-- ----------------------------

-- ----------------------------
-- Table structure for `sintomas`
-- ----------------------------
DROP TABLE IF EXISTS `sintomas`;
CREATE TABLE `sintomas` (
  `sinid` int(11) NOT NULL,
  `sinnome` varchar(255) NOT NULL,
  PRIMARY KEY (`sinid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sintomas
-- ----------------------------

-- ----------------------------
-- Table structure for `tratamentos`
-- ----------------------------
DROP TABLE IF EXISTS `tratamentos`;
CREATE TABLE `tratamentos` (
  `traid` int(11) NOT NULL,
  `tranome` varchar(255) NOT NULL,
  PRIMARY KEY (`traid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tratamentos
-- ----------------------------
