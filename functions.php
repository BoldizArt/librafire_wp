<?php
/**
 * Create one custom post type: employee
 */
function librafireRegisterPostType()
{
	// Define labels
	$labels = [
		'name' => __('Employee'),
		'singular_name' => __('Employee'),
		'add_new' => __('Add New Employee'),
		'add_new_item' => __('Add New Employee'),
		'edit_item' => __('Edit Employee'),
		'new_item' => __('Add New Employee'),
		'view_item' => __('View Employee'),
		'search_items' => __('Search Employee'),
		'not_found' => __('No employee found'),
		'not_found_in_trash' => __('No employee found in trash')
	];

	// Define supports
	$supports = [
		'title',
		'editor',
		'thumbnail',
		'comments',
		'revisions'
	];

	// Define arguments
	$args = [
		'labels' => $labels,
		'supports' => $supports,
		'public' => true,
		'capability_type' => 'post',
		'rewrite' => ['slug' => 'employee'],
		'has_archive' => true,
		'menu_position' => 30,
		'menu_icon' => 'dashicons-admin-users',
		'register_meta_box_cb' => 'librafireAddEmployeeMetaboxes',
	];

	// Register this post type
	register_post_type('employee', $args);
}
add_action('init', 'librafireRegisterPostType');
