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
<fieldset class="form-horizontal">
	<legend>
	<?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_TEAMPLAYER_PIC_TITLE',
		JoomleagueHelper::formatName(null, $this->project_player->firstname, $this->project_player->nickname, $this->project_player->lastname, 0),
		'<i>' . $this->teamws->name . '</i>', '<i>' . $this->projectws->name . '</i>' );
	?>
	</legend>
	<table class="admintable">
		<?php foreach ($this->form->getFieldset('picture') as $field): ?>
		<tr>
			<td class="key"><?php echo $field->label; ?></td>
			<td><?php echo $field->input; ?></td>
		</tr>					
		<?php endforeach; ?>
	</table>
</fieldset>		