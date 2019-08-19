<?php
/**
 * Register all actions and filters for the plugin
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 */

/**
 * Custom post type quote methods.
 *
 * Registration and methods for the WordPress custom post
 * type quote.
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 * @author     Marius Bezuidenhout <marius@blackhorse.co.za>
 */
class Quote_Builder_CPT_Quote {

	/**
	 * Singleton instance of this class
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Get instance of this or extended class
	 */
	public static function get_instance() {
		if ( ! static::$instance instanceof self ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register the quote post type.
	 *
	 * @since    1.0.0
	 */
	public function create_post_type() {
		register_post_type(
			'quote',
			apply_filters(
				'quote_builder_post_type_quote',
				array(
					'description'        => __( 'Customer sales quotes.', 'quote-builder' ),
					'menu_icon'          => 'dashicons-media-document',
					'labels'             => array(
						'name'                     => __( 'Quotes', 'quote-builder' ),
						'singular_name'            => __( 'Quote', 'quote-builder' ),
						'add_new_item'             => __( 'Add New Quote', 'quote-builder' ),
						'edit_item'                => __( 'Edit Quote', 'quote-builder' ),
						'new_item'                 => __( 'New Quote', 'quote-builder' ),
						'view_item'                => __( 'View Quote', 'quote-builder' ),
						'view_items'               => __( 'View Quotes', 'quote-builder' ),
						'search_items'             => __( 'Search Quotes', 'quote-builder' ),
						'not_found'                => __( 'No quotes found.', 'quote-builder' ),
						'not_found_in_trash'       => __( 'No quotes found in Trash.', 'quote-builder' ),
						'parent_item_colon'        => __( 'Parent Quote:', 'quote-builder' ),
						'all_items'                => __( 'All Quotes', 'quote-builder' ),
						'archived'                 => __( 'Quote Archives', 'quote-builder' ),
						'attributes'               => __( 'Quote Attributes', 'quote-builder' ),
						'insert_into_item'         => __( 'Insert into quote', 'quote-builder' ),
						'uploaded_to_this_item'    => __( 'Uploaded to this quote', 'quote-builder' ),
						'filter_items_list'        => __( 'Filter quotes list', 'quote-builder' ),
						'items_list_navigation'    => __( 'Quotes list navigation', 'quote-builder' ),
						'items_list'               => __( 'Quotes list', 'quote-builder' ),
						'item_published'           => __( 'Quote published.', 'quote-builder' ),
						'item_published_privately' => __( 'Quote published privately.', 'quote-builder' ),
						'item_reverted_to_draft'   => __( 'Quote reverted to draft.', 'quote-builder' ),
						'item_scheduled'           => __( 'Quote scheduled.', 'quote-builder' ),
						'item_updated'             => __( 'Quote updated.', 'quote-builder' ),
					),
					'public'             => false,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'show_in_rest'       => false,
					'show_in_admin_bar'  => true,
					'publicly_queryable' => false,
					'show_in_nav_menus'  => false,
					'hierarchical'       => false,
					'supports'           => array(
						'title',
						'author',
						'comments',
					),
					'has_archive'        => true,
					'delete_with_user'   => false,
				)
			)
		);

		register_post_status(
			'approved',
			array(
				'label'                     => '<span class="status-approved tips" data-tip="' . __( 'Approved', 'quote-builder' ) . '">' . __( 'Approved', 'quote-builder' ) . '</span>',
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: 1: count, 2: count */
				'label_count'               => _n_noop( 'Approved <span class="count">(%s)</span>', 'Approved <span class="count">(%s)</span>', 'quote-builder' ),
			)
		);
	}

	/**
	 * Get list of quote item columns.
	 *
	 * @return array|mixed
	 */
	public function get_columns() {
		$columns = apply_filters(
			'quote_builder_quote_fields',
			array(
				'description' => __( 'Description', 'quote-builder' ),
				'sku'         => __( 'SKU', 'quote-builder' ),
				'unit'        => __( 'Unit', 'quote-builder' ),
				'quantity'    => __( 'Quantity', 'quote-builder' ),
				'cost'        => __( 'Cost', 'quote-builder' ),
				'total'       => __( 'Total', 'quote-builder' ),
			)
		);
		return $columns;
	}

	/**
	 * Get the quote line items.
	 *
	 * @param int $post_id ID of the custom post type quote.
	 *
	 * @return mixed
	 */
	public function get_line_items( $post_id ) {
		return apply_filters( 'quote_builder_quote_items', get_post_meta( $post_id, 'quote_line_items', true ), $post_id );
	}

	/**
	 * Get the quote customer.
	 *
	 * @param int $post_id ID of the custom post type quote.
	 *
	 * @return mixed
	 */
	public function get_customer_user_id( $post_id ) {
		return apply_filters( 'quote_builder_quote_customer', intval( get_post_meta( $post_id, 'quote_customer_user_id', true ) ), $post_id );
	}
}