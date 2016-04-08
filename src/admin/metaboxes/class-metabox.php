<?php
/**
 * Coming Soon Metabox
 *
 * @package     Library\Admin\Metaboxe
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knownthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Admin\Metaboxes;

use Fulcrum\Config\Config_Contract;

class Metabox {

	/**
	 * Runtime configuration parameters
	 *
	 * @var Config_Contract
	 */
	protected $config;

	/**
	 * Array of records
	 *
	 * @var array
	 */
	protected $records;

	/**
	 * Array of values (prepped for the SQL save)
	 *
	 * @var array
	 */
	protected $values;

	/**
	 * Metabox constructor.
	 *
	 * @param Config_Contract $config
	 */
	public function __construct( Config_Contract $config ) {
		if ( ! is_admin() ) {
			return;
		}
		$this->config = $config;
		$this->init_events();
	}

	/**
	 * Initialize the events.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_events() {
		add_action( 'add_meta_boxes', array( $this, 'register_metabox' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_metabox' ), 1, 2 );
	}

	/**
	 * Register a new meta box to the post or page edit screen.
	 *
	 * @since 1.0.0
	 *
	 * @retun void
	 */
	public function register_metabox( $post_type, $post ) {
		if ( ! $this->is_target_post_type( $post_type ) ) {
			return;
		}

		add_meta_box(
			'user_history_metabox',
			__( 'User History Options', 'user_history' ),
			array( $this, 'render_metabox' ),
			$post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Render the metabox HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param stdClass $post
	 *
	 * @return void
	 */
	public function render_metabox( $post ) {
		if ( ! is_readable( $this->config->view ) ) {
			return;
		}

		$this->get_records( $post->ID );

		wp_nonce_field( $this->config->save_key, $this->config->nonce_key );

		include( $this->config->view );
	}

	/**
	 * Save the metabox records into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID.
	 * @param stdClass $post Post object.
	 *
	 * @return void
	 */
	public function save_metabox( $post_id, $post ) {

		if ( ! isset( $_POST['user_history'] ) ) {
			return;
		}

		foreach ( $_POST['user_history'] as $raw_data ) {
			if ( ! $raw_data['id'] && ! $raw_data['video_id'] ) {
				break;
			}
			$this->process_raw_record( $raw_data, $post_id );
		}

		$this->save_records();
	}

	/**
	 * Get the records from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	protected function get_records( $post_id ) {
		global $wpdb;

		$sql_query     = $wpdb->prepare(
			"
				SELECT *
				FROM {$this->config->db_tablename}
				WHERE post_id = %d
			", $post_id
		);
		$records       = $wpdb->get_results( $sql_query );
		$this->records = $records ?: false;
	}

	/**
	 * Process the raw record and assemble into the proper format for the SQL query.
	 *
	 * @since 1.0.0
	 *
	 * @param array $raw_record
	 * @param int $post_id
	 *
	 * @return void
	 */
	function process_raw_record( array $raw_record, $post_id ) {
		global $wpdb;
		$slug = $raw_record['slug'] ?: $post_id . '_' . $raw_record['video_id'];

		$this->values[] = $wpdb->prepare( '( %d, %s, %d, %s, %s )',
			$raw_record['id'],
			$slug,
			$post_id,
			$raw_record['video_id'],
			stripslashes( $raw_record['video_title'] )
		);
	}

	/**
	 * Save records into the database.
	 *
	 * @since 1.0.0
	 *
	 * @return bool|int Returns the number of records processed; else false.
	 */
	protected function save_records() {
		if ( ! $this->values ) {
			return;
		}

		global $wpdb;
		$values = join( ',', $this->values );

		$sql_query     = 
			"
				INSERT INTO {$this->config->db_tablename} ( id, slug, post_id, video_id, video_title )
				VALUES {$values}
				ON DUPLICATE KEY UPDATE
				 	slug=VALUES(slug),
					post_id=VALUES(post_id),
					video_id=VALUES(video_id),
					video_title=VALUES(video_title)
			";

		return $wpdb->query( $sql_query );
	}

	/**
	 * Checks if the post type is one that needs this metabox.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type
	 *
	 * @return bool
	 */
	protected function is_target_post_type( $post_type ) {
		return in_array( $post_type, $this->config->post_types );
	}

	/**
	 * Get the value out of the record column if it exists.
	 *
	 * @since 1.0.0
	 *
	 * @param int $index
	 * @param string $column_name
	 *
	 * @return string
	 */
	protected function get_value_from_record( $index, $column_name ) {
		if ( ! $this->records || ! array_key_exists( $index, $this->records ) ) {
			return '';
		}
		$record = $this->records[ $index ];
		if ( ! is_object( $record ) || ! property_exists( $record, $column_name ) ) {
			return '';
		}

		return strip_tags( stripslashes( $record->$column_name ) );
	}
}