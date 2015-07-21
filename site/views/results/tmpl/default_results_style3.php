<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */

$nbcols		= 5;
$dates		= $this->sortByDate();
$nametype = $this->config['names'];

if($this->config['show_match_number']){$nbcols++;}
if($this->config['show_events']){$nbcols++;}
if(($this->config['show_playground'] || $this->config['show_playground_alert'])){$nbcols++;}
if($this->config['show_referee']){$nbcols++;}

?>
<table class="fixtures-results" border='1'>
	<tr>
		<td>
			May be designed in the future???
		</td>
	</tr>
</table><br />