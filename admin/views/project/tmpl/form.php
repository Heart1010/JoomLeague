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
<form action="index.php" method="post" id="adminForm" name="adminForm" class="form-validate">
	<div class="col50">
	<?php 
	$p=1;
	echo JHtml::_('bootstrap.startTabSet', 'tabs', array('active' => 'panel1'));
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_DETAILS', true));
	echo $this->loadTemplate('details');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_DATE', true));
	echo $this->loadTemplate('date');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_PROJECT', true));
	echo $this->loadTemplate('project');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_COMPETITION', true));
	echo $this->loadTemplate('competition');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_FAVORITE', true));
	echo $this->loadTemplate('favorite');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_PICTURE', true));
	echo $this->loadTemplate('picture');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_EXTENDED', true));
	echo $this->loadTemplate('extended');
	echo JHtml::_('bootstrap.endTab');
	
	if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague')
			|| JFactory::getUser()->authorise('core.admin', 'com_joomleague.project'))
	{
		echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('JCONFIG_PERMISSIONS_LABEL', true));
		echo $this->loadTemplate('permissions');
		echo JHtml::_('bootstrap.endTab');
	}
	
	echo JHtml::_('bootstrap.endTabSet');
	?>
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_joomleague" /> 
	<input type="hidden" name="task" value="" /> 
	<input type="hidden"name="oldseason" value="<?php echo $this->form->getValue('season_id'); ?>" />
	<input type="hidden" name="oldleague" value="<?php echo $this->form->getValue('league_id'); ?>" /> 
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" />
	<?php echo JHtml::_('form.token')."\n"; ?>
	</div>
</form>
