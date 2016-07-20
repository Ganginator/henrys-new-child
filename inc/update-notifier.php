<?php 

define( 'NOTIFIER_THEME_NAME', 'houseofcoffee' );
define( 'NOTIFIER_THEME_FOLDER_NAME', 'houseofcoffee' );
define( 'NOTIFIER_XML_FILE', 'http://none.net/_theme_version/houseofcoffee.xml' );
define( 'NOTIFIER_THEME_PAGE', 'http://themeforest.net/item/houseofcoffee-responsive-wordpress-theme/9553045?ref=none' );
define( 'NOTIFIER_AUTHOR_NAME', 'none' );
define( 'NOTIFIER_AUTHOR_PAGE', 'http://www.none.com' );
define( 'NOTIFIER_DOCS_PAGE', 'http://support.none.com/hc/en-us/articles/202117081-How-to-update-houseofcoffee-' );
define( 'NOTIFIER_CACHE_INTERVAL', 43200 ); // 12 hours

// Adds an update notification message
function update_notifier_message() { 
	
	$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL);
	$theme_data = wp_get_theme(NOTIFIER_THEME_FOLDER_NAME);
	
	if( $xml->latest > $theme_data->get('Version')) {
	?>

		<div class="error">
		<?php 
			$theme_update_notice = '<p><strong>Your active theme <a href="' . NOTIFIER_THEME_PAGE . '" target="_blank">' . NOTIFIER_THEME_NAME . '</a> by <a href="' . NOTIFIER_AUTHOR_PAGE . '" target="_blank">' . NOTIFIER_AUTHOR_NAME . '</a> is outdated</strong>! You have version v' . $theme_data->get('Version') . ' installed and the latest version is v' . $xml->latest .'. To avoid any security threats and ensure maximum compatibility with your plugins, please <a href="' . NOTIFIER_DOCS_PAGE . '" target="_blank">update now</a>.</p>';
			echo $theme_update_notice;
		?>
		</div>

	<?php
	}
}
add_action('admin_head','update_notifier_message');




// Adds an update notification to the WordPress Dashboard menu
/*function update_notifier_menu() {  
	if (function_exists('simplexml_load_string')) { // Stop if simplexml_load_string funtion isn't available
	    $xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
		$theme_data = wp_get_theme(NOTIFIER_THEME_FOLDER_NAME); // Read theme current version from the style.css
		
		if( $xml->latest > $theme_data->get('Version')) { // Compare current theme version with the remote XML version
			add_dashboard_page( NOTIFIER_THEME_NAME . ' Theme Updates', NOTIFIER_THEME_NAME . ' <span class="update-plugins count-1"><span class="update-count">1</span></span>', 'administrator', 'theme-update-notifier', 'update_notifier');
		}
	}	
}
add_action('admin_menu', 'update_notifier_menu');*/

// The notifier page
/*function update_notifier() { 
	
	$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
	$theme_data = wp_get_theme(NOTIFIER_THEME_FOLDER_NAME);
	?>
	
		<h2><?php echo NOTIFIER_THEME_NAME ?> Theme Updates</h2>
	    <div id="message"><p><strong>There is a new version of the <?php echo NOTIFIER_THEME_NAME; ?> theme available.</strong> You have version <?php echo $theme_data->get('Version'); ?> installed. Update to version <?php echo $xml->latest; ?>.</p></div>

		<img src="<?php echo get_bloginfo( 'template_url' ) . '/screenshot.png'; ?>" />
	    
	    <h3 class="title">Changelog</h3>
	    <?php echo $xml->changelog; ?>
    
	<?php
}*/


// Get the remote XML file contents and return its data (Version and Changelog)
// Uses the cached version if available and inside the time interval defined
function get_latest_theme_version($interval) {
	$notifier_file_url = NOTIFIER_XML_FILE;	
	$db_cache_field = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// check the cache
	if ( !$last || (( $now - $last ) > $interval) ) {
		// cache doesn't exist, or is old, so refresh it
		/*if (function_exists('curl_init')) {
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else */
		if ( (function_exists('wp_remote_get')) && (function_exists('wp_remote_retrieve_body')) ) {
			$cache = wp_remote_get($notifier_file_url);
			$cache = wp_remote_retrieve_body($cache);
		} else {
			$cache = @file_get_contents($notifier_file_url);
		}
		
		if ($cache) {			
			// we got good results	
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		} 
		// read from the cache file
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// cache file is fresh enough, so read from it
		$notifier_data = get_option( $db_cache_field );
	}
	
	// Let's see if the $xml data was returned as we expected it to.
	// If it didn't, use the default 1.0 as the latest version so that we don't have problems when the remote server hosting the XML file is down
	if( strpos((string)$notifier_data, '<notifier>') === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}
	
	// Load the remote XML data into a variable and return it
	$xml = simplexml_load_string($notifier_data);
	
	return $xml;
}

/* Inspired by: Joao Araujo */

?>