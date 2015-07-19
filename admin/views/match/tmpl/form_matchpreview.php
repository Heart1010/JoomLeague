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
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_MATCH_F_MP'); ?>
	</legend>
	<table class="admintable">
		<tr>
			<td class="key"><?php echo $this->form->getLabel('preview'); ?></td>
			<td><?php echo $this->form->getInput('preview'); ?></td>
		</tr>
	</table>
</fieldset>		