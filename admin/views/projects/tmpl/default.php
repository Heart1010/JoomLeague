<?php 
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">

<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_LIST_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button class="btn hasTooltip" onclick="this.form.submit();"><span class="icon-search"></span></button>
		<button class="btn hasTooltip" onclick="document.getElementById('search').value='';this.form.submit();"><span class="icon-remove"></span></button>
	</div>
	<div class="btn-wrapper pull-right">
		<?php echo $this->lists['sportstypes'].'&nbsp;&nbsp;'; ?>
		<?php echo $this->lists['leagues'].'&nbsp;&nbsp;'; ?>
		<?php echo $this->lists['seasons'].'&nbsp;&nbsp;'; ?>
		<?php echo $this->lists['state']; ?>
	</div>
</div>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="1%"><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM'); ?></th>
					<th width="5%" class="title">
						<input  type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th width="20">&nbsp;</th>
					<th class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTS_NAME_OF_PROJECT','p.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTS_LEAGUE','l.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTS_SEASON','s.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTS_SPORTSTYPE','st.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTS_PROJECTTYPE','p.project_type',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th width="5%" class="title">
						<?php
						echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_GAMES');
						?>
					</th>
					<th width="5%" class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PROJECTS_IS_UTC_CONVERTED','p.is_utc_converted',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th width="5%" class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_PUBLISHED','p.published',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th width="10%" class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ORDER','p.ordering',$this->lists['order_Dir'],$this->lists['order']);
						echo JHtml::_('grid.order', $this->items, 'filesave.png', 'project.saveorder');
						?>
					</th>
					<th width="5%" class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','p.id',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan='13'><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
			<tbody>
				<?php
				$n = count($this->items);
				foreach ($this->items as $i => $row) :

					$link=JRoute::_('index.php?option=com_joomleague&task=project.edit&cid[]='.$row->id);
					$link2=JRoute::_('index.php?option=com_joomleague&view=projects&task=project.display&&cid[]='.$row->id);
					$link2panel=JRoute::_('index.php?option=com_joomleague&task=joomleague.workspace&layout=panel&pid[]='.$row->id.'&stid[]='.$row->sports_type_id);

					$checked    = JHtml::_('grid.checkedout',$row,$i);
					//$published  = JHtml::_('jgrid.published',$row,$i,'tick.png','publish_x.png','project.');
					
					if($row->is_utc_converted) {
						$img = 'tick.png';
						$alt = JText::_('COM_JOOMLEAGUE_ADMIN_PROJECT_ALREADY_CONVERTED');
						$is_utc_converted = JHtml::_('image', 'admin/' . $img, $alt, array('title'=>$alt), true);
					} else {
						$img = 'publish_x.png';
						$alt = JText::_('COM_JOOMLEAGUE_ADMIN_PROJECT_NOT_CONVERTED');
						$is_utc_converted = JHtml::_('image', 'admin/' . $img, $alt, array('title'=>$alt), true);
					}
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td width="5%" class="center"><?php echo $checked; ?></td>
						<?php
						
						if (JLTable::_isCheckedOut($this->user->get ('id'),$row->checked_out))
						{
							$inputappend=' disabled="disabled"';
							?><td class="center">&nbsp;</td><?php
						}
						else
						{
							$inputappend='';
							?>
							<td class="center">
								<a href="<?php echo $link; ?>">
									<?php
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_EDIT_DETAILS');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
													$imageTitle, 'title= "'.$imageTitle.'"');
									?>
								</a>
							</td>
							<?php
						}
						?>
						<td>
							<?php
							if (JLTable::_isCheckedOut($this->user->get('id'),$row->checked_out))
							{
								echo $row->name;
							}
							else
							{
								?><a href="<?php echo $link2panel; ?>"><?php echo $row->name; ?></a><?php
							}
							?>
						</td>
						<td><?php echo $row->league; ?></td>
						<td class="center"><?php echo $row->season; ?></td>
						<td class="center"><?php echo JText::_($row->sportstype); ?></td>
						<td class="center"><?php echo JText::_('COM_JOOMLEAGUE_'.$row->project_type); ?></td>
						<td class="center">
							<?php if ($row->current_round): ?>
								<?php
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_GAMES_DETAILS');
								$image = JHtml::_('image','administrator/components/com_joomleague/assets/images/icon-16-Matchdays.png',
												$imageTitle, 'title= "'.$imageTitle.'"');
							
								echo JHtml::link('index.php?option=com_joomleague&view=matches&task=match.display&pid[]='.$row->id.'&rid[]='. $row->current_round, $image); ?>
							<?php endif; ?>
						</td>
						<td class="center"><?php echo $is_utc_converted; ?></td>
						<td class="center">
							<?php echo JHtml::_('jgrid.published', $row->published, $i, 'project.');?>
						</td>
						<td class="order">
							<span><?php echo $this->pagination->orderUpIcon($i,$i > 0 ,'project.orderup','COM_JOOMLEAGUE_GLOBAL_ORDER_UP',true); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i,$n,$i < $n,'project.orderdown','COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN',true); ?></span>
							<?php $disabled=true ?  '' : 'disabled="disabled"';	?>
							<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
						</td>
						<td class="center"><?php echo $row->id; ?></td>
					</tr>
					<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php 
		//Load the batch processing form.
		echo $this->loadTemplate('batch'); 
	?>
	<input type="hidden" name="task"				value="" />
	<input type="hidden" name="boxchecked"			value="0" />
	<input type="hidden" name="filter_order"		value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir"	value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
