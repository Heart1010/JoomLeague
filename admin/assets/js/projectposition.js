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
	if (task == 'projectposition.cancel') {
		Joomla.submitform(task);
		return;
	}
	if($('project_positionslist')) {
		var mylist = $('project_positionslist');
		for ( var i = 0; i < mylist.length; i++) {
			mylist[i].selected = true;
		}
	}
	Joomla.submitform(task);
}

function handleLeftToRight() {
	$('positionschanges_check').value = 1;
	move($('positionslist'), $('project_positionslist'));
	selectAll($('project_positionslist'));
}

function handleRightToLeft() {
	$('positionschanges_check').value = 1;
	move($('project_positionslist'), $('positionslist'));
	selectAll($('project_positionslist'));
}
