<?php
/**
 * The file that the quote object.
 *
 * A class definition for the quote object.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 */

namespace Quote_Builder;

/**
 * The file that the quote object.
 *
 * A class definition for the quote object.
 *
 * @since      1.0.0
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 * @author     Marius Bezuidenhout <marius@blackhorse.co.za>
 */
class Quote {

	/**
	 * Instance of WP_Post.
	 *
	 * @var WP_Post
	 */
	public $post;

	/**
	 * Quote constructor.
	 *
	 * @param \WP_Post|int $quote Instance of WP_Post of post id.
	 */
	public function __construct( $quote ) {
		if ( $quote instanceof WP_Post ) {
			$this->post = $quote;
		} else {
			$this->post = get_post( $quote );
		}
	}

	public function get_total() {
		return '';
	}

	public function get_revisions() {

	}

	/**
	 * Get the quote line items.
	 *
	 * @return mixed
	 */
	public function get_line_items() {
		return \apply_filters( 'quote_builder_quote_items', get_post_meta( $this->post->ID, 'quote_line_items', true ), $this->post->ID );
	}

	/**
	 * Get the quote notes.
	 *
	 * @return mixed
	 */
	public function get_notes() {
		return \apply_filters( 'quote_builder_quote_notes', get_post_meta( $this->post->ID, 'quote_notes', true ), $this->post->ID );
	}

	/**
	 * Get the quote customer.
	 *
	 * @return mixed
	 */
	public function get_customer_user_id() {
		return \apply_filters( 'quote_builder_quote_customer', intval( get_post_meta( $this->post->ID, 'quote_customer_user_id', true ) ), $this->post->ID );
	}
}
