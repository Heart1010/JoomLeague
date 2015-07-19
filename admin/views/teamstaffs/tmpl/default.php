<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'ppl.ordering');

$this->addTemplatePath(JPATH_COMPONENT.'/views/joomleague');
?>
<script>
	var quickaddsearchurl = '<?php echo JUri::root();?>administrator/index.php?option=com_joomleague&task=quickadd.searchstaff&projectteam_id=<?php echo $this->teamws->id; ?>';

	function searchTeamStaff(val)
	{
		var f = $('adminForm');
		if(f)
		{
			f.elements['search'].value = val;
			f.elements['search_mode'].value = 'matchfirst';
			f.submit();
		}
	}
</script>
<?php
$uri = JUri::root();
?>
<fieldset class="form-horizontal">
	<legend>
	<?php
	echo JText::_("COM_JOOMLEAGUE_ADMIN_TEAMSTAFFS_QUICKADD_STAFF");
	?>
	</legend>
	<form id="quickaddForm" action="<?php echo JUri::root(); ?>administrator/index.php?option=com_joomleague&task=quickadd.addstaff" method="post">
	<input type="hidden" name="projectteam_id" id="projectteam_id" value="<?php echo $this->teamws->id; ?>" />
	<input type="hidden" id="cpersonid" name="cpersonid" value="">
	<table>
		<tr>
			<td><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFFS_QUICKADD_DESCR');?>:</td>
			<td><input type="text" name="quickadd" id="quickadd" size="50" /></td>
			<td><input type="submit" name="submit" id="submit" value="<?php echo JText::_('Add');?>" /></td>
		</tr>
	</table>
	<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</fieldset>

<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	<fieldset class="form-horizontal">
		<legend>
			<?php
			echo JText::sprintf(	'COM_JOOMLEAGUE_ADMIN_TSTAFFS_TITLE2',
									'<i>' . $this->teamws->name . '</i>', '<i>' . $this->projectws->name . '</i>' );
			?>
		</legend>

<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.getElementById('search_mode').value='';document.adminForm.submit();" />
		<button class="btn hasTooltip" onclick="document.getElementById('search_mode').value='';this.form.submit();"><span class="icon-search"></span></button>
		<button class="btn hasTooltip" onclick="document.getElementById('search').value='';document.getElementById('search_mode').value='';this.form.submit();"><span class="icon-remove"></span></button>
	</div>
	<div class="btn-wrapper pull-right">
	<?php
		for ($i = 65; $i < 91; $i++){
			printf( "<a href=\"javascript:searchTeamStaff('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;", chr($i), chr($i));
		}
	?>
	</div>
