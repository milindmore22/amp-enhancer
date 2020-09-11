<?php
/*
Plugin Name: AMP Enhancer
Description: Enhances Your AMP Site by adding support for Woocommerce,Forms and pagebuilder plugins.
Author: AMPforWP Team
Version: 1.0
Author URI: http://ampforwp.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: amp-enhancer
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

define('AMP_ENHANCER_PLUGIN_URI', plugin_dir_url(__FILE__));
define('AMP_ENHANCER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AMP_ENHANCER_TEMPPLATE_DIR', plugin_dir_path(__FILE__).'templates/');

define('AMP_ENHANCER_VERSION','1.0');


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
}

// Amp form Santizer Modification
function amp_enhancer_form_sanitizer($data){
	require_once(AMP_ENHANCER_PLUGIN_DIR.'class-amp-enhancer-form-sanitizer.php');
		unset($data['AMP_Form_Sanitizer']);
		 $data['AMP_Enhancer_Form_Sanitizer'] = array();
		return $data;
}
