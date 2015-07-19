<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
JHtml::_( 'behavior.tooltip' );

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
<form method="post" name="statsform" id="statsform">
	<div id="jlstatsform">
	<fieldset>
		<div class="fltrt">
			<button type="button" onclick="Joomla.submitform('match.savestats', this.form);">
				<?php echo JText::_('JAPPLY');?></button>
			<button type="button" onclick="$('close').value=1; Joomla.submitform('match.savestats', this.form);">
				<?php echo JText::_('JSAVE');?></button>
			<button id="cancel" type="button" onclick="<?php echo JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : '';?>  window.parent.SqueezeBox.close();">
				<?php echo JText::_('JCANCEL');?></button>
		</div>
		<div class="configuration" >
			Stats
		</div>
	</fieldset>
	<div class="clear"></div>
		<?php
		echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
		echo JHtml::_('tabs.panel',JText::_($this->teams->team1), 'panel1');
		echo $this->loadTemplate('home');
		
		echo JHtml::_('tabs.panel',JText::_($this->teams->team2), 'panel2');
		echo $this->loadTemplate('away');
		
		echo JHtml::_('tabs.end');
		?>
		
		<input type="hidden" name="option" value="com_joomleague" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="close" id="close" value="0" />
		<input type="hidden" name="task" id="match.savestats" value="" />
		<input type="hidden" name="project_id"	value="<?php echo $this->match->project_id; ?>" />
		<input type="hidden" name="match_id"	value="<?php echo $this->match->id; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>
<div style="clear: both"></div>
