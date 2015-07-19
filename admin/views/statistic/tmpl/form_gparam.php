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
	<?php foreach ($this->form->getFieldsets('baseparams') as $fieldset): ?>
		<fieldset class="adminform">
			<legend>
				<?php
				echo JText::_($fieldset->label);
				?>
			</legend>
			<table class="admintable">
				<?php
				// render is defined in joomla\libraries\joomla\html\parameter.php
				foreach ($this->form->getFieldset($fieldset->name) as $field):
				?>
				<tr>
					<td class="key"><?php echo $field->label; ?></td>
					<td><?php echo $field->input; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		</fieldset>
	<?php endforeach; ?>