<?php

/**
 * User History DB Helpers Class
 *
 * @package     User_History\Database
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Database;

interface Db_Helpers_Contract {

	/**
	 * Update an activity.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data_packet
	 *
	 * @return false|int
	 */
	public function update_activity( array $data_packet );

	/**
	 * Insert a new activity.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data_packet
	 *
	 * @return false|int
	 */
	public function insert_activity( array $data_packet );

	/**
	 * Get the record ID for the user.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 * @param string $video_id
	 * @param int $activity_id
	 * @param int $user_id Defaults to 0 and uses the current user ID
	 *
	 * @return int
	 */
	public function get_record_id( $post_id, $video_id, $activity_id, $user_id = 0 );

	/**
	 * Get the history record.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param int $post_id
	 * @param string $video_id
	 *
	 * @return array|null|object
	 */
	public function get_record( $post_id, $video_id, $user_id = 0 );

	/**
	 * Get all records for the user.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return array|bool|mixed|null|object
	 */
	public function get_all_records( $user_id = 0 );

	/**
	 * Get all records by the Post ID for this user.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param int $post_id
	 *
	 * @return array|null|object
	 */
	public function get_records_by_post_id( $post_id, $user_id = 0 );

	/**
	 * Get all records for the activity.
	 *
	 * @since 1.0.0
	 *
	 * @param int $activity_id
	 * @param int $user_id
	 *
	 * @return array|bool|mixed|null|object
	 */
	public function get_records_by_activity( $activity_id, $user_id = 0 );

	/**
	 * Get the current user ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function get_user_id( $user_id = 0 );
}