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

use Fulcrum\Config\Config_Contract;
use Fulcrum\Foundation\Pagination\Pagination_Contract;

class Db_Helpers implements Db_Helpers_Contract {

	/**
	 * Instance of the configuration
	 *
	 * @var Config_Contract
	 */
	protected $config;

	/**
	 * Db Tablename
	 *
	 * @var string
	 */
	protected $db_tablename;

	/**
	 * Instance of the pagination
	 *
	 * @var Pagination_Contract
	 */
	protected $pagination;

	/**
	 * Current User ID
	 *
	 * @var int
	 */
	protected $user_id = 0;

	protected $pagination_sql;

	protected $current_page = 1;

	/**************
	 * Getters
	 **************/

	/**
	 * Get the Pagination SQL property.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_pagination_sql() {
		return $this->pagination_sql;
	}

	/**
	 * Get the Current Page property.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_current_page() {
		$this->current_page = array_key_exists( 'pageNum', (array) $_REQUEST )
			? absint( $_REQUEST['pageNum'] )
			: 1;

		return $this->current_page;
	}

	/**
	 * Db_Helpers constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Config_Contract $config
	 */
	public function __construct( Config_Contract $config ) {
		$this->config       = $config;
		$this->db_tablename = $config->tablename;
	}

	/**
	 * Update an activity.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data_packet
	 *
	 * @return false|int
	 */
	public function update_activity( array $data_packet ) {
		global $wpdb;

		$result = $wpdb->update(
			$this->db_tablename,
			array(
				'activity_id'    => $data_packet['activity_id'],
				'activity_state' => $data_packet['activity_state'],
				'date_updated'   => current_time( 'mysql' )
			),
			array( 'id' => $data_packet['id'] ),
			array(
				'%d',
				'%d',
				'%s',
			),
			array( '%d' )
		);

		if ( $result ) {
			return $data_packet['id'];
		}

		return 0;
	}

	/**
	 * Insert a new activity.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data_packet
	 *
	 * @return false|int
	 */
	public function insert_activity( array $data_packet ) {
		global $wpdb;
		$date = current_time( 'mysql' );

		$result = $wpdb->insert(
			$this->db_tablename,
			array(
				'user_id'        => get_current_user_id(),
				'post_id'        => $data_packet['post_id'],
				'activity_id'    => $data_packet['activity_id'],
				'video_id'       => $data_packet['video_id'],
				'activity_state' => $data_packet['activity_state'],
				'date_created'   => $date,
				'date_updated'   => $date
			),
			array(
				'%d',
				'%d',
				'%d',
				'%s',
				'%d',
				'%s',
				'%s'
			)
		);

		return (int) $result ? $wpdb->insert_id : 0;
	}

	/**
	 * Delete the record.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data_packet
	 *
	 * @return false|int
	 */
	public function delete_record( array $data_packet ) {
		global $wpdb;

		return $wpdb->delete(
			$this->db_tablename,
			array(
				'user_id'     => get_current_user_id(),
				'post_id'     => $data_packet['post_id'],
				'activity_id' => $data_packet['activity_id'],
				'video_id'    => $data_packet['video_id'],
			),
			array(
				'%d',
				'%d',
				'%d',
				'%s'
			)
		);
	}

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
	public function get_record_id( $post_id, $video_id, $activity_id, $user_id = 0 ) {
		global $wpdb;
		$user_id = $this->get_user_id( $user_id );

		$sql_query = $wpdb->prepare(
			"
				SELECT id
				FROM {$this->db_tablename}
				WHERE user_id = %d AND post_id = %d AND video_id = %s AND activity_id = %d
			", $user_id, $post_id, $video_id, $activity_id
		);
		$result    = $wpdb->get_col( $sql_query );

		return $result ? (int) $result[0] : 0;
	}

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
	public function get_record( $post_id, $video_id, $user_id = 0 ) {
		$user_id = $this->get_user_id( $user_id );

		$cache_key = 'uh_' . $user_id . '_post_id_' . $post_id . '_video_id_' . $video_id;

		$record = wp_cache_get( $cache_key, $this->config->cache_group );
		if ( $record ) {
			return $record;
		}
		global $wpdb;

		$sql_query = $wpdb->prepare(
			"
				SELECT *
				FROM {$this->db_tablename}
				WHERE user_id = %d AND post_id = %d AND video_id = %s
			", $user_id, $post_id, $video_id
		);
		$record    = $wpdb->get_results( $sql_query );

		if ( $record ) {
			wp_cache_set( $cache_key, $record, $this->config->cache_group );
		}

		return $record;
	}

