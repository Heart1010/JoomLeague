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

JHtmlBehavior::formvalidation();
JHtml::_('behavior.tooltip');

$i = 1;
?>
<style type="text/css">
	<!--
	fieldset.panelform label, fieldset.panelform div.paramrow label, fieldset.panelform span.faux-label {
		max-width: 255px;
		min-width: 255px;
		padding: 0 5px 0 0;
	}
	-->
</style>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	<div style='text-align: right;'>
		<?php echo $this->lists['templates']; ?>
	</div>
	<?php
	if ($this->project->id != $this->template->project_id) {
		JError::raiseNotice(0, JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATE_MASTER_WARNING'));
		?><input type="hidden" name="master_id" value="<?php echo $this->template->project_id; ?>"/><?php
	}
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_TEMPLATE_LEGEND', '<i>' . JText::_('COM_JOOMLEAGUE_FES_' . strtoupper($this->form->getName()) . '_NAME') . '</i>', '<i>' . $this->project->name . '</i>'); ?></legend>
		<fieldset class="adminform">
			<?php
			echo JText::_('COM_JOOMLEAGUE_FES_' . strtoupper($this->form->getName()) . '_DESCR');
			?>

		<?php
		$p=1;
		echo JHtml::_('bootstrap.startTabSet', 'tabs', array('active' => 'panel1'));
		
		$fieldSets = $this->form->getFieldsets();
		
		foreach ($fieldSets as $name => $fieldSet) :
		echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_($fieldSet->label, true));
		
		if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="alert alert-info">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
		endif;
		?>
				<?php foreach ($this->form->getFieldset($name) as $field) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				<?php endforeach; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endforeach; ?>
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
 	</fieldset></fieldset>
    <div class="clr"></div>
	<div>		
		<input type="hidden" name="boxchecked" value="1" />
		<input type='hidden' name='user_id' value='<?php echo $this->user->id; ?>'/>
		<input type="hidden" name="cid[]" value="<?php echo $this->template->id; ?>"/>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
