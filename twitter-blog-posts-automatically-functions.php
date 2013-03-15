<?php
	// Add it to the menu
	function t2wp_admin_actions() {
		add_options_page('Twitter to Blog Posts', 'Twitter to Blog Posts', 1, 't2wp-admin', 't2wp_admin');
	};	

	// Display the Admin
	function t2wp_admin() {
		include('t2wp-admin.php');
	} 

	// Activate the Hourly Update
	function t2wp_activation() {
		wp_schedule_event(current_time('timestamp'), 'hourly', 't2wp_hourly_update_action');
		update_option('t2wp_lastran', current_time('timestamp'));
	}

	// Perform the hourly update
	function t2wp_hourly_update() {
		t2wp_search();
	}

	// Deactivate the Hourly Update
	function t2wp_deactivation() {
		wp_clear_scheduled_hook('t2wp_hourly_update_action');
	}
	
	// Curl or file_get_contents
	function t2wp_curl($query) {
	
		if (!function_exists('curl_init')){ 
			$ch = file_get_contents('http://search.twitter.com/search.json?rpp=100&result_type=recent&q=' . urlencode($query));
			$content = trim(curl_exec($ch));
		}
		else
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://search.twitter.com/search.json?rpp=100&result_type=recent&q=' . urlencode($query));  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '5');
			$content = trim(curl_exec($ch));
			curl_close($ch);
		}
		
		return $content;
	}
	
	// Search Twitter API
	function t2wp_search($returnTotal = FALSE) {
	
		// Get the values
		$hashtags = get_option('t2wp_hashtags');
		$usernames = get_option('t2wp_usernames');
		$category = get_option('t2wp_category');
		$user = get_option('t2wp_user');
		$format = get_option('t2wp_format');
		
		// Run Hashtags
		if(!empty($hashtags))
		{
			$hashes = explode(',', $hashtags);
			foreach($hashes as $hash)
				$query = '#' . $hash . '+OR+';
			$query = substr($query, 0, -4);
			$result = json_decode(t2wp_curl($query), TRUE);
			$totalInserted += t2wp_insert($result['results']);
		}
		
		// Run Usernames
		if(!empty($usernames))
		{
			$users = explode(',', $usernames);
			foreach($users as $user)
				$query = 'from:'.$user . '+OR+';
			$query = substr($query, 0, -4);
			$result = json_decode(t2wp_curl($query), TRUE);
			$totalInserted += t2wp_insert($result['results']);
		}
		
		if($returnTotal==TRUE)
			return $totalInserted;
	}
	
	// Insert a Post
	function t2wp_insert($array) {
	
		$lastRan = get_option('t2wp_lastran');		date_default_timezone_set('GMT');		$newlastRan = strtotime(date('Y-m-d H:i:s',  $lastRan));
		$format = get_option('t2wp_format');	
		foreach($array as $object)
		{		
			if(strtotime($object['created_at']) > $newlastRan)
			{
				$StatusID = $object['id_str'];				date_default_timezone_set(get_option('timezone_string'));
                $post['post_date'] = date("Y-m-d H:i:s",strtotime($object['created_at']));
                $post['post_content'] = 'https://twitter.com/'. $object['from_user'] .'/status/' . $StatusID;
				$post['post_status'] = 'publish';
				$post['post_author'] = get_option('t2wp_user');
				$post['post_category'] = array(get_option('t2wp_category'));
				//$post['post_title'] = 'Tweeted: ' . $post['post_date'];
				//$post['post_slug'] = 'Tweeted: ' . $post['post_date'];
				$post['post_type'] = $format;
				
				//print_r($post);
				
				$theResult = wp_insert_post($post, TRUE);
				
                if(is_wp_error($theResult))
				{
					echo $theResult->get_error_message();
				}
				else
				{
					update_option('t2wp_lastran', current_time('timestamp'));
					$totalInserted++;
				}
			}
		}
	
		return $totalInserted;
	}
?>