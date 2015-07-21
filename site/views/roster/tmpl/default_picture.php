<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */ 
?>

<?php
	// Show team-picture if defined.
	if ( ( $this->config['show_team_logo'] == 1 ) )
	{
		?>
		<table width="96%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="center">
					<?php
					//dynamic object property string
					$pic = $this->config['show_picture'];
					echo JoomleagueHelper::getPictureThumb($this->projectteam->$pic,
															$this->projectteam->name,
															$this->config['team_picture_width'],
															$this->config['team_picture_height'],
															1);
					?>
				</td>
			</tr>
		</table>
	<?php
	}
	?>