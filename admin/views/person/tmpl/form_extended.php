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
foreach ($this->extended->getFieldsets() as $fieldset)
{
	?>
	<fieldset class="adminform">
	<legend><?php echo JText::_($fieldset->name); ?></legend>
	<?php
	$fields = $this->extended->getFieldset($fieldset->name);
	
	if(!count($fields)) {
		echo JText::_('COM_JOOMLEAGUE_GLOBAL_NO_PARAMS');
	}
	
	foreach ($fields as $field)
	{
		echo $field->label;
       	echo $field->input;
	}
	?>
	</fieldset>
	<?php
}
?>
