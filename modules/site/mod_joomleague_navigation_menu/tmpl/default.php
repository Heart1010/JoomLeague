<?php
/**
 * Joomleague
 * @subpackage	Module-NavigationMenu
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
?>
<div id="jl-nav-module">
<div class="jl-nav-module<?php echo $params->get('moduleclass_sfx'); ?>">

<form method="post" action="<?php echo JUri::root();?>">

<ul class="nav-list">

	<?php if ($params->get('show_project_dropdown') == 'season') :?>
		<?php if ($seasonselect): ?>
		<li class="season-select"><?php echo $seasonselect; ?></li>
		<?php endif; ?>
		<?php if ($leagueselect): ?>
		<li class="league-select"><?php echo $leagueselect; ?></li>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php if ($params->get('show_project_dropdown')) :?>
		<?php if ($projectselect): ?>
		<li class="project-select"><?php echo $projectselect; ?></li>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php if ($params->get('show_division_dropdown')) :?>
		<?php if ($divisionselect): ?>
		<li class="division-select"><?php echo $divisionselect; ?></li>
		<?php endif; ?>
	<?php endif; ?>
	
		<?php if ($params->get('show_teams_dropdown') && $teamselect): ?>
	<li class="team-select">
		<?php if ($params->get('heading_teams_dropdown')): ?>
			<span class="label"><?php echo $params->get('heading_teams_dropdown'); ?></span><?php echo $teamselect; ?>
		<?php else: ?>
			<?php echo $teamselect; ?>
		<?php endif; ?>
	</li>
	<?php endif; ?>
	
	<?php if ($params->get('show_nav_links')): ?>
	
		<?php for ($i = 1; $i < 17; $i++): ?>
			<?php if ($params->get('navpoint'.$i) && $link = $helper->getLink($params->get('navpoint'.$i))): ?>
				<li class="nav-item"><?php echo JHtml::link(JRoute::_($link), $params->get('navpoint_label'.$i)); ?></li>
			<?php elseif ($params->get('navpoint'.$i) == "separator"): ?>
				<li class="nav-item separator"><?php echo $params->get('navpoint_label'.$i); ?></li>
			<?php endif; ?>
		<?php endfor; ?>		
		
	<?php endif; ?>
</ul>

<input type="hidden" name="option" value="com_joomleague" />
<input type="hidden" name="view" value="<?php echo $defaultview; ?>" />
<input type="hidden" name="teamview" value="<?php echo $params->get('link_teams_dropdown', 'roster'); ?>" />
<input type="hidden" name="o" value="<?php echo $params->get('project_ordering', 0); ?>" />
<input type="hidden" name="include_season" value="<?php echo $params->get('project_include_season_name', 0); ?>" />
<input type="hidden" name="itemid" value="<?php echo $defaultitemid; ?>" />
</form>

</div>
</div>