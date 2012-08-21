<?php
/*
Plugin Name: FireStats Charts
Plugin URI: http://wordpress.org/extend/plugins/firestats-charts/
Description: Add a chart view to firestats's statistics. <strong>Require <a href="http://firestats.cc/" target="_blank">FireStats</a> > 1.6.3</strong>.
Version: 1.1.3
Author: David "mArm" Ansermot
Author URI: http://www.ansermot.ch
*/

/*  Copyright 2010  David Ansermot  (email : dev@ansermot.ch)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// *************************************
// Check of PHP5 version
// *************************************
if (version_compare(phpversion(), '5.1', '<'))	die ('This plugin requires PHP 5.1.0 or higher.');


// *************************************
// Disable plugin if not in admin parts
// *************************************
$fsChartsDisabled = false;
if (stripos('wp-admin', $_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI']) !== false) {
	$fsChartsDisabled = true;
}
// Start plugin execution context
define('_FSC', true);

/**********************************************
 *
 *           Plugin's Class 
 *
 */
if (!class_exists('FsCharts') && !$fsChartsDisabled) {

	// *************************************
	// Class declaration
	// *************************************
	class FsCharts {

		// Verbose mode for debuging
		protected $verbose = false;
		// Internal variables
		protected $vars = array();
		// Plugin's key
		protected $piKey = 'wp-fscharts-pi';
    // Plugin's version
    protected $version = '1.1.3';
		// Plugin's helper
		protected $helper = null;
		// Plugin's absolute url
		protected $absUrl = '';
		// Plugin's absolute path
		protected $absPath = '';
		// Plugin's admin page url
		protected $adminUrl = '';
		// Plugin's internal html output buffer
		protected $htmlBuffer = '';
		// Plugin's html render engine
		protected $render = null;
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
				
		/**********************************************
		 *
		 *           MAIN PART
		 *
		 */
	 
	 
	 	/**
		 * Constructor
		 *
		 * @param 	bool	$verbose: Active debug if true
		 * @return 	void
		 * @access 	public
		 */
		public function __construct($verbose = null) {
			
			// Loads translations
			$plugin_dir = basename(dirname(__FILE__));
			load_plugin_textdomain('firestats-charts', 'wp-content/plugins/'.$plugin_dir.'/i18n', $plugin_dir.'/i18n');
			
			// URIs and URLs
			$this->absUrl = get_option('home').'/wp-content/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
			$this->absPath = dirname(__FILE__).'/';
			$this->adminUrl = get_option('home').'/wp-admin/options-general.php?page='.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)).basename(__FILE__);

			// Detect verbose mode
			if ($verbose !== null && is_bool($verbose)) {
				$this->verbose = $verbose;
			} else {
				if (isset($_GET['verbose']) && !empty($_GET['verbose'])) {
					$this->verbose = ($_GET['verbose'] === 1) ? true : false;
				}
				// @todo : add $_POST['verbose'] support
			}
      
			// Include plugin's additional files
			require_once($this->absPath.'inc/class.helper.php');
			require_once($this->absPath.'inc/class.renderer.php');
			
			$this->render = new FsChartsRenderer($this);
			$this->helper = new FsChartsHelper($this);
			
			$this->loadPiVars();
			
      // Include jpgraph library
			require_once($this->absPath.'res/jpgraph/jpgraph.php');
		  require_once($this->absPath.'res/jpgraph/jpgraph_line.php');

		}
		
		/**
		 * Plugin installation
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
		 */
		public function install() {
			
      // Check if pi is installed
			$installed = get_option('fscharts_version', null);
			
			// If not installed, add install it
			if ($installed === null) {

        // Chart configuration
        add_option('fscharts_height', 240);
        add_option('fscharts_width', 500);

        // Display configuration
        add_option('fscharts_display_visits', 1);
        add_option('fscharts_display_hits', 1);
        add_option('fscharts_display_table', 1);

        // Zoom configuration
        add_option('fscharts_zoom', 0);
        add_option('fscharts_zoom_height', 600);
        add_option('fscharts_zoom_width', 800);

        $installed = '1.0.0';

      }
			
			// Additional install - 1.1.3
			if (version_compare($installer, '1.1.3', '<')) {
				add_option('fscharts_cache_expire', 3600);
				$installed = '1.1.3';
			}
			
			// Update version number
			if ($installed === null) {
				add_option('fscharts_version', $this->version);
			} else {
				update_option('fscharts_version', $this->version);
			}
			
		}
		
		/**
		 * Install the widget on the dashboard
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
     * @since   1.0.3
		 */
    public function installWidget() {

			global $wpdb;
			global $fscPi;
		
			// Check if FireStats is installed
			if (!defined('FS_WORDPRESS_PLUGIN_VER')) {
				echo '<p>'.__('FireStats Charts').' '.__('require FireStats >= 1.6.3').'</p>';
			} else {
				// Get datas from firestats table
				$graph = new Graph((int)get_option('fscharts_width'), (int)get_option('fscharts_height'));
				$graph->SetScale('intint'/*, 0, 0, 0, max($days) - min($days) + 1*/);
				$graph->SetMargin(40,30,40,100);
				$graph->title->Set(__('FireStats Charts').' '.$this->version);
				$graph->xaxis->SetLabelAngle(90);
				$graph->xaxis->title->Set(__('Days'));
				$graph->yaxis->title->Set(__('Count'));
			
				// Create the linear plot
				$hits = $fscPi->getHits();
				$lineplot = new LinePlot($hits['datas']);
				$graph->xaxis->SetTickLabels($hits['labels']);
				
				$lineplot->SetFillColor('orange@0.5');
				$lineplot->value->Show();
				$lineplot->value->SetColor('darkred');
				//$lineplot->value->SetFont('', '', 8);
				$lineplot->value->SetFormat('%d');
			
				// Add the plot to the graph
				$graph->Add($lineplot);
				
				// Delete too old temp files (add 1.0.2)
				$this->cleanTempDirectory();
				
				// Graph key for temp file (add 1.0.2)
				$graphKey = 'graph_'.md5(time());
				$graph->Stroke(dirname(__FILE__).'/out/'.$graphKey.'.png');
			
				echo '<div style="text-align: center;"><img src="'.$this->absUrl.'out/'.$graphKey.'.png" alt="'.__('FireStats Graph').'" style="width: 100%;" /></div>';
			}

    }
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/**********************************************
		 *
		 *          DATAS PART
		 *
		 */
		
		/**
		 * Get all hits
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
		 */
		public function getHits() {
			
			global $wpdb;
			
			$ydata = array();
  		$days = array();
			
      $req = 'SELECT *,count(*) AS toto FROM '.$wpdb->prefix.'firestats_hits GROUP BY SUBSTRING(timestamp,1,10)';
      $visites = $wpdb->get_results($req);
      foreach ($visites as $visite) {
        $ydata[] = $visite->toto;
        $days[] = substr($visite->timestamp, 0, 10);
      }
			
      return array('datas' => $ydata, 'labels' => $days);
			
    }
		
		
		
		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
		/**********************************************
		 *
		 *          ADMIN PART
		 *
		 */	
		
		
		/**
		 * Add the admin menu entry for the plugin
		 *
		 * @param		void
		 * @return 	void
		 * @access 	protected
		 * @since		1.1.0
		 */
		public function adminMenu() {
			add_options_page('FireStats Charts', 'FireStats Charts', 8, __FILE__, array(&$this, 'adminMenuOptionPage'));
		}
		
		
		/**
		 * Option page dispatch function
		 *
		 * @param		void
		 * @return 	void
		 * @access 	protected
		 * @since		1.1.0
		 */
		public function adminMenuOptionPage() {
				
				// @todo : detect task
				if (!isset($this->vars['task'])) $this->vars['task'] = 'settings';
				$task = $this->vars['task'];
	
				$html = '';
				
				if ($task == 'settings') {
					
					if ($this->vars['submitted'] == 1) {
						
						if (isset($this->vars['chart-width'])) {
							update_option('fscharts_width', $this->vars['chart-width']);
						}
						
						if (isset($this->vars['chart-height'])) {
							update_option('fscharts_height', $this->vars['chart-height']);
						}
						
						if (isset($this->vars['cache-expire'])) {
							update_option('fscharts_cache_expire', $this->vars['cache-expire']);
						}
						
					}
					
					// Display parameters
					$params = array(
						'chart-width' => get_option('fscharts_width'),
						'chart-height' => get_option('fscharts_height'),
						'cache-expire' => get_option('fscharts_cache_expire'),
					);
					
					// Build the html
					$html = $this->render->settingsPageHTML($params, _('Settings updated'));
					
				}
				
				echo $html;
				
		}
		
		
		
		
		
	
	
	
	
	
	
	
	
	
	
	
	
	
		/**********************************************
		 *
		 *          MISC PART
		 *
		 */	
		
		
		/**
		 * Return the plugin's version
		 *
		 * @param 	void
		 * @return 	string
		 * @access 	public
		 * @since		1.1.0
		 */
		public function getVersion() {
			return $this->version;
		}
		
		
		/**
		 * Return the plugin's key
		 *
		 * @param 	void
		 * @return 	string
		 * @access 	public
		 * @since		1.1.0
		 */
		public function getPiKey() {
			return $this->piKey;
		}
		
		
		/**
		 * Return the plugin's folder absolute path
		 *
		 * @param 	void
		 * @return 	string
		 * @access 	public
		 * @since		1.1.0
		 */
		public function getAbsPath() {
			return $this->absPath;
		}
		
		
		/**
		 * Return the plugin's folder absolute url
		 *
		 * @param 	void
		 * @return 	string
		 * @access 	public
		 * @since		1.1.0
		 */
		public function getAbsUrl() {
			return $this->absUrl;
		}
		
		
		/**
		 * Return the plugin's admin page url
		 *
		 * @param 	void
		 * @return 	string
		 * @access 	public
		 * @since		1.1.0
		 */
		public function getAdminUrl() {
			return $this->adminUrl;
		}
		
		
		/**
		 * Load plugin's variables (post and get, priory post)
		 *
		 * @param void
		 * @return void
		 * @access 	protected
		 * @since		1.1.4
		 */
		protected function loadPiVars() {
			
			// Get plugin's variables GET
			if (isset($this->helper::getVar($this->piKey, null, 'GET')) && is_array($this->helper::getVar($this->piKey, null, 'GET'))) {
				$getVars = $this->helper::getVar($this->piKey, null, 'GET');
				foreach ($getVars as $key => $value) {
					$this->vars[$key] = $value;
				}
			}

			// Get plugin's variables POST (override GET's variables)
			if(isset($this->helper::getVar($this->piKey, null, 'POST')) && is_array($this->helper::getVar($this->piKey, null, 'POST'))) {
				$postVars = $this->helper::getVar($this->piKey, null, 'POST');
				foreach ($postVars as $key => $value) {
					$this->vars[$key] = $value;
				}
			}
			
		}
		
		
		/**
		 * Clean up temp directory
		 *
		 * @param 	int		$lifetime: La durée de vie d'un fichier 
		 * @return 	bool
		 * @access 	protected
		 * @since		1.0.2
		 */
		protected function cleanTempDirectory() {
			
			$directory = dirname(__FILE__).'/out/';
			$expire = get_option('fscharts_cache_expire', 3600);
		
			// If must make dir, return true
			if (!is_dir($directory)) {
				mkdir($directory);
				return true;
			}
			
			// Open directory
			if (!$dirHandler = opendir($directory)) {
				return false;
			}
			
			// List files
			while ($file = readdir($dirHandler)) {
			
				if ($file != '.' &&  $file != '..' && !is_dir($directory.'/'.$file) && (substr($file, -4) == '.png')) {
					
					// Modification date
					$filetime = filemtime($directory.'/'.$file);
					
					// Delete if too old ( > 1h00)
					if (time() - $filetime > $expire) {
						unlink($directory.'/'.$file);
					}
				}
			}
		}
	}
}



















