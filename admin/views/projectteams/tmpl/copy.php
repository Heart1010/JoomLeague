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

<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	
	<fieldset>
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_COPY_DEST')?></legend>
	<table class="admintable">
		<tr>
			<td class="key">
				<label for="dest"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_SELECT_PROJECT' ).':'; ?></label>
			</td>
			<td>
				<?php echo $this->lists['projects']; ?>
			</td>
		</tr>
	</table>
	</fieldset>
	
	<?php foreach ($this->ptids as $ptid): ?>
	<input type="hidden" name="ptids[]" value="<?php echo $ptid; ?>"/>
	<?php endforeach; ?>
	<input type="hidden" name="task" value="" />
</form>