<?php
/**
 * Defined the plugin settings.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/includes
 */

/**
 * Class Quote_Builder_Settings defines the plugin options.
 */
class Quote_Builder_Settings {
	/**
	 * Singleton instance of this class.
	 *
	 * @var Quote_Builder_Settings
	 */
	private static $instance;

	/**
	 * Instance of the Quote_Builder_Settings_API class.
	 *
	 * @var Quote_Builder_Settings_API
	 */
	private $settings_api;

	/**
	 * Array of plugin settings.
	 *
	 * @var array
	 */
	protected $basic_settings;

	/**
	 * Returns the singleton instance of the settings class
	 */
	public static function get_instance() {
		if ( ! self::$instance instanceof self ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Quote_Builder_Settings constructor.
	 */
	public function __construct() {
		$this->settings_api = new Quote_Builder_Settings_API();

		$default_settings = array();
		foreach ( $this->get_settings_fields()['quote_builder_basics'] as $setting ) {
			$default_settings[ $setting['name'] ] = $setting['default'];
		}

		$settings             = empty( get_option( 'quote_builder_basics' ) ) ? array() : get_option( 'quote_builder_basics' );
		$this->basic_settings = wp_parse_args( $settings, $default_settings );
	}

	/**
	 * Get setting by name
	 *
	 * @param string $name Name of setting to retrieve.
	 *
	 * @return mixed
	 */
	public function get_setting( $name ) {
		if ( isset( $this->basic_settings[ $name ] ) ) {
			return $this->basic_settings[ $name ];
		} else {
			return false;
		}
	}

	/**
	 * Register plugin settings
	 */
	public function settings_page() {
		echo '<div class="wrap">';

		$this->settings_api->show_navigation();
		$this->settings_api->show_forms();

		echo '</div>';
	}

	/**
	 * Define the settings sections.
	 *
	 * @return array
	 */
	private function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'quote_builder_basics',
				'title' => __( 'Quote Builder Settings', 'quote-builder' ),
			),
		);

		return apply_filters( 'quote_builder_settings_sections', $sections );
	}

	/**
	 * Registers plugin settings
	 */
	public function add_settings_fields() {
		// set the settings.
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings.
		$this->settings_api->admin_init();
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields() {
		$settings_fields = array(
			'quote_builder_basics' => array(
				array(
					'name'        => 'logo',
					'label'       => __( 'Logo', 'quote-builder' ),
					'desc'        => __( 'Quotation logo', 'quote-builder' ),
					'placeholder' => __( 'Logo', 'quote-builder' ),
					'type'        => 'file',
					'default'     => '',
				),
				array(
					'name'        => 'page_size',
					'label'       => __( 'Page size', 'quote-builder' ),
					'desc'        => __( 'Quotation print page size', 'quote-builder' ),
					'placeholder' => '',
					'type'        => 'select',
					'default'     => 'A4',
					'options'     => array(
						'A3'     => 'A3',
						'A4'     => 'A4',
						'A5'     => 'A5',
						'Letter' => 'Letter',
						'Legal'  => 'Legal',
					),
				),
				array(
					'name'        => 'page_orientation',
					'label'       => __( 'Page orientation', 'quote-builder' ),
					'desc'        => __( 'Quotation print page orientation', 'quote-builder' ),
					'placeholder' => '',
					'type'        => 'select',
					'default'     => 'Portrait',
					'options'     => array(
						'Portrait'  => 'Portrait',
						'Landscape' => 'Landscape',
					),
				),
			),
		);

		return apply_filters( 'quote_builder_settings', $settings_fields );
	}
}
