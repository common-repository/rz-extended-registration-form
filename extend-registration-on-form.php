<?php
/*
Plugin Name:       extend form on registration 
Plugin URI:        https://devles.com
Description:       extend registration form extention for WooCommerce.
Version:           1.0
Requires at least: 5.2
Requires PHP:      7.2
Author:            Rezwan Shiblu
Author URI:        http://devles.com
License:           GPL v2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       extended
*/

defined('ABSPATH') or die(' you are cheating ');

/**
 * Check if WooCommerce is activated
 */
if ( ! function_exists( 'extend_is_woocommerce_activated' ) ) {

	function extend_is_woocommerce_activated() {

		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/**
* registration form
* mobile number and name field added
*/
add_action( 'woocommerce_register_form', 'extend_add_register_form_field' );
 
function extend_add_register_form_field() {
 
	woocommerce_form_field(
	   'mobile_number',
		array(
			'type'        => 'text',
			'required'    => true, // just adds an "*"
			'label'        => esc_html__( 'Mobile Number', "extended" )
		),
		( isset( $_POST['mobile_number'] ) ? sanitize_text_field( $_POST['mobile_number'] ) : '' )
	);

	woocommerce_form_field(
	   'full_name',
		array(
			'type'        => 'text',
			'required'    => true, // just adds an "*"
			'label'        => esc_html__( 'Full Name', "extended" )
		),
		( isset($_POST['full_name'] ) ? sanitize_text_field( $_POST['full_name'] ) : '' )
	);
}

/**
* registration form
* mobile number and name field validation
*/
add_action( 'woocommerce_register_post', 'extend_validate_fields', 10, 3 );
 
function extend_validate_fields( $username, $email, $errors ) {
 
	if ( empty( $_POST['mobile_number'] ) ) {

		$errors->add( 'mobile_number_error', 'add your mobile number!' );
	}
	if ( empty( $_POST['full_name'] ) ) {

		$errors->add( 'full_name_error', 'add your full name!' );
	} 
}

/**
* registration form
* mobile number and name field save on database
*/
add_action( 'woocommerce_created_customer', 'extend_save_register_fields' );
 
function extend_save_register_fields( $customer_id ) {
 
	if ( isset( $_POST['mobile_number'] ) ) {

		update_user_meta( $customer_id, 'mobile_number', sanitize_text_field( $_POST['mobile_number'] ) );
	}
	if ( isset( $_POST['full_name'] ) ) {

		update_user_meta( $customer_id, 'full_name', sanitize_text_field( $_POST['full_name'] ) );
	}
}

/**
* created mobile number and name colum on user table
*/
add_action('manage_users_columns', 'extend_register_custom_user_column');

function extend_register_custom_user_column( $columns ) {

    $columns['mobile_number'] = 'Mobile number';
    $columns['full_name'] = 'Full Name';

    return $columns;
}

/**
* display mobile number and name data on moile coloum 
*/
add_action('manage_users_custom_column', 'extend_register_custom_user_column_view', 10, 3);

function extend_register_custom_user_column_view( $value, $column_name, $user_id ) {

    $extend_user_info = get_userdata( $user_id );
    if( $column_name == 'mobile_number' ) return $extend_user_info->mobile_number;
    if( $column_name == 'full_name' ) return $extend_user_info->full_name;

    return $value;
}

/**
 * Admin Notice
 */
add_action( 'admin_notices', 'extend_registation_admin_notice__error' );

function extend_registation_admin_notice__error() {
    ?>

    <div class="notice notice-success is-dismissible">
        <p><?php esc_html_e( 'rz extended registration form is a woocommerce based plugin so woocommerce plugin must be activated.', 'mblogin' ); ?></p>
    </div>

    <?php
}





