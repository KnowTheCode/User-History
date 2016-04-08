<?php
/**
 * Description
 *
 * @package     User_History\Views
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Views;


use Fulcrum\Config\Config_Contract;
use User_History\Controller;

class View_Builder {

	/**
	 * Instance of the runtime configuration parameters
	 *
	 * @var Config_Contract
	 */
	protected $config;

	/**
	 * Instance of the Controller
	 *
	 * @var Controller
	 */
	protected $controller;

	/**
	 * Array of the records for this user and page.
	 *
	 * @var array
	 */
	protected $records;

	/**
	 * Security token
	 *
	 * @var string
	 */
	protected $nonce;

	/**
	 * Array of activity button text
	 *
	 * @var array
	 */
	protected $text = array();

	/**
	 * Array of activity attributes
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * View_Builder constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Config_Contract $config ;
	 * @param Controller $controller
	 */
	public function __construct( Config_Contract $config, Controller $controller ) {
		$this->config     = $config;
		$this->controller = $controller;
		$this->nonce      = wp_create_nonce( 'ktc_user_history' );

		add_filter( 'ktc_embed_video_footer', array( $this, 'render_video_footer' ), 10, 3 );
	}


	/**
	 * Render the video footer.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 * @param int $video_id
	 * @param int $post_id
	 *
	 * @return string Return the HTML
	 */
	public function render_video_footer( $html, $video_id, $post_id ) {
		$this->queue_records( $post_id );
		$this->build_properties( $video_id, $post_id );

		ob_start();

		if ( is_readable( $this->config->view ) ) {
			include( $this->config->view );
		}

		return ob_get_clean();
	}

	/**
	 * Queue up all of the records.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	protected function queue_records( $post_id ) {
		$this->records = $this->records ?: $this->controller->get_records_by_post_id( $post_id );
		if ( ! is_array( $this->records ) ) {
			$this->records = array();
		}
	}

	/**
	 * Build the properties.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $video_id
	 * @param int $post_id
	 *
	 * @return void
	 */
	protected function build_properties( $video_id, $post_id ) {
		foreach ( $this->config->activities as $activity => $activity_id ) {
			$record_key = $video_id . '__' . $activity_id;

			$this->build_html_attributes( $record_key, $video_id, $post_id, $activity, $activity_id );
			$this->build_the_button_text( $record_key, $activity );
		}
	}

	/**
	 * Get the HTML attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $video_id
	 * @param int $post_id
	 *
	 * @return array
	 */
	protected function build_html_attributes( $record_key, $video_id, $post_id, $activity, $activity_id ) {
		$attribute = array(
			'class'            => $this->get_styling_classes( $activity, $record_key ),
			'data-id'          => $this->get_record_id( $record_key ),
			'data-post-id'     => (int) $post_id,
			'data-video-id'    => (int) $video_id,
			'data-activity-id' => $activity_id,
			'data-nonce'       => $this->nonce,
		);

		$this->attributes[ $activity ] = $this->build_the_attributes( $activity, $attribute );
	}

	/**
	 * Build the Button Text.
	 *
	 * @since 1.0.0
	 *
	 * @param string $record_key
	 * @param string $activity
	 *
	 * @return void
	 */
	protected function build_the_button_text( $record_key, $activity ) {
		$index = $this->state_is_active( $record_key ) ? 1 : 0;

		$this->text[ $activity ] = $this->config->text[ $activity ][ $index ];
	}

	/**
	 * Get the styling classes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $activity
	 * @param string $record_key
	 *
	 * @return string
	 */
	protected function get_styling_classes( $activity, $record_key ) {
		$class = 'embed-video__activity embed-video__activity_' . $activity;

		if ( $this->state_is_active( $record_key ) ) {
			$class .= $this->config->has_record_class;
		}

		return $class;
	}

	protected function build_the_attributes( $activity, $attribute ) {
		$attributes = '';
		foreach ( $attribute as $data_attribute => $value ) {
			$attributes .= sprintf( '%s="%s" ', $data_attribute, $value );
		}

		return $attributes;
	}

	/**
	 * Get Record ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $record_key
	 *
	 * @return int
	 */
	protected function get_record_id( $record_key ) {
		if ( ! $this->has_record( $record_key ) ) {
			return 0;
		}

		$record = $this->records[ $record_key ];
		if ( ! is_object( $record ) ) {
			return 0;
		}

		return (int) $record->id;
	}

	/**
	 * Checks if the record exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $record_key
	 *
	 * @return bool
	 */
	protected function has_record( $record_key ) {
		return array_key_exists( $record_key, (array) $this->records );
	}

	/**
	 * Checks if the activity's state is active (equal to 1).
	 *
	 * @since 1.0.0
	 *
	 * @param string $record_key
	 *
	 * @return bool
	 */
	protected function state_is_active( $record_key ) {
		if ( ! $this->has_record( $record_key ) ) {
			return false;
		}

		$record = $this->records[ $record_key ];

		return ( $record->activity_state == 1 );
	}
}