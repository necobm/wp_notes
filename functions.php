<?php


/**
 * Add custom widget
 *
 * @return void
 */
function wporg_add_dashboard_widgets(): void
{
//    wp_add_dashboard_widget(
//        'wporg_dashboard_widget',                          // Widget slug.
//        esc_html__( 'Example Dashboard Widget', 'wporg' ), // Title.
//        'wporg_dashboard_widget_render'                    // Display function.
//    );

//    add_meta_box(
//        'wporg_dashboard_widget',
//        esc_html__( 'Example Dashboard Widget', 'wporg' ),
//        'wporg_dashboard_widget_render',
//        'dashboard',
//        'side',
//        'high'
//    );
}
add_action( 'wp_dashboard_setup', 'wporg_add_dashboard_widgets' );

/**
 * Create the function to output the content of our Dashboard Widget.
 */
function wporg_dashboard_widget_render(): void
{
    // Display whatever you want to show.
    esc_html_e( "Howdy! I'm a great Dashboard Widget.", "wporg" );
}

function wporg_remove_all_dashboard_metaboxes() {
    // Remove Welcome panel
    remove_action( 'welcome_panel', 'wp_welcome_panel' );
    // Remove the rest of the dashboard widgets
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'health_check_status', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}
add_action( 'wp_dashboard_setup', 'wporg_remove_all_dashboard_metaboxes' );

function fill_admin_menu() {

	//get all categories

	$categories = get_categories();
	$child_categories = [];

	//var_dump($categories); die;

	/** @var WP_Term $category */
	foreach ($categories as $category) {
		if ($category->parent === 0) {
			add_menu_page(
				$category->name,
				$category->name,
				'edit_themes',
				'edit.php?category_name=' . $category->slug,
				"",
				"dashicons-admin-page",
				3
			);
			continue;
		}
		$child_categories[] = $category;
	}

	/** @var WP_Term $child_category */
	foreach ($child_categories as $child_category) {
		$parents = get_category_parents($child_category->term_id, false, '|', true);

		if (!empty($parents)) {
			$parents_arr = explode('|', $parents);
			foreach ($parents_arr as $parent) {
				add_submenu_page(
					'edit.php?category_name=' . $parent,
					$child_category->name,
					$child_category->name,
					'edit_themes',
					'edit.php?category_name=' . $child_category->slug
				);
			}
		}
	}

	// All Categories Item

	add_menu_page(
		__('View All'),
		__('View All'),
		'edit_themes',
		'edit-tags.php?taxonomy=category',
		"",
		"dashicons-admin-page",
		4
	);


}

function remove_admin_menu_elements() {
	remove_menu_page('tools.php');
	remove_menu_page('users.php');
	remove_menu_page('plugins.php');
	remove_menu_page('upload.php');
	remove_menu_page('edit.php');
	remove_menu_page('edit-pages.php');
	remove_menu_page('edit-comments.php');
	remove_menu_page('themes.php');
	remove_menu_page('page.php');
	//var_dump($res);die;
}

add_action('admin_menu', 'remove_admin_menu_elements', 102);

add_action( 'admin_menu', 'fill_admin_menu', 102 );


