<?php

namespace calderawp\eddBundleUpdates\emails;


/**
 * Class add
 * @package calderawp\eddBundleUpdates\emails
 */
class queue {

	/** @var string */
	const POST_TYPE = '_cwp_update_email';

	/**
	 * Add a post for a payment to send email on later
	 *
	 * @param \EDD_Payment $payment
	 *
	 * @return int|\WP_Error
	 */
	public static function add( \EDD_Payment $payment ){
		$id = wp_insert_post([
			'post_title'   => $payment->ID . '|' . $payment->email,
			'post_content' => 'Hi Roy',
			'post_status'  => 'publish',
			'post_type'    => static::POST_TYPE
		]);


		return $id;
	}

	/**
	 * Get payment ID from queued post
	 *
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_payment_id( int $post_id ){
		$id = 0;
		$post = get_post( $post_id );
		if( is_object( $post ) && static::POST_TYPE == get_post_type( $post ) ){
			$a = explode( '|', $post->post_title );
			$id = $a[0];
		}

		return $id;


	}

}