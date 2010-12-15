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
 * @package	FireStats-Charts
 * @file		class.helper.php
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
	final class FsChartsHelper {
		
		protected $piRef = null;
		protected $piKey = '';
		
		/**
		 * Constructor
		 * 
		 * @param $pi: The pi root ref
		 * @return void
		 * @access public
		 * @since 1.1.4
		 */
		public function __construct(&$pi) {
			$this->piRef = $pi;
			$this->piKey = $pi->getPiKey();
		}
		
		/**
		 * Get a variable (POST, GET, COOKIE)
		 *
		 * @param string $var: The variable
		 * @param mixed $default: Default value if var not found
		 * @param string $offset : Table to search for variable (GET, POST, ALL)
		 * @return mixed The value
		 * @access public
		 * @since 1.1.4
		 */
		public static function getVar($var, $default = null, $offset = 'ALL') {
			
			if (isset($_POST[$var]) && !empty($_POST[$var]) && $offset != 'GET') {
				return $_POST[$var];
			} 
			
			if (isset($_GET[$var]) && !empty($_GET[$var]) && $offset != 'POST') {
				return $_GET[$var];
			}
			
			return $default;
			
		}
		
	}
	
}

?>