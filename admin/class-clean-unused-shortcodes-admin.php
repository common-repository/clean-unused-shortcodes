<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://amrelarabi.com
 * @since      1.0.0
 *
 * @package    Clean_Unused_Shortcodes
 * @subpackage Clean_Unused_Shortcodes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Clean_Unused_Shortcodes
 * @subpackage Clean_Unused_Shortcodes/admin
 * @author     Amr Elarabi <contact@amrelarabi.com>
 */
class Clean_Unused_Shortcodes_Admin {

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
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version    The version of this plugin.
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
		 * defined in Clean_Unused_Shortcodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Clean_Unused_Shortcodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/clean-unused-shortcodes-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', false, '4.0.13', 'all' );
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
		 * defined in Clean_Unused_Shortcodes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Clean_Unused_Shortcodes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), '4.0.13', true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/clean-unused-shortcodes-admin.js', array( 'jquery', $this->plugin_name . '-select2' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'cus_ajax_object',
			array(
				'admin_ajax'     => admin_url( 'admin-ajax.php' ),
				'cus_ajax_nonce' => wp_create_nonce( 'cus_ajax_nonce' ),
			)
		);
	}

	/**
	 * Plugin tools page.
	 *
	 * @return void
	 */
	public function cus_admin_menu() {
		add_submenu_page(
			'tools.php',
			__( 'Clean unused shortcodes', 'clean-unused-shortcodes' ),
			__( 'Clean unused shortcodes', 'clean-unused-shortcodes' ),
			'manage_options',
			'clean-unused-shortcodes',
			array( $this, 'cus_tool_page' ),
		);
	}

	/**
	 * Render tool page.
	 *
	 * @return void
	 */
	public function cus_tool_page() {
		add_thickbox();
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);
		require_once CUS_PLUGIN_PATH . 'admin/partials/clean-unused-shortcodes-admin-display.php';
	}

	/**
	 * Clean post shortcodes by ID.
	 *
	 * @param  mixed  $post_id post ID to clean it.
	 * @param  string $content post content to strip.
	 * @return void
	 */
	private function cus_clean_post_shortcodes( $post_id, $content ) {
		global $shortcode_tags;
		$active_shortcodes = ( is_array( $shortcode_tags ) && ! empty( $shortcode_tags ) ) ? array_keys( $shortcode_tags ) : array();
		if ( ! empty( $active_shortcodes ) ) {
			$active_regex    = implode( '|', $active_shortcodes );
			$striped_content = preg_replace( "~(?:\[/?)(?!(?:$active_regex))[^/\]]+/?\]~s", '', $content );
		} else {
			$striped_content = preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $content );
		}
		$data = array(
			'ID'           => $post_id,
			'post_content' => $striped_content,
		);
		wp_update_post( $data );
	}
	/**
	 * Clean shortcodes AJAX.
	 *
	 * @return void
	 */
	public function cus_clean_shortcodes() {
		if ( ! isset( $_REQUEST['nonce'] ) ) {
			wp_send_json_error( __( 'Not valid nonce.', 'clean-unused-shortcodes' ) );
		}
		if ( ! wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'cus_ajax_nonce' ) ) {
			wp_send_json_error( __( 'Not valid nonce.', 'clean-unused-shortcodes' ) );
		}
		$types = isset( $_REQUEST['types'] ) && ! empty( $_REQUEST['types'] ) && is_array( $_REQUEST['types'] ) ? $_REQUEST['types'] : '';// phpcs:ignore
		$types = in_array( 'all', $types, true ) ? 'any' : $types;
		$args  = array(
			'post_type'      => $types,
			'posts_per_page' => -1,
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$this->cus_clean_post_shortcodes( get_the_ID(), get_the_content() );
				wp_reset_postdata();
			}
		}
		wp_send_json_success( __( 'Cleaned successfully.', 'clean-unused-shortcodes' ) );
	}
}