</div>		
		
		<div id="editcell">
			<table class="adminlist table table-striped">
				<thead>
					<tr>
						<th width="5" >
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_GLOBAL_NUM' );
							?>
						</th>
						<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="20" >
							&nbsp;
						</th>
						<th class="title" class="nowrap" >
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_NAME', 'ppl.lastname', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th width="20" >
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_PID' );
							?>
						</th>
						<th class="title" class="nowrap" >
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_IMAGE' );
							?>
						</th>
						<th class="title" class="nowrap" >
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_POS', 'ppl.project_position_id', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th class="title" class="nowrap" >
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_STATUS' );
							?>
						</th>
						<th class="title" class="nowrap">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_PUBLISHED','ts.published',$this->lists['order_Dir'],$this->lists['order']);
						?></th>
						<th width="10%" >
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_GLOBAL_ORDER', 'ppl.ordering', $this->lists['order_Dir'], $this->lists['order'] );
							echo '<br />';
							echo JHtml::_('grid.order',$this->items, 'filesave.png', 'teamstaff.saveorder');
							?>
						</th>
						<th width="5%" >
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_GLOBAL_ID', 'ppl.id', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="12">
							<?php
							echo $this->pagination->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$n = count($this->items);
					foreach ($this->items as $i => $row) :
						#echo '<pre>'; print_r($row); echo '</pre>';
						$link = JRoute::_(	'index.php?option=com_joomleague&task=teamstaff.edit&team=' .
											$this->teamws->id . '&cid[]=' . $row->id );
						$checked = JHtml::_( 'grid.checkedout', $row, $i );
						$inputappend = '';

						?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center">
								<?php
								echo $this->pagination->getRowOffset( $i );
								?>
							</td>
							<td class="center">
								<?php
								echo $checked;
								?>
							</td>
							<?php
							if ( JLTable::_isCheckedOut( $this->user->get ('id'), $row->checked_out ) )
							{
								$inputappend = ' disabled="disabled"';
								?>
								<td>
									&nbsp;
								</td>
								<?php
							}
							else
							{
								?>
								<td class="center">
									<a href="<?php echo $link; ?>">
										<?php
										$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_EDIT_DETAILS' );
										echo JHtml::_(	'image', 'administrator/components/com_joomleague/assets/images/edit.png',
														$imageTitle,
														'title= "' . $imageTitle . '"' );
										?>
									</a>
								</td>
								<?php
							}
							?>
							<td>
								<?php echo JoomleagueHelper::formatName(null, $row->firstname, $row->nickname, $row->lastname, 0) ?>
							</td>
							<td class="center">
								<?php
								echo $row->person_id;
								?>
							</td>
							<td class="center">
								<?php
								if ( $row->picture == '' )
								{
									$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_NO_IMAGE' );
									echo JHtml::_(	'image',
													'administrator/components/com_joomleague/assets/images/delete.png',
													$imageTitle,
													'title= "' . $imageTitle . '"' );

								}
								elseif ( $row->picture == JoomleagueHelper::getDefaultPlaceholder("player"))
								{
										$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_DEFAULT_IMAGE' );
										echo JHtml::_(	'image',
														'administrator/components/com_joomleague/assets/images/information.png',
														$imageTitle,
														'title= "' . $imageTitle . '"' );
								}
								elseif ( $row->picture == !'')
								{
									$playerName = JoomleagueHelper::formatName(null ,$row->firstname, $row->nickname, $row->lastname, 0);
									echo JoomleagueHelper::getPictureThumb($row->picture, $playerName, 0, 21, 4);
								}
								?>
							</td>
							<td class="nowrap" class="center">
								<?php
								if ( $row->project_position_id != 0 )
								{
									$selectedvalue = $row->project_position_id;
									$append = '';
								}
								else
								{
									$selectedvalue = 0;
									$append = '';
								}

								if ( $append != '' )
								{
									?>
									<script language="javascript">document.getElementById('cb<?php echo $i; ?>').checked=true;</script>
									<?php
								}

								if ( $row->project_position_id == 0 )
								{
									$append=' style="background-color:#FFCCCC"';
								}

								echo JHtml::_( 'select.genericlist', $this->lists['project_position_id'], 'project_position_id' . $row->id, $inputappend . 'class="inputbox" size="1" onchange="document.getElementById(\'cb' . $i . '\').checked=true"' . $append, 'value', 'text', $selectedvalue );
								?>
							</td>

							<td class="nowrap" class="center">
								<?php
								//$row->injury = 1;
								//$row->suspension = 1;
								//$row->away = 1;
								if ( $row->injury > 0 )
								{
									$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_INJURED' );
									echo JHtml::_(	'image', 'administrator/components/com_joomleague/assets/images/injured.gif',
													$imageTitle,
													'title= "' . $imageTitle . '"' );
								}
								if ( $row->suspension > 0 )
								{
									$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_SUSPENDED' );
									echo JHtml::_(	'image', 'administrator/components/com_joomleague/assets/images/suspension.gif',
													$imageTitle,
													'title= "' . $imageTitle . '"' );
								}
								if ( $row->away > 0 )
								{
									$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_AWAY' );
									echo JHtml::_(	'image', 'administrator/components/com_joomleague/assets/images/away.gif',
													$imageTitle,
													'title= "' . $imageTitle . '"' );
								}
								?>
								&nbsp;
							</td>
							<td class="center">
								<?php
								echo JHtml::_('grid.published',$row,$i, 'tick.png','publish_x.png','teamstaff.');
								?>
							</td>
							<td class="order">
								<span>
									<?php
									echo $this->pagination->orderUpIcon( $i, $i > 0, 'teamstaff.orderup', 'COM_JOOMLEAGUE_GLOBAL_ORDER_UP', true );
									?>
								</span>
								<span>
									<?php
									echo $this->pagination->orderDownIcon( $i, $n, $i < $n, 'teamstaff.orderdown', 'COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN', true );
									?>
								</span>
								<?php
								$disabled = true ?	'' : 'disabled="disabled"';
								?>
								<input	type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?>
										class="text_area" style="text-align: center; " />
							</td>
							<td class="center">
								<?php
								echo $row->id;
								?>
							</td>
						</tr>
						<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</fieldset>
	<input type="hidden" name="team" value="<?php echo $this->teamws->id; ?>" />
	<input type="hidden" name="search_mode" value="<?php echo $this->lists['search_mode'];?>" id="search_mode" />
	<input type="hidden" name="task" value="teamstaff.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>