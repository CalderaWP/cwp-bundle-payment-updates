<?php

namespace calderawp\eddBundleUpdates;


/**
 * Class missing
 *
 * Abstraction for meta to store IDs of downloads added
 *
 * @package calderawp\eddBundleUpdates
 */
class missing {

	protected $key = '_cwp_missing_downloads';

	protected $bundleKey = '_cwp_missing_download_bundle';

	/**
	 * @var \EDD_Payment
	 */
	protected $payment;

	/**
	 * missing constructor.
	 *
	 * @param \EDD_Payment $payment
	 */
	public function __construct( \EDD_Payment $payment ) {
		$this->payment = $payment;
	}

	/**
	 * Store ID of downloads added to payment
	 *
	 * @param array $missing
	 */
	public function set_missing( array $missing ){
		$this->payment->update_meta( $this->key, $missing );
	}

	/**
	 * Get the downloads added to payment
	 *
	 * @return array
	 */
	public function get_missing(): array
	{
		if( is_array( $this->payment->get_meta( $this->key ) ) ){
			return $this->payment->get_meta( $this->key );
		}

		return [];
	}

	/**
	 * Clear the saved meta
	 */
	public function clear(){
		$this->set_missing([]);
		$this->set_bundle(0);
	}

	/**
	 * Set ID of bundle that downloads were added to
	 *
	 * @param int $bundle
	 */
	public function set_bundle( int $bundle ){
		$this->payment->update_meta( $this->bundleKey, $bundle );
	}

	/**
	 * Return ID of bundle downloads were added to
	 *
	 * @return int
	 */
	public function get_bundle() : int
	{
		if( is_numeric( $this->payment->get_meta( $this->bundleKey ) ) ){
			return intval($this->payment->get_meta( $this->bundleKey ) );
		}

		return 0;
	}
}