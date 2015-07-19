/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

Joomla.submitbutton = function(task) {
	var res = true;
	var validator = document.formvalidator;
	var form = document.id('adminForm');

	if (task == 'club.cancel') {
		Joomla.submitform(task);
		if(window.parent.SqueezeBox) {
			window.parent.SqueezeBox.close();
		}
		return;
	}

	// do field validation
	if (validator.validate(form.name) === false) {
		alert(Joomla.JText._('COM_JOOMLEAGUE_ADMIN_CLUB_CSJS_NO_NAME'));
		form.name.focus();		
		res = false;
	}
	if (res) {
		Joomla.submitform(task);
	} else {
		return false;
	}
}
