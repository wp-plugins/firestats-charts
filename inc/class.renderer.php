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
		 * @param 	void
		 * @return 	string	The html
		 * @access 	public
		 */
		public function settingsPageHTML($options) {
		
			$out = '<div class="wrap">
								<div id="icon-options-general" class="icon32"><br>
								</div>
								<h2>FireStats Charts '.$this->version.' - Settings</h2>
								<form method="post" action="'.$this->piRef->getAdminUrl().'"> 
									<input type="hidden" id="'.$this->piRef->getPiKey().'_submitted" name="'.$this->piRef->getPiKey().'[submitted]" value="1" />
									<input type="hidden" id="'.$this->piRef->getPiKey().'_task" name="'.$this->piRef->getPiKey().'[task]" value="settings" />
									<input type="hidden" id="'.$this->piRef->getPiKey().'_action" name="'.$this->piRef->getPiKey().'[action]" value="update" />';
					
			$out .='<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"></th>
										<td><fieldset>
											<legend class="screen-reader-text"><span>Chart</span></legend>
											<p>
												<label for="'.$this->piRef->getPiKey().'_width">Width</label>
												<input name="'.$this->piRef->getPiKey().'[width]" type="text" id="'.$this->piRef->getPiKey().'_width" value="'.$options['width'].'" />
											</p>
											<p>
												<label for="'.$this->piRef->getPiKey.'_height">Height</label>
												<input name="'.$this->piRef->getPiKey.'[height]" type="text" id="'.$this->piRef->getPiKey().'_height" value="'.$options['height'].'" />
											</p>
											</fieldset></td>
									</tr>
								</tbody>
							</table>';
					
				$out .= '<p class="submit">
									<input name="Submit" class="button-primary" value="Update settings" type="submit">
								</p></form>';
				
				$out .= '</div>';
				
				return $out;
		}
		
	}
	
}

?>