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
$ordering=($this->lists['order'] == 'v.ordering');

JHtml::_('behavior.tooltip');
?>
<script>
function searchPlayground(val,key) 
{
	var f=$('adminForm');

	if(f)
	{
		f.elements['search'].value=val;
		f.elements['search_mode'].value= 'matchfirst';
		f.submit();
	}
}
</script>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">

<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button class="btn hasTooltip" onclick="this.form.submit();"><span class="icon-search"></span></button>
		<button class="btn hasTooltip" onclick="document.getElementById('search').value='';this.form.submit();"><span class="icon-remove"></span></button>
	</div>
	<div class="btn-wrapper pull-left">
		<?php echo '&nbsp;&nbsp;'.$this->pagination->getLimitBox(); ?>
	</div>
	<div class="btn-wrapper pull-right">
		<div style="max-width: 700px; overflow: auto; float: right">
			<?php
			$startRange = hexdec($this->component_params->get('character_filter_start_hex', '0041'));
			$endRange = hexdec($this->component_params->get('character_filter_end_hex', '005A'));
			for ($i=$startRange; $i <= $endRange; $i++)
			{
				printf("<a href=\"javascript:searchPlayground('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;",chr($i),chr($i));
			}
			?>
		</div>
	</div>
</div>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM'); ?></th>
					<th width="1%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="20">&nbsp;</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_NAME','v.name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_S_NAME','v.short_name',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_CLUBNAME','club',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_CAPACITY','v.max_visitors',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_IMAGE','v.picture',$this->lists['order_Dir'],$this->lists['order']);
						?>
					</th>
					<th width="10%">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ORDER','v.ordering',$this->lists['order_Dir'],$this->lists['order']);
						echo JHtml::_('grid.order',$this->items, 'filesave.png', 'playground.saveorder');
						?>
					</th>
					<th width="5%">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','v.id',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan="10"></td></tr></tfoot>
			<tbody>
				<?php
				$n = count($this->items);
				foreach ($this->items as $i => $row) :
					$link=JRoute::_('index.php?option=com_joomleague&task=playground.edit&cid[]='.$row->id);
					$checked=JHtml::_('grid.checkedout',$row,$i);
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td class="center"><?php echo $checked; ?></td>
						<?php
						if (JLTable::_isCheckedOut($this->user->get('id'),$row->checked_out))
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
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_EDIT_DETAILS');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
													$imageTitle,'title= "'.$imageTitle.'"');
									?>
								</a>
							</td>
							<?php
						}
						?>
						<td><?php echo $row->name; ?></td>
						<td class="center"><?php echo $row->short_name; ?></td>
						<td><?php echo $row->club; ?></td>
						<td class="center"><?php echo $row->max_visitors; ?></td>
						<td width="5%" class="center">
							<?php
							if ($row->picture == '')
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_NO_IMAGE');
								echo JHtml::_('image','administrator/components/com_joomleague/assets/images/delete.png',
												$imageTitle,'title= "'.$imageTitle.'"');

							}
							elseif($row->picture == JoomleagueHelper::getDefaultPlaceholder("team"))
								{
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_DEFAULT_IMAGE');
									echo JHtml::_(	'image','/administrator/components/com_joomleague/assets/images/information.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}
								elseif($row->picture !== '')
									{
										$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_CUSTOM_IMAGE');
										echo JHtml::_('image','administrator/components/com_joomleague/assets/images/ok.png',
														$imageTitle,'title= "'.$imageTitle.'"');
									}
									?>
						</td>
						<td class="order">
							<span>
								<?php echo $this->pagination->orderUpIcon($i,$i > 0,'playground.orderup','COM_JOOMLEAGUE_GLOBAL_ORDER_UP',true); ?>
							</span>
							<span>
								<?php echo $this->pagination->orderDownIcon($i,$n,$i < $n,'playground.orderdown','COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN',true); ?>
								<?php $disabled=true ?  '' : 'disabled="disabled"'; ?>
							</span>
							<input  type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?>
									class="text_area" style="text-align: center" />
						</td>
						<td class="center"><?php echo $row->id; ?></td>
					</tr>
					<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->pagination->getListFooter(); ?>
	</div>

	<input type="hidden" name="search_mode" value="<?php echo $this->lists['search_mode']; ?>" />
	<input type="hidden" name="task" value="playground.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>