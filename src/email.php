<?php

namespace calderawp\eddBundleUpdates;


/**
 * Class email
 * @package calderawp\eddBundleUpdates
 */
class email {

	/** @var  string */
	const SECTION = 'cwp_bundle_updates';
	public function __construct() {
		add_filter( 'edd_settings_emails', [ $this, 'add_email' ] );
		add_filter( 'edd_settings_sections_emails', function( $sections ){
			$sections[ static::SECTION ] = __( 'Bundle Updated', 'cwp-edd-bundle-update' );
			return $sections;
		});

		edd_add_email_tag( 'bundle-added', __( 'Ordered list of products added' ), [ $this, 'added_tag' ] );

	}


	public function added_tag( $arg ){
		//@todo
	}

	/**
	 * Add email to settings
	 *
	 * @uses "edd_settings_emails"
	 *
	 * @param $emails
	 *
	 * @return mixed
	 */
	public function add_email( $emails ){
		$emails[ static::SECTION ] =  array(
			'cwp_bundle_update_settings' => array(
				'id'   => 'cwp_bundle_update_settings',
				'name' => '<h3>' . __( 'Bundle Updated', 'cwp-edd-bundle-update' ) . '</h3>',
				'type' => 'header',
			),
			'from_name' => array(
				'id'   => 'from_name',
				'name' => __( 'From Name', 'cwp-edd-bundle-update' ),
				'type' => 'text',
				'std'  => get_bloginfo( 'name' ),
			),
			'from_email' => array(
				'id'   => 'from_email',
				'name' => __( 'From Email', 'cwp-edd-bundle-update' ),
				'type' => 'text',
				'std'  => get_bloginfo( 'admin_email' ),
			),
			'purchase_subject' => array(
				'id'   => 'purchase_subject',
				'name' => __( 'Email Subject', 'cwp-edd-bundle-update' ),
				'type' => 'text',
			),
			'purchase_heading' => array(
				'id'   => 'purchase_heading',
				'name' => __( 'Email Heading', 'cwp-edd-bundle-update' ),
				'type' => 'text',
			),
			'purchase_receipt' => array(
				'id'   => 'purchase_receipt',
				'name' => __( 'Message', 'cwp-edd-bundle-update' ),
				'desc' =>  edd_get_emails_tags_list(),
				'type' => 'rich_editor',
			),
		);

		return $emails;

	}

}