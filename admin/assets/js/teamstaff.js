/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

Joomla.submitbutton = function(task) {
	var res = true;
	var form = $('adminForm');

	if (task == 'teamstaff.cancel') {
		Joomla.submitform(task);
		if(window.parent.SqueezeBox) {
			window.parent.SqueezeBox.close();
		}
		return;
	}
	
	if (res) {
		Joomla.submitform(task);
	} else {
		return false;
	}
}