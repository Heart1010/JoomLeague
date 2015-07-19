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
<form action="index.php" method="post" id="adminForm" name="adminForm" class="form-validate">
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
	echo $this->loadTemplate('extended');
	echo JHtml::_('bootstrap.endTab');
	
	if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague') || JFactory::getUser()->authorise('core.admin', 'com_joomleague.club')) {
		echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('JCONFIG_PERMISSIONS_LABEL', true));
		echo $this->loadTemplate('permissions');
		echo JHtml::_('bootstrap.endTab');
	}

	echo JHtml::_('bootstrap.endTabSet');
?>
	<div class="clr"></div>
	<input type="hidden" name="option" value="com_joomleague" />
	<input type="hidden" name="hidemainmenu" value="<?php echo JRequest::getVar('hidemainmenu', 0); ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" />
    <input type="hidden" name="task" value="" />	
</div>
	<?php echo JHtml::_('form.token'); ?>
</form>