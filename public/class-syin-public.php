<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       None
 * @since      1.0.0
 *
 * @package    Syin
 * @subpackage Syin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Syin
 * @subpackage Syin/public
 * @author     Sebastian <Stampfel>
 */
class Syin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
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
		 * defined in Syin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Syin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/syin-public.css', array(), null, 'all' );

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
		 * defined in Syin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Syin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/syin-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Callback function for sybos-operations shortcode.
	 *
	 * Initalizes a sybos-api object. Depening on shortcode attributes fetches all available operations
	 * or only operations for a specific year.
	 *
	 * Operations are then filtered according to shortcode attribute "department", which allows
	 * display of operations for a specific fire department in a specific town/city.
	 * See comments in *class-sybos-api.php* for further details about filtering.
	 *
	 * @param $atts array|string Shortcode attributes, see wordpress documentation for further details
	 *
	 * @return string Clean HTML for adding to the page the shortcode is placed on
	 * @since 1.0.0
	 */
	function display_operations( $atts ): string {
		require_once( "class-sybos-api.php" );

		/**
		 * Initialize API with key and base url provided in settings
		 */
		$api = new Sybos_API( get_option( "sybos_integration_api_key_setting" ), get_option( "sybos_integration_api_base_url_setting" ) );
		unset( $operations ); // Cleans variable if peviously set

		/**
		 * If year is provided as shortcode attribute,
		 * only retrieve operations for the year specified.
		 *
		 * If department is provided as shortcode attribute,
		 * only retireve operations for the department specified.
		 */
		$department = "";
		$year = null;
		if ( is_array( $atts ) ) {
			if ( array_key_exists( "year", $atts ) ) {
				$year = $atts["year"];
			}
			if ( array_key_exists( "department", $atts ) ) {
				$department = $atts["department"];
			}
		}

		if($year != null){
			$operations = $api->fetchOperationsForYear( $year );
		} else {
			$operations = $api->fetchOperations();
		}

		$content = "<div class='operations-list'>";

		$counter = 0;   // Allows for unique ids with the operation-divs
		foreach ( $operations as $operation ) {

			/**
			 * Ugly hack to filter for a specific department,
			 * please close your eyes and ignore this if you
			 * do not want to throw up.
			 */
			if ( ! empty( $department ) ) {
				if ( $operation->Abteilung != $department ) {
					continue;
				}
			}

			$content .= "<div class='operation' id='operation-" . $counter . "'>";
			$content .= "<h4>" . $operation->Alarmierung . " <span class='time'>(" . $operation->AlarmierungZeit . " Uhr)</span></h4>";
			if ( $operation->Hauptaetigkeit != null ) {
				$content .= "<p class='operation-details'><span class='prefix'>" . $operation->Kategorie . " (" . $operation->Einsatzort . "): " . "</span>" .
				            $operation->Hauptaetigkeit . "</p>";
			} else {
				$content .= "<p class='operation-details'><span class='prefix'>" . $operation->Kategorie . " (" . $operation->Einsatzort . ")" . "</span>" . "</p>";
			}
			$content .= "<p>" . $operation->Unfallhergang . "</p>";
			if ( $operation->Einsatzleiter != " " ) {
				$content .= "<small>Einsatzleitung: " . $operation->Einsatzleiter . "</small>";
			}
			$content .= "</div>";
			$counter ++;
		}
		$content .= "</div>";

		return $content;
	}

	/**
	 * Auxiliary function, just for development purposes. Makes var_dump() output
	 * behave in HTML context.
	 * Taken from https://www.tutorialspoint.com/how-to-capture-the-result-of-var-dump-to-a-string-in-php
	 *
	 * @param $var Variable to be dumped
	 *
	 * @return false|string False on failure, content on success
	 */
	function varDumpToString( $var ) {
		ob_start();
		var_dump( $var );
		$ret = ob_get_clean();

		return $ret;
	}

	public function register_shortcodes() {
		add_shortcode( 'sybos-operations', array( $this, 'display_operations' ) );
	}

}
