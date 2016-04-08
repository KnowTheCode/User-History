<?php
/**
 * Runtime configuration parameters for the metabox
 *
 * @package     User_History\Admin\Metaboxes}
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */
namespace User_History\Admin\Metaboxes;

return array(
	'db_tablename' => 'user_history_video_titles',
	'nonce_key'    => 'ktc_user_history_nonce',
	'save_key'     => 'ktc_user_history_save',
	'view'         => USER_HISTORY_PLUGIN_DIR . 'src/admin/metaboxes/views/user-history-metabox.php',
	'post_types'   => array(
		'post',
		'page',
		'lab',
		'docx',
	),
);