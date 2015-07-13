<?php defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" id="adminForm">
<div class="col50">
<?php
$p=1;
echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DETAILS'), 'panel'.$p++);
echo $this->loadTemplate('details');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PICTURE'), 'panel'.$p++);
echo $this->loadTemplate('picture');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DESCRIPTION'), 'panel'.$p++);
echo $this->loadTemplate('description');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_EXTENDED'), 'panel'.$p++);
echo $this->loadTemplate('extended');

if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague') ||
	JFactory::getUser()->authorise('core.admin', 'com_joomleague.club')) {
	echo JHtml::_('tabs.panel',JText::_('JCONFIG_PERMISSIONS_LABEL'), 'panel'.$p++);
	echo $this->loadTemplate('permissions');
}

echo JHtml::_('tabs.end');
?>
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_joomleague" />
	<input type="hidden" name="hidemainmenu" value="<?php echo JRequest::getVar('hidemainmenu', 0); ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" />
    <input type="hidden" name="task" value="" />	
</div>
	<?php echo JHtml::_('form.token'); ?>
</form>