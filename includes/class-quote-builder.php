<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 * @author     Marius Bezuidenhout <marius@blackhorse.co.za>
 */
class Quote_Builder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Quote_Builder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'QUOTE_BUILDER_VERSION' ) ) {
			$this->version = QUOTE_BUILDER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'quote-builder';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();

		// Load admin dependencies.
		add_action( 'init', array( $this, 'define_admin_hooks' ) );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Quote_Builder_Loader. Orchestrates the hooks of the plugin.
	 * - Quote_Builder_i18n. Defines internationalization functionality.
	 * - Quote_Builder_Admin. Defines all hooks for the admin area.
	 * - Quote_Builder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quote-builder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quote-builder-i18n.php';

		/**
		 * The class responsible for the custom post type quote.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quote-builder-cpt-quote.php';

		/**
		 * The class responsible for providing WordPress Settings API functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quote-builder-settings-api.php';

		/**
		 * The class responsible for plugin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quote-builder-settings.php';

		if ( is_admin() ) {
			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-quote-builder-admin.php';

			/**
			 * The class responsible for defining strings and methods.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-cpt-quote.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-quote-builder-public.php';

		/**
		 * Load packages loaded through composer.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		$this->loader = new Quote_Builder_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Quote_Builder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Quote_Builder_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function define_admin_hooks() {
		if ( is_user_logged_in() && is_admin() ) {
			$plugin_admin = new Quote_Builder_Admin( $this->get_plugin_name(), $this->get_version() );
			$cpt_quote    = Admin_CPT_Quote::get_instance( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
			$this->loader->add_action( 'quote_builder_print', $plugin_admin, 'print_quote' );

			$this->loader->add_filter( 'post_updated_messages', $cpt_quote, 'post_updated_messages' );
			$this->loader->add_filter( 'bulk_post_updated_messages', $cpt_quote, 'bulk_post_updated_messages', 10, 2 );
			$this->loader->add_filter( 'enter_title_here', $cpt_quote, 'title_placeholder', 10., 2 );
			$this->loader->add_filter( 'user_contactmethods', $cpt_quote, 'user_contactmethods' );
			$this->loader->add_action( 'admin_enqueue_scripts', $cpt_quote, 'enqueue_scripts' );
			$this->loader->add_action( 'post_row_actions', $cpt_quote, 'post_row_actions', 10, 2 );

			$post_type = 'quote';
			$this->loader->add_action( "add_meta_boxes_{$post_type}", $cpt_quote, 'add_meta_boxes' );
			$this->loader->add_action( "save_post_{$post_type}", $cpt_quote, 'save_quote_line_items', 10, 3 );
			$this->loader->add_action( "save_post_{$post_type}", $cpt_quote, 'save_quote_customer', 10, 3 );
			$this->loader->add_filter( "manage_{$post_type}_posts_columns", $cpt_quote, 'quotes_columns' );
			$this->loader->add_filter( 'manage_edit-quote_sortable_columns', $cpt_quote, 'quotes_sortable_columns' );

			$this->run();
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Quote_Builder_Public( $this->get_plugin_name(), $this->get_version() );

		$cpt_quote = Quote_Builder_CPT_Quote::get_instance( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $cpt_quote, 'create_post_type' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Quote_Builder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
