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
	<legend>
		<?php
		echo JText::sprintf(	'COM_JOOMLEAGUE_ADMIN_P_REF_DESCR',
				JoomleagueHelper::formatName(null, $this->projectreferee->firstname, $this->projectreferee->nickname, $this->projectreferee->lastname, 0),
				$this->projectws->name);
		?>
	</legend>
	<table class="admintable">
		<?php foreach ($this->form->getFieldset('description') as $field): ?>
		<tr>
			<td class="key"><?php echo $field->label; ?></td>
			<td><?php echo $field->input; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</fieldset>
