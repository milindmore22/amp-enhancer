<?php

function amp_enhancer_astra_back_to_top(){

  ?>
  <div id="enbacktotop"></div>
    <div id="marker">
        <amp-position-observer on="enter:enHideAnim.start; exit:enShowAnim.start"
          layout="nodisplay">
        </amp-position-observer>
      </div>

  <?php
}

function amp_enhancer_astra_back_to_top_link(){

 $scroll_top_alignment = astra_get_option( 'scroll-to-top-icon-position' );
 $scroll_top_devices   = astra_get_option( 'scroll-to-top-on-devices' );
?>
<a id="ast-scroll-top" class="<?php echo esc_attr( apply_filters( 'astra_scroll_top_icon', 'ast-scroll-top-icon' ) ); ?> ast-scroll-to-top-<?php echo esc_attr( $scroll_top_alignment ); ?>" data-on-devices="<?php echo esc_attr( $scroll_top_devices ); ?>" on="tap:enbacktotop.scrollTo(duration=500)" >
  <span class="screen-reader-text"><?php esc_html_e( 'Scroll to Top', 'astra-addon' ); ?></span>
</a>

   <amp-animation id="enShowAnim"
      layout="nodisplay">
      <script type="application/json">
        {
          "duration": "400ms",
          "fill": "both",
          "iterations": "1",
          "direction": "alternate",
          "animations": [{
            "selector": "#ast-scroll-top",
            "keyframes": [{
              "opacity": "1",
              "visibility": "visible"
            }]
          }]
        }
      </script>
    </amp-animation>
    <amp-animation id="enHideAnim"
      layout="nodisplay">
      <script type="application/json">
        {
          "duration": "400ms",
          "fill": "both",
          "iterations": "1",
          "direction": "alternate",
          "animations": [{
            "selector": "#ast-scroll-top",
            "keyframes": [{
              "opacity": "0",
              "visibility": "hidden"
            }]
          }]
        }
      </script>
    </amp-animation>
  <?php
}


function amp_enhancer_astra_sticky_header_css(){

   $sticky_devices = astra_get_option('sticky-header-on-devices');
   // both //mobile //desktop
	  if(isset($sticky_devices) && $sticky_devices == 'mobile'){
	    $sticky_css = '@media(max-width: 768px){.main-header-bar {position: fixed;width: 100%;} }';
	  }elseif(isset($sticky_devices) && $sticky_devices == 'desktop'){
	    $sticky_css = '@media(min-width: 768px){.main-header-bar {position: fixed;width: 100%;}}';
	  }else{
	  	$sticky_css = '.main-header-bar {position: fixed;width: 100%;}';
	  }
return $sticky_css;
}


add_action('wp','amp_enhancer_modify_astra_get_option',999);


function amp_enhancer_modify_astra_get_option(){

    if (  (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) && class_exists('Astra_Ext_Extension') && true === Astra_Ext_Extension::is_active( 'advanced-search' ) ) {
      add_filter( 'astra_get_option_header-main-rt-section-search-box-type', 'amp_enhancer_return_search_box_type');
      add_filter('astra_addon_get_template','amp_enhancer_astra_addon_template_override',10,5);
      add_filter( 'astra_get_search', 'amp_enhancer_get_search_markup', 10, 2 );
    }
}

function amp_enhancer_return_search_box_type(){

   $search_box_type = get_option('amp_enhancer_astra_options');
  
  return $search_box_type;
}

add_action('wp_loaded','amp_enhancer_astra_addon_module_options');

function amp_enhancer_astra_addon_module_options(){
add_option('amp_enhancer_astra_options',false);
  if ( class_exists('Astra_Ext_Extension') && true === Astra_Ext_Extension::is_active( 'advanced-search' ) ) {
    $search_box_type = astra_get_option( 'header-main-rt-section-search-box-type' );
    update_option('amp_enhancer_astra_options',$search_box_type);
  }
}


function amp_enhancer_astra_addon_template_override($located, $template_name, $args, $template_path, $default_path){

  if($template_name == 'advanced-search/template/full-screen.php'){
    $located = AMP_ENHANCER_TEMPLATE_DIR.'astra-addon/advanced-search/full-screen.php';
  }
  if($template_name == 'advanced-search/template/header-cover.php'){
    $located = AMP_ENHANCER_TEMPLATE_DIR.'astra-addon/advanced-search/header-cover.php';
  }
  return $located;
}

function amp_enhancer_get_search_markup( $search_markup, $option = '' ) {

  $search_box_type = get_option('amp_enhancer_astra_options');

    if($search_box_type != 'slide-search' && preg_match('/<div\s+class="ast-search-icon"(.*?)<a(.*?)>(.*?)<\/a>/s', $search_markup)){
      $search_markup = preg_replace('/<div\s+class="ast-search-icon"(.*?)<a(.*?)>(.*?)<\/a>/s', '<div class="ast-search-icon"$1<a$2 on="tap:AMP.setState({ astSearch: false })" >$3</a>', $search_markup);
    }
  return $search_markup;
  }

  // Mobile Header

  function amp_enhancer_astra_mobile_header() {

      $located =  AMP_ENHANCER_TEMPLATE_DIR.'astra-addon/header/mobile-builder-layout.php';
      
      include $located;

    }

   //
   
   function amp_enhancer_astra_mobile_trigger() {

      $icon             = astra_get_option( 'header-trigger-icon' );
      $mobile_label     = astra_get_option( 'mobile-header-menu-label' );
      $toggle_btn_style = astra_get_option( 'mobile-header-toggle-btn-style' );
      $aria_controls    = '';
      if ( ! Astra_Builder_Helper::$is_header_footer_builder_active ) {
        $aria_controls = 'aria-controls="primary-menu"';
      }
      ?>
      <div class="ast-button-wrap" [hidden]="showMenu">
        <button type="button" class="menu-toggle main-header-menu-toggle ast-mobile-menu-trigger-<?php echo esc_attr( $toggle_btn_style ); ?>" <?php echo esc_attr( $aria_controls ); ?> aria-expanded="false"on="tap:AMP.setState({menuToggle: false,showMenu:true})">
          <span class="screen-reader-text">Main Menu</span>
          <span class="mobile-menu-toggle-icon">
            <?php
              echo Astra_Builder_UI_Controller::fetch_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
             // echo Astra_Builder_UI_Controller::fetch_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
          </span>
          <?php
          if ( isset( $mobile_label ) && ! empty( $mobile_label ) ) {
            ?>

            <span class="mobile-menu-wrap">
              <span class="mobile-menu"><?php echo esc_html( $mobile_label ); ?></span>
            </span>
            <?php
          }
          ?>
        </button>
      </div>

       <div class="ast-button-wrap" hidden [hidden]="menuToggle">
        <button type="button" class="menu-toggle main-header-menu-toggle ast-mobile-menu-trigger-<?php echo esc_attr( $toggle_btn_style ); ?>" <?php echo esc_attr( $aria_controls ); ?> aria-expanded="false"on="tap:AMP.setState({menuToggle: true,showMenu:false})">
          <span class="screen-reader-text">Main Menu</span>
          <span class="mobile-menu-toggle-icon">
            <?php
             // echo Astra_Builder_UI_Controller::fetch_svg_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              echo Astra_Builder_UI_Controller::fetch_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
          </span>
          <?php
          if ( isset( $mobile_label ) && ! empty( $mobile_label ) ) {
            ?>

            <span class="mobile-menu-wrap">
              <span class="mobile-menu"><?php echo esc_html( $mobile_label ); ?></span>
            </span>
            <?php
          }
          ?>
        </button>
      </div>
      <?php
    } 