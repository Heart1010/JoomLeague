<?php defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" id="adminForm">
	<div class="col50">
		<?php
		$idxPanel=1;
		echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DETAILS'), 'panel'.$idxPanel++);
		echo $this->loadTemplate('details');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PICTURE'), 'panel'.$idxPanel++);
		echo $this->loadTemplate('picture');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DESCRIPTION'), 'panel'.$idxPanel++);
		echo $this->loadTemplate('description');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_EXTENDED'), 'panel'.$idxPanel++);
		echo $this->loadTemplate('extended');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_FRONTEND'), 'panel'.$idxPanel++);
		echo $this->loadTemplate('frontend');

		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_ASSIGN'), 'panel'.$idxPanel++);
		echo $this->loadTemplate('assign');

		echo JHtml::_('tabs.end');
		?>
	</div>
	<input type="hidden" name="assignperson" value="0" id="assignperson" />
	<input type="hidden" name="option" value="com_joomleague" /> 
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" /> 
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>
