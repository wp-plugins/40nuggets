<?php
class Fortynuggets_Plugin_menus{
	
	public function show(){
		$plugin = new Fortynuggets_Plugin();
		$options = $plugin->get_options();
		
		//40Nuggets top level menu
		$this->main_menu();
			
		if (!empty($options->akl)){
			//link sub-menus
			$this->sub_menus();
			add_action('admin_head', array(&$this, 'open_links_in_new_tab'));
			//switch account submenu
			$this->switch_accont();
		}
	}
	
	//open submenu links in new tab
	public function open_links_in_new_tab() {
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {
				$( "ul#adminmenu a[href*='40nuggets.com']" ).attr( 'target', '_blank' );
			});
		</script>
		<?php
	}

	private function main_menu() {
		global $menu;
		add_menu_page(
			'40Nuggets Options',		// The title to be displayed on the corresponding page for this menu
			'40Nuggets',				// The text to be displayed for this actual menu item
			'administrator',			// Which type of users can see this menu
			'40Nuggets',				// The unique ID - that is, the slug - for this menu item
			array(&$this, 'submenu_login_page_display'),							// The name of the function to call when rendering the menu for this page
			'http://40nuggets.com/dashboard/images/favicon.ico'
		);
	} 

	private function sub_menus() {
		$plugin = new Fortynuggets_Plugin();
		//sub-menus
		global $submenu;
		$submenu['40Nuggets'][0] = array( 'Dashboard', 'manage_options' , $plugin->getURL("dashboard"));
		$submenu['40Nuggets'][1] = array( 'Add New Nugget', 'manage_options' , $plugin->getURL("nuggets/editor"));
		$submenu['40Nuggets'][2] = array( 'Upgrade to Pro', 'manage_options' , $plugin->getURL("billing"));
	}

	private function switch_accont() {
		add_submenu_page(
			'40Nuggets',				// Register this submenu with the menu defined above
			'Switch Account',			// The text to the display in the browser when this menu item is active
			'Switch Account',			// The text for this menu item
			'administrator',			// Which type of users can see this menu
			'40Nuggets-login',			// The unique ID - the slug - for this menu item
			array(&$this, 'submenu_login_page_display') 	// The function used to render the menu for this page to the screen
		);
	}
	
	public function submenu_login_page_display() {
		require_once ('login.php');	
	}
	
}
?>