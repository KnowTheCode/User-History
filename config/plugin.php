<?php
/**
 * User History Plugin Runtime Configuration Parameters.
 *
 * @package     User_History
 * @since       1.0.0
 * @author      hellofromTonya
 * @link        https://knowthecode.io
 * @license     GNU General Public License 2.0+
 */

namespace User_History;

use Fulcrum\Config\Config;
use Fulcrum\Foundation\Pagination\Pagination;
use User_History\Admin\Metaboxes\Metabox;
use User_History\Database\Db_Helpers;
use User_History\Views\View_Builder;

return array(
	'per_page' => 10,

	/****************************
	 * Service Providers
	 ****************************/

	'service_providers' => array(

		'script.user_history'    => array(
			'provider' => 'provider.asset',
			'config'   => USER_HISTORY_PLUGIN_DIR . 'config/asset/script.php',
		),

		/****************************
		 * Shortcodes
		 ****************************/
		'shortcode.user_history' => array(
			'provider' => 'provider.shortcode',
			'config'   => USER_HISTORY_PLUGIN_DIR . 'config/shortcode/user-history.php',
		),
	),

	/****************************
	 * Admin Service Providers
	 ****************************/

	'admin_service_providers' => array(
		'schema.user_history' => array(
			'provider' => 'provider.schema',
			'config'   => USER_HISTORY_PLUGIN_DIR . 'config/database/schema.php',
		),
	),

	/****************************
	 * Concretes
	 ****************************/

	'register_concretes' => array(
		'ajax.user_history'         => array(
			'autoload' => false,
			'concrete' => function ( $container ) {
				return new User_History_Ajax(
					new Config( USER_HISTORY_PLUGIN_DIR . 'config/ajax.php' ),
					$container
				);
			}
		),
		'pagination'                => array(
			'autoload' => false,
			'concrete' => function ( $container ) {
				return new Pagination();
			}
		),
		'db_helpers.user_history'   => array(
			'autoload' => false,
			'concrete' => function ( $container ) {
				return new Db_Helpers(
					new Config( USER_HISTORY_PLUGIN_DIR . 'config/database/db-helpers.php' )
				);
			}
		),
		'view_builder.user_history' => array(
			'autoload' => false,
			'concrete' => function ( $container ) {
				return new View_Builder(
					new Config( USER_HISTORY_PLUGIN_DIR . 'config/views/view-builder.php' ),
					$container['user_history']
				);
			}
		),
		'metabox.user_history'      => array(
			'autoload' => true,
			'concrete' => function ( $container ) {
				return new Metabox(
					new Config( USER_HISTORY_PLUGIN_DIR . 'config/admin/metabox/user-history-metabox.php' )
				);
			}
		),
	),
);

