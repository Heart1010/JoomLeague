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
<!-- import the functions to move the events between selection lists	-->
<?php
$version = urlencode(JoomleagueHelper::getVersion());
echo JHtml::script('JL_eventsediting.js?v='.$version,'administrator/components/com_joomleague/assets/js/');
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
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
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_DESCRIPTION', true));
	echo $this->loadTemplate('description');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_EXTENDED', true));
	echo $this->loadTemplate('training');
	echo JHtml::_('bootstrap.endTab');
	
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_EXTENDED', true));
	echo $this->loadTemplate('extended');
	echo JHtml::_('bootstrap.endTab');
	
	if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague') || 
		JFactory::getUser()->authorise('core.admin', 'com_joomleague.project.'.(int)$this->projectws->id)) {
		echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('JCONFIG_PERMISSIONS_LABEL', true));
		echo $this->loadTemplate('permissions');
		echo JHtml::_('bootstrap.endTab');
	}
	
	echo JHtml::_('bootstrap.endTabSet');
	?>
		<div class="clr"></div>
		<input type="hidden" name="eventschanges_check"	value="0"	id="eventschanges_check" />
		<input type="hidden" name="option"				value="com_joomleague" />
		<input type="hidden" name="cid[]"				value="<?php echo $this->project_team->id; ?>" />
		<input type="hidden" name="project_id"			value="<?php echo $this->projectws->id; ?>" />
		<input type="hidden" name="task"				value="" id='task'/>
	</div>
	<?php echo JHtml::_('form.token'); ?>
</form>