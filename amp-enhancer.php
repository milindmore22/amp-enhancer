<?php
/*
Plugin Name: AMP Enhancer
Description: AMP Enhancer is a Compatibility Layer for AMP by Google.
Author: Magazine3
Version: 1.0
Author URI: http://ampforwp.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: amp-enhancer
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define('AMP_ENHANCER_VERSION','1.0');
define('AMP_ENHANCER_PLUGIN_URI', plugin_dir_url(__FILE__));
define('AMP_ENHANCER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AMP_ENHANCER_BASENAME',plugin_basename(__FILE__));
define('AMP_ENHANCER_TEMPPLATE_DIR', plugin_dir_path(__FILE__).'templates/');
define('AMP_ENHANCER_ElEMENTOR_URI', plugin_dir_url(__FILE__).'pagebuilders/');


require_once(AMP_ENHANCER_PLUGIN_DIR.'includes/functions.php');


add_action('plugins_loaded','amp_enhancer_third_party_plugins_support');

function amp_enhancer_third_party_plugins_support(){

			// Amp form Santizer Modification
		   if(function_exists('amp_activate')){
			  add_filter('amp_content_sanitizers','amp_enhancer_form_sanitizer',100);
			}
			// Woocommerce Template override
		   if(function_exists('WC')){
		     require_once(AMP_ENHANCER_TEMPPLATE_DIR.'woocommerce/wc_functions.php');
		    }
		   // Elementor Plugin  Support
		   if(class_exists('\Elementor\Plugin')){
			  require_once AMP_ENHANCER_PLUGIN_DIR.'/pagebuilders/elementor/amp-enhancer-elementor-pagebuilder.php';
		    }
		    // Contact Form Response Support
		    if(class_exists('WPCF7_ContactForm')){
		      require_once(AMP_ENHANCER_TEMPPLATE_DIR.'contact-form7/cf7_functions.php');
		    }
	       // Cookie Notice
	       if(class_exists('Cookie_Notice')){
		      require_once(AMP_ENHANCER_TEMPPLATE_DIR.'cookie-notice/cookie-notice-functions.php');
		   }

		   // Cookie Notice
	       if(class_exists('Cookie_Law_Info')){
		      require_once(AMP_ENHANCER_TEMPPLATE_DIR.'cookie-law-info/cookie-law-info-functions.php');
		   }  
}
