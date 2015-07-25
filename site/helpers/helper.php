<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JLHelperFront
{
	/**
	 * This function is making sure that we are retrieving a int
	 * from a string passed as "nr:name"
	 */
	public static function stringToInt($variable) 
	{	
		if ($variable) {
			# is the : within the string?
			if (strpos($variable,':') !== false) {
				$arr = explode(":", $variable, 2);
				$variable = $arr[0];
			}
		}
		
		return $variable;
	}
}

