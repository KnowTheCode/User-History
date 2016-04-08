<?php
/**
 * User History Script runtime configuration parameters
 *
 * @package     User_History\Asset
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Asset;

use User_History\Controller;

return array(
	'is_script' => true,
	'handle'    => 'user_history_js',
	'config'    => array(
		'file'      => USER_HISTORY_PLUGIN_URL . 'assets/js/jquery.plugin.js',
		'deps'      => array( 'jquery', ),
		'version'   => Controller::VERSION,
		'in_footer' => true,
		'localize'  => array(
			'params'      => array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'action'  => 'user_history_activity',
			),
			'js_var_name' => 'uhParameters',
		),
	),
);
