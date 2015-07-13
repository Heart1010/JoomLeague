<?php defined('_JEXEC') or die('Restricted access');
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
</script>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm">
	<div style="width: 100%;">
		<div style="float: left;">
				<?php
				echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER');
				?>&nbsp;<input	type="text" name="search" id="search"
								value="<?php echo $this->lists['search']; ?>"
								class="text_area" onchange="$('adminForm').submit(); " />
				<button onclick="this.form.submit(); "><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_GO'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit(); ">
					<?php
					echo JText::_('COM_JOOMLEAGUE_GLOBAL_RESET');
					?>
				</button>
		</div>
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
	<div style="clear: both;"></div>
	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM'); ?></th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
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
				$k=0;
				for ($i=0,$n=count($this->items); $i < $n; $i++)
				{
					$row=&$this->items[$i];
					if (($row->firstname != '!Unknown') && ($row->lastname != '!Player')) // Ghostplayer for match-events
					{
						$link       = JRoute::_('index.php?option=com_joomleague&task=person.edit&cid[]='.$row->id);
						$checked    = JHtml::_('grid.checkedout',$row,$i);
						$is_checked = JLTable::_isCheckedOut($this->user->get('id'),$row->checked_out);
                        $published  = JHtml::_('grid.published',$row,$i, 'tick.png','publish_x.png','person.');
						?>
						<tr class="<?php echo "row$k"; ?>">
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
										class="inputbox" name="firstname<?php echo $row->id; ?>"
										value="<?php echo stripslashes(htmlspecialchars($row->firstname)); ?>"
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input	<?php echo $inputappend; ?> type="text" size="15"
										class="inputbox" name="nickname<?php echo $row->id; ?>"
										value="<?php echo stripslashes(htmlspecialchars($row->nickname)); ?>"
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input	<?php echo $inputappend; ?> type="text" size="15"
										class="inputbox" name="lastname<?php echo $row->id; ?>"
										value="<?php echo stripslashes(htmlspecialchars($row->lastname)); ?>"
										onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<?php
								if (empty($row->picture) || !JFile::exists(JPATH_SITE.DS.$row->picture))
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
									$playerName = JoomleagueHelper::formatName(null ,$row->firstname, $row->nickname, $row->lastname, 0);
									echo JoomleagueHelper::getPictureThumb($row->picture, $playerName, 0, 21, 4);
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
						$k=1 - $k;
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="search_mode" value="<?php echo $this->lists['search_mode'];?>" id="search_mode" />
	<input type="hidden" name="task" value="person.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>
