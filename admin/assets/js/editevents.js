/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

window.addEvent('domready', function() {
	updatePlayerSelect();
	if($('team_id')) {
		$('team_id').addEvent('change', updatePlayerSelect);

		
		// button-delete: click function for comments
		$$('input.button-delete-c').addEvent('click', deletecomment);
		
		// button-delete: click function for events
		$$('input.button-delete-e').addEvent('click', deleteevent);

		// button-newevent: click function
		$('save-new').addEvent(
				'click',
				function() {
					var url = baseajaxurl + '&task=match.saveevent&';
					var player = $('teamplayer_id').value;
					var event = $('event_type_id').value;
					var team = $('team_id').value;
					//var token = $('token').value;
					var time = $('event_time').value;
                    var notice = encodeURIComponent($('notice').value);
					var querystring = 'teamplayer_id=' + player +
					'&projectteam_id=' + team + 
					'&event_type_id=' + event + 
					'&event_time=' + time + 
					'&match_id=' + matchid + 
					'&event_sum=' + $('event_sum').value +
					'&notice=' + notice
					//+ '&'
					//+ token
					;
					if (team != 0 && event != 0) {
						var myXhr = new Request.JSON( {
							url: url + querystring,
							method : 'post',
							onRequest : reqsent,
							onFailure : reqfailed,
							onSuccess : eventsaved
						});
						myXhr.post();
					}
				});
	}
	// button-newcomment: click function
    $('save-new-comment').addEvent(
			'click',
			function() {
				$('ajaxresponse').set('text','');
				var url = baseajaxurl + '&task=match.savecomment';
				var player = 0;
				var event = 0;
				var team = 0;
				var ctype = $('ctype').value;
                var comnt = encodeURIComponent($('notes').value)
				var time = $('c_event_time').value;
				var querystring = '&teamplayer_id=' + player
				+ '&projectteam_id=' + team + '&event_type_id='
				+ event + '&event_time=' + time + '&match_id='
				+ matchid + '&event_sum='
				+ ctype + '&notes='
				+ comnt;
				
				if (comnt == '') {
					$('ajaxresponse').set('text','fill in notes').style.color='red';
				}
				
				if (ctype != 0 && comnt != '') {
					var myXhr = new Request.JSON( {
						url: url + querystring,
						method : 'post',
						onRequest : reqsent,
						onFailure : reqfailed,
						onSuccess : commentsaved
					});
					myXhr.post();
				}
			});
	});

// comment delete
function deletecomment() {
	var eventid = this.id.substr(7);
	var url = baseajaxurl + '&task=match.removeComment';
	var querystring = '&event_id=' + eventid;
	if (eventid) {
		var myXhr = new Request.JSON( {
			url: url + querystring,
			method : 'post',
			onRequest : reqsent,
			onFailure : reqfailed,
			onSuccess : commentdeleted,
			rowid : eventid
		});
		myXhr.post();
	}
}


// comment deleted
function commentdeleted(response) {
	var resp = response.split("\n");
	if (resp[0] != '0') {
		var currentrow = $('rowc-' + this.options.rowid);
		currentrow.dispose();
	}

	$('ajaxresponse').removeClass('ajax-loading');
	$('ajaxresponse').set('text',resp[1]);
}


//  comment saved
function commentsaved(response) {
	$('ajaxresponse').removeClass('ajax-loading');
	// first line contains the row, second line contains status.
	var resp = response.split("\n");
	if (resp[0] != '0') {
		// create new row in comments table
		var newrow = new Element('tr', {
			id : 'rowc-' + resp[0]
		});
		
		// add td's
		new Element('td').set('text', $('ctype').options[$('ctype').selectedIndex].text)
			.inject(newrow,'inside');
		new Element('td').set('text',$('c_event_time').value).inject(newrow,'inside');
		new Element('td', {
			title : $('notes').value
		}).addClass("hasTooltip").set('text',$('notes').value).inject(newrow,'inside');
		
		// Append deletebutton
		var deletebutton = new Element('input', {
			id : 'delete-' + resp[0],
			type : 'button',
			value : str_delete
		}).addClass('inputbox button-delete-c btn').addEvent('click', deletecomment);
		new Element('td').appendChild(deletebutton).inject(newrow,'inside');
		newrow.inject($('row-new-comment'),'before');
		
		$('ajaxresponse').set('text',resp[1]);
	} else {
		$('ajaxresponse').set('text',resp[1]).style.color='red';
	}
}

