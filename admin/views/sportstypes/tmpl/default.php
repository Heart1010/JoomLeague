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
$ordering=($this->lists['order'] == 's.ordering');

JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">

<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button class="btn hasTooltip" onclick="this.form.submit();"><span class="icon-search"></span></button>
		<button class="btn hasTooltip" onclick="document.getElementById('search').value='';this.form.submit();"><span class="icon-remove"></span></button>
	</div>
</div>

<table class="table table-striped" id="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM'); ?></th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="20">&nbsp;</th>
					<th class="title">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_SPORTSTYPES_NAME','s.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th class="title">
						<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_SPORTSTYPES_TRANSLATION'); ?>
					</th>
					<th width="10%" class="title">
						<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_SPORTSTYPES_ICON'); ?>
					</th>
					<th width="10%">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ORDER','s.ordering',$this->lists['order_Dir'],$this->lists['order']);
						echo JHtml::_('grid.order',$this->items, 'filesave.png', 'sportstype.saveorder');
						?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','s.id',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
			<tbody>
				<?php
				$n = count($this->items);
				foreach ($this->items as $i => $row) :
					$link=JRoute::_('index.php?option=com_joomleague&task=sportstype.edit&cid[]='.$row->id);
					$checked=JHtml::_('grid.checkedout',$row,$i);
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td class="center"><?php echo $checked; ?></td>
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
							<td class="center">
								<a href="<?php echo $link; ?>">
									<?php
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_SPORTSTYPES_EDIT_DETAILS');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
													$imageTitle,'title= "'.$imageTitle.'"');
									?>
								</a>
							</td>
							<?php
						}
						?>
						<td><?php echo $row->name; ?></td>
						<td><?php if ($row->name != JText::_($row->name)){echo JText::_($row->name);} ?></td>
						<td class="center">
							<?php
							$picture=JPATH_SITE.'/'.$row->icon;
							$desc=JText::_($row->name);
							echo JoomleagueHelper::getPictureThumb($picture, $desc, 0, 21, 4);
							?>
						</td>
						<td class="order">
							<span>
								<?php echo $this->pagination->orderUpIcon($i,$i > 0,'sportstype.orderup','COM_JOOMLEAGUE_GLOBAL_ORDER_UP',$ordering); ?>
							</span>
							<span>
								<?php
								echo $this->pagination->orderDownIcon($i,$n,$i < $n,'sportstype.orderdown','COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN',$ordering);
								?>
								<?php $disabled=true ? '' : 'disabled="disabled"'; ?>
							</span>
							<input	type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?>
									class="text_area" style="text-align: center" />
						</td>
						<td class="center"><?php echo $row->id; ?></td>
					</tr>
					<?php endforeach; ?>
			</tbody>
		</table>
	<!-- input fiels -->
	<input type="hidden" name="task" value="sportstype.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>