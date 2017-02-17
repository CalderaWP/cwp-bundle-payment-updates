<?php

namespace calderawp\eddBundleUpdates;


/**
 * Class init
 * @package calderawp\eddBundleUpdates
 */
class init {

	/** @var string */
	const TYPE = 'cwp-edd-bundle-update';

	public function __construct() {
		add_filter(  'loco_register_batch_processor', [ $this, 'make_processor' ], 10, 3 );
		add_action( 'locomotive_init', [ $this, 'register' ] );
	}

	/**
	 * Make the processor type
	 *
	 * @uses "loco_register_batch_processor"
	 *
	 * @param $return_value
	 * @param $type
	 * @param $args
	 *
	 * @return string
	 */
	public function make_processor( $return_value, $type, $args ){
		if( $type  != static::TYPE ){
			return $return_value;
		}

		return 'calderawp\eddBundleUpdates\batch';


	}

	/**
	 * Register a batch process for each bundle
	 */
	public function register(){
		foreach (  $this->get_bundles() as $id  ) {
			$download = edd_get_download( $id );

			register_batch_process( array(
				'name'     => sprintf( 'Update Bundle %s', $download->post_title ),
				'type'     => static::TYPE,
				'callback' => [ $this, sprintf( 'cb_%d', $id ) ],
				'args' => [
					'download' => $id
				]
			) );
		}
	}

	/**
	 * Get bundles to process on
	 *
	 * @return array
	 */
	protected function get_bundles(){
		return apply_filters( 'cwp_edd_bundle_payment_updates', array() );
	}

	/**
	 * Callback handler
	 *
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call( $name, $arguments ) {
		if( 0 === strpos( $name, 'cb_' ) ){
			$id = substr( $name, 3 );
			if( in_array( $id, $this->get_bundles() ) && isset( $arguments[0] ) ){
				( new \calderawp\eddBundleUpdates\handlers\bundles( $arguments[0], $id ) )->run();
			}
		}
	}
}