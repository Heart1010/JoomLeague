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
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_POSITION_DETAILS_LEGEND'); ?></legend>
	<table class="admintable">
		<tr>
			<td class="key"><?php echo $this->form->getLabel('name'); ?></td>
			<td><?php echo $this->form->getInput('name'); ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo $this->form->getLabel('alias'); ?></td>
			<td><?php echo $this->form->getInput('alias'); ?></td>
		</tr>	
		<tr>
			<td class="key"><?php echo $this->form->getLabel('sports_type_id'); ?></td>
			<td><?php echo $this->form->getInput('sports_type_id'); ?></td>
		</tr>	
		<tr>
			<td class="key"><?php echo $this->form->getLabel('published'); ?></td>
			<td><?php echo $this->form->getInput('published'); ?></td>
		</tr>			
		<tr>
			<td class="key"><?php echo $this->form->getLabel('persontype'); ?></td>
			<td><?php echo $this->form->getInput('persontype'); ?></td>
		</tr>	
				<tr>
					<td class="key"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_POSITION_P_POSITION'); ?></td>
					<td><?php echo $this->lists['parents']; ?></td>
				</tr>		
	</table>
</fieldset>	