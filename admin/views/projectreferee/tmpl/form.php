<?php defined('_JEXEC') or die('Restricted access');?>
<form action="index.php" method="post" id="adminForm">
	<div class="col50">
		<?php
		echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DETAILS'), 'panel1');
		echo $this->loadTemplate('details');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PICTURE'), 'panel2');
		echo $this->loadTemplate('picture');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DESCRIPTION'), 'panel3');
		echo $this->loadTemplate('description');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_EXTENDED'), 'panel4');
		echo $this->loadTemplate('extended');
		if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague') ||
			JFactory::getUser()->authorise('core.admin', 'com_joomleague.project.'.(int)$this->projectws->id)) {
			echo JHtml::_('tabs.panel',JText::_('JCONFIG_PERMISSIONS_LABEL'), 'panel5');
			echo $this->loadTemplate('permissions');
		}
		echo JHtml::_('tabs.end');
		?>
		<input type="hidden" name="option" value="com_joomleague" /> 
		<input type="hidden" name="project_id"  value="<?php echo $this->projectws->id; ?>" /> 
		<input type="hidden" name="id"  value="<?php echo $this->projectreferee->id; ?>" /> 
		<input type="hidden" name="task"   value="" />
	</div>
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>
