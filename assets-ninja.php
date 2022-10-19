<?php

/**
 * Plugin Name:       AssetsNinja
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Assets management in depth with OOP, practice plugin from LWHH.
 * Version:           1.0
 * Author:            Habib
 * Author URI:        https://github.com/habibur-dev
 * License:           GPL v2 or later
 * Text Domain:       assetsninja
 * Domain Path:       /languages
 */

 //file fath define
 define('ASN_ASSETS_DIR', plugin_dir_url(__FILE__)."assets");
 define('ASN_ASSETS_PUBLIC_DIR', plugin_dir_url(__FILE__)."assets/public");
 define('ASN_ASSETS_ADMIN_DIR', plugin_dir_url(__FILE__)."assets/admin");
 
 class AssetsNinja{

    private $version;
    
    function __construct(){
        $this->version = time();
        add_action('plugin_loaded', array($this, 'load_textdoamin'));
        add_action('wp_enqueue_scripts', array($this, 'load_front_assets'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
    }

    function load_textdoamin(){
        load_plugin_textdomain('assetsninja', false, plugin_dir_url(__FILE__)."/languages");
    }

    function load_front_assets(){
        wp_enqueue_style('asen-main-css', ASN_ASSETS_PUBLIC_DIR."/css/main.css", null, $this->version );
        // wp_enqueue_script('asn-main-script', ASN_ASSETS_PUBLIC_DIR."/js/main.js", array('jquery', 'asn-another-script'), $this->version, true);
        // wp_enqueue_script('asn-another-script', ASN_ASSETS_PUBLIC_DIR."/js/another.js", array('jquery'), $this->version, true);

        // enqueue js with array
        $js_files = array(
            'asn-main-script' => array('path'=>ASN_ASSETS_PUBLIC_DIR."/js/main.js", 'dep' => array('jquery', 'asn-another-script')),
            'asn-another-script' => array('path'=>ASN_ASSETS_PUBLIC_DIR."/js/another.js", 'dep' => array('jquery')),
        );

        foreach($js_files as $handle => $fileinfo){
            wp_enqueue_script($handle, $fileinfo['path'], $fileinfo['dep'], $this->version, true);
        }

        //Localize script
        $data = array(
            'name' => 'Test Name',
            'url'  => 'https://example.com'
        );

        wp_localize_script('asn-main-script', 'localized_data', $data);
    }

    function load_admin_assets($screen){

        //get current screen
        $_screen = get_current_screen();

        //check page and screen
        if('edit.php' == $screen && $_screen->post_type == 'page'){
            wp_enqueue_script('asn-admin-script', ASN_ASSETS_ADMIN_DIR."/js/admin.js", array('jquery'), $this->version, true);
        }
    }

 }

 new AssetsNinja();