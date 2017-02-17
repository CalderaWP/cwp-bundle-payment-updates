<?php
/**
 * @TODO What this does
 *
 * @package cfdotcom
 * Copyright 2017 Josh Pollock <Josh@CalderaWP.com
 */

namespace calderawp\eddBundleUpdates\handlers;
use calderawp\eddBundleUpdates\emails\queue;
use calderawp\eddBundleUpdates\missing;


/**
 * Class process
 * @package calderawp\eddBundleUpdates
 */
class bundles {

	/** @var \EDD_Payment  */
	protected $payment;

	/** @var int  */
	protected $bundle_id;

	/** @var  array */
	protected $bundle_contents;

	/** @var  array */
	protected $missing;

	/** @var  array */
	protected $downloads_in_payment;

	/**
	 * bundles constructor.
	 *
	 * @param \EDD_Payment $payment
	 * @param int $bundle_id
	 */
	public function __construct( \EDD_Payment $payment, int $bundle_id ) {
		$this->payment = $payment;
		$this->bundle_id = $bundle_id;

	}

	/**
	 * Run checks for this payment
	 */
	public function run(){
		$this->set_bundle_contents();

		//Figure out what is missing
		$this->check_downloads();

		//add licenses
		\EDD_SL_Retroactive_Licensing::generate_license_keys( $this->payment->ID, $this->bundle_id );

		//If we identified missing downloads save that info and queue an email
		if( ! empty( $this->missing ) ){
			$this->save_missing();
			queue::add( $this->payment );
		}

	}

	/**
	 * Save missing IDs and bundle ID in payment meta so we can send an email later
	 */
	protected function save_missing(){
		$save = new missing( $this->payment );
		$save->set_missing( $this->missing );
		$save->set_bundle( $this->bundle_id );
	}


	/**
	 * Find our missing downloads
	 */
	protected function check_downloads(){
		$currently_licensed = [];
		$licenses = \EDD_Software_Licensing::instance()->get_licenses_of_purchase( $this->payment->ID );
		/** @var \EDD_SL_License $license */
		foreach ( $licenses as $license ){
			$currently_licensed[] =  $license->download_id;
		}

		$this->missing = array_diff( $this->bundle_contents, $currently_licensed );

		$this->missing = array_unique( $this->missing );


	}

	/**
	 * Set bundle contents, removing free downloads
	 */
	protected function set_bundle_contents() {
		$this->bundle_contents = edd_get_bundled_products( $this->bundle_id );
		foreach ( $this->bundle_contents as $i => $download ){
			if( edd_is_free_download( $download ) ){
				unset( $download[ $i ] );
			}
		}
	}
}