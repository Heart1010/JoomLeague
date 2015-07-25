<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * A piece of com_finder was taken for the quickadd
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
JHtml::_('behavior.tooltip');
$app = JFactory::getApplication();

//Ordering allowed ?
$ordering=($this->lists['order'] == 't.name');

//load navigation menu
$this->addTemplatePath(JPATH_COMPONENT.'/views/joomleague');

?>
<script>
function searchTeam(val,key)
{
	var f= $('adminForm');
	if(f) {
		f.elements['search'].value=val;
		f.elements['search_mode'].value= 'matchfirst';
		f.submit();
	}
}

var quickaddsearchurl = '<?php echo JUri::root();?>administrator/index.php?option=com_joomleague&task=quickadd.searchteam&project_id=<?php echo $this->projectws->id; ?>';
</script>
<?php 
$script = "
jQuery(document).ready(function() {
	var value, searchword = jQuery('#quickadd');

		// Set the input value if not already set.
		if (!searchword.val())
		{
			searchword.val('" . JText::_('Search', true) . "');
		}

		// Get the current value.
		value = searchword.val();

		// If the current value equals the default value, clear it.
		searchword.on('focus', function()
		{	var el = jQuery(this);
			if (el.val() === '" . JText::_('Search', true) . "')
			{
				el.val('');
			}
		});

		// If the current value is empty, set the previous value.
		searchword.on('blur', function()
		{	var el = jQuery(this);
			if (!el.val())
			{
				el.val(value);
			}
		});

		jQuery('#quickaddForm').on('submit', function(e){
			e.stopPropagation();
		});";


/*
 * @todo Change text // 24-07-2015
 * At the moment only passing a name is showing results
 * This segment of code sets up the autocompleter.
 */
	JHtml::_('script', 'media/jui/js/jquery.autocomplete.min.js', false, false, false, false, true);

	$script .= "
	var suggest = jQuery('#quickadd').autocomplete({
		serviceUrl: '" . JRoute::_('index.php?option=com_joomleague&task=quickadd.searchteam&project_id='.$this->projectws->id, false) . "',		
		paramName: 'q',
		minChars: 1,
		maxHeight: 400,
		width: 300,
		zIndex: 9999,
		deferRequestBy: 500
	});";

$script .= "});";

JFactory::getDocument()->addScriptDeclaration($script);
?>

<fieldset class="form-horizontal">
	<legend>
	<?php
	echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_QUICKADD_TEAM');
	?>
	</legend>
	<form id="quickaddForm" action="<?php echo JRoute::_(JUri::root().'administrator/index.php?option=com_joomleague&task=quickadd.addteam'); ?>" method="post">
		<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_QUICKADD_DESCR'); ?>
		<div class="clearfix"></div>
		<div class="btn-wrapper input-append pull-left">
			<input type="text" name="p" id="quickadd" size="50" value="<?php htmlspecialchars(JFactory::getApplication()->input->get('q', '', 'string')); ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_ADD');?>" />
		</div>
		
		<input type="hidden" name="project_id" id="project_id" value="<?php echo $this->projectws->id; ?>" />
		<input type="hidden" id="cteamid" name="cteamid" value="">
		<?php echo JHtml::_('form.token')."\n"; ?>
	</form>
</fieldset>

<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	<div id="editcell">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_LEGEND','<i>'.$this->projectws->name.'</i>'); ?></legend>
			<?php $cell_count=22; ?>
			<table class="adminlist table table-striped">
				<thead>
					<tr>
						<th width="5"><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM'); ?></th>
						<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="20">&nbsp;</th>
						<th>
							<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_TEAMNAME','t.name',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
						<th colspan="2"><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_MANAGE_PERSONNEL'); ?></th>
						<?php
						if ($this->projectws->project_type == 'DIVISIONS_LEAGUE')
						{
							$cell_count++;
							?><th>
								<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_DIVISION','d.name',$this->lists['order_Dir'],$this->lists['order']);
									echo '<br>'.JHtml::_(	'select.genericlist',
														$this->lists['divisions'],
														'division',
														'class="inputbox" size="1" onchange="window.location.href=window.location.href.split(\'&division=\')[0]+\'&division=\'+this.value"',
														'value','text', $this->division);
								?>
							</th><?php
						}
						?>
						<th>
							<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_PICTURE','pt.picture',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_INITIAL_POINTS'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_MA'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_PLUS_P'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_MINUS_P'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_W'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_D'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_L'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_HG'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_GG'); ?></th>
						<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_DG'); ?></th>
						<th width="10%">
							<?php
							echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ORDER','pt.ordering',$this->lists['order_Dir'],$this->lists['order']);
							echo JHtml::_('grid.order',$this->projectteam, 'filesave.png', 'projectteam.saveorder');
							?>
						</th>
						<th width="1%">
							<?php echo JHtml::_('grid.sort','TID','team_id',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
						<th width="1%">
							<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','pt.id',$this->lists['order_Dir'],$this->lists['order']); ?>
						</th>
					</tr>
				</thead>
				<tfoot><tr><td colspan="<?php echo $cell_count; ?>"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
				<tbody>
					<?php
					$n = count($this->projectteam);
					foreach ($this->projectteam as $i => $row) :
						$link1=JRoute::_('index.php?option=com_joomleague&task=projectteam.edit&cid[]='.$row->id);
						$link2=JRoute::_('index.php?option=com_joomleague&task=teamplayer.select&project_team_id='.$row->id."&team_id=".$row->team_id.'&pid[]='.$this->projectws->id);
						$link3=JRoute::_('index.php?option=com_joomleague&task=teamstaff.select&project_team_id='.$row->id."&team_id=".$row->team_id.'&pid[]='.$this->projectws->id);
						$checked=JHtml::_('grid.checkedout',$row,$i);
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
							<td class="center"><?php echo $checked;?></td>
							<?php
							if (JLTable::_isCheckedOut($this->user->get('id'),$row->checked_out))
							{
								$inputappend=' disabled="disabled"';
								?><td class="center">&nbsp;</td><?php
							}
							else
							{
								$inputappend='';
								?>
								<td style="text-align:center; "><?php
									$imageFile='administrator/components/com_joomleague/assets/images/edit.png';
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_EDIT_DETAILS');
									$imageParams='title= "'.$imageTitle.'"';
									$image=JHtml::image($imageFile,$imageTitle,$imageParams);
									$linkParams='';
									echo JHtml::link($link1,$image);
									?></td>
								<?php
							}
							?>
							<td><?php echo $row->name; ?></td>
							<td class="center"><?php
								if($row->playercount==0) {
									$image = "players_add.png";
								} else {
									$image = "players_edit.png";
								}
								$imageFile='administrator/components/com_joomleague/assets/images/'.$image;
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_MANAGE_PLAYERS');
								$imageParams='title= "'.$imageTitle.'"';
								$image=JHtml::image($imageFile,$imageTitle,$imageParams).' <sub>'.$row->playercount.'</sub>';
								$linkParams='';
								echo JHtml::link($link2,$image);
								?></td>
							<td class="center"><?php
								if($row->staffcount==0) {
									$image = "players_add.png";
								} else {
									$image = "players_edit.png";
								}
								$imageFile='administrator/components/com_joomleague/assets/images/'.$image;
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_MANAGE_STAFF');
								$imageParams='title= "'.$imageTitle.'"';
								$image=JHtml::image($imageFile,$imageTitle,$imageParams).' <sub>'.$row->staffcount.'</sub>';
								$linkParams='';
								echo JHtml::link($link3,$image);
								?></td>
							<?php
							if ($this->projectws->project_type == 'DIVISIONS_LEAGUE')
							{
								?>
								<td class="nowrap" class="center">
									<?php
									$append='';
									if ($row->division_id == 0)
									{
										$append=' style="background-color:#bbffff"';
									}
									echo JHtml::_(	'select.genericlist',
													$this->lists['divisions'],
													'division_id'.$row->id,
													$inputappend.'class="inputbox" size="1" onchange="document.getElementById(\'cb' .
													$i.'\').checked=true"'.$append,
													'value','text',$row->division_id);
									?>
								</td>
								<?php
							}
							?>
							<td class="center">
								<?php
								if (empty($row->picture) || !JFile::exists(JPATH_SITE.'/'.$row->picture))
								{
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_NO_IMAGE').$row->picture;
									echo JHtml::image(	'administrator/components/com_joomleague/assets/images/delete.png',
														$imageTitle,'title= "'.$imageTitle.'"');
								}
								elseif ($row->picture == JoomleagueHelper::getDefaultPlaceholder("team"))
								{
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_DEFAULT_IMAGE');
									echo JHtml::image('administrator/components/com_joomleague/assets/images/information.png',
														$imageTitle,'title= "'.$imageTitle.'"');
								}
								else
								{
									if (JFile::exists(JPATH_SITE.'/'.$row->picture)) {
										$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMS_CUSTOM_IMAGE');
										echo JHtml::_('image','administrator/components/com_joomleague/assets/images/ok.png',$imageTitle,'title= "'.$imageTitle.'"');
									
									}
									/*
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_CUSTOM_IMAGE');
									$imageParams=array();
									$imageParams['title']=$imageTitle ;
									$imageParams['height']=30;
									//$imageParams['width'] =40;
									echo JHtml::image($row->picture,$imageTitle,$imageParams);
									*/
								}
								?>
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="1" class="inputbox"
																	name="start_points<?php echo $row->id; ?>"
																	value="<?php echo $row->start_points; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="matches_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->matches_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="points_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->points_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="neg_points_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->neg_points_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="won_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->won_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="draws_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->draws_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="lost_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->lost_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="homegoals_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->homegoals_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="guestgoals_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->guestgoals_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="inputbox"
																	name="diffgoals_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->diffgoals_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="order">
								<span>
									<?php
									echo $this->pagination->orderUpIcon($i,$i > 0,'projectteam.orderup','COM_JOOMLEAGUE_GLOBAL_ORDER_UP', 'pt.ordering');
									?>
								</span>
								<span>
									<?php
									echo $this->pagination->orderDownIcon($i,$n,$i < $n,'projectteam.orderdown','COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN', 'pt.ordering');
									?>
									<?php
									$disabled=true ? '' : 'disabled="disabled"';
									?>
								</span>
								<input	type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?>
										class="text_area" style="text-align: center" />
							</td>
							<td class="center" width="5%">
								<?php 
									$team_edit_link = JRoute::_('index.php?option=com_joomleague&task=team.edit&cid[]=' . $row->team_id );
								?>
								<a href="<?php echo $team_edit_link ?>">
									<?php echo $row->team_id; ?>
								</a>
							</td>
							<td class="center" width="5%"><?php echo $row->id; ?></td>
						</tr>
						<?php endforeach; ?>
				</tbody>

			</table>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="projectteam.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="search_mode" value="<?php echo $this->lists['search_mode']; ?>" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>
