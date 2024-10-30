<?php
/*
Plugin Name: Close Comments
Description: A shortcode that closes comments on a page or post at a pre-determined date & time
Plugin URI: http://www.mgmtechdev.com/
Version: 0.8.2
Author: Michael Grace-Martin
Text Domain: n/a
Copyright: 2019, Michael Grace-Martin
*/
add_action('admin_menu', 'closecom_options_menu');
function closecom_options_menu() {
	// add_options_page( Page Title, Menu Title, capability, menu slug, function to be called)
	add_options_page('Close Comments Plugin', 'Close Comments', 'manage_options', 'closecom_plugin', 'closecom_options_page');
	//call register function
	add_action( 'admin_init', 'register_closecom_plugin_settings' );
}
function register_closecom_plugin_settings() {
}

function closecom_action_links ( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=closecom_plugin') ) .'">Example Usage</a>';
   $links[] = '<a href="http://www.mgmtechdev.com/" target="_blank">More Info</a>';
   return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'closecom_action_links' );

function closecom_options_page() {
?>
<div>
<h2>Close Comments Plugin</h2>
<h3 style="margin-top:40px;"><b>Code Examples</b></h3>
<p>Close comments on the page at 12:01pm on June 13, 2019:<br /><b>[close_comments datetime="2019-06-13 12:01:00PM"]</b></p>
<p>Note: your Wordpress website is currently set for this time zone: <?php echo "<b>".get_option('timezone_string')."</b>"; ?> <br />
(you can change this in Settings > General)</p>

<p style="margin-top:30px;"><a href="https://www.mgmtechdev.com/2019/06/13/close-comments-wordpress-predetermined-date-time/" target="_blank">See more instructions</a></p>
<?php
}
/*
example:
[close_comments datetime="2019-06-13 12:01:00PM"] 
*/

function close_comments_func( $atts, $content = "" ) {
	if(isset($atts["datetime"])) {
		date_default_timezone_set(get_option('timezone_string'));
		$expire = strtotime($atts["datetime"]);
		$current = time();
		if ($current>$expire) {
			$post_data = get_post( get_the_ID(), ARRAY_A );
			$comment_status =  $post_data['comment_status'];
			if ($comment_status == "open") {
				$my_post = array(
					'ID' => get_the_ID(),
					'comment_status' => 'closed',
				);
				wp_update_post( $my_post );
			}
		}
	}
}
add_shortcode( 'close_comments', 'close_comments_func' );

?>