<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.cmsminds.com
 * @since      1.0.0
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/includes
 * @author     Ankit Jani <ankitj@cmsminds.com>
 */
class Cms_Contact_Form_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$cms_contact_form_table = CMS_CONTACT_FORM_TABLE;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		//phpcs:ignore
		$table_check = $wpdb->get_var( "show tables like '{$cms_contact_form_table}'" );

		// create the cms contact form database table.
		if ( $table_check !== $cms_contact_form_table ) {
			$sql = 'CREATE TABLE ' . $cms_contact_form_table . ' (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`first_name` varchar(255) NOT NULL,
				`last_name` varchar(255) NOT NULL,
				`email` varchar(255) NOT NULL,
				`contact_number` varchar(255) NOT NULL,
				`message` longtext NOT NULL,
				PRIMARY KEY (id)
			);';

			dbDelta( $sql );
		}
	}
}
