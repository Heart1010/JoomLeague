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

<div class="logo">
<?php 
	echo JHtml::_('image', 'com_joomleague/logo.png', null, NULL, true); 
?>
</div>
<div class="clearfix"></div>
<div class="componentheading label">
	<?php echo $this->pagetitle; ?>
</div>
<table class="about">
	<tr>
		<td><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_TEXT'); ?></td>
	</tr>
</table>
<br />
<div class="componentheading label">
	<?php echo JText::_('COM_JOOMLEAGUE_ABOUT_DETAILS'); ?>
</div>
<table class="about">
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_TRANSLATIONS'); ?></b></td>
		<td><?php echo $this->about->translations; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_REPOSITORY'); ?></b></td>
		<td><?php echo $this->about->repository; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_VERSION'); ?></b></td>
		<td><?php echo $this->about->version; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_AUTHOR'); ?></b></td>
		<td><?php echo $this->about->author; ?></td>
	</tr>

	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_WEBSITE'); ?></b></td>
		<td>
			<a href="<?php echo $this->about->page; ?>" target="_blank">
				<?php echo $this->about->page; ?>
			</a>
		</td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_SUPPORT_FORUM'); ?></b></td>
		<td>
			<a href="<?php echo $this->about->forum; ?>" target="_blank">
				<?php echo $this->about->forum; ?></a>
		</td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_BUGS'); ?></b></td>
		<td>
			<a href="<?php echo $this->about->bugs; ?>" target="_blank">
				<?php echo $this->about->bugs; ?></a>
		</td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_WIKI'); ?></b></td>
		<td>
			<a href="<?php echo $this->about->wiki; ?>" target="_blank">
				<?php echo $this->about->wiki; ?></a>
		</td>
	</tr>	
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_DEVELOPERS'); ?></b></td>
		<td><?php echo $this->about->developer; ?></td>
	</tr>
<!--
	<tr>
		<td><b><?php //echo JText::_('COM_JOOMLEAGUE_ABOUT_SUPPORTERS'); ?></b></td>
		<td><?php //echo $this->about->supporters; ?></td>
	</tr>
	<tr>
		<td><b><?php //echo JText::_('COM_JOOMLEAGUE_ABOUT_TRANSLATORS'); ?></b></td>
		<td><?php //echo $this->about->translator; ?></td>
	</tr>
-->
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_DESIGNER'); ?></b></td>
		<td><?php echo $this->about->designer; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_ICONS'); ?></b></td>
		<td><?php echo $this->about->icons; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_FLASH_STATISTICS'); ?></b></td>
		<td><?php echo $this->about->flash; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_PHPTHUMB'); ?></b></td>
		<td><?php echo $this->about->phpthumb; ?></td>
	</tr>	
<!--
	<tr>
		<td><b><?php //echo JText::_('COM_JOOMLEAGUE_ABOUT_GRAPHIC_LIBRARY'); ?></b></td>
		<td><?php //echo $this->about->graphic_library; ?></td>
	</tr>
-->
</table>
<br />
<div class="componentheading label">
	<?php echo JText::_('COM_JOOMLEAGUE_ABOUT_LICENSE'); ?>
</div>
<table class="about">
	<tr>
		<td><?php echo JText::_('COM_JOOMLEAGUE_ABOUT_LICENSE_TEXT'); ?></td>
	</tr>
</table>
<!-- backbutton -->
<?php
/*
if ($this->config['show_back_button'] > "0")
{
	echo $this->loadTemplate('backbutton');
}
?>
<!-- footer -->
<?php echo $this->loadTemplate('footer');
*/
?>