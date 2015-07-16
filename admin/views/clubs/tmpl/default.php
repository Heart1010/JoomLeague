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

//Ordering allowed ?
$ordering=($this->lists['order'] == 'a.ordering');

JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm">
	
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
				printf("<a href=\"javascript:searchClub('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;",chr($i),chr($i));
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
					<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
					<th width="50">&nbsp;</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_CLUBS_NAME_OF_CLUB','a.name',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
					<th>
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_CLUBS_WEBSITE','a.website',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
					<th width="20">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_CLUBS_L_LOGO','a.logo_big',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
					<th width="20">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_CLUBS_M_LOGO','a.logo_middle',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
					<th width="20">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_CLUBS_S_LOGO','a.logo_small',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
					<th width="20">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_CLUBS_COUNTRY','a.country',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
					<th width="10%">
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ORDER','a.ordering',$this->lists['order_Dir'],$this->lists['order']);
						echo JHtml::_('grid.order',$this->items, 'filesave.png', 'club.saveorder');
						?>
					</th>
					<th width="1%">
						<?php echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','a.id',$this->lists['order_Dir'],$this->lists['order']); ?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
			<tbody>
				<?php
				$n = count($this->items);
				foreach ($this->items as $i => $row) :
					$link=JRoute::_('index.php?option=com_joomleague&task=club.edit&cid[]='.$row->id);
					$link2=JRoute::_('index.php?option=com_joomleague&view=teams&task=team.display&cid='.$row->id);
					$checked= JHtml::_('grid.checkedout',$row,$i);
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
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_EDIT_DETAILS');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
													$imageTitle,'title= "'.$imageTitle.'"');
									?>
								</a>
                                <a href="<?php echo $link2; ?>">
									<?php
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_SHOW_TEAMS');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/icon-16-Teams.png',
													$imageTitle,'title= "'.$imageTitle.'"');
									?>
								</a>
							</td>
							<?php
						}
						?>
						<td><?php echo $row->name; ?></td>
						<td>
							<?php
							if ($row->website != ''){echo '<a href="'.$row->website.'" target="_blank">';}
							echo $row->website;
							if ($row->website != ''){echo '</a>';}
							?>
						</td>
						<td class="center">
							<?php
							if ($row->logo_big == '')
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_NO_IMAGE');
								echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');

							}
							elseif ($row->logo_big == JoomleagueHelper::getDefaultPlaceholder("clublogobig"))
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_DEFAULT_IMAGE');
								echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');
							} else {
								if (JFile::exists(JPATH_SITE.'/'.$row->logo_big)) {
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_CUSTOM_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/ok.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								} else {
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_NO_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/delete.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}
							}
							?>
						</td>
						<td class="center">
							<?php
							if ($row->logo_middle == '')
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_NO_IMAGE');
								echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');
							}
							elseif ($row->logo_middle == JoomleagueHelper::getDefaultPlaceholder("clublogomedium"))
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_DEFAULT_IMAGE');
								echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');
							} else {
								if (JFile::exists(JPATH_SITE.'/'.$row->logo_middle)) {
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_CUSTOM_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/ok.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								} else {
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_NO_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/delete.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}
							}
							?>
						</td>
						<td class="center">
							<?php
							if ($row->logo_small == '')
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_NO_IMAGE');
								echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');
							}
							elseif ($row->logo_small == JoomleagueHelper::getDefaultPlaceholder("clublogosmall"))
							{
								$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_DEFAULT_IMAGE');
								echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/information.png',
				  								$imageTitle,'title= "'.$imageTitle.'"');
							} else {
								if (JFile::exists(JPATH_SITE.'/'.$row->logo_small)) {
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_CUSTOM_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/ok.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								} else {
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_NO_IMAGE');
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/delete.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}
							}
							?>
						</td>
						<td class="center"><?php echo Countries::getCountryFlag($row->country); ?></td>
						<td class="order">
							<span>
								<?php echo $this->pagination->orderUpIcon($i,$i > 0 ,'club.orderup','COM_JOOMLEAGUE_GLOBAL_ORDER_UP',true); ?>
							</span>
							<span>
								<?php echo $this->pagination->orderDownIcon($i,$n,$i < $n,'club.orderdown','COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN',true);
								$disabled=true ?  '' : 'disabled="disabled"';
								?>
							</span>
							<input  type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled; ?>
									class="text_area" style="text-align: center" />
						</td>
						<td class="center"><?php echo $row->id; ?></td>
					</tr>
					<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="search_mode" value="<?php echo $this->lists['search_mode']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="club.display" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHtml::_('form.token')."\n"; ?>
</form>