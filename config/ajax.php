<?php
/**
 * Ajax Runtime Configuration
 *
 * @package     User_History
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */
namespace User_History;

return array(
	'nonce_key'   => 'ktc_user_history',
	'data_packet' => array(
		'id'             => 'intval',
		'post_id'        => 'intval',
		'activity_id'    => 'strip_tags',
		'video_id'       => 'strip_tags',
		'activity_state' => 'intval',
	),
);