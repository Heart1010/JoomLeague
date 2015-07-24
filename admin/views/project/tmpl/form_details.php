<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC')or die;
?>
<fieldset class="form-horizontal">
	<legend><?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_PROJECT_LEGEND_DETAILS','<i>'.$this->form->getValue('name').'</i>'); ?></legend>
			
	<?php 
	echo $this->form->renderField('name');
	echo $this->form->renderField('alias');
	echo $this->form->renderField('published');
	echo $this->form->renderField('sports_type_id');
	?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('league_id'); ?></div>
		<div class="controls">
		<?php echo $this->form->getInput('league_id'); ?>
		<?php
			if (!$this->edit){
				echo '<input type="checkbox" name="newLeagueCheck" value="1"';
				echo ' onclick="if(this.checked){$(\'adminForm\').league_id.disabled=true;';
				echo '$(\'adminForm\').leagueNew.disabled=false;';
				echo '$(\'adminForm\').leagueNew.value='.''.'$(\'adminForm\').name.value} ';
				echo 'else {$(\'adminForm\').league_id.disabled=false;$(\'adminForm\').leagueNew.disabled=true}" />';
				echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECT_LEAGUE_NEW').'&nbsp;';
				echo '<input type="text" name="leagueNew" id="leagueNew" size="16" disabled / >';
			}
		?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('season_id'); ?></div>
		<div class="controls">
		<?php echo $this->form->getInput('season_id'); ?>
		<?php
		if (!$this->edit) {
			echo '<input type="checkbox" name="newSeasonCheck" value="1"';
			echo ' onclick="if(this.checked){$(\'adminForm\').season_id.disabled=true;';
			echo '$(\'adminForm\').seasonNew.disabled=false} ';
			echo ' else {$(\'adminForm\').season_id.disabled=false;';
			echo '$(\'adminForm\').seasonNew.disabled=true}" />';
			echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECT_SEASON_NEW'). "&nbsp;";
			echo '<input type="text" name="seasonNew" id="seasonNew" disabled />';
		}
		?>
		</div>
	</div>
	<?php 
	echo $this->form->renderField('project_type');
	echo $this->form->renderField('master_template');
	echo $this->form->renderField('extension');
	?>
</fieldset>