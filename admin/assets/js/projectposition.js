/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
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
