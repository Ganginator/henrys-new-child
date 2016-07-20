<?php

// create Portfolio
add_action( 'init', 'create_portfolio_item' );
function create_portfolio_item() {
	
	$labels = array(
		'name' 					=> __('Portfolio', 'houseofcoffee'),
		'singular_name' 		=> __('Portfolio Item', 'houseofcoffee'),
		'add_new' 				=> __('Add New', 'houseofcoffee'),
		'add_new_item' 			=> __('Add New Portfolio item', 'houseofcoffee'),
		'edit_item' 			=> __('Edit Portfolio item', 'houseofcoffee'),
		'new_item' 				=> __('New Portfolio item', 'houseofcoffee'),
		'all_items' 			=> __('All Portfolio items', 'houseofcoffee'),
		'view_item' 			=> __('View Portfolio item', 'houseofcoffee'),
		'search_items' 			=> __('Search Portfolio item', 'houseofcoffee'),
		'not_found' 			=> __('No Portfolio item found', 'houseofcoffee'),
		'not_found_in_trash' 	=> __('No Portfolio item found in Trash', 'houseofcoffee'), 
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('Portfolio', 'houseofcoffee'),
	);

	$args = array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'exclude_from_search' 	=> true,
		'show_ui' 				=> true, 
		'show_in_menu' 			=> true, 
		'show_in_nav_menus' 	=> true,
		'query_var' 			=> true,
		'rewrite' 				=> true,
		'capability_type' 		=> 'post',
		'has_archive' 			=> true, 
		'hierarchical' 			=> true,
		'menu_position' 		=> 4,
		'supports' 				=> array('title', 'editor', 'thumbnail'),
		'rewrite' 				=> array('slug' => 'portfolio-item'),
		'with_front' 			=> false
	);
	
	register_post_type('portfolio',$args);
	
}


// create Portfolio Taxonomy
	
add_action( 'init', 'create_portfolio_categories' );
function create_portfolio_categories() {
$labels = array(
	'name'                       => __('Portfolio Categories', 'houseofcoffee'),
	'singular_name'              => __('Portfolio Category', 'houseofcoffee'),
	'search_items'               => __('Search Portfolio Categories', 'houseofcoffee'),
	'popular_items'              => __('Popular Portfolio Categories', 'houseofcoffee'),
	'all_items'                  => __('All Portfolio Categories', 'houseofcoffee'),
	'edit_item'                  => __('Edit Portfolio Category', 'houseofcoffee'),
	'update_item'                => __('Update Portfolio Category', 'houseofcoffee'),
	'add_new_item'               => __('Add New Portfolio Category', 'houseofcoffee'),
	'new_item_name'              => __('New Portfolio Category Name', 'houseofcoffee'),
	'separate_items_with_commas' => __('Separate Portfolio Categories with commas', 'houseofcoffee'),
	'add_or_remove_items'        => __('Add or remove Portfolio Categories', 'houseofcoffee'),
	'choose_from_most_used'      => __('Choose from the most used Portfolio Categories', 'houseofcoffee'),
	'not_found'                  => __('No Portfolio Category found.', 'houseofcoffee'),
	'menu_name'                  => __('Portfolio Categories', 'houseofcoffee'),
);

$args = array(
	'hierarchical'          => true,
	'labels'                => $labels,
	'show_ui'               => true,
	'show_admin_column'     => true,
	'query_var'             => true,
	'rewrite'               => array( 'slug' => 'portfolio-category' ),
);

register_taxonomy("portfolio_categories", "portfolio", $args);
}

