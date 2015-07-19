/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

function searchClub(val, key) {
	var f = $('adminForm');
	if (f) {
		f.elements['search'].value = val;
		f.elements['search_mode'].value = 'matchfirst';
		f.submit();
	}
}