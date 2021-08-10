<?php
/**
 * WP_List_Table is not loaded automatically so we need to load it in our application.
 *
 * @package    Cms_Contact_Form
 * @subpackage Cms_Contact_Form/includes
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class CMS_Contact_Form_Listtable extends WP_List_Table {
	/**
	 * Prepare the items for the table to process
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		if ( isset( $_POST['action'] ) && ! empty( esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_form_entry'] ) ) ) ) ) {
			if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ) ) {
				wp_die( esc_html_e( 'Nonce not verified!', 'cms-contact-form' ) );
			} else {
				$delete_entry = esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_form_entry'] ) ) );
				$this->delete_entry( $delete_entry );
			}
		}

		$data         = $this->table_data();
		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$data                  = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'id'             => 'ID',
			'first_name'     => 'First Name',
			'last_name'      => 'Last Name',
			'email'          => 'Email',
			'contact_number' => 'Contact Number',
			'message'        => 'Message',
			'date'           => 'Date',
		);
		return $columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns() {
		return array(
			'id'             => array( 'id', false ),
			'first_name'     => array( 'first_name', false ),
			'last_name'      => array( 'last_name', false ),
			'email'          => array( 'email', false ),
			'contact_number' => array( 'contact_number', false ),
			'date'           => array( 'date', false ),
		);
	}

	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	private function table_data() {
		global $wpdb;
		$cms_contact_form_table = CMS_CONTACT_FORM_TABLE;
		$search                 = '';
		$orderby                = 'first_name';
		$order                  = 'asc';

		if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ) ) {
			wp_die( esc_html_e( 'Nonce not verified!', 'cms-contact-form' ) );
		} else {

			if ( isset( $_POST['s'] ) ) {
				$search = esc_attr( sanitize_text_field( wp_unslash( $_POST['s'] ) ) );
			}

			// If orderby is set, use this as the sort column.
			if ( ! empty( $_GET['orderby'] ) ) {
				$orderby = esc_attr( sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) );
			}

			// If order is set use this as the order.
			if ( ! empty( $_GET['order'] ) ) {
				$order = esc_attr( sanitize_text_field( wp_unslash( $_GET['order'] ) ) );
			}

			$data = array();
			if ( '' !== $search ) {
				//phpcs:ignore
				$cms_contactform_entries = $wpdb->get_results( "SELECT * FROM {$cms_contact_form_table} WHERE `first_name` LIKE '%{$search}%' OR `last_name` LIKE '%{$search}%' OR `email` LIKE '%{$search}%' OR `contact_number` LIKE '%{$search}%' ORDER BY {$orderby} {$order}", ARRAY_A );
			} else {
				//phpcs:ignore
				$cms_contactform_entries = $wpdb->get_results( "SELECT * FROM {$cms_contact_form_table} ORDER BY {$orderby} {$order}", ARRAY_A );
			}
			foreach ( $cms_contactform_entries as $key => $entries ) {
				$data[] = array(
					'id'             => $entries['id'],
					'first_name'     => $entries['first_name'],
					'last_name'      => $entries['last_name'],
					'email'          => $entries['email'],
					'contact_number' => $entries['contact_number'],
					'message'        => $entries['message'],
					'date'           => gmdate( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $entries['date'] ) ),
				);
			}
			return $data;
		}
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array  $item Data.
	 * @param  String $column_name - Current column name.
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'first_name':
			case 'last_name':
			case 'email':
			case 'contact_number':
			case 'message':
			case 'date':
				return $item[ $column_name ];

			default:
				//phpcs:ignore
				return print_r( $item, true );
		}
	}

	/**
	 * Get all Actions.
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete' => 'Delete',
		);
		return $actions;
	}

	/**
	 * Column for checkbox
	 *
	 * @param array $item Set Items.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input id="cb-select-%s" type="checkbox" name="contact_form_entry[]" value="%s">',
			$item['id'],
			$item['id']
		);
	}

	/**
	 * Delete Contact Form Entry
	 *
	 * @param array $entry_ids Lits of entry ids.
	 */
	private function delete_entry( $entry_ids = array() ) {
		global $wpdb;
		$cms_contact_form_table = CMS_CONTACT_FORM_TABLE;

		if ( ! empty( $entry_ids ) ) {
			$string_entry_ids = implode( ', ', $entry_ids );
			//phpcs:ignore
			$wpdb->query( "DELETE FROM {$cms_contact_form_table} WHERE id IN({$string_entry_ids})" );
		}
	}
}
