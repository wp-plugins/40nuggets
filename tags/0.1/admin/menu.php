<?php

/**
 * 40Nuggets top level
 */  
add_action('admin_menu', 'FortyNuggets_create_menu_page');
function FortyNuggets_create_menu_page() {

	add_menu_page(
		'40Nuggets Options',			// The title to be displayed on the corresponding page for this menu
		'40Nuggets',				// The text to be displayed for this actual menu item
		'administrator',			// Which type of users can see this menu
		'40Nuggets',				// The unique ID - that is, the slug - for this menu item
		'FortyNuggets_submenu_redirect_page_display',	// The name of the function to call when rendering the menu for this page
		'http://40nuggets.com/dashboard/images/favicon.ico'
	);
} 

/**
 * General submenu
 */
add_action('admin_menu', 'FortyNuggets_create_submenu_page'); 
function FortyNuggets_create_submenu_page() {
	//fix bug: http://wordpress.org/support/topic/top-level-menu-duplicated-as-submenu-in-admin-section-plugin
	add_submenu_page(
		'40Nuggets',					// Register this submenu with the menu defined above
		'',						// The text to the display in the browser when this menu item is active
		'',						// The text for this menu item
		'administrator',			// Which type of users can see this menu
		'40Nuggets',			// The unique ID - the slug - for this menu item
		'FortyNuggets_submenu_redirect_page_display' 	// The function used to render the menu for this page to the screen
	);

	//Add Call-to-action
	add_submenu_page(
		'40Nuggets',					// Register this submenu with the menu defined above
		'Add call-to-action',						// The text to the display in the browser when this menu item is active
		'Add New',						// The text for this menu item
		'administrator',			// Which type of users can see this menu
		'40Nuggets-ctaBuilder',			// The unique ID - the slug - for this menu item
		'FortyNuggets_submenu_redirect_page_display' 	// The function used to render the menu for this page to the screen
	);

	//Manage Call-to-action
	add_submenu_page(
		'40Nuggets',					// Register this submenu with the menu defined above
		'Manage calls-to-action',						// The text to the display in the browser when this menu item is active
		'All CTAs',						// The text for this menu item
		'administrator',			// Which type of users can see this menu
		'40Nuggets-callsToAction',			// The unique ID - the slug - for this menu item
		'FortyNuggets_submenu_redirect_page_display' 	// The function used to render the menu for this page to the screen
	);

	//Conversions
	add_submenu_page(
		'40Nuggets',					// Register this submenu with the menu defined above
		'Audience',						// The text to the display in the browser when this menu item is active
		'Audience',						// The text for this menu item
		'administrator',			// Which type of users can see this menu
		'40Nuggets-audience',			// The unique ID - the slug - for this menu item
		'FortyNuggets_submenu_redirect_page_display' 	// The function used to render the menu for this page to the screen
	);

	//Login
	add_submenu_page(
		'40Nuggets',					// Register this submenu with the menu defined above
		'Switch Account',						// The text to the display in the browser when this menu item is active
		'Switch Account',						// The text for this menu item
		'administrator',			// Which type of users can see this menu
		'40Nuggets-login',			// The unique ID - the slug - for this menu item
		'FortyNuggets_submenu_login_page_display' 	// The function used to render the menu for this page to the screen
	);

}

function FortyNuggets_submenu_users_page_display() {
	require_once(dirname(__FILE__) . '/users.php');	
}

function FortyNuggets_submenu_redirect_page_display() {
	require_once(dirname(__FILE__) . '/redirect.php');	
}

function FortyNuggets_submenu_login_page_display() {
	require_once(dirname(__FILE__) . '/login.php');	
}

?>