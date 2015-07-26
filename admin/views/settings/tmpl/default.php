<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

$option = $this->input->getCmd('option');
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
<form action="index.php" method="post" id="adminForm" name="adminForm"> 
   <?php
   	$p=1;
	echo JHtml::_('bootstrap.startTabSet', 'tabs', array('active' => 'panel1'));
	
	$fieldSets = $this->form->getFieldsets();
	foreach ($fieldSets as $name => $fieldSet) :
	$label = $fieldSet->name;
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_($label,true));
	?>
				<fieldset class="form-vertical">
					<?php
					if (isset($fieldSet->description) && !empty($fieldSet->description)) :
						echo '<fieldset class="adminform">'.JText::_($fieldSet->description).'</fieldset>';
					endif;
					?>
					
					<?php foreach ($this->form->getFieldset($name) as $field): ?>
					<div class="control-group">
						<?php if (!$field->hidden) : ?>
							<div class="control-label"><?php echo $field->label; ?></div>
						<?php endif; ?>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
					<?php endforeach; ?>
				</fieldset>
	 	<?php 
	 	echo JHtml::_('bootstrap.endTab'); 
	    endforeach; 
	    echo JHtml::_('bootstrap.endTabSet');
   		?>
   		<div class="clr"></div>
	<!-- Input fields -->
	<input type="hidden" name="task" value="setting.display">
	<input type="hidden" name="option" value="<?php echo $option; ?>">
	<?php echo JHtml::_('form.token'); ?>
</form>