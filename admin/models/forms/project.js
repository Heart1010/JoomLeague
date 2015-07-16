/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */


/**
 * javascript validation for project form
 */
jQuery(document).ready(function(){
	document.formvalidator.setHandler('date',
			function(value) {
				if (value == "") {
					return true;
				} else {
					timer = new Date();
					time = timer.getTime();
					regexp = new Array();
					regexp[time] = new RegExp(
							'^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$', 'gi');
					return regexp[time].test(value);
				}
			});
/*
	document.formvalidator.setHandler('matchday', function(value) {
		if (value == "") {
			return false;
		} else {
			var regexp = new RegExp('^[0-9]+$', 'gi');
			if (!regexp.test(value)) {
				return false;
			} else {
				return (getInt(value) > 0);
			}
		}
	});
*/
	document.formvalidator.setHandler('select-required', function(value) {
		return value != 0;
	});

	document.formvalidator.setHandler('time',
		function (value) {
			regex=/^[0-9]{1,2}:[0-9]{1,2}$/;
			return regex.test(value);
	});
});