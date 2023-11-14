<?php

/*
Plugin Name: Exit Intent Popup
Plugin URI: #
Description: Simple exit-intent trigger Popup
Version: 1.0.0
Author URI: Alvin
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_SLUG', 'eip' );
define( 'EIP_VERIONS', '1.0.0' );
define('PLUGIN_EIP_PATH', plugin_dir_path( __FILE__ ) );
define('PLUGIN_EIP_URL', plugins_url( '/', __FILE__ ) );

register_activation_hook( __FILE__, 'activate_eip' );
function activate_eip() {
    /**
     * Set unique cookie name.
     */
    $site_name = get_bloginfo( 'name' );

    // Combine everything
    $cookie_name = $site_name . PLUGIN_SLUG;

    // Now let's strip everything
    $cookie_name = str_replace( array( '[\', \']' ), '', $cookie_name );
    $cookie_name = preg_replace( '/\[.*\]/U', '', $cookie_name );
    $cookie_name = preg_replace( '/&(amp;)?#?[a-z0-9]+;/i', '-', $cookie_name );
    $cookie_name = preg_replace( array( '/[^a-z0-9]/i', '/[-]+/' ) , '-', $cookie_name );
    $cookie_name = strtolower( trim( $cookie_name, '-' ) );

    // Save the value to the database.
    add_option(  PLUGIN_SLUG . '_save_unique_cookie_name', $cookie_name );
}

register_deactivation_hook( __FILE__, 'deactivate_eip' );
function deactivate_eip() {
    // Get Unique Cookie Name
    $cookie_name = get_option( PLUGIN_SLUG . '_save_unique_cookie_name' );

    // Check if the unique cookie name exists.
    if ( $cookie_name ) {

        // Delete unique cookie name from the database.
        delete_option( PLUGIN_SLUG . '_save_unique_cookie_name' );

    }
}

add_action( 
    'wp_enqueue_scripts', 
    function() {
        wp_enqueue_style(
            'eip-style',
            PLUGIN_EIP_URL . 'assets/css/style.css',
            [],
            EIP_VERIONS
        );

        wp_enqueue_script( 
            'eip-cookies', 
            PLUGIN_EIP_URL . 'assets/js/cookies.js', 
            [], 
            EIP_VERIONS,
            true 
        );

        wp_enqueue_script( 
            'eip-popup', 
            PLUGIN_EIP_URL . 'assets/js/popup.js', 
            [], 
            EIP_VERIONS,
            true 
        );
        $cookie_name = get_option( PLUGIN_SLUG . '_save_unique_cookie_name' );
        wp_localize_script( 
            'eip-popup',
            'EIP',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'cookie_name' => $cookie_name,
            ]
        );
    }
);

add_action('wp_footer', 'eip_render_popup');
function eip_render_popup(){
    ?>
        <div class="eip-popup eip-popup-wrap">
            <div class="eip-popup-content">
                <div class="eip-popup-header">
                    <h4>EXIT INTENT POPUP</h4> 
                </div>
                <div class="eip-popup-body">
                    <div class="eip-cols">
                        <div class="col eip-image-wrapper">
                            <img src="<?= PLUGIN_EIP_URL . 'assets/images/eip.jpg' ?>" alt="EIP">
                        </div>
                        <div class="col eip-text-wrapper">
                            <h5>Lorem ipsum dolor sit amet</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </div>
                </div>
                <span class="close">x</span>
            </div>
        </div>
    <?php
}