<?php
/**
 * @package: ip-redirect-deepak
 * @version: 1.0
 * Plugin name: IP based redirect by deepak
 * Description: Simple IP based redirection in PHP curl. Work only for Maxico.
 * Author: Deepak Kumar
 * Author URI: https://www.linkedin.com/in/deepak01/
 * Version: 1.0
 */
if (!defined('ABSPATH')) {
    exit('Action Not Allowed');
}
$IP = $_SERVER['REMOTE_ADDR'];
$ch = curl_init('https://ipinfo.io/'.$IP.'/json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);
$data = json_decode($data);
$country = isset($data->country)?$data->country:'';
    if($country =='MX'){ 
		header('Location: https://playajardines.com/');
    }