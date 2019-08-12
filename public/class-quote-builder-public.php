<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/public
 * @author     Marius Bezuidenhout <marius@blackhorse.co.za>
 */
class Quote_Builder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quote_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quote_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quote-builder-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quote_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quote_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quote-builder-public.js', array( 'jquery' ), $this->version, false );

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
						'revisions',
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

}