// event delete
function deleteevent() {
	var eventid = this.id.substr(7);
	var url = baseajaxurl + '&task=match.removeEvent';
	var querystring = '&event_id=' + eventid;
	if (eventid) {
		var myXhr = new Request.JSON( {
			url: url + querystring,
			method : 'post',
			onRequest : reqsent,
			onFailure : reqfailed,
			onSuccess : eventdeleted,
			rowid : eventid
		});
		myXhr.post();
	}
}


// event deleted
function eventdeleted(response) {
	var resp = response.split("\n");
	if (resp[0] != '0') {
		var currentrow = $('rowe-' + this.options.rowid);
		currentrow.dispose();
	}

	$('ajaxresponse').removeClass('ajax-loading');
	$('ajaxresponse').set('text',resp[1]);
}

// event saved
function eventsaved(response) {
	$('ajaxresponse').removeClass('ajax-loading');
	// first line contains the row, second line contains the status.
	var resp = response.split("\n");
	if (resp[0] != '0') {
		// create new row in events table
		var newrow = new Element('tr', {
			id : 'rowe-' + resp[0]
		});
		new Element('td').set('text', $('team_id').options[$('team_id').selectedIndex].text)
				.inject(newrow,'inside');
		new Element('td')
				.set('text', $('teamplayer_id').options[$('teamplayer_id').selectedIndex].text)
				.inject(newrow,'inside');
		new Element('td')
				.set('text', $('event_type_id').options[$('event_type_id').selectedIndex].text)
				.inject(newrow,'inside');
		new Element('td').set('text',$('event_sum').value).inject(newrow,'inside');
		new Element('td').set('text',$('event_time').value).inject(newrow,'inside');
		new Element('td', {
			title : $('notice').value
		}).addClass("hasTooltip").set('text',trimstr($('notice').value, 20)).inject(newrow,'inside');
		var deletebutton = new Element('input', {
			id : 'delete-' + resp[0],
			type : 'button',
			value : str_delete
		}).addClass('inputbox button-delete-e btn').addEvent('click', deleteevent);
		new Element('td').appendChild(deletebutton).inject(newrow,'inside');
		newrow.inject($('row-new'),'before');
		$('ajaxresponse').set('text',resp[1]);
	} else {
		$('ajaxresponse').set('text',resp[1]);
	}
}

// request: failed
function reqfailed() {
	$('ajaxresponse').removeClass('ajax-loading');
	$('ajaxresponse').set('text',response);
}

// request: sent
function reqsent() {
	$('ajaxresponse').addClass('ajax-loading');
	$('ajaxresponse').set('text','');
}


// player select
function updatePlayerSelect() {
	if($('cell-player'))
	$('cell-player').empty().appendChild(
			getPlayerSelect($('team_id').selectedIndex));
}
/**
 * return players select for specified team
 *
 * @param int )
 *            for home, 1 for away
 * @return dom element
 */
function getPlayerSelect(index) {
	// homeroster and awayroster must be defined globally (in the view calling
	// the script)
	var roster = rosters[index];
	// build select
	var select = new Element('select', {
		id : "teamplayer_id"
	});
	for ( var i = 0, n = roster.length; i < n; i++) {
		new Element('option', {
			value : roster[i].value
		}).set('text',roster[i].text).inject(select,'inside');
	}
	return select;
}

function trimstr(str, mylength) {
	return (str.length > mylength) ? str.substr(0, mylength - 3) + '...' : str;
}