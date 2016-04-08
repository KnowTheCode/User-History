<?php
/**
 * User History Plugin
 *
 * @package         User_History
 * @author          hellofromTonya
 * @license         GPL-2.0+
 * @link            https://knowthecode.io
 *
 * @wordpress-plugin
 * Plugin Name:     KTC User History Plugin
 * Plugin URI:      https://knowthecode.io
 * Description:     User History - Adds features such as favorites, watch later, videos watch history, and more to provide a friendly membership experience.  Members can then go to through History page to quickly find the resources they want and need.
 * Version:         1.0.1
 * Author:          hellofromTonya
 * Author URI:      https://knowthecode.io
 * Text Domain:     user_history
 * Requires WP:     3.5
 * Requires PHP:    5.4
 */

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

namespace User_History;

use Fulcrum\Config\Config;
use Fulcrum\Fulcrum_Contract;

fulcrum_prevent_direct_file_access();

require_once( __DIR__ . '/assets/vendor/autoload.php' );

fulcrum_declare_plugin_constants( 'USER_HISTORY', __FILE__ );

add_action( 'fulcrum_is_loaded', __NAMESPACE__ . '\\launch' );
/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @param Fulcrum_Contract $fulcrum Instance of Fulcrum
 *
 * @return void
 */
function launch( Fulcrum_Contract $fulcrum ) {
	$path = __DIR__ . '/config/plugin.php';

	$fulcrum['user_history'] = new Controller(
		new Config( $path ),
		__FILE__,
		$fulcrum
	);
}
