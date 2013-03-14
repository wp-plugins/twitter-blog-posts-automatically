<?php 
	
	require_once('twitter-blog-posts-automatically-functions.php');
	
	// Clean up the string.
	function cleanString($s)
	{
		$a = explode(',', $s);
		
		foreach ($a as $k => $v)
		{
			$v = trim($v);
			$v = str_replace(array(' ', '#', '@'), '', $v);
			
			if (is_null($value) || empty($v))
				unset($a[$key]);
			else
				$a[$key] = $value;
		}
		
		$a = ( implode(',', array_values($a))  );    
		return $a;
	}
	
	// Handle the POST.
	if($_POST['t2wp_hidden'] == 'Y')
	{
		$hashtags   = cleanString($_POST['t2wp_hashtags']);
		$usernames  = cleanString($_POST['t2wp_usernames']);
		$category   = $_POST['t2wp_category'];
		$user       = $_POST['t2wp_user'];
		$format		= $_POST['t2wp_format'];

		if((!empty($hashtags) || !empty($usernames)) && !empty($category))
		{
			// Store the values
			update_option('t2wp_hashtags', $hashtags);
			update_option('t2wp_usernames', $usernames);
			update_option('t2wp_category', $category);
			update_option('t2wp_user', $user);
			update_option('t2wp_format', $format);
			$Success = 'Update was successful.';
		}
		elseif(empty($category))
			$Error = 'Please select a category to post the tweets to.';
		else
			$Error = 'Updated failed. Please type a username and/or hashtag. To stop this plugin from working, please disable this plugin.';
	}
	else
	{
		// Get the values
		$hashtags = get_option('t2wp_hashtags');
		$usernames = get_option('t2wp_usernames');
		$category = get_option('t2wp_category');
		$user = get_option('t2wp_user');
		$format = get_option('t2wp_format');
	}
	
	// Run a Twitter Scan
	if(isset($_POST['RunCron']))
		$Success = 'Successfully ran. A total of ' . t2wp_search(TRUE) . ' Tweets were published.';
	
?>

<div class="wrap">

<div style="width:230px;float:right;margin-top:20px;margin-right:20px;">
	<div style="padding:0 15px;background-color:#FFFFCC;border:1px solid #FFCC99;width:200px;">
		<h3>Developer</h3>
		<p>Developer is <a href="http://www.ss88.co.uk/" target="_blank">Steven Sullivan</a>.</p>
	</div>

	<div style="padding:0 15px;background-color:#FFFFCC;border:1px solid #FFCC99;margin-top:25px;width:200px;clear:both;">
		<h3>Support, Suggestions &amp; Bugs</h3>
		<p>If you have any suggestions, want to support me or have found a bug <a href="http://www.ss88.co.uk/twitter-blog-posts-automatically/" target="_blank">click here</a>.</p>
	</div>
</div>

<div style="float:left;">

	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Twitter to Blog Posts</h2>
	<p>Fill in the required fields below and every hour WordPress will scan Twitter for updates and publish them on your blog.</p>
	<p>Only Tweets tweeted after <strong><?php echo date('d/m/Y @ h:iA O', get_option('t2wp_lastran')); ?></strong> will be puslihed to your blog.</p>		<p>Current WordPress Date/Time: <?php echo current_time('mysql'); ?></p>

	<?php if(isset($Success)) { echo '<p style="color:green;padding:5px;border:1px dashed green;">'.$Success.'</p>'; } ?>
	<?php if(isset($Error)) { echo '<p style="color:red;padding:5px;border:1px dashed red;">'.$Error.'</p>'; } ?>

	<form method="post" action="">
		<input type="hidden" name="t2wp_hidden" value="Y">
		<table class="form-table">
			<tr>
				<td><label for="t2wp_hashtags">Hashtag(s):</label></td>
				<td><input type="text" name="t2wp_hashtags" id="t2wp_hashtags" value="<?php echo $hashtags; ?>" size="40"></input>&nbsp;<em>eg: tech, bigbrother, fbi (do not include the hash #)</em></input></td>
			</tr>
			<tr>
				<td><label for="t2wp_usernames">Twitter Username(s):</label></td>
				<td><input type="text" name="t2wp_usernames" id="t2wp_usernames" value="<?php echo $usernames; ?>" size="40">&nbsp;<em>eg: StevieSullivan, BBCNews (do not include the @)</em></td>
			</tr>
			<tr>
				<td><label for="t2wp_category">Post Category:</label></td>
				<td><?php
						$dropdown_options = array("show_option_all" => __("Please select..."), "hide_empty" => 0, "hierarchical" => 1, "show_count" => 1, "orderby" => "name", "name" => "t2wp_category", "selected" => $category);
						wp_dropdown_categories($dropdown_options);
					?>
				</td>
             </tr>
			<tr>
				<td><label for="t2wp_format">Post Format:</label></td>
				<td><select name="t2wp_format"><option value="post">Default (post)</option><?php

					if ( current_theme_supports( 'post-formats' ) ) {
						$post_formats = get_theme_support( 'post-formats' );
					
						if ( is_array( $post_formats[0] ) ) {
							foreach($post_formats[0] as $PFormat)
							{
								if($format == $PFormat)
									echo '<option selected>'.$PFormat.'</option>';
								else
									echo '<option>'.$PFormat.'</option>';
							}
						}
					}

					?></select>
				</td>
             </tr>
				<tr>
					<td><label for="t2wp_user">Default User:</label></td>
					<td><?php 
							$dropdown_options = array("name" => "t2wp_user", "selected" => $user);
							wp_dropdown_users($dropdown_options); 
						?>
					</td>
				</tr>
		</table>
		<p class="submit"><input type="submit" name="Submit" value="Update The Settings" /></p>
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>Manual Run</h2>
		<p><strong>Last Run:</strong> <?php echo date('d/m/Y @ h:iA O', get_option('t2wp_lastran')); ?></p>
		<p>This plugin runs every hour however, you can run it manually by pressing the button below.</p>
		<p class="submit"><input type="submit" name="RunCron" value="Run a Twitter Scan" onclick="this.value='Loading... Please wait.';" /></p>
	</form>	
	
</div>

</div>