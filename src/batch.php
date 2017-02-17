<?php

namespace calderawp\eddBundleUpdates;
use Rkv\Locomotive\Batches\Posts;


/**
 * Class batch
 *
 * Batch process payments
 *
 * @package calderawp\eddBundleUpdates
 */
class batch extends Posts {

	/** @var array  */
	public $default_args = [
		'paged'          => 1,
		'status'         => 'publish',
		'posts_per_page' => 10,
		'offset'         => 0,
	];

	/**
	 * @inheritdoc
	 */
	public function batch_get_results() {
		$paymentsQuery = new \EDD_Payments_Query(
			$this->args
		);

		$payments = $paymentsQuery->get_payments();
		$this->set_total_num_results( count( $payments ) );
		return $payments;
	}


}