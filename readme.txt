=== Plugin Name ===
Contributors: ss88
Donate link: http://www.ss88.co.uk/twitter-blog-posts-automatically/
Tags: twitter, blog, posts, automatically
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Publish (embed) your Tweets to your WordPress blog automatically (every hour) or manually with one simple click.

== Description ==

Publish (embed) your Tweets to your WordPress blog automatically (every hour) or manually with one simple click.

== Installation ==

1. Upload `/twitter-blog-posts-automatically/` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Define settings by going to Settings > Twitter to Blog Posts.
4. Wait or press the button `Run a Twitter Scan` to publish any tweets since the plugin activation.

== Frequently Asked Questions ==

= I need help... =

[Click here to go to my blog to ask for help](http://www.ss88.co.uk/twitter-blog-posts-automatically/)

== Screenshots ==

1. Admin Area visual.
2. Blog post visual.

== Changelog === 1.7 =* posts ended up as scheduled instead of posted because of tiezone issues
= 1.6 =* change timestamp issues, twitter issues
= 1.5 =
* fixed timestamp issues. Set to default to server time.

= 1.4 =
* fixed 'hashtag' bug. Now it's actually extracting #hashtags #like #this

= 1.3 =
* added error output if a post is not inserted and why. errors produced by wordpress.
* fixed the post format issue - default before was post/none
* removed slug and post title when inserting a post.


= 1.2 =
* added file_get_contents if curl is not working
* added new post format drop down. Setting this to 'status' is your template supports it is best for Twitter posts.
* Fixed some HTML issues in the admin area


= 1.1 =
* added urlencode() to $query param
* fixed bigint issue on 32 bit machines (hopefully)
* changed default category name
* require field is an actual posting category


= 1.0 =
* Launch.