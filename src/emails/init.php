<?php

namespace calderawp\eddBundleUpdates\emails;


/**
 * Class initEmail
 * @package calderawp\eddBundleUpdates
 */
class init extends emails {

	public function __construct() {
		add_filter( 'edd_settings_emails', [ $this, 'add_email' ] );
		add_filter( 'edd_settings_sections_emails', function( $sections ){
			$sections[ static::SECTION ] = __( 'Bundle Updated', 'cwp-edd-bundle-update' );
			return $sections;
		});

		edd_add_email_tag( 'bundle-added', __( 'Ordered list of products added' ), [ $this, 'added_tag' ] );

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
		$emails[ static::SECTION ] =$this->settings();

		return $emails;

	}

}