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
	<legend>
		<?php echo JText::sprintf(	'COM_JOOMLEAGUE_ADMIN_P_TEAM_TITLE_DESCR',
			'<i>' . $this->project_team->name . '</i>',
			'<i>' . $this->projectws->name . '</i>'); ?>
	</legend>
	
	<div class="control-group">
		<div class="control-label"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_INFO'); ?>:</div>
		<div class="controls"><input class="text_area" type="text" name="info" id="title" size="50" maxlength="250" value="<?php echo $this->project_team->info; ?>" /></div>
	</div>
	
	
	<fieldset class="form-vertical">
		<div class="control-group">
			<div class="control-label"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_DESCRIPTION'); ?>:</div>
			<div class="controls">
			<?php
				$editor = JFactory::getEditor();
				$this->editor = $editor;
				// parameters : areaname, content, hidden field, width, height, rows, cols
				echo $this->editor->display('notes',$this->project_team->notes,'600','400','70','15');
			?>
			</div>
		</div>
	</fieldset>
</fieldset>