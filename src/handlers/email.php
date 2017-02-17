<?php


namespace calderawp\eddBundleUpdates\handlers;
use calderawp\eddBundleUpdates\emails\emails;
use calderawp\eddBundleUpdates\emails\queue;

/**
 * Class email
 * @package calderawp\eddBundleUpdates\handlers
 */
class email extends emails {
	/**
	 * Send the email
	 *
	 * @param \WP_Post $post
	 */
	public static function email_handler( \WP_Post $post ){
		$payment_id = queue::get_payment_id( $post->ID );
		$payment = new \EDD_Payment( $payment_id  );
		if ( 0 != $payment->ID   ) {
			$sender = new \calderawp\eddBundleUpdates\emails\email( $payment );
			$sender->send();
			wp_delete_post( $post->ID );
		}
	}


}