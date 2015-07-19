/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

Joomla.submitbutton = function(task) {
	var form = $('adminForm');
	if (task == 'template.cancel') {
		Joomla.submitform(task);
		return;
	}

	// do field validation
	if (document.formvalidator.isValid(form)) {
		Joomla.submitform(task);
		return true;
	} else {
		alert(Joomla.JText._('COM_JOOMLEAGUE_ADMIN_TEMPLATE_CSJS_WRONG_VALUES'));
	}
	return false;
}