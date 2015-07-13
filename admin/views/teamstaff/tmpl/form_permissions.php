<?php defined('_JEXEC')or die('Restricted access');
?>

<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_('JCONFIG_PERMISSIONS_LABEL'); ?></legend>
		<?php foreach ($this->form->getFieldset('Permissions') as $field): ?>
			<?php echo $field->label; ?>
			<div class="clr"> </div>
			<?php echo $field->input; ?>
		<?php endforeach; ?>
	</fieldset>
</div>
