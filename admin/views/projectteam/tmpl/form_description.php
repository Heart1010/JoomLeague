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
	
			<fieldset class="form-horizontal">
				<legend>
					<?php echo JText::sprintf(	'COM_JOOMLEAGUE_ADMIN_P_TEAM_TITLE_DESCR',
												'<i>' . $this->project_team->name . '</i>',
												'<i>' . $this->projectws->name . '</i>'); ?>
				</legend>
				
				
				
				
				<table class="admintable">
					<tr>
						<td class="key">
							<label for="description">
								<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_INFO'); ?>:
							</label>
						</td>
						<td>
							<input class="text_area" type="text" name="info" id="title" size="50" maxlength="250" value="<?php echo $this->project_team->info; ?>" />
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="description">
								<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_DESCRIPTION'); ?>:
							</label>
						</td>
						<td>
							<?php
							$editor = JFactory::getEditor();
							$this->editor = $editor;
							// parameters : areaname, content, hidden field, width, height, rows, cols
							echo $this->editor->display('notes',$this->project_team->notes,'600','400','70','15');
							?>
						</td>
					</tr>

				</table>
			</fieldset>
