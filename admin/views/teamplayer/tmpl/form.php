<?php defined('_JEXEC') or die('Restricted access');?>
<!-- import the functions to move the events between selection lists	-->
<?php
$version = urlencode(JoomleagueHelper::getVersion());
echo JHtml::script( 'JL_eventsediting.js?v='.$version, 'administrator/components/com_joomleague/assets/js/' );
?>
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

		<input type="hidden" name="eventschanges_check"	id="eventschanges_check" value="0" /> 
		<input type="hidden" name="option" value="com_joomleague" /> 
		<input type="hidden" name="team_id" value="<?php echo $this->teamws->team_id; ?>" /> 
		<input type="hidden" name="cid[]" value="<?php echo $this->project_player->id; ?>" /> 
		<input type="hidden" name="task" value="" />
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
