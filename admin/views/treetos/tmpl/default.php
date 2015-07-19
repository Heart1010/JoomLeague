<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @todo
 * - check include of JS
 */
defined('_JEXEC') or die;
//$version = urlencode(JoomleagueHelper::getVersion());
//JHtml::script('JL_matchdetailsediting.js?v='.$version,'administrator/components/com_joomleague/assets/js/');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
<div id="editcell">
	<fieldset class="adminform">
		<legend><?php echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_TREETOS_TITLE','<i>','<i>'.$this->projectws->name.'</i>'); ?></legend>
		
			<?php
			$colspan= 11;
			?>
			<table class="adminlist table table-striped">
				<thead>
					<tr>
						<th width="5" style="vertical-align: top; "><?php echo count($this->items).'/'.$this->pagination->total; ?></th>
						<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="20" style="vertical-align: top; ">&nbsp;</th>
						<th width="20" style="vertical-align: top; ">&nbsp;</th>
						<th class="title" nowrap="nowrap" style="vertical-align: top; "><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_NAME'); ?></th>
						<?php
						if ($this->projectws->project_type == 'DIVISIONS_LEAGUE')
						{
							$colspan++;
							?><th class="title" style="vertical-align:top; " nowrap="nowrap">
								<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_TREETO_DIVISION');
									echo '<br>'.JHtml::_(	'select.genericlist',
														$this->lists['divisions'],
														'division',
														'class="inputbox" size="1" onchange="window.location.href=window.location.href.split(\'&division=\')[0]+\'&division=\'+this.value"',
														'value','text', $this->division);
								?>
							</th><?php
						}
						?>
						<th class="title" nowrap="nowrap" style="vertical-align: top; "><?php echo JTEXT::_('COM_JOOMLEAGUE_ADMIN_TREETOS_DEPTH'); ?></th>
			
						<th style="text-align: center; vertical-align: top; "><?php echo JTEXT::_('COM_JOOMLEAGUE_ADMIN_TREETOS_HIDE'); ?></th>
						<th width="1%" nowrap="nowrap" style="vertical-align: top; "><?php echo JTEXT::_('COM_JOOMLEAGUE_GLOBAL_PUBLISHED'); ?></th>
						<th class="title" nowrap="nowrap" style="vertical-align: top; "><?php echo JTEXT::_('COM_JOOMLEAGUE_GLOBAL_ID'); ?></th>
					</tr>
				</thead>
				<tfoot><tr><td colspan="<?php echo $colspan; ?>"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
				<tbody>
					<?php
					
					$n = count($this->items);
					foreach ($this->items as $i => $row) :
						$checked	= JHtml::_('grid.checkedout',$row,$i,'id');
						$published	= JHtml::_('grid.published',$row,$i,'tick.png','publish_x.png','treeto.');
					?>
						<tr class="row<?php echo $i % 2; ?>">
							<td style="text-align:center; ">
								<?php
								echo $this->pagination->getRowOffset($i);
								?>
							</td>
							<td style="text-align:center; ">
								<?php
								echo $checked;
								?>
							</td>
							<td style="text-align:center;">
								<a
									href="index.php?option=com_joomleague&task=treeto.edit&cid[]=<?php echo $row->id; ?>">
									<?php
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
													JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_EDIT_DETAILS'),'title= "' .
													JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_EDIT_DETAILS').'"');
									?>
								</a>
							</td>
							<td style="text-align:center;">
								<?php
								if($row->leafed==0)
								{ ?>
								<a
									href="index.php?option=com_joomleague&task=treeto.genNode&cid[]=<?php echo $row->id; ?>">
								<?php
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/update.png',
													JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_GENERATE'),'title= "' .
													JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_GENERATE').'"');
									?>
								</a><?php
								}
								else
								{ ?>
								<a
									href="index.php?option=com_joomleague&view=treetonodes&task=treetonode.display&tid[]=<?php echo $row->id; ?>">
									<?php
									echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/icon-16-Tree.png',
													JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_EDIT_TREE'),'title= "' .
													JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_EDIT_TREE').'"');
									?>
								</a>
								<?php
								}
								?>
							</td>
							<td style="text-align:center; ">
								<?php
								echo $row->name;
								?>
							</td>
							<?php
							if ($this->projectws->project_type == 'DIVISIONS_LEAGUE')
							{
								?>
								<td nowrap="nowrap" style="text-align:center;">
									<?php
									$append='';
									if ($row->division_id == 0)
									{
										$append=' style="background-color:#bbffff"';
									}
									echo JHtml::_(	'select.genericlist',
													$this->lists['divisions'],
													'division_id'.$row->id,
													$append.'class="inputbox" size="1" onchange="document.getElementById(\'cb' .
													$i.'\').checked=true"'.$append,
													'value','text',$row->division_id);
									?>
								</td>
								<?php
							}
							?>
							<td style="text-align:center; ">
								<?php
								echo $row->tree_i;
								?>
							</td>
					
							<td style="text-align:center; ">
								<?php
								echo $row->hide;
								?>
							</td>
							<td style="text-align:center; ">
								<?php
								echo $published;
								?>
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
	</fieldset>
</div>
			<!-- Input fields -->
			<input type="hidden" name="project_id" value="<?php echo $this->projectws->id; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="view" value="treetos" />
			<input type="hidden" name="task" value="treeto.display" />
			<?php echo JHtml::_('form.token'); ?>
		</form>


