<?php
/*
Plugin Name: 4dem
Plugin URI: http://4marketing.it
Description: 4dem control panel
Version: 1.0
Author: Foysal Ahmed
Author URI: http://4marketing.it
*/

// creating global constants for the plugin
define( 'FOURDEM_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'FOURDEM_PLUGIN_PATH', plugin_dir_path(__FILE__) );

/**
 * main class for the plugin
 */
class FourDem{

	public $admin_page_name;
	public $tabs;

	function __construct(){
		$this->admin_page_name = '4dem';
		$this->tabs = array(
			'config' => array(
				'title' => 'Configuration'
			), 
			'user_sync' => array(
				'title' => 'Sync Users',
				'subtabs' => array(
					'auto' => 'Setup Auto Sync',
					'manual' => 'Manual Sync'
				)
			)
		);

		$this->hook_the_hooks();
	}

	/*
	 * adding the menu option to the admin menu
	 * uses the functions 
	 * - create_plugin_page()
	 */
	public function add_plugin_page(){
		add_menu_page(
			'4dem Control Panel', 
			'4dem Panel', 
			'manage_options', 
			$this->admin_page_name,
			array(&$this, 'create_plugin_page')
		);
	}

	/*
	* calling all the functions from the class to be called from the construction method
	*/
	public function registration(){

	}

	/*
	 * Creates the plugin page content in html
	 * uses
	 * @FUNCTIONS 
	 * - create_plugin_page_tabs()
	 * - render_section()
	 */
	public function create_plugin_page(){
		echo '<div class="wrap">';
		
		$cur_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'config';
		
		$this->create_plugin_page_tabs($cur_tab);
		$this->create_plugin_page_subtabs($cur_tab);
		$this->create_plugin_page_section($cur_tab); 
		
		echo '</div>';
	}


	/*
	 * Creates all the tabs of the plugin page.
	 * highlights the current tab passed as 
	 * @PARAMS
	 * - $current_tab
	 */
	private function create_plugin_page_tabs($current_tab){
	    echo '<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div>';
	    echo '<h2 class="nav-tab-wrapper">';

	    foreach( $this->tabs as $tab => $details ){
	        $class = ( $tab == $current_tab ) ? ' nav-tab-active' : '';
	        $href = 'href="admin.php?page='. $this->admin_page_name .'&tab='. $tab .'"';

	        echo '<a class="nav-tab'. $class .'" '. $href .'>'. $details['title'] .'</a>';

	    }
	    echo '</h2>';
	}


	/*
	 * Creates all the tabs of the plugin page.
	 * highlights the current tab passed as 
	 * @PARAMS
	 * - $current_tab
	 */
	private function create_plugin_page_subtabs($current_tab){
		if( isset($this->tabs[$current_tab]) ){ // i think my brain doesn't really work! why am i doing this?
			$cur_subtab = (isset($_GET['subtab'])) ? $_GET['subtab'] : key($this->tabs[$current_tab]['subtabs']);
			$counter = 0;

		    echo '<ul class="subsubsub" id="email_tpl_subsubsub">';

		    foreach( $this->tabs[$current_tab]['subtabs'] as $subtab=>$title ){
		    	$class = ($subtab === $cur_subtab) ? ' class="current" ' : '';
		    	$href = ' href="admin.php?page='. $this->admin_page_name .'&tab='. $current_tab .'&subtab='. $subtab .'" ';
				
				echo ($counter !== 0) ? ' | ' : '';
				echo '<li><a '. $class . $href .'>'. $title .'</a></li>';
				++$counter;
			}

		    echo '</ul>';
		}
	}

	/*
	 *Creates the html content of each section as passed in
	 * @PARAMS
	 * -$section
	 */
	private function create_plugin_page_section($section){
		switch ($section) {
			case 'config':
				include_once(FOURDEM_PLUGIN_PATH .'templates/configurations.php');
				break;

			case 'user_sync':
				include_once(FOURDEM_PLUGIN_PATH .'templates/sync.php');
				break;
			
			default:
				include_once(FOURDEM_PLUGIN_PATH .'templates/configurations.php');
				break;
		}
	}

	/*
	 * initializes all the hooks
	 */
	private function hook_the_hooks(){
		add_action( 'admin_menu', array(&$this, 'add_plugin_page') );
	}
}

new Fourdem();