<?php
   /*
   Plugin Name: Twitter Blog Posts Automatically
   Plugin URI: http://www.ss88.co.uk/twitter-blog-posts-automatically/
   Description: Publish your Tweets to your WordPress blog automatically.
   Version: 1.7
   Author: Steven Sullivan
   Author URI: http://www.ss88.co.uk/
   License: GPL2
   */

	require_once('twitter-blog-posts-automatically-functions.php');

	// Start, Register, Go!
	add_action('admin_menu', 't2wp_admin_actions');
	add_action('t2wp_hourly_update_action', 't2wp_hourly_update');
	register_activation_hook(__FILE__, 't2wp_activation');
	register_deactivation_hook(__FILE__, 't2wp_deactivation');
      
?>