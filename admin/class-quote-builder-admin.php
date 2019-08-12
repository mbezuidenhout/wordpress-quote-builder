<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/admin
 * @author     Marius Bezuidenhout <marius@blackhorse.co.za>
 */
class Quote_Builder_Admin {

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
	 * @since    1.0.0
	 * @param      string $plugin_name  The name of this plugin.
	 * @param      string $version      The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/quote-builder-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/quote-builder-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create admin menu options.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {
		remove_submenu_page( 'edit.php?post_type=quote', 'post-new.php?post_type=quote' );

		$cptobj = get_post_type_object( 'quote' );
		if ( current_user_can( $cptobj->cap->create_posts ) ) {
			add_submenu_page(
				'edit.php?post_type=quote',
				$cptobj->labels->add_new_item,
				$cptobj->labels->add_new,
				$cptobj->cap->create_posts,
				'new_quote',
				array( $this, 'new_quote' )
			);
		}
	}

	public function new_quote() {

	}

	/**
	 * Replace Add Quote URL in admin pages.
	 *
	 * @param string $url  Original new post type url.
	 * @param string $path Requested path.
	 *
	 * @return string|void
	 *
	 * @since    1.0.0
	 */
	public function admin_new_quote_url( $url, $path ) {
		if ( 'post-new.php?post_type=quote' === $path ) {
			return admin_url( 'edit.php?post_type=quote&page=new_quote' );
		}

		return $url;
	}

	/**
	 * Redirect any attempts to create a new quote post type to our new page.
	 */
	public function redirect_admin_new_quote_url() {
		global $pagenow;

		if ( 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && 'quote' === $_GET['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_safe_redirect( admin_url( 'post-new.php?post_type=quote' ), '301' );
			exit;
		}
	}

}
