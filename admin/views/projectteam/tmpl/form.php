<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
?>
<!-- import the functions to move the events between selection lists	-->
<?php
$version = urlencode(JoomleagueHelper::getVersion());
echo JHtml::script('JL_eventsediting.js?v='.$version,'administrator/components/com_joomleague/assets/js/');
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
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
		<?php echo JHtml::_('form.token'); ?>
</form>