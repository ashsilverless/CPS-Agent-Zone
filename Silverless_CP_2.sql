/*
Source Server         : Silverless-OLD
Source Server Version : 50505
Source Host           : 79.170.43.15:3306
Source Database       : cl13-silverles

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-10-25 11:27:41
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
-- Table structure for `tbl_assets`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_assets`;
CREATE TABLE `tbl_assets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `asset_title` varchar(250) DEFAULT NULL,
  `asset_type` varchar(250) DEFAULT NULL,
  `asset_attributes` mediumtext,
  `property_id` int(10) DEFAULT '1',
  `asset_loc` varchar(250) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_charges`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_charges`;
CREATE TABLE `tbl_charges` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `property_id` int(10) DEFAULT '0',
  `additional_charge` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `2pax` decimal(10,2) DEFAULT '0.00',
  `3pax` decimal(10,2) DEFAULT '0.00',
  `4pax` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(250) DEFAULT NULL,
  `rate` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

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

-- ----------------------------
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
) ENGINE=MyISAM AUTO_INCREMENT=4324 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_itineraries`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itineraries`;
CREATE TABLE `tbl_itineraries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `itinerary_title` varchar(250) DEFAULT NULL,
  `itinerary_desc` mediumtext,
  `itinerary_banner` varchar(250) DEFAULT NULL,
  `classic_factors` mediumtext,
  `best_for` varchar(250) DEFAULT NULL,
  `properties_inc` varchar(250) DEFAULT NULL,
  `duration` int(10) DEFAULT '1',
  `arrival_airport` int(10) DEFAULT '1',
  `cancellation_terms` mediumtext,
  `general_terms` mediumtext,
  `currency` varchar(250) DEFAULT '&dollar;',
  `rate1` decimal(10,2) DEFAULT NULL,
  `rate2` decimal(10,2) DEFAULT NULL,
  `rate3` decimal(10,2) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_itinerary_docs`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itinerary_docs`;
CREATE TABLE `tbl_itinerary_docs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `itinerary_id` int(10) DEFAULT '0',
  `data_loc` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_itinerary_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itinerary_gallery`;
CREATE TABLE `tbl_itinerary_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `itinerary_id` int(10) DEFAULT '0',
  `image_loc` varchar(250) DEFAULT NULL,
  `image_loc_low` varchar(250) DEFAULT NULL,
  `image_alt` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_itinerary_prop_dates`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_itinerary_prop_dates`;
CREATE TABLE `tbl_itinerary_prop_dates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `itinerary_id` int(10) DEFAULT '0',
  `prop_id` int(10) DEFAULT '0',
  `day_from` int(10) DEFAULT '0',
  `day_to` int(10) DEFAULT '0',
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--------------
-- Table structure for `tbl_metadata_docs`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_metadata_docs`;
CREATE TABLE `tbl_metadata_docs` (
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
-- Table structure for `tbl_prop_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_prop_gallery`;
CREATE TABLE `tbl_prop_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `prop_id` int(10) DEFAULT '0',
  `image_loc` varchar(250) DEFAULT NULL,
  `image_loc_low` varchar(250) DEFAULT NULL,
  `image_alt` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

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
  `prop_lat` varchar(100) DEFAULT NULL,
  `prop_long` varchar(100) DEFAULT NULL,
  `prop_banner` varchar(250) DEFAULT NULL,
  `camp_layout` varchar(250) DEFAULT NULL,
  `classic_factors` mediumtext,
  `transfer_terms` mediumtext,
  `included` mediumtext,
  `excluded` mediumtext,
  `access_details` mediumtext,
  `children` mediumtext,
  `check_in` varchar(100) DEFAULT NULL,
  `check_out` varchar(100) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_room_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_room_gallery`;
CREATE TABLE `tbl_room_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `room_id` int(10) DEFAULT '0',
  `prop_id` int(10) DEFAULT '0',
  `image_loc` varchar(250) DEFAULT NULL,
  `image_loc_low` varchar(250) DEFAULT NULL,
  `image_alt` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_room_rates`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_room_rates`;
CREATE TABLE `tbl_room_rates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `room_id` int(10) DEFAULT '0',
  `prop_id` int(10) DEFAULT '0',
  `room_name` varchar(250) DEFAULT NULL,
  `room_date` date DEFAULT NULL,
  `availability` int(10) DEFAULT '0',
  `agent1_rate` decimal(10,2) DEFAULT '0.00',
  `agent2_rate` decimal(10,2) DEFAULT '0.00',
  `agent3_rate` decimal(10,2) DEFAULT '0.00',
  `agent4_rate` decimal(10,2) DEFAULT '0.00',
  `agent5_rate` decimal(10,2) DEFAULT '0.00',
  `agent6_rate` decimal(10,2) DEFAULT '0.00',
  `agent7_rate` decimal(10,2) DEFAULT '0.00',
  `agent8_rate` decimal(10,2) DEFAULT '0.00',
  `agent9_rate` decimal(10,2) DEFAULT '0.00',
  `agent10_rate` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(100) DEFAULT '&dollar;',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=813 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_rooms`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_rooms`;
CREATE TABLE `tbl_rooms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `prop_id` int(10) DEFAULT '0',
  `room_title` varchar(250) DEFAULT NULL,
  `capacity_adult` int(10) DEFAULT '1',
  `capacity_child` int(10) DEFAULT '1',
  `room_quantity` int(10) DEFAULT '1',
  `room_desc` mediumtext,
  `banner_image` varchar(250) DEFAULT NULL,
  `configuration` mediumtext,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_specials`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_specials`;
CREATE TABLE `tbl_specials` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `property_id` int(10) DEFAULT '0',
  `special_title` varchar(250) DEFAULT NULL,
  `special_desc` mediumtext,
  `special_pdf` varchar(250) DEFAULT NULL,
  `special_image` varchar(250) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `tbl_transfers`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_transfers`;
CREATE TABLE `tbl_transfers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `property_id` int(10) DEFAULT '0',
  `method` varchar(250) DEFAULT NULL,
  `from` varchar(250) DEFAULT NULL,
  `duration` varchar(250) DEFAULT NULL,
  `2pax` decimal(10,2) DEFAULT '0.00',
  `3pax` decimal(10,2) DEFAULT '0.00',
  `4pax` decimal(10,2) DEFAULT '0.00',
  `rate` varchar(250) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `bl_live` int(10) DEFAULT '1',
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` varchar(250) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

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