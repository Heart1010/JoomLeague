<?php 
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;
?>
<fieldset class="adminform">
	<legend>
		<?php
		echo JText::_('COM_JOOMLEAGUE_ADMIN_PERSON_ASSIGN_DESCR');
		?>
	</legend>
	<table class="admintable">
		<tr>
			<td colspan="2">
				<div class="button2-left" style="display: inline">
					<div class="readmore">
						<?php
						//create the button code to use in form while selecting a project and team to assign a new person to
						$button = '<a class="modal-button" title="Select" ';
						$button .= 'href="index.php?option=com_joomleague&view=person&task=person.personassign" ';
						$button .= 'rel="{handler: \'iframe\', size: {x: 600, y: 400}}">' . JText::_('Select') . '</a>';
						echo $button;
						?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="project_id"> <?php
			echo JText::_('COM_JOOMLEAGUE_ADMIN_PERSON_ASSIGN_PID');
			?>
			</label>
			</td>
			<td><input onblur="$('project_name').value=''" type="text" name="project_id" id="project_id" value="" size="5" maxlength="6" /> 
				<input type="text" readonly name="project_name" id="project_name" value="" size="50"  />
			</td>
		<tr>
			<td class="key"><label for="team"> <?php
			echo JText::_('COM_JOOMLEAGUE_ADMIN_PERSON_ASSIGN_TID');
			?>
			</label>
			</td>
			<td><input onblur="$('team_name').value=''" type="text" name="team_id" id="team_id" value="" size="5" maxlength="6" /> 
				<input type="text" readonly name="team_name" id="team_name" value="" size="50"  />
			</td>
		</tr>
	</table>
</fieldset>
