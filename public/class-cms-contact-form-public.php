<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.cmsminds.com
 * @since      1.0.0
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/public
 * @author     Ankit Jani <ankitj@cmsminds.com>
 */
class Cms_Contact_Form_Public {

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
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( 'bootstrap-style', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '4.3.1', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cms-contact-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( 'bootstrap-script', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), '4.3.1', false );
		wp_enqueue_script( 'jquery-validate-script', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), '1.19.2', false );
		wp_enqueue_script( 'jquery-additional-methods-script', plugin_dir_url( __FILE__ ) . 'js/additional-methods.min.js', array( 'jquery' ), '1.19.2', false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cms-contact-form-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_url', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Render the contact form HTML
	 *
	 * @param array $atts The attributes of the shortcode.
	 * @since 1.0.0
	 */
	public function cms_render_contact_form( $atts ) {

		$content = '';

		$content .= '<div class="cms-contact-form-wrapper">';
		$content .= '<div class="cms-contact-form-notices"></div>';
		$content .= '<form id="cmsContactForm" class="cms-contact-form form-horizontal" action="" method="post">
						' . wp_nonce_field( 'cms_contact_form_naction', 'cms_contact_form_nfield' ) . '
						<div class="form-row">
							<div class="form-group col-md-6">
								<label class="control-label" for="inputFirstName">' . __( 'First Name:', 'cms-contact-form' ) . '</label>
								<input type="text" id="first_name" name="first_name" class="form-control" id="inputFirstName" placeholder="First Name" />
							</div>
							<div class="form-group col-md-6">
								<label class="control-label" for="inputLastName">' . __( 'Last Name:', 'cms-contact-form' ) . '</label>
								<input type="text" id="last_name" name="last_name" class="form-control" id="inputLastName" placeholder="Last Name" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="inputEmail">' . __( 'Email:', 'cms-contact-form' ) . '</label>
							<input type="email" id="email" name="email" class="form-control" id="inputEmail" placeholder="Enter email" />
						</div>
						<div class="form-group">
							<label class="control-label" for="inputContactNumber">' . __( 'Contact Number:', 'cms-contact-form' ) . '</label>
							<input type="text" id="contact_number" name="contact_number" class="form-control" id="inputContactNumber" placeholder="Enter Contact Number" />
						</div>
						<div class="form-group">
					    	<label class="control-label" for="inputMessage">' . __( 'Message:', 'cms-contact-form' ) . '</label>
					    	<textarea class="form-control" id="message" name="message" id="inputMessage" rows="3" placeholder="Enter Message"></textarea>
					  	</div>  
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default cms-from-submit">' . __( 'Submit', 'cms-contact-form' ) . '</button>
							</div>
						</div>
					</form>';
		$content .= '</div>';

		return $content;
	}

	/**
	 * Store Contact form entries
	 * to custom plugin table
	 */
	public function cms_save_form_entries() {
		global $wpdb;
		$cms_contact_form_table = CMS_CONTACT_FORM_TABLE;
		$return_data            = array();

		if ( ! empty( $_POST ) ) {
			if ( ! isset( $_POST['cms_contact_form_nfield'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cms_contact_form_nfield'] ) ), 'cms_contact_form_naction' ) ) {
				$return_data['error'] = 'Sorry, your nonce did not verify.';
			} else {
				$first_name     = ( ! empty( $_POST['first_name'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) ) : '';
				$last_name      = ( ! empty( $_POST['last_name'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) ) : '';
				$email          = ( ! empty( $_POST['email'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['email'] ) ) ) : '';
				$contact_number = ( ! empty( $_POST['contact_number'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_number'] ) ) ) : '';
				$message        = ( ! empty( $_POST['message'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['message'] ) ) ) : '';

				//phpcs:ignore 
				$insert = $wpdb->insert(
					$cms_contact_form_table,
					array(
						'first_name'     => $first_name,
						'last_name'      => $last_name,
						'email'          => $email,
						'contact_number' => $contact_number,
						'message'        => $message,
					),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
					)
				);
				if ( $insert ) {
					$return_data['success'] = __( 'Your inquiry sucessfully submitted! We will try to reach you as soon as possible!', 'cms-contact-form' );
				} else {
					$return_data['error'] = $wpdb->print_error();
				}
			}
			echo wp_json_encode( $return_data );
		}
		die();
	}
}
