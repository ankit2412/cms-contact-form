<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.cmsminds.com
 * @since      1.0.0
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/admin
 * @author     Ankit Jani <ankitj@cmsminds.com>
 */
class Cms_Contact_Form_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cms_Contact_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cms_Contact_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cms-contact-form-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cms_Contact_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cms_Contact_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cms-contact-form-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Create Dynamic Contact Form page
	 *
	 * @since    1.0.0
	 */
	public function cms_create_contact_form_page() {

		if ( ! $this->cms_page_exists_by_slug( 'cms-contact-form' ) ) {
			$page_details = array(
				'post_title'   => 'CMS Contact Form',
				'post_name'    => 'cms-contact-form',
				'post_content' => '[cms-contact-form]',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'page',
			);
			wp_insert_post( $page_details );
		}
	}

	/**
	 * Check if page already exists
	 *
	 * @since 1.0.0
	 * @param string $page_slug slug of the page.
	 */
	public function cms_page_exists_by_slug( $page_slug ) {
		$page = get_page_by_path( $page_slug, OBJECT );
		if ( isset( $page ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Create Admin menu page for Entries
	 *
	 * @since    1.0.0
	 */
	public function cms_create_admin_menu_page() {
		add_menu_page(
			__( 'CMS Contact Form Entries', 'cms-contact-form' ),
			__( 'CMS Contact Form Entries', 'cms-contact-form' ),
			'manage_options',
			'cms-contactform-entries-page',
			array( $this, 'contactform_entries_page_contents' ),
			'dashicons-id',
			85
		);
	}

	/**
	 * Show Admin menu page contents
	 *
	 * @since    1.0.0
	 */
	public function contactform_entries_page_contents() {
		$contact_form_list_table = new CMS_Contact_Form_Listtable();
		$contact_form_list_table->prepare_items();
		//phpcs:ignore
		$page = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : 'cms-contactform-entries-page';

		echo '<div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Contact Form Entries</h2>
            <form id="search_contact_form_entry" method="post">
                <input type="hidden" name="page" value="' . esc_attr( sanitize_text_field( wp_unslash( $page ) ) ) . '" />';
				$contact_form_list_table->search_box( 'Search Entry', 'search_entry' );
				$contact_form_list_table->display();
		echo '</form>
        </div>';
	}
}
