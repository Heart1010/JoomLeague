<?php 
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	<fieldset class="form-horizontal">
		<legend>
			<?php
			echo JText::sprintf(	'COM_JOOMLEAGUE_ADMIN_DIVS_TITLE2',
									'<i>' . $this->projectws->name . '</i>' );
			?>
		</legend>
		
<div class="clearfix">
	<div class="btn-wrapper input-append pull-left">
		<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_FILTER' ); ?>:
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button class="btn hasTooltip" onclick="this.form.submit();"><span class="icon-search"></span></button>
		<button class="btn hasTooltip" onclick="document.getElementById('search').value='';this.form.submit();"><span class="icon-remove"></span></button>
	</div>
	<div class="btn-wrapper pull-right">
		<?php echo $this->lists['state']; ?>
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
						<th>
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_ADMIN_DIVS_NAME', 'dv.name', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th>
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_ADMIN_DIVS_S_NAME', 'dv.shortname', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th>
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_ADMIN_DIVS_PARENT_NAME', 'parent_name', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
						<th width="10%">
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_GLOBAL_ORDER', 'dv.ordering', $this->lists['order_Dir'], $this->lists['order'] );
							echo '<br />';
							echo JHtml::_('grid.order',$this->items, 'filesave.png', 'division.saveorder');
							?>
						</th>
						<th width="5%">
							<?php
							echo JHtml::_( 'grid.sort', 'COM_JOOMLEAGUE_GLOBAL_ID', 'dv.id', $this->lists['order_Dir'], $this->lists['order'] );
							?>
						</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<td colspan="8">
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
						$link 		= JRoute::_( 'index.php?option=com_joomleague&task=division.edit&cid[]=' . $row->id );
						$checked 	= JHtml::_( 'grid.checkedout',   $row, $i );
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<td style="text-align:center; ">
								<?php echo $this->pagination->getRowOffset( $i ); ?>
							</td>
							<td style="text-align:center; ">
								<?php echo $checked; ?>
							</td>
							<?php
							if ( JLTable::_isCheckedOut( $this->user->get( 'id' ), $row->checked_out ) )
							{
								$inputappend = ' disabled="disabled"';
								?>
								<td style="text-align:center; ">
									&nbsp;
								</td>
								<?php
							}
							else
							{
								$inputappend = '';
								?>
								<td style="text-align:center; ">
									<a href="<?php echo $link; ?>">
										<?php
										$imageTitle = JText::_( 'COM_JOOMLEAGUE_ADMIN_DIVS_EDIT_DETAILS' );
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
								<?php
								echo $row->name;
								?>
							</td>
							<td>
								<?php
								echo $row->shortname;
								?>
							</td>
							<td>
								<?php
								echo $row->parent_name;
								?>
							</td>
							<td class="order">
								<span>
									<?php
									echo $this->pagination->orderUpIcon( $i, $i > 0 , 'division.orderup', 'COM_JOOMLEAGUE_GLOBAL_ORDER_UP', true );
									?>
								</span>
								<span>
									<?php
									echo $this->pagination->orderDownIcon( $i, $n, $i < $n, 'division.orderdown', 'COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN', true );
									$disabled = true ?  '' : 'disabled="disabled"';
									?>
								</span>
								<input  type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled; ?>
										class="text_area" style="text-align: center" />
							</td>
							<td style="text-align:center; ">
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

	<input type="hidden" name="task"				value="" />
	<input type="hidden" name="boxchecked"			value="0" />
	<input type="hidden" name="filter_order"		value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir"	value="" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
