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

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="col50">
		<?php
		$p=1;
		echo JHtml::_('bootstrap.startTabSet', 'tabs', array('active' => 'panel1'));
		echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_DETAILS', true));
		echo $this->loadTemplate('details');
		echo JHtml::_('bootstrap.endTab');
		
		echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_PICTURE', true));
		echo $this->loadTemplate('picture');
		echo JHtml::_('bootstrap.endTab');
		
		if ($this->edit) {
			echo JHtml::_('bootstrap.addTab', 'tabs', 'panel3', JText::_('COM_JOOMLEAGUE_TABS_PARAMETERS', true));
			echo $this->loadTemplate('param');
			echo JHtml::_('bootstrap.endTab');
			
			echo JHtml::_('bootstrap.addTab', 'tabs', 'panel4', JText::_('COM_JOOMLEAGUE_TABS_GENERAL_PARAMETERS', true));
			echo $this->loadTemplate('gparam');
			echo JHtml::_('bootstrap.endTab');
		}
		
		echo JHtml::_('bootstrap.endTabSet');
		?>	
	</div>

	<div class="clr"></div>
	<?php if ($this->edit): ?>
		<input type="hidden" name="calculated" value="<?php echo $this->calculated; ?>" />
	<?php endif; ?>
	<input type="hidden" name="option" value="com_joomleague" />
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>