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
		update_option('t2wp_lastran', current_time('timestamp', 1));
	}

	// Perform the hourly update
	function t2wp_hourly_update() {
		t2wp_search();
	}

	// Deactivate the Hourly Update
	function t2wp_deactivation() {
		wp_clear_scheduled_hook('t2wp_hourly_update_action');
	}
	
	// Curl
	function t2wp_curl($query) {
	
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, 'http://search.twitter.com/search.json?rpp=100&q=' . $query);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '5');

		$content = trim(curl_exec($ch));
		curl_close($ch);
		
		return $content;
	}
	
	// Search Twitter API
	function t2wp_search($returnTotal = FALSE) {
	
		// Get the values
		$hashtags = get_option('t2wp_hashtags');
		$usernames = get_option('t2wp_usernames');
		$category = get_option('t2wp_category');
		$user = get_option('t2wp_user');
		
		// Run Hashtags
		if(!empty($hashtags))
		{
			$hashes = explode(',', $hashtags);
			foreach($hashes as $hash)
				$query = $hash . '+OR+';
			
			$result = json_decode(t2wp_curl($query), TRUE);
			$totalInserted += t2wp_insert($result['results']);
		}
		
		// Run Usernames
		if(!empty($usernames))
		{
			$users = explode(',', $usernames);
			foreach($users as $user)
				$query = 'from:'.$user . '+OR+';
			
			$result = json_decode(t2wp_curl($query), TRUE);
			$totalInserted += t2wp_insert($result['results']);
		}
		
		if($returnTotal==TRUE)
			return $totalInserted;
	}
	
	// Insert a Post
	function t2wp_insert($array) {
	
		$lastRan = get_option('t2wp_lastran');
	
		foreach($array as $object)
		{		
			if(strtotime($object['created_at']) > $lastRan)
			{
				$StatusID = $object['id'];
                $post['post_date'] = date("Y-m-d H:i:s",strtotime($object['created_at']));
                $post['post_content'] = 'https://twitter.com/'. $object['from_user'] .'/status/' . $StatusID;
				$post['post_status'] = 'publish';
				$post['post_author'] = get_option('t2wp_user');
				$post['post_category'] = array(get_option('t2wp_category'));
				$post['post_title'] = 'Tweeted: ' . $post['post_date'];
				$post['post_slug'] = 'Tweeted: ' . $post['post_date'];
				
                @wp_insert_post($post);
				update_option('t2wp_lastran', current_time('timestamp', 1));
				$totalInserted++;
			}
		}
	
		return $totalInserted;
	}
?>