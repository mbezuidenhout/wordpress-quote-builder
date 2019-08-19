<?php
/**
 * Actions and filters for the custom post type quote.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 */

/**
 * Actions and filters for the custom post type quote.
 *
 * Changes made to column headings, adding of meta boxes, saving.
 * Admin console functionality for the custom post type quote
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 * @author     Marius Bezuidenhout <marius@blackhorse.co.za>
 */
class Admin_CPT_Quote {

	/**
	 * Singleton instance of this class
	 *
	 * @var self
	 */
	protected static $instance;

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
	private $plugin_version;

	public function __construct( $plugin_name, $plugin_version ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;
	}

	/**
	 * Get instance of this or extended class
	 *
	 * @param string $plugin_name
	 * @param string $plugin_version
	 *
	 * @return Admin_CPT_Quote
	 */
	public static function get_instance( $plugin_name, $plugin_version ) {
		if ( ! static::$instance instanceof self ) {
			static::$instance = new static( $plugin_name, $plugin_version );
		}

		return static::$instance;
	}

	/**
	 * Change the column heading for All Quotes.
	 *
	 * @param array $posts_columns Associative array of columns.
	 *
	 * @return array
	 */
	public function quotes_columns( $posts_columns ) {
		/* translators: manage posts column name */
		$posts_columns['title'] = _x( 'Quote number', 'column name', 'quote-builder' );
		$posts_columns          = array_slice( $posts_columns, 0, 2, true ) +
								array( 'customer' => _x( 'Customer', 'column name', 'quote-builder' ) ) +
								array_slice( $posts_columns, 2, null, true );
		return $posts_columns;
	}

	/** Adds columns to the sortable columns array
	 *
	 * @param array $sortable_columns Associative array of sortable columns.
	 *
	 * @return array
	 */
	public function quotes_sortable_columns( $sortable_columns ) {
		$sortable_columns['customer'] = 'customer';
		return $sortable_columns;
	}

	/**
	 * Saving of meta boxes for the custom post type quote.
	 *
	 * @param int     $post_ID Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 *
	 * @return int
	 */
	public function save_quote( $post_ID, $post, $update ) {
		if ( ! isset( $_POST['_wpnonce_quote_items'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['_wpnonce_quote_items'] ), 'quote-items' ) ) {
			return $post_ID;
		}

		// check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_ID;
		}

		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return $post_ID;
		}

		$old = Quote_Builder_CPT_Quote::get_instance()->get_line_items( $post_ID );
		$new = $_POST['quote_line_items'];

		if ( $new && $new !== $old ) {
			update_post_meta( $post_ID, 'quote_line_items', $new );
		}
	}

	/**
	 * Add the quote meta boxes.
	 *
	 * @param post $post The post object.
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
	 */
	public function add_meta_boxes( $post ) {
		remove_meta_box( 'commentstatusdiv', 'quote', 'normal' );
		remove_meta_box( 'slugdiv', 'quote', 'normal' );
		add_meta_box( 'quoteitemsdiv', __( 'Quote line items', 'quote-builder' ), array( $this, 'meta_box_quote_items' ), 'quote', 'normal', 'high' );
	}

	/**
	 * Change post updated messages.
	 *
	 * @param array $messages List of messages when updating posts grouped by post type.
	 *
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		global $post_ID;
		$quote = get_post();
		$link  = esc_url( get_permalink( $quote->ID ) );

		$messages['quote'] = array(
			0  => '',
			1  => __( 'Quote updated.' ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.' ),
			4  => __( 'Quote updated.' ),
			/* translators: %s: Post title. */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Quote restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: Permalink. */
			6  => sprintf( __( 'Quote published. <a href="%s">View quote</a>' ), $link ),
			7  => __( 'Quote saved.' ),
			/* translators: %s: Permalink. */
			8  => sprintf( __( 'Quote submitted. <a target="_blank" href="%s">Preview quote</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			/* translators: 1: Post date, 2: Permalink */
			9  => sprintf( __( 'Quote scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview study</a>' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $quote->post_date ) ), $link ),
			/* translators: %s: Permalink. */
			10 => sprintf( __( 'Quote draft updated. <a target="_blank" href="%s">Preview quote</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		);

		return $messages;
	}

	/**
	 * Change messages when making changes to quotes in bulk.
	 *
	 * @param array $bulk_messages Array of messages.
	 * @param int   $bulk_counts   Number of updated posts.
	 *
	 * @return array
	 */
	public function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {
		$bulk_messages['quote'] = array(
			/* translators: %s: Number of quotes updated. */
			'updated'   => _n( '%s quote updated.', '%s quotes updated.', $bulk_counts['updated'] ),
			/* translators: %s: Number of quotes locked. */
			'locked'    => _n( '%s quote not updated, somebody is editing it.', '%s quotes not updated, somebody is editing them.', $bulk_counts['locked'] ),
			/* translators: %s: Number of quotes deleted. */
			'deleted'   => _n( '%s quote permanently deleted.', '%s quotes permanently deleted.', $bulk_counts['deleted'] ),
			/* translators: %s: Number of quotes moved to trash. */
			'trashed'   => _n( '%s quote moved to the Trash.', '%s quotes moved to the Trash.', $bulk_counts['trashed'] ),
			/* translators: %s: Number of quotes moved from trash. */
			'untrashed' => _n( '%s quote restored from the Trash.', '%s quotes restored from the Trash.', $bulk_counts['untrashed'] ),
		);

		return $bulk_messages;
	}

	/**
	 * Replace the title place holder for the custom post type quote.
	 *
	 * @param string  $placeholder Current placeholder string.
	 * @param WP_Post $post Instance of WP_Post.
	 *
	 * @return string;
	 */
	public function title_placeholder( $placeholder, $post ) {
		if ( 'quote' === $post->post_type ) {
			return __( 'Add quote number', 'quote-builder' );
		}
		return $placeholder;
	}

	/**
	 * Add view action for the custom post type quote.
	 *
	 * @param array   $actions An array of actions.
	 * @param WP_Post $post    Instance of WP_Post.
	 *
	 * @return mixed
	 */
	public function post_row_actions( $actions, $post ) {
		if ( 'quote' === $post->post_type ) {
			$view_quote_page = plugin_dir_url( __FILE__ ) . 'quote.php?quote=' . $post->ID . '&_wpnonce=' . wp_create_nonce();
			$actions['view'] = sprintf(
				'<a href="%s" class="submitview" aria-label="%s &#8220;%s&#8221;">%s</a>',
				admin_url( 'edit.php?post_type=quote&page=view&post=' . $post->ID ), // admin_url( 'edit.php?post=' . $post->ID . '&post_type=quote&_wpnonce=' . wp_create_nonce() ),.
				__( 'View' ),
				$post->post_title,
				__( 'View' )
			);
		}
		return $actions;
	}

	/**
	 * Enqueue script for the add/edit quote screen
	 */
	public function enqueue_scripts( $post ) {
		wp_register_script( 'quote-items', plugin_dir_url( __FILE__ ) . 'js/quote-items.js', array( 'jquery' ), $this->plugin_version, true );
		wp_enqueue_script( 'quote-items' );
	}

	/**
	 * Build custom field meta box
	 *
	 * @param WP_Post $post The post object.
	 */
	public function meta_box_quote_items( $post ) {
		$columns = Quote_Builder_CPT_Quote::get_instance()->get_columns();

		include 'partials/quote-items.php';
	}

}
