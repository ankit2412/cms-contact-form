<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.cmsminds.com
 * @since      1.0.0
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/includes
 * @author     Ankit Jani <ankitj@cmsminds.com>
 */
class Cms_Contact_Form_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$page = get_page_by_path( 'cms-contact-form' );
		if ( ! empty( $page ) ) {
			wp_delete_post( $page->ID, true );
		}
	}
}