/**********************************************
 *
 *           Init
 *
 */


// Check if class exists and isn't set
if (class_exists('FsCharts') && !isset($fscPi) && !isset($GLOBALS['fscPi'])) {

	// Plugin initialization
  $fscPi  = new FsCharts();
  $GLOBALS['fscPi'] = $fscPi;
	
}


















/**********************************************
 *
 *          Hooks management
 *
 */
 
 
if (isset($fscPi)) {

	// Add the widget installation
  add_action('wp_dashboard_setup', 'fsc_install_widget');
	
	// Add admin menu entry for the plugin
	add_action('admin_menu', array(&$fscPi, 'adminMenu'));
	
	// Register activation function
	register_activation_hook(__FILE__, array(&$fscPi, 'install'));
	
	/**
	 * Add the widget to the dashboard
	 *
	 * @param		void
	 * @return 	void
	 * @access 	public
	 */
  function fsc_install_widget() {
    wp_add_dashboard_widget('fsc_dashboard_widget', 'FireStats Charts', 'fsc_installWidget');
  }
	
}

/**
 * Install the widget on the dashboard
 *
 * @param	void
 * @return 	void
 * @access 	public
 * @since		1.0.3
 */
function fsc_installWidget() {
	global $fscPi;
	$fscPi->installWidget();
}




















/**********************************************
 *
 *          Template Tags
 *
 */






















/**********************************************
 *
 *          WordPress Hacks
 *
 */

 

?>