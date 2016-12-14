<?php
/**
 * Plugin Name: Ys Create Full text Feed By Category
 * Plugin URI: https://github.com/yosiakatsuki/ys-create-full-text-feed-by-category
 * Description: Even in the case of "For each article in a feed, show" setting to "Summary", full-text Feed of a specific category can be created.
 * Version: 1.0.0
 * Author: yosiakatsuki
 * Author URI: http://yosiakatsuki.net/
 * License: GPLv2
 * Text Domain: ys-create-full-text-feed-by-category
 * Domain Path: /languages
 */
/*  Copyright 2016 Yoshiaki Ogata (http://yosiakatsuki.net/)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


register_activation_hook( __FILE__, array( new ys_create_full_text_feed_by_category, 'activate' ) );
register_deactivation_hook( __FILE__, array( new ys_create_full_text_feed_by_category, 'deactivate' ) );


class ys_create_full_text_feed_by_category {

	private $langs = '';

	function __construct() {
		$data = get_file_data(
								__FILE__,
								array('langs' => 'Domain Path')
							);
		$this->langs   = $data['langs'];
	}

	public function init() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_filter('option_rss_use_excerpt', array( $this, 'ys_option_rss_use_excerpt'));
	}

	public function admin_menu() {
		add_options_page(
			'Ys Create Full text feed by category',
			'Ys Create Full text feed by category',
			'manage_options',
			'ys_cftfbc',
			array( $this, 'add_options_page' )
		);
	}

	public function admin_init() {
		register_setting( 'ys_cftfbc_settings', 'ys_cftfbc_create_full_text_cat', array( $this, 'sanitize_selected_cats' ) );
	}


	public function add_options_page() {
		include dirname( __FILE__ ).'/inc/option_page.php';
	}

	public function sanitize_selected_cats( $input ) {
		if (in_array(false, array_map('is_numeric', $input))) {
				wp_die( 'Invalid data' );
		} else {
				return $input;
		}
	}

	public function ys_option_rss_use_excerpt($rss_use_excerpt) {
		$option = get_site_option( 'ys_cftfbc_create_full_text_cat' );
		if($option){
			if(in_category($option)) {
				return 0;
			}
		}
		return $rss_use_excerpt;
	}

	public function activate() {
		$option = get_site_option( 'ys_cftfbc_create_full_text_cat' );
		if(!$option) {
			$option = array();
			update_option('ys_cftfbc_create_full_text_cat',$option);
		}
	}

	public function deactivate() {
		delete_option('ys_cftfbc_create_full_text_cat');
	}

}// class ys_create_full_text_feed_by_category


$ys_create_full_text_feed_by_category = new ys_create_full_text_feed_by_category();
$ys_create_full_text_feed_by_category->init();