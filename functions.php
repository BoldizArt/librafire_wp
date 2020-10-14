<?php
/**
 * Create one custom post type: employee
 */
function librafireRegisterPostType()
{
	// Define labels
	$labels = [
		'name' => __('Employee', 'librafire'),
		'singular_name' => __('Employee', 'librafire'),
		'add_new' => __('Add New Employee', 'librafire'),
		'add_new_item' => __('Add New Employee', 'librafire'),
		'edit_item' => __('Edit Employee', 'librafire'),
		'new_item' => __('Add New Employee', 'librafire'),
		'view_item' => __('View Employee', 'librafire'),
		'search_items' => __('Search Employee', 'librafire'),
		'not_found' => __('No employee found', 'librafire'),
		'not_found_in_trash' => __('No employee found in trash', 'librafire')
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
		'name' => __('Roles', 'taxonomy general name', 'librafire'),
		'singular_name' => __('Role', 'taxonomy singular name', 'librafire'),
		'search_items' =>  __('Search roles', 'librafire'),
		'all_items' => __('All roles', 'librafire'),
		'parent_item' => __('Parent role', 'librafire'),
		'parent_item_colon' => __('Parent role:', 'librafire'),
		'edit_item' => __('Edit role', 'librafire'), 
		'update_item' => __('Update role', 'librafire'),
		'add_new_item' => __('Add new role', 'librafire'),
		'new_item_name' => __('New role name', 'librafire'),
		'menu_name' => __('Roles', 'librafire'),
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

// Create terms
function librafireInsertTerms()
{
	// Define an array with terms
	$terms = [
		'developer' => __('Developer', 'librafire'),
		'designer,' => __('Designer', 'librafire'),
		'qa' => __('QA', 'librafire')
	];

	foreach ($terms as $slug => $term) {
		// Check if the term is exists
		if (!term_exists($term, 'role')) {
			wp_insert_term(
				$term,
				'role', // The taxonomy
				[
					'description' => "The employee is a {$term}",
					'slug' => $slug,
				]
			);
		}
	}
}

// Hook into the 'init' action
add_action('init', 'librafireInsertTerms', 0);

/**
 * Adds a metaboxes to the "Employee" post type
 */
function librafireAddEmployeeMetaboxes()
{
	add_meta_box(
		'librafireEmployeeMeta',
		'Description',
		'librafireEmployeeMeta',
		'employee',
		'side',
		'default'
	);
}

/**
 * Output the HTML for the metabox.
 */
function librafireEmployeeMeta()
{
	global $post;

	// Nonce field to validate form request came from current site
	wp_nonce_field(basename(__FILE__), 'employee_fields');

	// Get the employee data if it's already been entered
	$employee = get_post_meta($post->ID, 'employee', true);

	// Output the field
	echo '<input type="text" name="employee" value="' . esc_textarea($employee) . '" class="widefat">';
}

/**
 * Save the metabox data
 */
function librafireSaveMeta($postId, $post)
{
	// Return if the user doesn't have edit permissions.
	if (!current_user_can('edit_post', $postId)) {
		return $postId;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	if (!isset($_POST['employee']) || !wp_verify_nonce($_POST['employee_fields'], basename(__FILE__))) {
		return $postId;
	}

	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $events_meta.
	$events_meta['employee'] = esc_textarea($_POST['employee']);

	// Cycle through the $events_meta array.
	// Note, in this example we just have one item, but this is helpful if you have multiple.
    foreach ($events_meta as $key => $value) {
        // Don't store custom data twice
        if ('revision' === $post->post_type) {
            return;
        }

        if (get_post_meta($postId, $key, false)) {
            // If the custom field already has a value, update it.
            update_post_meta($postId, $key, $value);
        } else {
            // If the custom field doesn't have a value, add it.
            add_post_meta($postId, $key, $value);
        }

        if (!$value) {
            // Delete the meta key if there's no value
            delete_post_meta($postId, $key);
        }
    }
}

// Hook into the 'save_post' action
add_action('save_post', 'librafireSaveMeta', 1, 2);
