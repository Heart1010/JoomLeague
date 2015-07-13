<?php defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" name="adminForm" id="match-form" class="form-validate">
	<div class="col50">
		<?php
echo JHtml::_('tabs.start','tabs', array('useCookie'=>1));
echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_DETAILS'), 'panel1');
echo $this->loadTemplate('details');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PICTURE'), 'panel2');
echo $this->loadTemplate('picture');
		
if ($this->edit):
echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_PARAMETERS'), 'panel3');
echo $this->loadTemplate('param');

echo JHtml::_('tabs.panel',JText::_('COM_JOOMLEAGUE_TABS_GENERAL_PARAMETERS'), 'panel4');
echo $this->loadTemplate('gparam');		
endif;		
		
echo JHtml::_('tabs.end');
		?>	

	</div>

	<div class="clr"></div>
	<?php if ($this->edit): ?>
		<input type="hidden" name="calculated" value="<?php echo $this->calculated; ?>" />
	<?php endif; ?>
	<input type="hidden" name="option" value="com_joomleague" />
	<input type="hidden" name="cid[]" value="<?php echo $this->form->getValue('id'); ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>