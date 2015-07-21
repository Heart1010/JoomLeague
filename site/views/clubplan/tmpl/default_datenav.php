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
<form name="adminForm" id="adminForm" method="post">
	<?php $dateformat="%d-%m-%Y"; ?>
	
<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php
		echo JHtml::calendar(JoomleagueHelper::convertDate($this->startdate,1),'startdate','startdate',$dateformat,array('class'=>'input-small'));
		echo ' - '.JHtml::calendar(JoomleagueHelper::convertDate($this->enddate,1),'enddate','enddate',$dateformat,array('class'=>'input-small'));
		?>
		<input type="submit" class="button btn" name="reload View" value="<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER'); ?>" />
	</div>
	<div class="btn-wrapper input-append pull-right">
	<?php
		if($this->club)
		{
			$picture=$this->club->logo_middle;
			echo JoomleagueHelper::getPictureThumb($picture, 
			$this->club->name,50,50,2);
		}
	?>
	</div>
</div>
	<?php echo JHtml::_('form.token')."\n"; ?>
</form><br />