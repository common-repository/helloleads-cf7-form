<?php

/*

 * Plugin Name: HelloLeads CF7 Form 

 * Plugin URI: https://www.helloleads.io/

 * Description: This plugin is use for creating a lead into HelloLeads CRM when form is submitted via Contact form. Make sure field name should be same as mention in plugin CF7 list menu pdf.

 * Author: HelloLeads

 * Text Domain: https://www.helloleads.io/

 * Version: 1.0

 * Requires at least: 4.7

 * Tested up to: 6.0.1

 */

defined( 'ABSPATH' ) or exit;



  add_action('plugins_loaded', 'load_hlol_scrape_plugin');

  function load_hlol_scrape_plugin() {

    define('HLOL_US_PLUGIN_URL', plugin_dir_url(__FILE__));
    define('HLOL_US_PLUGIN_DIR', plugin_dir_path(__FILE__));

    define('HLOL_US_GETLEADLIST_URL', 'https://app.helloleads.io/index.php/private/api/lists');
    define('HLOL_US_CREATELEAD_URL', 'https://app.helloleads.io/index.php/private/api/leads');
     

      require_once HLOL_US_PLUGIN_DIR . '/inc/loader.php';
      
  }





 /*-------------------------------------------------------------------
| Activation Hook 
 --------------------------------------------------------------------*/


  register_activation_hook(__FILE__, 'hlol_us_activate_print');

  function hlol_us_activate_print() {

      
  }





  /*-------------------------------------------------------------------
  | Deactivate Hook 
  --------------------------------------------------------------------*/

  register_deactivation_hook(__FILE__, 'hlol_us_deactivation_event');

  function hlol_us_deactivation_event() {

  }


  /*-------------------------------------------------------------------
  | Uninstalled Hook 
  --------------------------------------------------------------------*/
  function hlol_us_plugin_uninstall(){
     

  }

  register_uninstall_hook(__FILE__, 'hlol_us_plugin_uninstall');




?>