<?php
/**
Plugin name: EDD Update Bundle Payments
 */

add_action( 'plugins_loaded', function() {
	if( ! defined( 'LOCO_VERSION' ) || ! class_exists( 'EDD_Payments_Query' ) ){
		return;
	}

	spl_autoload_register( function ( $class ) {
		$prefix = 'calderawp\\eddBundleUpdates\\';
		$base_dir = dirname( __FILE__ ) . '/src/' ;
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {

			return;
		}
		$relative_class = substr($class, $len);
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

		if ( file_exists( $file )) {
			require $file;
		}
	});

	new \calderawp\eddBundleUpdates\init;
	new \calderawp\eddBundleUpdates\email;



});



function cwp_edd_process_payment( $post ){
	$payment = new EDD_Payment( $post->ID );
	( new \calderawp\eddBundleUpdates\handlers\bundles( $payment, 45191 ) )->run();
}

if( ! function_exists( 'cwp_update_emails_post_type' )) {


	function cwp__update_emails_post_type() {

		$labels = array(
			'name'                  => 'Update Emails',
			'singular_name'         => 'Update Email',
		);
		$args = array(
			'label'                 => 'Update Email',
			'description'           => 'Post Type Description',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor'),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 55,
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
		);
		register_post_type( '_update_emails', $args );

	}
	add_action( 'init', 'cwp__update_emails_post_type', 0 );

}