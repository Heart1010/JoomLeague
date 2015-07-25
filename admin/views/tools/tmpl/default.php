<?php
/**
 * Joomleague
*
* @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.joomleague.at
*/
defined('_JEXEC') or die;


?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
<div style="height:40px;">


</div>
<table class="table tools table-hover">
	<thead>
		<tr>
			<th class="center">Select</th>
			<th>Table</th>
			<th class="center">CSV</th>
			<th class="center">SQL</th>
			<th class="center">Truncate</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$n = count($this->tables);
		foreach ($this->tables as $i => $row) :
	?>
		<tr>
			<td class="center"><?php echo JHtml::_('grid.id', $i, $row); ?></td>
			<td><?php echo $row; ?></td>
			<td class="center">
				<a class="exportcsv" id="csvexport" onclick="return listItemTask(<?php echo $i; ?>,'tools.exporttablecsv')" href="javascript:void(0)"><?php echo JHtml::_('image', 'com_joomleague/export_excel.png',null, NULL, true); ?></a>
			</td>
			<td class="center">
				<a class="exportsql" id="sqlexport" onclick="return listItemTask('<?php echo $i; ?>','tools.exporttablesql')" href="javascript:void(0)"><?php echo JHtml::_('image', 'com_joomleague/sql.png', null, NULL, true); ?></a>
			</td>
			<td class="center">
				<a class="truncate" id="truncate" onclick="return listItemTask('<?php echo $i; ?>','tools.truncate')" href="javascript:void(0)"><?php echo JHtml::_('image', 'com_joomleague/truncate.png', null, NULL, true); ?></a>
			</td>
		</tr>
	
	<?php endforeach; ?>
	</tbody>
	<tfoot><tr><td colspan="11"></td></tr></tfoot>
</table>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
