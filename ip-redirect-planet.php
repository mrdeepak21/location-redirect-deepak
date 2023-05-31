<?php
/**
 * @package: ip-redirect-planet
 * @version: 1.0
 * Plugin Name: Location-based URL Redirect
 * Description: Redirect users based on their location.
 * Author: Plant-for-the-Planet
 * Author URI: https://pp.eco
 * Version: 1.0
 */
if (!defined('ABSPATH')) {
    exit('Action Not Allowed');
}

// Add the settings page to the WordPress admin menu
function location_redirect_settings_menu() {
  add_options_page(
      'Location Redirect Settings',
      'Location Redirect',
      'manage_options',
      'location-redirect',
      'location_redirect_settings_page'
  );
}
add_action('admin_menu', 'location_redirect_settings_menu');

// Display the settings page in the WordPress admin
function location_redirect_settings_page() {
  // Save settings if the form is submitted
  if (isset($_POST['submit'])) {
      update_option('location_redirect_location', $_POST['location']);
      update_option('location_redirect_url', $_POST['url']);
      echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
  }

  // Retrieve saved settings
  $location = get_option('location_redirect_location');
  $url = get_option('location_redirect_url');
  ?>

  <div class="wrap">
      <h1>Location Redirect Settings</h1>

      <form method="post" action="">
          <table class="form-table">
              <tr>
                  <th scope="row"><label for="location">Location</label></th>
                  <td>
                      <input type="text" name="location" id="location" value="<?php echo esc_attr($location); ?>" />
                  </td>
              </tr>
              <tr>
                  <th scope="row"><label for="url">Redirect URL</label></th>
                  <td>
                      <input type="text" name="url" id="url" value="<?php echo esc_attr($url); ?>" />
                  </td>
              </tr>
          </table>

          <p class="submit">
              <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
          </p>
      </form>
  </div>

  <?php
}

// Redirect users based on their location
function location_redirect() {
  $location = get_option('location_redirect_location');
  $url = get_option('location_redirect_url');
  
  // Get the user's IP address
  $ip = $_SERVER['REMOTE_ADDR'];

  // Use a geolocation API to get the user's location based on IP address

  $api_url = "https://ipinfo.io/$ip/json";
  $response = wp_remote_get($api_url);
  if (!is_wp_error($response)) {
      $body = wp_remote_retrieve_body($response);
      $data = json_decode($body);
      // Check if the user's location matches the configured location
      if ($data->country === $location && is_front_page()) {
          wp_redirect($url);
          exit;
      }
  }
}
add_action('template_redirect', 'location_redirect');
