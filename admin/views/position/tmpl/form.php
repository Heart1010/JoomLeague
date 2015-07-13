<?php defined('_JEXEC') or die('Restricted access');
$uri = JFactory::getURI();
?>
<!-- import the functions to move the events between selection lists  -->
<?php 
$version = urlencode(JoomleagueHelper::getVersion());
echo JHtml::script('eventsediting.js?v='.$version,'administrator/components/com_joomleague/assets/js/'); ?>
<form action="index.php" method="post" id="adminForm">
	<div class="col50">
<?php
echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DETAILS'), 'panel1');
echo $this->loadTemplate('details');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_EVENTS'), 'panel2');
echo $this->loadTemplate('events');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_STATISTICS'), 'panel3');
echo $this->loadTemplate('statistics');

echo JHtml::_('tabs.end');
?>
		<div class="clr"></div>
		<input type="hidden" name="eventschanges_check" id="eventschanges_check" value="0" />
		<input type="hidden" name="statschanges_check" id="statschanges_check" value="0" />
		<input type="hidden" name="option" value="com_joomleague" />
		<input type="hidden" name="cid[]" value="<?php echo $this->position->id; ?>" />
		<input type="hidden" name="task" value="" />
	</div>
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>