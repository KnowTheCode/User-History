<?php

/**
 * User History Database Schema
 *
 * @package     User_History\Database
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Database;

return array(
	'autoload' => true,
	'config'   => array(
		/*****************************************
		 * Tablenames
		 *****************************************/
		'tablenames' => array(
			'user_history',
			'user_history_activity',
			'user_history_video_titles',
		),
		/*****************************************
		 * Database Version
		 *****************************************/

		'version' => '1.0.1',
		/*****************************************
		 * Db Version Option Key
		 *****************************************/

		'option_name' => '_user_history_db_version',
		/*****************************************
		 * SQL Schema
		 *****************************************/

		'sql' => array(
"CREATE TABLE user_history_activity (
	activity_id bigint(20) NOT NULL AUTO_INCREMENT,
	name varchar(200) NOT NULL,
	slug varchar(200) NOT NULL,
	date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	date_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	PRIMARY KEY (activity_id),
	KEY slug (slug)
) ",
"CREATE TABLE user_history_video_titles (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	slug varchar(160) NOT NULL,
	post_id bigint(20) NOT NULL,
	video_id varchar(200) NOT NULL,
	video_title text NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY slug(slug),
	KEY post_id (post_id),
	KEY video_id (video_id)
) ",
"CREATE TABLE user_history (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	user_id bigint(20) NOT NULL,
	post_id bigint(20) NOT NULL,
	activity_id bigint(20) unsigned NOT NULL default '0',
	video_id varchar(200) NOT NULL,
	activity_state tinyint(1) DEFAULT 0 NOT NULL,
	date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	date_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	PRIMARY KEY (id),
	KEY user_id (user_id),
	KEY post_id (post_id),
	KEY activity_id (activity_id),
	KEY video_id (video_id)
) ",
		),
		/*****************************************
		 * Use Seed Tables when creating
		 *****************************************/

		'use_seed_tables'    => true,
		'seed_only_on_empty' => true,
		'seeder'             => array(
			'user_history_activity' => array(
				'seed_only_on_empty' => true,
				'seed_file'          => USER_HISTORY_PLUGIN_DIR . 'config/database/seed-data.php',
			),
		),
	),
);
