<?php

/**
 * Vimeo Shortcode - Runtime Configuration Parameters
 *
 * @package     Library\Shortcode
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History\Shortcode;

return array(
	'autoload'  => true,
	'classname' => 'User_History\Shortcode\User_History',
	'config'    => array(
		'shortcode'      => 'user_history',
		'view'           => USER_HISTORY_PLUGIN_DIR . 'src/shortcode/views/user-history-table.php',
		'row_view'       => USER_HISTORY_PLUGIN_DIR . 'src/shortcode/views/user-history.php',
		'defaults'       => array(
			'id'         => '',
			'class'      => '',
			'actid'      => 1,
			'orderby'    => 'slug',
			'order'      => 'ASC',
			'per_page'   => 5,
			'none_found' => __( 'Hum, we didn\'t find any for you.', 'user_history' ),
		),
	),
);
