<?php
/*
Plugin Name: FireStats Charts
Plugin URI: http://wordpress.org/extend/plugins/firestats-charts/
Description: Add a chart view to firestats's statistics. <strong>Require <a href="http://firestats.cc/" target="_blank">FireStats</a> > 1.6.3</strong>.
Version: 1.1.0
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
    protected $version = '1.1.0';
		
		
		
		
		
				
		/**********************************************
		 *
		 *           MAIN PART
		 *
		 */
	 
		public function __construct($verbose = null) {

			// Detect verbose mode
			if ($verbose !== null && is_bool($verbose)) {
				$this->verbose = $verbose;
			} else {
				if (isset($_GET['verbose']) && !empty($_GET['verbose'])) {
					$this->verbose = ($_GET['verbose'] === 1) ? true : false;
				}
				// @todo : add $_POST['verbose'] support
			}

			// Get plugin's variables GET
			if (isset($_GET[$this->piKey]) && is_array($_GET[$this->piKey])) {
				foreach ($_GET[$this->piKey] as $key => $value) {
					$this->vars[$key] = $value;
				}
			}

			// Get plugin's variables POST (override GET's variables)
			if(isset($_POST[$this->piKey]) && is_array($_POST[$this->piKey])) {
				foreach ($_POST[$this->piKey] as $key => $value) {
					$this->vars[$key] = $value;
				}
			}
      
      // Include jpgraph library
      require_once('res/jpgraph/jpgraph.php');
			require_once('res/jpgraph/jpgraph_line.php');
		
			$this->additionnalHeaders();
		
		}

		
		/**
		 * Install the plugin
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
     * @since   1.1.0
		 */
		public function install() {
			
			// For 1.1.0
			
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
     * @since   1.1.0
		 */
    public function installWidget() {

      global $wpdb;

      // Get datas from firestats table
      //$graph = new Graph(650, 380);
			$graph = new Graph(get_option('fscharts_width'), get_option('fscharts_height'));
      $graph->SetScale('intint'/*, 0, 0, 0, max($days) - min($days) + 1*/);
      $graph->SetMargin(40,30,40,100);
      $graph->title->Set('FireStats Charts');
      $graph->xaxis->SetLabelAngle(90);
      $graph->xaxis->title->Set('Days');
      $graph->yaxis->title->Set('Hits');

      // Create the linear plot
      $hits = $this->getHits();
      $lineplot = new LinePlot($hits['datas']);
      $graph->xaxis->SetTickLabels($hits['labels']);

      $lineplot->SetFillColor('orange@0.5');
      $lineplot->value->Show();
      $lineplot->value->SetColor('darkred');
      //$lineplot->value->SetFont('', '', 8);
      $lineplot->value->SetFormat('%d');

      // Add the plot to the graph
      $graph->Add($lineplot);


      $graph->Stroke(dirname(__FILE__).'/out/graph.png');

      // Open the box
      $out = '<div style="text-align: center;">';
      
      // Configuration panel rendering
      $out .= '<div id="widgetConfigPanel">';
			$out .= '<form method="post" action="'.get_option('home').'/wp-admin/" name="fschartsPanelForm" id="fschartsPanelForm">';
			$out .= '<input type="hidden" name="'.$this->piKey.'[submitted]" id="fscSubmitted" value="1" />';
			$out .= '<input type="hidden" name="'.$this->piKey.'[task]" id="fscTask" value="config" />';
			
      $out .= '<div id="widgetLeftPanel">';
      $out .= $this->getLeftPanelHTML();
      $out .= '</div>';
			
      $out .= '<div id="widgetRightPanel">';
      $out .= $this->getRightPanelHTML();
      $out .= '</div>';
			
			$out .= '<div class="spacer"></div>';
      $out .= '</div>';
      
      // The chart
      $out .= '<img src="http://www.ansermot.ch/wp-content/plugins/fscharts/out/graph.png" alt="FireStats Graph" />';

      // Close the box...
      $out .=' </div>';
      echo $out;

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
		 *          RENDERING PART
		 *
		 */
		
		/**
		 * Create the HTML for the left panel
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
		 */
		public function getLeftPanelHTML() {
		
			$out = '<ul>';
			$out .= '<li><label for="'.$this->piKey.'-width">'.__('Width').' :</label>';
			$out .= '<input type="text" name="'.$this->pikey.'[width]" id="'.$this->piKey.'-width" value="'.get_option('fscharts_width').'" /></li>';
			$out .= '<li><label for="'.$this->piKey.'-height">'.__('Height').' :</label>';
			$out .= '<input type="text" name="'.$this->pikey.'[height]" id="'.$this->piKey.'-height" value="'.get_option('fscharts_height').'" /></li>';
      
			$checked = (get_option('fscharts_table') == 1) ? 'checked="checked" ' : '';
			$out .= '<li><label for="'.$this->piKey.'-table">'.__('Display stats table').' :</label>';
			$out .= '<input type="checkbox" name="'.$this->pikey.'[table]" id="'.$this->piKey.'-table" '.$checked.'/></li>';
      
			$checked = (get_option('fscharts_hits') == 1) ? 'checked="checked" ' : '';
			$out .= '<li><label for="'.$this->piKey.'-hits">'.__('Display hits').' :</label>';
			$out .= '<input type="checkbox" name="'.$this->pikey.'[hits]" id="'.$this->piKey.'-hits" '.$checked.'/></li>';
			
			$checked = (get_option('fscharts_visited') == 1) ? 'checked="checked" ' : '';
      $out .= '<li><label for="'.$this->piKey.'-visits">'.__('Display visits').' :</label>';
			$out .= '<input type="checkbox" name="'.$this->pikey.'[visits]" id="'.$this->piKey.'-visits" '.$checked.'/></li>';
			$out .= '</ul>';
			
			return $out;
			
		}
		
		
		
		/**
		 * Create the HTML for the right panel
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
		 */
		public function getRightPanelHTML() {
		
			$out = '<ul>';
			$out .= '<li><label for="'.$this->piKey.'-zoom-width">'.__('Zoom width').' :</label>';
			$out .= '<input type="text" name="'.$this->pikey.'[zoom_width]" id="'.$this->piKey.'-zoom-width" value="'.get_option('fscharts_zoom_width').'" /></li>';
			$out .= '<li><label for="'.$this->piKey.'-zoom-height">'.__('Zoom height').' :</label>';
			$out .= '<input type="text" name="'.$this->pikey.'[zoom-height]" id="'.$this->piKey.'-zoom-height" value="'.get_option('fscharts_zoom_height').'" /></li>';
      
			$checked = (get_option('fscharts_zoom') == 1) ? 'checked="checked" ' : '';
			$out .= '<li><label for="'.$this->piKey.'-table">'.__('Enable zoom').' :</label>';
			$out .= '<input type="checkbox" name="'.$this->pikey.'[zoom]" id="'.$this->piKey.'-zoom" '.$checked.'/></li>';
			
			$out .= '<li><input type="submit" name="'.$this->piKey.'[submit] id="'.$this->piKey.'-submit" value="'.__('Apply').'" /></li>';
      
			$out .= '</ul>';
			
			return $out;
			
		}
		
		/**********************************************
		 *
		 *          PROTECTED METHODS PART
		 *
		 */
		
		/**
		 * Add Pi headers to WP <head>
		 *
		 * @param 	void
		 * @return 	void
		 * @access 	public
		 */
		protected function additionnalHeaders() {
			wp_register_style('FsChartsBackend', '/wp-content/plugins/fscharts/res/css/be.css');
			wp_register_script('FsChartsBEeffects', '/wp-content/plugins/fscharts/res/scripts/beeffects.js');
			
			wp_enqueue_style('FsChartsBackend');
			wp_enqueue_script("jquery");
			wp_enqueue_script('FsChartsBEeffects');
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

  add_action('wp_dashboard_setup', 'fsc_install_widget');
	
	// Add the admin menu entry
	//add_action('admin_menu', array(&$fscPi, 'adminMenu'));
	// Register activation hook function
	register_activation_hook(__FILE__, array(&$fscPi, 'install'));
	
	/**
	 * Add the widget to the dashboard
	 *
	 * @param	void
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