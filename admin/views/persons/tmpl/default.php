<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
?>
<script>
function searchPerson(val)
{
	var f = $('adminForm');
	if(f)
	{
		f.elements['search'].value=val;
		f.elements['search_mode'].value= 'matchfirst';
		f.submit();
	}
}

function onupdatebirthday(cal)
{
	$($(cal.params.inputField).getProperty('cb')).setProperty('checked','checked');
}



jQuery('.typeahead').typeahead({
    source: function (query, process) {
        return jQuery.get('/typeahead', { query: query }, function (data) {
            return process(data.options);
        });
    }
});
</script>

<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">

<!-- Typeahead -->


<!-- Filter -->
<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button class="btn hasTooltip" onclick="this.form.submit();"><span class="icon-search"></span></button>
		<button class="btn hasTooltip" onclick="document.getElementById('search').value='';this.form.submit();"><span class="icon-remove"></span></button>
	</div>
	<div class="btn-wrapper pull-right">
		<div style="max-width: 700px; overflow: auto; float: right">
			<?php
			$startRange = hexdec($this->component_params->get('character_filter_start_hex', '0041'));
			$endRange = hexdec($this->component_params->get('character_filter_end_hex', '005A'));
			for ($i=$startRange; $i <= $endRange; $i++)
			{
				printf("<a href=\"javascript:searchPerson('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;",chr($i),chr($i));
			}
			?>
		</div>
	</div>
</div>

<!-- Rows -->
		<table class="table table-striped persons" id="articleList">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM'); ?></th>
					<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="20">&nbsp;</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PERSONS_F_NAME','pl.firstname',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PERSONS_N_NAME','pl.nickname',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PERSONS_L_NAME','pl.lastname',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_IMAGE'); ?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PERSONS_BIRTHDAY','pl.birthday',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PERSONS_NATIONALITY','pl.country',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PERSONS_POSITION','pl.position_id',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
					<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_PUBLISHED','pl.published',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="nowrap">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','pl.id',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan='12'><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
			<tbody>
				<?php
				$n = count($this->items);
				foreach ($this->items as $i => $row) :
					if (($row->firstname != '!Unknown') && ($row->lastname != '!Player')) // Ghostplayer for match-events
					{
						$link       = JRoute::_('index.php?option=com_joomleague&task=person.edit&cid[]='.$row->id);
						$checked    = JHtml::_('grid.checkedout',$row,$i);
						$is_checked = JLTable::_isCheckedOut($this->user->get('id'),$row->checked_out);
                        $published  = JHtml::_('grid.published',$row,$i, 'tick.png','publish_x.png','person.');
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
							<td class="center"><?php echo $checked; ?></td>
							<?php
							if ($is_checked)
							{
								$inputappend=' disabled="disabled" ';
								?><td class="center">&nbsp;</td><?php
							}
							else
							{
								$inputappend='';
								?>
								<td class="center">
									<a href="<?php echo $link; ?>">
										<?php
										$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_EDIT_DETAILS');
										echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
														$imageTitle,'title= "'.$imageTitle.'"');
										?>
									</a>
								</td>
								<?php
							}
							?>
							<td class="center">
								<input	<?php echo $inputappend; ?> type="text" size="15"
										class="input-medium" name="firstname<?php echo $row->id; ?>"
										value="<?php echo stripslashes(htmlspecialchars($row->firstname)); ?>"
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input	<?php echo $inputappend; ?> type="text" size="15"
										class="input-medium" name="nickname<?php echo $row->id; ?>"
										value="<?php echo stripslashes(htmlspecialchars($row->nickname)); ?>"
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input	<?php echo $inputappend; ?> type="text" size="15"
										class="input-medium" name="lastname<?php echo $row->id; ?>"
										value="<?php echo stripslashes(htmlspecialchars($row->lastname)); ?>"
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center image">
								<?php
								if (empty($row->picture) || !JFile::exists(JPATH_SITE.'/'.$row->picture))
								{
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_NO_IMAGE').$row->picture;
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/delete.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}
								elseif ($row->picture == JoomleagueHelper::getDefaultPlaceholder("player"))
								{
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_DEFAULT_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}
								else
								{
									if (JFile::exists(JPATH_SITE.'/'.$row->picture)) {
										$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMS_CUSTOM_IMAGE');
										echo JHtml::_('image','administrator/components/com_joomleague/assets/images/ok.png',$imageTitle,'title= "'.$imageTitle.'"');
										
									/*
									$playerName = JoomleagueHelper::formatName(null ,$row->firstname, $row->nickname, $row->lastname, 0);
									echo JoomleagueHelper::getPictureThumb($row->picture, $playerName, 0, 21, 4);
									*/
								}
								}
								?>
							</td>
							<td class="nowrap" class="center">
								<?php
								$append='style="float: left; margin: 5px 5px 5px 0;"';
								if ($row->birthday == '0000-00-00')
								{
									$date = '';	
									$append='style="background-color:#FFCCCC; float: left; margin: 5px 5px 5px 0;"';
								} else {
									$date = JHtml::date( $row->birthday, 'Y-m-d', true);
								}
								
								if ($is_checked)
								{
									echo $row->birthday;
								}
								else
								{
									echo $this->calendar(	$date,
															'birthday'.$row->id,
															'birthday'.$row->id,	
															'%Y-%m-%d',
															'size="10" '.$append.' cb="cb'.$i.'"',
															'onupdatebirthday',
															$i);
								}
								?>
							</td>
							<td class="nowrap" class="center">
								<?php
								$append='';
								if (empty($row->country)){$append=' background-color:#FFCCCC;';}
								echo JHtmlSelect::genericlist(	$this->lists['nation'],
																'country'.$row->id,
																$inputappend.' class="inputbox" style="width:140px; '.$append.'" onchange="document.getElementById(\'cb'.$i.'\').checked=true"',
																'value',
																'text',
																$row->country);
								?>
							</td>
							<td class="nowrap" class="center">
								<?php
								$append='';
								if (empty($row->position_id)){$append=' background-color:#FFCCCC;';}
								echo JHtmlSelect::genericlist(	$this->lists['positions'],
																'position'.$row->id,
																$inputappend.'class="inputbox" style="width:140px; '.$append.'" onchange="document.getElementById(\'cb'.$i.'\').checked=true"',
																'value',
																'text',
																$row->position_id);
								?>
							</td>
							<td class="center"><?php echo $published; ?></td>
							<td class="center"><?php echo $row->id; ?></td>
						</tr>
						<?php
					}
					endforeach;
				?>
			</tbody>
		</table>

	
	<!-- Input fields -->
	<input type="hidden" name="search_mode" value="<?php echo $this->lists['search_mode'];?>" id="search_mode" />
	<input type="hidden" name="task" value="person.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>
