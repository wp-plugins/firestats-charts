<?php
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

/**
 * $Id$
 * @package	FireStats-Charts
 * @file		class.renderer.php
 * @since		1.1.0
 */

// *************************************
// Prevent class execution outside of
// FireStats Charts
// *************************************
if (defined('_FSC')) {
	
	// *************************************
	// Class declaration
	// *************************************
	class FsChartsRenderer {
		
		// Plugin's reference
		protected $piRef = null;
		
		/**
		 * Constructor
		 *
		 * @param 	obj		&$piRef: Parent plugin reference
		 * @return 	void
		 * @access 	public
		 */
		public function __construct(&$piRef) {
			$this->piRef = $piRef;
		}
		
		
		/**
		 * Build the settings page's html
		 *
		 * @param array $options : the options
		 * @param array $msgs : the message list
		 * @return 	string	The html
		 * @access 	public
		 */
		public function settingsPageHTML($options, $msgs = null) {
		
			$out = '<div class="wrap">
								<div id="icon-options-general" class="icon32"><br>
								</div>
								<h2>'.__('FireStats Charts').' '.$this->version.' - '.__('Settings').'</h2>';
								
								if (isset($msgs) && is_array($msgs) && count($msgs) > 0) {
									$out .= '<ul class="fscharts-msgs">';
									foreach ($msgs as $msg) {
										$out .= '<li>'.$msg.'</li>';
									}
									$out .= '</ul>';
								}
								
			$out .= '<form method="post" action="'.$this->piRef->getAdminUrl().'" name="firestats-charts-settings" id="firestats-charts-settings"> 
									<input type="hidden" id="'.$this->piRef->getPiKey().'_submitted" name="'.$this->piRef->getPiKey().'[submitted]" value="1" />
									<input type="hidden" id="'.$this->piRef->getPiKey().'_task" name="'.$this->piRef->getPiKey().'[task]" value="settings" />
									<input type="hidden" id="'.$this->piRef->getPiKey().'_action" name="'.$this->piRef->getPiKey().'[action]" value="update" />';
					
			$out .='<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"></th>
										<td><fieldset>
											<legend class="screen-reader-text"><span>'.__('Chart').'</span></legend>
											<p>
												<input name="'.$this->piRef->getPiKey().'[chart-width]" type="text" id="'.$this->piRef->getPiKey().'_width" value="'.$options['chart-width'].'" />
												<label for="'.$this->piRef->getPiKey().'_width">'.__('Width').'</label>
											</p>
											<p>
												<input name="'.$this->piRef->getPiKey().'[chart-height]" type="text" id="'.$this->piRef->getPiKey().'_height" value="'.$options['chart-height'].'" />
												<label for="'.$this->piRef->getPiKey().'_height">'.__('Height').'</label>
											</p>
											<p>
												<input name="'.$this->piRef->getPiKey().'[cache-expire]" type="text" id="'.$this->piRef->getPiKey().'_cache_expire" value="'.$options['cache-expire'].'" />
												<label for="'.$this->piRef->getPiKey().'_cache_expire">'.__('Cache expire').'</label>
											</p>
											</fieldset></td>
									</tr>
								</tbody>
							</table>';
					
				$out .= '<p class="submit">
									<input name="Submit" class="button-primary" value="'.__('Update settings').'" type="submit">
								</p></form>';
				
				$out .= '</div>';
				
				return $out;
		}
		
	}
	
}

?>