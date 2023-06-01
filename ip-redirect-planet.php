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
    update_option('location_redirect_location_1', $_POST['location_1']);
    update_option('location_redirect_url_1', $_POST['url_1']);
    update_option('location_redirect_location_2', $_POST['location_2']);
    update_option('location_redirect_url_2', $_POST['url_2']);
    update_option('location_redirect_location_3', $_POST['location_3']);
    update_option('location_redirect_url_3', $_POST['url_3']);
    echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
}

  // Retrieve saved settings
  $location_1 = get_option('location_redirect_location_1');
  $url_1 = get_option('location_redirect_url_1');
  $location_2 = get_option('location_redirect_location_2');
  $url_2 = get_option('location_redirect_url_2');
  $location_3 = get_option('location_redirect_location_3');
  $url_3 = get_option('location_redirect_url_3');
  ?>

  <div class="wrap">
      <h1>Location Redirect Settings</h1>

      <form method="post" action="">
          <table class="form-table">
          <tr>
                    <th scope="row"><label for="location_1">Location 1</label></th>
                    <td>
                        <input type="text" name="location_1" id="location_1" value="<?php echo esc_attr($location_1); ?>" />
                    </td>
                    <th scope="row"><label for="url_1">Redirect URL 1</label></th>
                    <td>
                        <input type="text" name="url_1" id="url_1" value="<?php echo esc_attr($url_1); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="location_2">Location 2</label></th>
                    <td>
                        <input type="text" name="location_2" id="location_2" value="<?php echo esc_attr($location_2); ?>" />
                    </td>
                    <th scope="row"><label for="url_2">Redirect URL 2</label></th>
                    <td>
                        <input type="text" name="url_2" id="url_2" value="<?php echo esc_attr($url_2); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="location_3">Location 3</label></th>
                    <td>
                        <input type="text" name="location_3" id="location_3" value="<?php echo esc_attr($location_3); ?>" />
                    </td>
                    <th scope="row"><label for="url_3">Redirect URL 3</label></th>
                    <td>
                        <input type="text" name="url_3" id="url_3" value="<?php echo esc_attr($url_3); ?>" />
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
  $location_1 = get_option('location_redirect_location_1');
  $url_1 = get_option('location_redirect_url_1');
  $location_2 = get_option('location_redirect_location_2');
  $url_2 = get_option('location_redirect_url_2');
  $location_3 = get_option('location_redirect_location_3');
  $url_3 = get_option('location_redirect_url_3');
  
  // Get the user's IP address
  $ip = $_SERVER['REMOTE_ADDR'];

  // Use a geolocation API to get the user's location based on IP address

  $api_url = "https://ipinfo.io/$ip/json";
  $response = wp_remote_get($api_url);

  if (!is_wp_error($response) && $_SERVER['REQUEST_URI']==='/'  && !isset($_COOKIE['redirected'])) {
      $body = wp_remote_retrieve_body($response);
      $data = json_decode($body);
      // Check if the user's location matches the configured location        
        
      setcookie("redirected", true, time()+1296000, COOKIEPATH, COOKIE_DOMAIN, true, true );

        switch ($data->country) {
            case $location_1:
                wp_redirect($url_1);
                 exit;
                break;
            
            case $location_2:
                    wp_redirect($url_2);
                     exit;
                    break;
             case $location_2:
                wp_redirect($url_2);
                   exit;
                   break;
            
            default:
                return;
                break;
        }
         
  }
}
add_action('template_redirect', 'location_redirect');
