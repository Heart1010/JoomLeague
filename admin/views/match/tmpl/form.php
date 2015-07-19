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
<div id="matchdetails">
	<form method="post" id="adminForm">
		<!-- Score Table START -->
		<?php
		//save and close 
		$close = JRequest::getInt('close',0);
		if($close == 1) {
			?><script>
			window.addEvent('domready', function() {
				$('cancel').onclick();	
			});
			</script>
			<?php 
		}
		?>
			<fieldset>
				<div class="fltrt">
					<button type="button" onclick="Joomla.submitform('match.savedetails');">
						<?php echo JText::_('JAPPLY');?></button>
					<button type="button" onclick="$('close').value=1; Joomla.submitform('match.savedetails');">
						<?php echo JText::_('JSAVE');?></button>
					<button id="cancel" type="button" onclick="<?php echo JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : '';?>  window.parent.SqueezeBox.close();">
						<?php echo JText::_('JCANCEL');?></button>
				</div>
				<div class="configuration" >
					<?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_MATCH_F_TITLE',$this->match->hometeam,$this->match->awayteam); ?>
				</div>
			</fieldset>
		<?php
		// focus matchreport tab when the match was already played
		$startOffset = 0;
		if (!empty($this->match->match_date))
		{
			$now = new DateTime('now', new DateTimeZone($this->match->timezone));
			$matchStart = new DateTime($this->match->match_date->toSql(), new DateTimeZone($this->match->timezone));
			if ($matchStart < $now)
			{
				$startOffset = 4;
			}
		}
		echo JHtml::_('tabs.start','tabs', array('startOffset'=>$startOffset));
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_MATCHPREVIEW'), 'panel1');
		echo $this->loadTemplate('matchpreview');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_MATCHDETAILS'), 'panel2');
		echo $this->loadTemplate('matchdetails');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_SCOREDETAILS'), 'panel3');
		echo $this->loadTemplate('scoredetails');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_ALTDECISION'), 'panel4');
		echo $this->loadTemplate('altdecision');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_MATCHREPORT'), 'panel5');
		echo $this->loadTemplate('matchreport');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_MATCHRELATION'), 'panel6');
		echo $this->loadTemplate('matchrelation');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_EXTENDED'), 'panel7');
		echo $this->loadTemplate('matchextended');
		
		if(	JFactory::getUser()->authorise('core.admin', 'com_joomleague') ||
			JFactory::getUser()->authorise('core.admin', 'com_joomleague.project.' . (int) $this->project->id) ||
			JFactory::getUser()->authorise('core.admin', 'com_joomleague.match'.(int) $this->match->id)) {
			echo JHtml::_('tabs.panel',JText::_('JCONFIG_PERMISSIONS_LABEL'), 'panel8');
			echo $this->loadTemplate('permissions');
		}
		
		echo JHtml::_('tabs.end');
		
		?>
		<!-- Additional Details Table END -->
		<div class="clr"></div>
		<input type="hidden" name="option" value="com_joomleague"/>
		<input type="hidden" name="task" value="match.savedetails"/>
		<input type="hidden" name="close" id="close" value="0"/>
		<input type="hidden" name="cid[]" value="<?php echo $this->match->id; ?>"/>
		<?php echo JHtml::_('form.token')."\n"; ?>
	</form>
</div>