<?php
// no direct access
defined('_JEXEC') or die;

?>
<fieldset class="batch">
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_BATCH_OPTIONS');?></legend>
	<p><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_BATCH_TIP'); ?></p>

	<fieldset id="batch-fix-game-dates-action" class="combo">
	<label id="batch-fix-game-dates-lbl" for="batch-category-id">
		<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_BATCH_FIX_DATES_LABEL'); ?>
	</label>
	<button type="submit" onclick="submitbutton('project.fixdates');">
		<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_BATCH_FIX_DATES_BUTTON'); ?>
	</button>
	</fieldset>
</fieldset>