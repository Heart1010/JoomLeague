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
	<legend><?php echo JText::_( 'COM_JOOMLEAGUE_ADMIN_STAT_PIC' );?></legend>
		<table class="admintable">
			<tr>
				<td class="key"><?php echo $this->form->getField('icon')->label; ?></td>
				<td><?php echo $this->form->getField('icon')->input; ?></td>
			</tr>
		</table>
</fieldset>