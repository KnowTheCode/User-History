<?php

/**
 * User's History Directory Shortcode - which lists all of their activity
 *
 * @package     Library\Shortcode
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Shortcode;

use Carbon\Carbon;
use Fulcrum\Custom\Shortcode\Shortcode;
use Fulcrum\Fulcrum;

class User_History extends Shortcode {

	/**
	 * Instance of the Controller
	 *
	 * @var \User_History\Controller
	 */
	protected $controller;

	/**
	 * Current activity ID
	 *
	 * @var int
	 */
	protected $activity_id;

	/************************
	 * Workers
	 ***********************/

	/**
	 * Render out the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function render() {
		if ( ! is_user_logged_in() ) {
			return '';
		}
		
		$records = $this->get_records();
		ob_start();

		include( $this->config->view );

		$html = ob_get_clean();

		$html .= $this->controller->get_pagination_html();

		return $html;
	}

	/**************
	 * Helpers
	 *************/

	/**
	 * Builds the arguments for the query.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_records() {
		$this->get_the_controller();

		$activity_id = $this->get_activity_id();

		return $this->controller->get_records_by_activity( $activity_id );
	}

	/**
	 * Get the button class if it's the current and active on.
	 *
	 * @since 1.0.0
	 *
	 * @param $activity_id
	 *
	 * @return string
	 */
	protected function get_button_class( $activity_id ) {
		return $this->get_activity_id() == $activity_id ? ' current' : '';
	}

	/************************
	 * Getters
	 ***********************/

	/**
	 * Get the controller out of the container.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function get_the_controller() {
		$fulcrum = Fulcrum::getFulcrum();

		$this->controller = $fulcrum['user_history'];
	}

	/**
	 * Get the activity ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	protected function get_activity_id() {
		if ( $this->activity_id ) {
			return $this->activity_id;
		}

		if ( ! array_key_exists( 'actid', $_REQUEST ) ) {
			return $this->atts['actid'];
		}

		$activity_id = $_REQUEST['actid'];

		$this->activity_id = (int) $activity_id = $activity_id ?: $this->atts['actid'];

		return $this->activity_id;
	}
}
