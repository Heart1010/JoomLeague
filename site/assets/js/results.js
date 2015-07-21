/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

window.addEvent('domready', function(){
	$$('.eventstoggle').addEvent('click', function(){
		var id = this.getProperty('id').substr(7);
		if ($('info'+id).getStyle('display') == 'block') {
			$('info'+id).setStyle('display', 'none');
		}
		else {
			$('info'+id).setStyle('display', 'block');
		}
	});
});