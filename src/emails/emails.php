<?php

namespace calderawp\eddBundleUpdates\emails;


/**
 * Class email
 * @package calderawp\eddBundleUpdates
 */
abstract class emails {

	/** @var  string */
	const SECTION = 'cwp_bundle_updates';

	/** @var \EDD_Emails  */
	protected $edd_email_object;

	public function get_setting( string  $setting ){
		$settings = $this->settings();
		if( array_key_exists( $setting, $settings ) ){
			return edd_get_option(static::SECTION . '_' . $setting  );
		}

		return false;
	}

	public function get_edd_email_object() : \EDD_Emails
	{
		if( null === $this->edd_email_object ){
			$this->edd_email_object = new \EDD_Emails();
			foreach ( [
				'from_address',
				'from_name',
				'subject',
			] as $field ) {
				$this->edd_email_object->$field = $this->get_setting( $field );
			}

		}

		return $this->edd_email_object;
	}


	protected function settings() : array
	{
		return array(
			'cwp_bundle_update_settings' => array(
				'id'   => static::SECTION . '_settings',
				'name' => '<h3>' . __( 'Bundle Updated', 'cwp-edd-bundle-update' ) . '</h3>',
				'type' => 'header',
			),
			'from_name' => array(
				'id'   => static::SECTION . '_from_name',
				'name' => __( 'From Name', 'cwp-edd-bundle-update' ),
				'type' => 'text',
				'std'  => get_bloginfo( 'name' ),
			),
			'from_email' => array(
				'id'   => static::SECTION . '_from_email',
				'name' => __( 'From Email', 'cwp-edd-bundle-update' ),
				'type' => 'text',
				'std'  => get_bloginfo( 'admin_email' ),
			),
			'subject' => array(
				'id'   => static::SECTION . '_subject',
				'name' => __( 'Email Subject', 'cwp-edd-bundle-update' ),
				'type' => 'text',
			),
			'heading' => array(
				'id'   => static::SECTION . '_heading',
				'name' => __( 'Email Heading', 'cwp-edd-bundle-update' ),
				'type' => 'text',
			),
			'content' => array(
				'id'   => static::SECTION . '_content',
				'name' => __( 'Message', 'cwp-edd-bundle-update' ),
				'desc' =>  edd_get_emails_tags_list(),
				'type' => 'rich_editor',
			),
		);
	}

}