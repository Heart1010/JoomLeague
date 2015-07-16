/**
* @copyright	Copyright (C) 2005-2015 joomleague.at. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
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