<?php defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" id="adminForm">
	<div class="col50">
	<?php
	echo JHtml::_('tabs.start','tabs', array('useCookie'=>1,'startOffset'=>0));
	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DETAILS'), 'panel1');
	echo $this->loadTemplate('details');

	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DATE'), 'panel2');
	echo $this->loadTemplate('date');

	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PROJECT'), 'panel3');
	echo $this->loadTemplate('project');

	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_COMPETITION'), 'panel4');
	echo $this->loadTemplate('competition');

	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_FAVORITE'), 'panel5');
	echo $this->loadTemplate('favorite');

	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PICTURE'), 'panel6');
	echo $this->loadTemplate('picture');

	echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_EXTENDED'), 'panel7');
	echo $this->loadTemplate('extended');

	if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague') || 
		JFactory::getUser()->authorise('core.admin', 'com_joomleague.project')) {
		echo JHtml::_('tabs.panel',JText::_('JCONFIG_PERMISSIONS_LABEL'), 'panel8');
		echo $this->loadTemplate('permissions');
	}
	
	echo JHtml::_('tabs.end');
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
