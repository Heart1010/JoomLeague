<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<form action="index.php" method="post" id="adminForm" name="adminForm">
	<div class="col50">
	<?php
	$p=1;
	echo JHtml::_('bootstrap.startTabSet', 'tabs', array('active' => 'panel1'));
	echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, JText::_('COM_JOOMLEAGUE_TABS_DETAILS', true));
	echo $this->loadTemplate('details');
	echo JHtml::_('bootstrap.endTab');
	echo JHtml::_('bootstrap.endTabSet');
	?>
	<div class="clr"></div>
	<input type="hidden" name="option"	value="com_joomleague" />
	<input type="hidden" name="cid[]" value="<?php echo $this->treeto->id; ?>" />
	<input type="hidden" name="task"	value="" />
	<?php echo JHtml::_('form.token')."\n"; ?>
	</div>
</form>