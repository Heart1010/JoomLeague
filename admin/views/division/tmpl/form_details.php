<?php 
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_JOOMLEAGUE_ADMIN_DIVISION' );?>
	</legend>
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
			<td class="key"><?php echo $this->form->getLabel('middle_name'); ?></td>
			<td><?php echo $this->form->getInput('middle_name'); ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo $this->form->getLabel('shortname'); ?></td>
			<td><?php echo $this->form->getInput('shortname'); ?></td>
		</tr>		
		<tr>
			<td class="key"><?php echo JText::_( 'COM_JOOMLEAGUE_ADMIN_DIVISION_PARENT' );?></td>
			<td><?php echo $this->lists['parents'];?></td>
		</tr>
	</table>
</fieldset>