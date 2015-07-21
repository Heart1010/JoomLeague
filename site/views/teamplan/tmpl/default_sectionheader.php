<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
?>
<table class="contentpaneopen">
	<tr>
		<td class="contentheading"><?php echo $this->pagetitle; ?></td>
		<?php
		if($this->config['show_ical_link'])
		{
			?>
			<td class="contentheading" style="text-align: right;">
				<?php
				if (!is_null($this->ptid))
				{
				$link=JoomleagueHelperRoute::getIcalRoute($this->project->id,$this->teams[$this->ptid]->team_id,null,null);
				$text=JHtml::_('image','administrator/components/com_joomleague/assets/images/calendar.png', JText::_('COM_JOOMLEAGUE_TEAMPLAN_ICAL_EXPORT'));
				$attribs	= array('title' => JText::_('COM_JOOMLEAGUE_TEAMPLAN_ICAL_EXPORT'));
				echo JHtml::_('link',$link,$text,$attribs);
				}
				?>
			</td>
			<?php
		}
		?>
	</tr>
</table><br />