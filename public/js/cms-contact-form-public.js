/**
 * Global cms_contact_form_admin
 *
 * @package Cms_Contact_Form
 */

var $ = jQuery.noConflict();

$( document ).ready(function() {
	/**
	 * Validate contact form fields
	 */
	$( "#cmsContactForm" ).validate({
		rules: {
			first_name: "required",
			last_name: "required",
			email: {
				required: true,
				email: true
			},
			contact_number: {
				required: true,
				phoneUS: true
			}
		},
		messages: {
			first_name: "Please enter your firstname",
			last_name: "Please enter your lastname",
			email: "Please enter a valid email address",
			contact_number: "Please enter a valid phone number"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `invalid-feedback` class to the error element.
			error.addClass( "invalid-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.next( "label" ) );
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
		},
		submitHandler: function(form) {
			/**
			 * Store contact form entires
			 */
			var cms_contact_form_nfield = $( '#cms_contact_form_nfield' ).val();
			var first_name              = $( '#first_name' ).val();
			var last_name               = $( '#last_name' ).val();
			var email                   = $( '#email' ).val();
			var contact_number          = $( '#contact_number' ).val();
			var message                 = $( '#message' ).val();
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: ajax_url.url,
				data: {
					action: 'cms_form_submit',
					cms_contact_form_nfield: cms_contact_form_nfield,
					first_name: first_name,
					last_name: last_name,
					email: email,
					contact_number: contact_number,
					message: message
				},
				success: function (response ) {
					if ( response.success ) {
						$( '.cms-contact-form-wrapper .cms-contact-form-notices' ).addClass( 'success' );
						$( '.cms-contact-form-wrapper .cms-contact-form-notices' ).css( "border", "2px solid green" ).html( '<p>' + response.success + '</p>' );
						$( '.cms-contact-form-wrapper #cmsContactForm' ).hide();
						$( '.cms-contact-form-wrapper .cms-contact-form-notices' ).show();
					} else {
						$( '.cms-contact-form-wrapper .cms-contact-form-notices' ).addClass( 'error' );
						$( '.cms-contact-form-wrapper .cms-contact-form-notices' ).css( "border", "2px solid red" ).html( '<p>' + response.error + '</p>' );
						$( '.cms-contact-form-wrapper .cms-contact-form-notices' ).show();
					}
				}
			});

			return false;  // block the default submit action
		}
	});
});
