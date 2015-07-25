<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

if (count($this->history) > 0)
{
	?>
<h2>

<?php echo JText::_('COM_JOOMLEAGUE_PERSON_PLAYING_CAREER');	?></h2>
<!-- staff history START -->
<table class="table">
	<tr>
		<td><br />
			<table class="gameshistory">
				<tr class="sectiontableheader">
					<th class="td_l"><?php echo JText::_('COM_JOOMLEAGUE_PERSON_COMPETITION'); ?></th>
					<th class="td_l"><?php echo JText::_('COM_JOOMLEAGUE_PERSON_SEASON'); ?></th>
					<th class="td_l"><?php echo JText::_('COM_JOOMLEAGUE_PERSON_POSITION'); ?></th>
				</tr>
					<?php
					$k=0;
					foreach ($this->history AS $station)
					{
						$link1=JoomleagueHelperRoute::getRefereeRoute($station->project_slug,$this->referee->slug);
						?>
						<tr class="<?php echo ($k==0)? $this->config['style_class1'] : $this->config['style_class2']; ?>">
							<td class="td_l"><?php echo JHtml::link($link1,$station->project_name); ?></td>
							<td class="td_l"><?php echo $station->season_name; ?></td>
							<td class="td_l"><?php echo ($station->position_name ? JText::_($station->position_name) : ""); ?></td>
						</tr>
						<?php
						$k=(1-$k);
					}
					?>
				</table>
		</td>
	</tr>
</table>
<br />
<br />
<!-- staff history END -->

<?php
}
?>