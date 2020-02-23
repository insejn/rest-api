<?php
/*
Plugin Name: AKPRO REST API Case Studies
Description: Plugin for custom REST API Endpoint handling
Author: AKPRO Studio
Author URI: http://akprostudio.pl
Version: 1.0.0
License: GPL3
*/

$plugin_version    = '1.0.0';
$plugin_textdomain = 'akpro-restapi-case-studies';
$plugin_file       = __FILE__;
$namespace         = 'AKPRO\\CaseStudies\\';

/**
 * Composer autoload
 */
require_once( 'vendor/autoload.php' );

/**
 * Requirements check
 */
$requirements = new \Micropackage\Requirements\Requirements( __( 'AKPRO REST API Case Studies' ), array(
	'php' => '5.3.9',
	'wp'  => '4.0',
	'plugins' => [
		array( 'file' => 'advanced-custom-fields-pro/acf.php', 'name' => 'Advanced Custom Fields PRO', 'version' => '5.0.0' ),
	]
) );

if ( ! $requirements->satisfied() ) {
	$requirements->print_notice();
	return;
}

$taxonomies = new AKPRO\CaseStudies\Taxonomies();
$post_type = new AKPRO\CaseStudies\PostType();
$fields = new AKPRO\CaseStudies\Fields();
$fields->add_hooks();
$api = new AKPRO\CaseStudies\RestApi();
$api->add_hooks();
