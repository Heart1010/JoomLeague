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
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_MATCH_F_MD'); ?>
	</legend>
	<table class="admintable">
		<tr>
			<td class="key"><?php echo $this->form->getLabel('cancel'); ?></td>
			<td><?php echo $this->form->getInput('cancel'); ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo $this->form->getLabel('cancel_reason'); ?></td>
			<td><?php echo $this->form->getInput('cancel_reason'); ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo $this->form->getLabel('playground_id'); ?></td>
			<td><?php echo $this->form->getInput('playground_id'); ?></td>
		</tr>		
	</table>
</fieldset>		