<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cmsminds.com
 * @since             1.0.0
 * @package           Cms_Contact_Form
 *
 * @wordpress-plugin
 * Plugin Name:       CMS Contact Form
 * Plugin URI:        https://github.com/ankit-cms/cms-contact-form
 * Description:       Contact form plugin where admin can see form enquiries in admin panel.
 * Version:           1.0.0
 * Author:            Ankit Jani
 * Author URI:        https://www.cmsminds.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cms-contact-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpdb;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CMS_CONTACT_FORM_VERSION', '1.0.0' );


/**
 * Cms Contact Form Table
 */
define( 'CMS_CONTACT_FORM_TABLE', $wpdb->prefix . 'cms_contact_form_entries' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cms-contact-form-activator.php
 */
function activate_cms_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cms-contact-form-activator.php';
	Cms_Contact_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cms-contact-form-deactivator.php
 */
function deactivate_cms_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cms-contact-form-deactivator.php';
	Cms_Contact_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cms_contact_form' );
register_deactivation_hook( __FILE__, 'deactivate_cms_contact_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cms-contact-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cms_contact_form() {

	$plugin = new Cms_Contact_Form();
	$plugin->run();

}
run_cms_contact_form();
