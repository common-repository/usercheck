<?php
/**
 * UserCheck
 *
 * @package           UserCheck
 * @author            usercheck
 * @copyright         2024 UserCheck
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       UserCheck
 * Plugin URI:        https://www.usercheck.com
 * Description:       Protect your WordPress site from disposable email addresses.
 * Version:           0.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            UserCheck
 * Author URI:        https://www.usercheck.com
 * Text Domain:       usercheck
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main UserCheck class.
 */
class UserCheck {
	/**
	 * API key for UserCheck.
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * Base URL for the UserCheck API.
	 *
	 * @var string
	 */
	private $api_base_url = 'https://api.usercheck.com';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->api_key = get_option( 'usercheck_api_key', '' );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'is_email', array( $this, 'validate_email' ), 10, 2 );
	}

	/**
	 * Add options page to the admin menu.
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'UserCheck Settings', 'usercheck' ),
			__( 'UserCheck', 'usercheck' ),
			'manage_options',
			'usercheck',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'usercheck_settings', 'usercheck_api_key' );
	}

	/**
	 * Render the settings page.
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'UserCheck Settings', 'usercheck' ); ?></h1>
			<?php if ( empty( $this->api_key ) ) : ?>
				<div class="notice notice-info">
					<p><strong><?php esc_html_e( 'Add your API key to unlock higher limits!', 'usercheck' ); ?></strong></p>
					<p><?php esc_html_e( 'UserCheck works without an API key, but is limited to 60 requests per hour.', 'usercheck' ); ?></p>
					<p><a href="https://app.usercheck.com/" target="_blank"><?php esc_html_e( 'Get your Free API key here', 'usercheck' ); ?></a>.</p>
				</div>
			<?php endif; ?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'usercheck_settings' );
				do_settings_sections( 'usercheck_settings' );
				?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="usercheck_api_key"><?php esc_html_e( 'API Key', 'usercheck' ); ?></label></th>
						<td>
							<input type="text" id="usercheck_api_key" name="usercheck_api_key" value="<?php echo esc_attr( $this->api_key ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Enter your UserCheck API key. Leave blank to send unauthenticated requests (limited to 60 requests per hour).', 'usercheck' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Validate email using UserCheck API.
	 *
	 * @param bool   $is_valid Whether the email address is valid.
	 * @param string $email    The email address to check.
	 * @return bool Whether the email address is valid.
	 */
	public function validate_email( $is_valid, $email ) {
		if ( ! $is_valid ) {
			return false; // If it's already invalid, no need to check further.
		}

		$domain = $this->get_domain_from_email( $email );

		$url = $this->api_base_url . '/domain/' . urlencode( $domain );

		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->api_key,
				'Accept'        => 'application/json',
			),
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			error_log( 'UserCheck API Error: ' . $response->get_error_message() );
			return $is_valid; // Return original validity if API check fails.
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( isset( $data['disposable'] ) && true === $data['disposable'] ) {
			return false; // Domain is disposable, so we return false to indicate it's invalid.
		}

		return $is_valid;
	}

	/**
	 * Extract domain from email address.
	 *
	 * @param string $email The email address.
	 * @return string The domain part of the email address.
	 */
	private function get_domain_from_email( $email ) {
		$parts = explode( '@', $email );
		return isset( $parts[1] ) ? $parts[1] : '';
	}
}

new UserCheck();
