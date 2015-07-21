<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */ 
?>

<?php if (!empty($this->rounds)): ?>
<table class="not-playing" width="96%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="text-align:left; ">
			<?php echo $this->showNotPlayingTeams($this->matches, $this->teams, $this->config, $this->favteams, $this->project); ?>
		</td>
	</tr>
</table>
<?php endif; ?>