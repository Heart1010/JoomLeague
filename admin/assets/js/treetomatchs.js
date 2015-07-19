/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

Joomla.submitbutton = function(task) {
	var form = $('adminForm');
	if($('node_matcheslist')) {
		var mylist = $('node_matcheslist');
		for ( var i = 0; i < mylist.length; i++) {
			mylist[i].selected = true;
		}
	}
	Joomla.submitform(task);
}

function handleLeftToRight() {
	$('matcheschanges_check').value = 1;
	move($('matcheslist'), $('node_matcheslist'));
	selectAll($('node_matcheslist'));
}

function handleRightToLeft() {
	$('matcheschanges_check').value = 1;
	move($('node_matcheslist'), $('matcheslist'));
	selectAll($('node_matcheslist'));
}
