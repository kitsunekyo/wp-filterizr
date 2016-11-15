<?php
/**
 * Plugin Name: kitsunekyo | Filter Grid
 * Description: This plugin lets you filter content
 * Version: 1.0.0
 * Author: Alexander Spieslechner
 * Author URI: https://www.dasblattwerk.at
 */

class Kitsunekyo_filterizr {

  static $add_script;
  static $columns;

  function __construct(){
    add_shortcode( 'filterizr', array( $this, 'kitsunekyo_filterizr_shortcode' ) );
    add_shortcode( 'filterizr_item', array( $this, 'kitsunekyo_filterizr_item_shortcode' ) );

    add_action( 'wp_enqueue_scripts', array( $this, 'kitsunekyo_register_scripts' ) );
    add_action( 'wp_footer', array( $this, 'kitsunekyo_filterizr_footer_scripts' ) );
  }

  function kitsunekyo_filterizr_footer_scripts() {
    if (!self::$add_script)
      return;
    ?>
    <!-- Kick off Filterizr -->
    <script type="text/javascript">
        jQuery(function() {
            //Initialize filterizr with default options
            jQuery('.filtr-container').filterizr();
        });
    </script>
    <?php
  }

  function kitsunekyo_register_scripts() {
    wp_register_script( 'filterizr-script', plugin_dir_url( __FILE__ ) . '/js/jquery.filterizr.min.js', array( 'jquery' ) );

    wp_enqueue_script('filterizr-script');
    wp_enqueue_style( 'filterizr-style', plugin_dir_url( __FILE__ ) . '/css/kitsunekyo-filterizr-style.css');
  }

  function kitsunekyo_filterizr_shortcode($atts, $content){
    self::$add_script = true;

    $a = shortcode_atts( array(
        'search' => 'true',
        'columns' => '3'
    ), $atts );

    self::$columns = esc_attr($a['columns']);

    $content = do_shortcode($content);
    $content = str_replace( '<br>', '', $content );

    return '<div class="filterizr-search">
              <div class="filterizr-search-box">
                <input class="filtr-search" name="filtr-search" type="text" data-search="" placeholder="Search our Integrations" />
                <i class="pe-7s-search"></i>
              </div>
            </div>
            <div class="filtr-container">
            ' . $content .
            '</div>';
  }

  function kitsunekyo_filterizr_item_shortcode($atts, $content){
    $a = shortcode_atts( array(
        'url' => '',
        'alt' => '',
        'category' => '1',
        'description' => ''
    ), $atts );

    return '<div class="filtr-item col-xs-3 col-md-' . self::$columns . '" data-category="'.esc_attr($a['category']).'">
              <img src="'.esc_attr($a['url']).'" alt="'.esc_attr($a['alt']).'" />
              <span class="item-desc">'.esc_attr($a['description']).'</span>
            </div>';
  }
}
// init
$filterizr_plugin = new kitsunekyo_filterizr();
