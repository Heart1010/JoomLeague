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
defined('_JEXEC')or die;
?>

<div>
	<fieldset class="form-horizontal">
		<legend><?php echo JText::_('JCONFIG_PERMISSIONS_LABEL'); ?></legend>
		<?php foreach ($this->form->getFieldset('Permissions') as $field): ?>
			<?php echo $field->label; ?>
			<div class="clr"> </div>
			<?php echo $field->input; ?>
		<?php endforeach; ?>
	</fieldset>
</div>
