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
	<legend><?php echo JText::_( 'COM_JOOMLEAGUE_ADMIN_STAT_STAT' ); ?></legend>
	<?php 
	echo $this->form->renderField('name');
	echo $this->form->renderField('sports_type_id');
	echo $this->form->renderField('short');
	echo $this->form->renderField('alias');
	echo $this->form->renderField('class');
	echo $this->form->renderField('published');		
	?>
	<div class="control-group">
		<div class="control-label"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_STAT_NOTE'); ?></div>
		<div class="controls"><input type="text" id="note" name="note" value="<?php echo $this->form->getValue('note'); ?>" size="100"/></div>
	</div>	
</fieldset>