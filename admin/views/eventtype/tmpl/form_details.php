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
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_EVENTTYPE_DETAILS' );?></legend>
		<table class="admintable">
			<?php foreach ($this->form->getFieldset('details') as $field): ?>
			<tr>
				<td class="key"><?php echo $field->label; ?></td>
				<td><?php echo $field->input; ?></td>
			</tr>					
			<?php endforeach; ?>
		</table>
</fieldset>