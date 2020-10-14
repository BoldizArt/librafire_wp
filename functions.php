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


// Add new taxonomy and make it hierarchical like categories
function librafireCustomTaxonomy() {
	// Define the labels
	$labels = [
		'name' => __('roles', 'taxonomy general name'),
		'singular_name' => __('Role', 'taxonomy singular name'),
		'search_items' =>  __('Search roles'),
		'all_items' => __('All roles'),
		'parent_item' => __('Parent role'),
		'parent_item_colon' => __('Parent role:'),
		'edit_item' => __('Edit role'), 
		'update_item' => __('Update role'),
		'add_new_item' => __('Add new role'),
		'new_item_name' => __('New role name'),
		'menu_name' => __('Roles'),
	];    
 
	// Register the taxonomy
	register_taxonomy('role', 
		[
			'employee'
		], 
		[
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => [
				'slug' => 'role'
			],
		]
	);
}

// Hook into the 'init' action
add_action('init', 'librafireCustomTaxonomy', 0);
