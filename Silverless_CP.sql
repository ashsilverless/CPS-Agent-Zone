/*

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-10-08 08:54:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `tbl_activities`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_activities`;
CREATE TABLE `tbl_activities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `activity_title` varchar(250) DEFAULT NULL,
  `activity_icon` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_airports`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_airports`;
CREATE TABLE `tbl_airports` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `region_id` int(10) DEFAULT '0',
  `airport_name` varchar(250) DEFAULT NULL,
  `lat` varchar(250) DEFAULT NULL,
  `long` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `tbl_bestfor`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_bestfor`;
CREATE TABLE `tbl_bestfor` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bestfor_title` varchar(250) DEFAULT NULL,
  `bestfor_icon` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_company`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_company`;
CREATE TABLE `tbl_company` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(250) DEFAULT NULL,
  `address` mediumtext,
  `email_address` varchar(250) DEFAULT NULL,
  `telephone` varchar(250) DEFAULT NULL,
  `fax` varchar(250) DEFAULT NULL,
  `mobile` varchar(250) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `primary_colour` varchar(250) DEFAULT NULL,
  `secondary_colour` varchar(250) DEFAULT NULL,
  `tertiary_colour` varchar(250) DEFAULT NULL,
  `typeface` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_countries`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_countries`;
CREATE TABLE `tbl_countries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(250) DEFAULT NULL,
  `country_desc` mediumtext,
  `country_icon` varchar(250) DEFAULT NULL,
  `country_banner` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '2',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_country_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_country_gallery`;
CREATE TABLE `tbl_country_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_id` int(10) DEFAULT '0',
  `image_loc` varchar(250) DEFAULT NULL,
  `image_loc_low` varchar(250) DEFAULT NULL,
  `image_alt` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

------------------------
-- Table structure for `tbl_facilities`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_facilities`;
CREATE TABLE `tbl_facilities` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `facility_title` varchar(250) DEFAULT NULL,
  `facility_icon` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_flight_maps`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_flight_maps`;
CREATE TABLE `tbl_flight_maps` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `flight_id` int(10) DEFAULT '0',
  `flight_map` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_flights`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_flights`;
CREATE TABLE `tbl_flights` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `flight_name` varchar(250) DEFAULT NULL,
  `intro_text` mediumtext,
  `banner_image` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

------------------------
-- Table structure for `tbl_hits`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_hits`;
CREATE TABLE `tbl_hits` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `str_ip` varchar(100) DEFAULT NULL,
  `dt_date` datetime DEFAULT NULL,
  `str_page` varchar(250) DEFAULT NULL,
  `str_querystring` mediumtext,
  `str_ref` varchar(250) DEFAULT NULL,
  `int_user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1882 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_metadata`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_metadata`;
CREATE TABLE `tbl_metadata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT '0',
  `data_type` varchar(250) DEFAULT NULL,
  `data_title` varchar(250) DEFAULT NULL,
  `data_loc` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_properties`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_properties`;
CREATE TABLE `tbl_properties` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_id` int(10) DEFAULT '0',
  `region_id` int(10) DEFAULT '0',
  `prop_title` varchar(250) DEFAULT NULL,
  `prop_desc` mediumtext,
  `banner_image` varchar(250) DEFAULT NULL,
  `camp_layout` varchar(250) DEFAULT NULL,
  `classic_factors` mediumtext,
  `transfer_terms` mediumtext,
  `included` mediumtext,
  `excluded` mediumtext,
  `access_details` mediumtext,
  `children` mediumtext,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `checkinout_restrictions` mediumtext,
  `cancellation_terms` mediumtext,
  `general_terms` mediumtext,
  `capacity` int(10) DEFAULT NULL,
  `facilities` varchar(250) DEFAULT NULL,
  `activities` varchar(250) DEFAULT NULL,
  `best_for` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `tbl_region_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_region_gallery`;
CREATE TABLE `tbl_region_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `region_id` int(10) DEFAULT '0',
  `image_loc` varchar(250) DEFAULT NULL,
  `image_alt` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `tbl_regions`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_regions`;
CREATE TABLE `tbl_regions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country_id` int(10) DEFAULT '0',
  `region_name` varchar(250) DEFAULT NULL,
  `region_desc` mediumtext,
  `region_icon` varchar(250) DEFAULT NULL,
  `region_banner` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '2',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_seasons`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_seasons`;
CREATE TABLE `tbl_seasons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `region_id` int(10) DEFAULT '0',
  `country_id` int(10) DEFAULT '0',
  `season_title` varchar(250) DEFAULT NULL,
  `month_from` int(10) DEFAULT '1',
  `month_to` int(10) DEFAULT '12',
  `max_temp` varchar(250) DEFAULT NULL,
  `min_temp` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_users`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(250) DEFAULT NULL,
  `first_fame` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `email_address` varchar(250) DEFAULT NULL,
  `company_id` int(10) DEFAULT '0',
  `agent_level` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `destruct_date` date DEFAULT NULL,
  `last_logged_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;