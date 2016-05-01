<?php
/*
Plugin Name: Blupixel Zillow API for WP
Description: Zillow api integration for wordpress. Enter your Zillow web service ID in Settings -> WP Zillow.
Version: 1.0
Author: Steven Santee
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
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

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'WPZILLOW_VERSION', '1.0' );
define( 'WPZILLOW__MINIMUM_WP_VERSION', '3.1' );
define( 'WPZILLOW__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPZILLOW__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

define( 'WPZILLOWBP_TEMPLATESTR', '{{zillowdata}}');
define( 'WPZILLOWBP_ERRSTR', '{{zillowerr}}');

global $wp_zillow_bp_gotdata;

$wp_zillow_bp_gotdata = false;

require_once('class.wpzillow.php');

require_once('functions.php');

require_once('wp-zillow-bp-admin.php');

function wp_zillowbp_register_shortcode() {
    add_shortcode( 'zillow-data', 'wp_zillowbp_shortcodes' );
}

//[zillow-data method="getSearchResults" city="" state="" zip=""]
add_action( 'init', 'wp_zillowbp_register_shortcode' );
add_action( 'init', 'wp_zillowbp_doPropertySearch' );



add_action( 'wp_footer', 'wp_zillow_bp_footer' );

?>