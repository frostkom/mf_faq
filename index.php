<?php
/*
Plugin Name: MF FAQ
Plugin URI: https://github.com/frostkom/mf_faq
Description:
Version: 1.0.3
Licence: GPLv2 or later
Author: Martin Fors
Author URI: https://martinfors.se
Text Domain: lang_faq
Domain Path: /lang

Requires Plugins: meta-box
*/

if(!function_exists('is_plugin_active') || function_exists('is_plugin_active') && is_plugin_active("mf_base/index.php"))
{
	include_once("include/classes.php");

	$obj_faq = new mf_faq();

	add_action('enqueue_block_editor_assets', array($obj_faq, 'enqueue_block_editor_assets'));
	add_action('init', array($obj_faq, 'init'));

	if(is_admin())
	{
		register_uninstall_hook(__FILE__, 'uninstall_faq');

		add_filter('manage_'.$obj_faq->post_type.'_posts_columns', array($obj_faq, 'column_header'), 5);
		add_action('manage_'.$obj_faq->post_type.'_posts_custom_column', array($obj_faq, 'column_cell'), 5, 2);

		add_action('rwmb_meta_boxes', array($obj_faq, 'rwmb_meta_boxes'));
	}

	function uninstall_faq()
	{
		include_once("include/classes.php");

		$obj_faq = new mf_faq();

		mf_uninstall_plugin(array(
			'post_types' => array($obj_faq->post_type),
		));
	}
}