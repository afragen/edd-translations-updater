<?php
/**
 * EDD Translations Updater
 *
 * @package   Fragen\EDD_Translations_Updater
 * @author    Andy Fragen
 * @license   MIT
 * @link      https://github.com/afragen/edd-translations-updater
 */

namespace Fragen\EDD_Translations_Updater;

/**
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Init
 *
 * @package Fragen\EDD_Translations_Updater
 */
class Init {
	use Base;

	/**
	 * Let's get going. Load relevant action/filter hooks.
	 */
	public function run() {
		add_action( 'post_edd_sl_plugin_updater_setup', array( &$this, 'get_edd_plugin_data' ), 15, 1 );
		add_action( 'post_edd_sl_theme_updater_setup', array( &$this, 'get_edd_theme_data' ), 15, 1 );
	}

	/**
	 * Test for proper user capabilities.
	 *
	 * @return bool
	 */
	private function can_update() {
		global $pagenow;

		$load_multisite   = ( is_network_admin() && current_user_can( 'manage_network' ) );
		$load_single_site = ( ! is_multisite() && current_user_can( 'manage_options' ) );
		$user_can_update  = $load_multisite || $load_single_site;

		$admin_pages = array(
			'plugins.php',
			'themes.php',
			'update-core.php',
			'update.php',
		);

		return $user_can_update && in_array( $pagenow, array_unique( $admin_pages ), true );
	}

	/**
	 * Check user permissions and get remote data.
	 *
	 * @param array $edd_plugin_data EDD SL plugin data.
	 */
	public function get_edd_plugin_data( $edd_plugin_data ) {
		if ( $this->can_update() ) {
			$edd_plugin_data['type'] = 'plugin';
			$this->get_remote_repo_data( $edd_plugin_data );
		}
	}

	/**
	 * Check user permissions and get remote data.
	 *
	 * @param array $edd_theme_data EDD SL theme data.
	 */
	public function get_edd_theme_data( $edd_theme_data ) {
		if ( $this->can_update() ) {
			$edd_theme_data['type'] = 'theme';
			$this->get_remote_repo_data( $edd_theme_data );
		}
	}

}
