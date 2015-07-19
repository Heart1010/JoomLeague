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
<form id="startingSquadsForm" name="startingSquadsForm" method="post">
	<fieldset>
		<div class="fltrt">
			<button type="button" onclick="Joomla.submitform('match.saveroster', this.form);">
				<?php echo JText::_('JAPPLY');?></button>
			<button type="button" onclick="$('close').value=1; Joomla.submitform('match.saveroster', this.form);">
				<?php echo JText::_('JSAVE');?></button>
			<button id="cancel" type="button" onclick="<?php echo JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : '';?>  window.parent.SqueezeBox.close();">
				<?php echo JText::_('JCANCEL');?></button>
		</div>
		<div class="configuration" >
			<?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_MATCH_ELU_TITLE',$this->teamname); ?>
		</div>
	</fieldset>
	<div class="clear"></div>
	<div id="lineup">
		<?php
		// focus on players tab 
		$startOffset = 1
		;
		echo JHtml::_('tabs.start','tabs', array('startOffset'=>$startOffset));
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_SUBST'), 'panel1');
		echo $this->loadTemplate('substitutions');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PLAYERS'), 'panel2');
		echo $this->loadTemplate('players');
		
		echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_STAFF'), 'panel3');
		echo $this->loadTemplate('staff');
		
		echo JHtml::_('tabs.end');
		?>
		<input type="hidden" name="task" value="match.saveroster" />
		<input type="hidden" name="view" value="match" />
		<input type="hidden" name="close" id="close" value="0" />
		<input type="hidden" name="cid[]" value="<?php echo $this->match->id; ?>" />
		<input type="hidden" name="changes_check" value="0" id="changes_check" />
		<input type="hidden" name="option" value="com_joomleague" id="option" />
		<input type="hidden" name="team" value="<?php echo $this->tid; ?>" id="team" />
		<input type="hidden" name="positionscount" value="<?php echo count($this->positions); ?>" id="positioncount"	/>
		<?php echo JHtml::_('form.token')."\n"; ?>
	</div>
</form>