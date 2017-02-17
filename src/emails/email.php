<?php


namespace calderawp\eddBundleUpdates\emails;
use calderawp\eddBundleUpdates\missing;


/**
 * Class email
 * @package calderawp\eddBundleUpdates
 */
class email extends emails {

	/** @var \EDD_Payment  */
	protected $payment;

	/** @var missing  */
	protected $meta;

	public function __construct( \EDD_Payment $payment ) {
		$this->payment = $payment;
		$this->meta = new missing( $this->payment );
	}

	/**
	 * Send the update email for this payment
	 */
	public function send(){
		$to = $this->payment->email;
		$subject = $this->get_setting(  'subject' );
		$message = $this->parse_message();
		$this->get_edd_email_object()->send( $to, $subject, $message );
	}

	/**
	 * Prepare the message
	 *
	 * @return bool|mixed
	 */
	protected function parse_message(){
		$message = $this->get_setting( 'content' );
		$message = str_replace( '{{added}}', $this->added_tag(), $message );
		$message = str_replace( '{{bundle}}', $this->bundle_tag(), $message );
		return $message;
	}

	/**
	 * Get the ordered list of added products
	 *
	 * @return string
	 */
	protected function added_tag() : string
	{
		$ids = $this->meta->get_missing();
		$pattern = '<li><a href="%s">%s</a></li>';
		$out = [];
		foreach ( $ids  as $id ){
			$download = edd_get_download( $id );
			if ( is_object( $download ) ) {
				$out[] = sprintf( $pattern, esc_url( get_permalink( $id ) ), $download->post_title );
			}
			
		}

		if (  ! empty( $out ) ) {
			return '<ul>' . implode( $out ) . '</ul>';
		}

		return '';
	}

	/**
	 * Get the bundle
	 *
	 * @return string
	 */
	protected function bundle_tag() : string
	{
		$download = edd_get_download( $this->meta->get_bundle() );
		if (  is_object( $download ) ) {
			return $download->post_title;
		}

		return '';
	}


}