<?php
/*
Plugin Name: Automatic Facebook Cache Cleaner
Plugin URI: http://automaticfacebookcachecleaner.com
Description: Have you ever posted a link to Facebook only to realize afterwards that it's got a typo, and when you correct it, Facebook still shares the wrong stuff? This happens because the first post is cached, and most people have no clue how to solve it. The manual solution it is not even evident... it is done via <a href="https://developers.facebook.com/tools/debug">Facebook's developer-focused debugger</a>. But just download our new Automatic Facebook Cache Cleaner plugin, and this will be handled for you automatically everytime!
Author: Ricardo Salta
Version: 1.0
Author URI: http://www.ricardosalta.com
*/

add_action('plugins_loaded', 'afu_linter_loaded');

function afu_linter_loaded()
{
	global $afu_linter_fields;

	$afu_linter_fields = array('afu_linter_head','afu_linter_foot');
	
	define('AFU_LINTER_PLUGIN_FOLDER',str_replace('\\','/',dirname(__FILE__)));
	define('AFU_LINTER_PLUGIN_PATH','/' . substr(AFU_LINTER_PLUGIN_FOLDER,stripos(AFU_LINTER_PLUGIN_FOLDER,'wp-content')));
	define('AFU_LINTER_CSS',AFU_LINTER_PLUGIN_PATH . '/afu_linter.css');

	define('AFU_LINTER_META_BOX_NAME','Automatic Facebook Cache Cleaner');

	add_action('admin_init','afu_linter_init');
	add_action('wp_head','afu_linter_head_inject'); 
	add_action('wp_footer','afu_linter_foot_inject');
}

function afu_linter_head_inject()
{
	global $post;

	if (isset($post->ID))
	{
		$v = get_post_meta($post->ID,'afu_linter_head',TRUE);

		if ($v) echo "\n" . $v . "\n";
	}
}

function afu_linter_foot_inject()
{
	global $post;

	if (isset($post->ID))
	{
		$v = get_post_meta($post->ID,'afu_linter_foot',TRUE);
	
		if ($v) echo "\n" . $v . "\n";
	}
}

function afu_linter_init()
{
	wp_enqueue_style('afu_linter_admin_css', AFU_LINTER_CSS);

	add_meta_box('afu_linter_options_meta', __(AFU_LINTER_META_BOX_NAME, 'afu_linter'), 'afu_linter_options_meta', 'post', 'normal', 'high');
	add_meta_box('afu_linter_options_meta', __(AFU_LINTER_META_BOX_NAME, 'afu_linter'), 'afu_linter_options_meta', 'page', 'normal', 'high');

	add_action('save_post','afu_linter_save_meta');
}

function afu_linter_options_meta()
{
	global $post, $afu_linter_fields;

	foreach ($afu_linter_fields as $field_name)
	{
		${$field_name} = get_post_meta($post->ID,$field_name,TRUE);
	}

	include(AFU_LINTER_PLUGIN_FOLDER . '/meta.php');

	echo '<input type="hidden" name="afu_linter_options_noncename" id="afu_linter_options_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />' . "\n";
}

function afu_linter_save_meta($post_id) 
{

	global $afu_linter_fields;

	// make sure all new data came from the proper AFU_LINTER entry fields
	if (!wp_verify_nonce($_POST['afu_linter_options_noncename'],plugin_basename(__FILE__)))
	{
		return $post_id;
	}

	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	else 
	{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}

	// save data
	foreach ($afu_linter_fields as $field_name) 
	{
		$current_data = get_post_meta($post_id, $field_name, TRUE);	
		$new_data = $_POST[$field_name];

		if ($current_data) 
		{
			if ($new_data == '') delete_post_meta($post_id,$field_name);
			elseif ($new_data != $current_data) update_post_meta($post_id,$field_name,$new_data);
		}
		elseif ($new_data != '')
		{
			add_post_meta($post_id,$field_name,$new_data,TRUE);
		}
	}
}

?>