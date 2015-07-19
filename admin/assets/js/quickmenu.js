/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

window.addEvent('domready', function() {
	
	
	
	
	
	
	$('stid').addEvent('change', function(){
		var form = $('adminForm1');
		form.submit();
	});
	if ($('seasonnav')) {
		$('seasonnav').addEvent('change', function(){
			var form = $('adminForm1');
			if (this.value != 0) {
				$('jl_short_act').value = 'seasons';
			}
			form.submit();
		});
	}

	if($('pid')!=null){
		$('pid').addEvent('change', function(){
			var form = $('adminForm1');
			if (this.value != 0) {
				$('jl_short_act').value = 'projects';
			}
			form.submit();
		});
	}

	if($('tid')!=null){
		$('tid').addEvent('change', function(){
			var form = $('adminForm1');
			if (this.value != 0) {
				$('jl_short_act').value = 'teams';
			}
		form.submit();
	});}
	
	if($('rid')!=null){
		$('rid').addEvent('change', function(){
		var form = $('adminForm1');
		if (this.value != 0) {
			$('jl_short_act').value = 'rounds';
		}
		form.submit();
	});}
});