	/**
	 * Get all records for the user.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return array|bool|mixed|null|object
	 */
	public function get_all_records( $user_id = 0 ) {
		$user_id = $this->get_user_id( $user_id );

		$cache_key = 'uh_' . $user_id;

		$records = wp_cache_get( $cache_key, $this->config->cache_group );
		if ( $records ) {
			return $records;
		}
		global $wpdb;

		$sql_query = $wpdb->prepare(
			"
				SELECT *
				FROM {$this->db_tablename}
				WHERE user_id = %d
			", $user_id
		);
		$records   = $wpdb->get_results( $sql_query );

		if ( ! $records ) {
			return;
		}
		$records = $this->key_records_by_video_and_activity( $records );

		wp_cache_set( $cache_key, $records, $this->config->cache_group );


		return $records;
	}

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
	public function get_records_by_post_id( $post_id, $user_id = 0 ) {
		$user_id   = $this->get_user_id( $user_id );
		$cache_key = 'uh_' . $user_id . '_post_id_' . $post_id;

		$records = wp_cache_get( $cache_key, $this->config->cache_group );
		if ( $records ) {
			return $records;
		}
		global $wpdb;

		$sql_query = $wpdb->prepare(
			"
				SELECT *
				FROM {$this->db_tablename}
				WHERE user_id = %d AND post_id = %d
			", $user_id, $post_id
		);
		$records   = $wpdb->get_results( $sql_query );

		if ( ! $records ) {
			return;
		}

		$records = $this->key_records_by_video_and_activity( $records );
		wp_cache_set( $cache_key, $records, $this->config->cache_group );

		return $records;
	}

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
	public function get_records_by_activity( $activity_id, $user_id = 0 ) {
		$user_id = $this->get_user_id( $user_id );

		global $wpdb;

		$sql_query = $wpdb->prepare(
			"SELECT uh.*, vt.video_title
			FROM {$this->db_tablename} AS uh
			LEFT JOIN user_history_video_titles AS vt ON vt.post_id = uh.post_id AND vt.video_id = uh.video_id			
			WHERE user_id = %d AND activity_id = %d
			ORDER BY date_updated
			", $user_id, $activity_id );

		$this->pagination_sql = $sql_query;

		$sql_query .= $this->get_paged_sql();

		$records = $wpdb->get_results( $sql_query );
		if ( ! $records ) {
			return;
		}

		return $this->key_records_by_video_and_activity( $records );
	}

	/**
	 * Get the current user ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function get_user_id( $user_id = 0 ) {
		if ( $user_id ) {
			return (int) $user_id;
		}

		return $this->user_id ?: get_current_user_id();
	}

	/**
	 * Key the records by the video and activity IDs to make it easier to find each record for the view.
	 *
	 * @since 1.0.0
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	protected function key_records_by_video_and_activity( array $records ) {
		$model = array();
		foreach ( $records as $record ) {
			$key           = sprintf( '%s__%d', strip_tags( $record->video_id ), (int) $record->activity_id );
			$model[ $key ] = $record;
		}

		return $model;
	}

	/**
	 * Get Paged SQL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_paged_sql() {
		$per_page = $this->config->per_page;

		global $wpdb;

		return $wpdb->prepare( "LIMIT %d, %d", absint( ( $this->get_current_page() - 1 ) * $per_page ), $per_page );
	}
}
