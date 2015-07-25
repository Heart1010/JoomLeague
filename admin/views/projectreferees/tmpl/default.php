<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */ 
defined('_JEXEC') or die;
JHtml::_('behavior.framework');
?>
<style>
.search-item {
    font:normal 11px tahoma,arial,helvetica,sans-serif;
    padding:3px 10px 3px 10px;
    border:1px solid #fff;
    border-bottom:1px solid #eeeeee;
    white-space:normal;
    color:#555;
}
.search-item h3 {
    display:block;
    font:inherit;
    font-weight:bold;
    color:#222;
}

.search-item h3 span {
    float: right;
    font-weight:normal;
    margin:0 0 5px 5px;
    width:100px;
    display:block;
    clear:none;
}
</style>
<script>

var quickaddsearchurl = '<?php echo JUri::root();?>administrator/index.php?option=com_joomleague&task=quickadd.searchreferee';

function searchPlayer(val)
{
	var f = $('adminForm');
	if(f)
	{
		f.elements['search'].value=val;
		f.elements['search_mode'].value='matchfirst';
		f.submit();
	}
}
</script>
<?php 
$script = "
jQuery(document).ready(function() {
	var value, searchword = jQuery('#quickadd');

		// Set the input value if not already set.
		if (!searchword.val())
		{
			searchword.val('" . JText::_('Search', true) . "');
		}

		// Get the current value.
		value = searchword.val();

		// If the current value equals the default value, clear it.
		searchword.on('focus', function()
		{	var el = jQuery(this);
			if (el.val() === '" . JText::_('Search', true) . "')
			{
				el.val('');
			}
		});

		// If the current value is empty, set the previous value.
		searchword.on('blur', function()
		{	var el = jQuery(this);
			if (!el.val())
			{
				el.val(value);
			}
		});

		jQuery('#quickaddForm').on('submit', function(e){
			e.stopPropagation();
		});";


/*
 * @todo Change text // 24-07-2015
 * At the moment only passing a name is showing results
 * This segment of code sets up the autocompleter.
 */
	JHtml::_('script', 'media/jui/js/jquery.autocomplete.min.js', false, false, false, false, true);

	$script .= "
	var suggest = jQuery('#quickadd').autocomplete({
		serviceUrl: '" . JRoute::_('index.php?option=com_joomleague&task=quickadd.searchreferee&project_id='.$this->projectws->id, false) . "',		
		paramName: 'q',
		minChars: 1,
		maxHeight: 400,
		width: 300,
		zIndex: 9999,
		deferRequestBy: 500
	});";

$script .= "});";

JFactory::getDocument()->addScriptDeclaration($script);
?>

<?php
$uri=JUri::root();
?>
<fieldset class="form-horizontal">
	<legend><?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTREFEREES_QUICKADD_REFEREE');?></legend>
	
<form id="quickaddForm" action="<?php echo JUri::root(); ?>administrator/index.php?option=com_joomleague&task=quickadd.addreferee" method="post">
	<?php echo JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTREFEREES_QUICKADD_DESCR');?>
	<div class="clearfix"></div>
	<div class="btn-wrapper input-append pull-left">
		<input type="text" name="p" id="quickadd" size="50" value="<?php htmlspecialchars(JFactory::getApplication()->input->getString('q', '')); ?>" />
		<input class="btn" type="submit" name="submit" id="submit" value="<?php echo JText::_('COM_JOOMLEAGUE_GLOBAL_ADD');?>" />
		<input type="hidden" id="cpersonid" name="cpersonid" value="">
	</div>
	<?php echo JHtml::_('form.token'); ?>
</form>
</fieldset>

<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">
	<fieldset class="form-horizontal">
		<legend>
			<?php
			echo JText::sprintf('COM_JOOMLEAGUE_ADMIN_PREF_TITLE2','<i>'.$this->projectws->name.'</i>');
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
		for ($i=65; $i < 91; $i++) {
			printf("<a href=\"javascript:searchPlayer('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;",chr($i),chr($i));
		}
	?>
	</div>
</div>		
		
		<div id="editcell">
			<table class="adminlist table table-striped">
				<thead>
					<tr>
						<th width="5">
							<?php
							echo JText::_('COM_JOOMLEAGUE_GLOBAL_NUM');
							?>
						</th>
						<th width="1%" class="center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="20">
							&nbsp;
						</th>
						<th>
							<?php
							echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PREF_NAME','p.lastname',$this->lists['order_Dir'],$this->lists['order']);
							?>
						</th>
						<th>
							<?php
							echo JText::_('COM_JOOMLEAGUE_ADMIN_PREF_IMAGE');
							?>
						</th>
						<th>
							<?php
							echo JHtml::_('grid.sort','COM_JOOMLEAGUE_ADMIN_PREF_POS','pref.project_position_id',$this->lists['order_Dir'],$this->lists['order']);
							?>
						</th>
						<th>
						<?php
						echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_PUBLISHED','pref.published',$this->lists['order_Dir'],$this->lists['order']);
						?>
						</th>
						<th width="10%">
							<?php
							echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ORDER','pref.ordering',$this->lists['order_Dir'],$this->lists['order']);
							echo JHtml::_('grid.order',$this->items, 'filesave.png', 'projectreferee.saveorder');
							?>
						</th>
						<th width="5%">
							<?php
							echo JText::_('COM_JOOMLEAGUE_ADMIN_PREF_PID');
							?>
						</th>
						<th width="5%">
							<?php
							echo JHtml::_('grid.sort','COM_JOOMLEAGUE_GLOBAL_ID','p.id',$this->lists['order_Dir'],$this->lists['order']);
							?>
						</th>
					</tr>
				</thead>
				<tfoot><tr><td colspan='12'><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
				<tbody>
					<?php
					$n = count($this->items);
					foreach ($this->items as $i => $row) :
						$link			= JRoute::_('index.php?option=com_joomleague&task=projectreferee.edit&cid[]='.$row->id);
						$checked		= JHtml::_('grid.checkedout',$row,$i);
						$inputappend	= '';
						/* $published		= JHtml::_('grid.published',$row,$i, 'tick.png','publish_x.png','projectreferee.'); */
						$published 		= JHtml::_('jgrid.published', $row->published, $i, 'projectreferee.');
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="center">
								<?php
								echo $this->pagination->getRowOffset($i);
								?>
							</td>
							<td class="center">
								<?php
								echo $checked;
								?>
							</td>
							<?php
							if (JLTable::_isCheckedOut($this->user->get('id'),$row->checked_out))
							{
								$inputappend=' disabled="disabled"';
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
										$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PREF_EDIT_DETAILS');
										echo JHtml::_(	'image','administrator/components/com_joomleague/assets/images/edit.png',
														$imageTitle,
														'title= "'.$imageTitle.'"');
										?>
									</a>
								</td>
								<?php
							}
							?>
							<td>
								<?php 
								$option = JRequest::getCmd('option');
								$params = JComponentHelper::getParams( $option );
								$default_name_format = $params->get("name_format");
								echo JoomleagueHelper::formatName(null, $row->firstname, $row->nickname, $row->lastname, $default_name_format) ?>
							</td>
							<td class="center">
								<?php
								if ($row->picture == '')
								{
									$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PREF_NO_IMAGE');
									echo JHtml::_(	'image',
													'administrator/components/com_joomleague/assets/images/delete.png',
													$imageTitle,
													'title= "'.$imageTitle.'"');

								}
								elseif ($row->picture == JoomleagueHelper::getDefaultPlaceholder("player"))
								{
										$imageTitle=JText::_('COM_JOOMLEAGUE_ADMIN_PREF_DEFAULT_IMAGE');
										echo JHtml::_(	'image',
														'administrator/components/com_joomleague/assets/images/information.png',
														$imageTitle,
														'title= "'.$imageTitle.'"');
								}
								elseif ($row->picture == !'')
								{
									$playerName = JoomleagueHelper::formatName(null ,$row->firstname, $row->nickname, $row->lastname, $default_name_format);
									$picture=JPATH_SITE.'/'.$row->picture;
									echo JoomleagueHelper::getPictureThumb($picture, $playerName, 0, 21, 4);
								}
								?>
							</td>
							<td class="center">
								<?php
								if ($row->project_position_id != 0)
								{
									$selectedvalue=$row->project_position_id;
									$append='';
								}
								else
								{
									$selectedvalue=0;
									$append=' style="background-color:#FFCCCC"';
								}

								if ($append != '')
								{
									?>
									<script>document.getElementById('cb<?php echo $i; ?>').checked=true;</script>
									<?php
								}

								if ($row->project_position_id == 0)
								{
									$append=' style="background-color:#FFCCCC"';
								}

								echo JHtml::_('select.genericlist',
												$this->lists['project_position_id'],'project_position_id'.$row->id,
												$inputappend.'class="inputbox" size="1" onchange="document.getElementById(\'cb'.$i.'\').checked=true"'.$append,
												'value','text',$selectedvalue);
								?>
							</td>
							<td class="center">
								<?php
								echo $published;
								?>
							</td>
							<td class="order">
								<span>
									<?php
									echo $this->pagination->orderUpIcon($i,$i > 0,'projectreferee.orderup','COM_JOOMLEAGUE_GLOBAL_ORDER_UP',true);
									?>
								</span>
								<span>
									<?php
									echo $this->pagination->orderDownIcon($i,$n,$i < $n,'projectreferee.orderdown','COM_JOOMLEAGUE_GLOBAL_ORDER_DOWN',true);
									?>
								</span>
								<?php
								$disabled=true ?	'' : 'disabled="disabled"';
								?>
								<input	type="text" name="order[]" size="5"
										value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area"
										style="text-align: center; " />
							</td>
							<td class="center" width="5%">
								<?php 
									$person_edit_link = JRoute::_('index.php?option=com_joomleague&task=person.edit&cid[]=' . $row->person_id );
								?>
								<a href="<?php echo $person_edit_link ?>">
								<?php
								echo $row->person_id;
								?>
								</a>
							</td>
							<td class="center" width="5%"><?php echo $row->id; ?></td>
						</tr>
						<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</fieldset>
	<input type="hidden" name="search_mode"			value="<?php echo $this->lists['search_mode'];?>" id="search_mode" />
	<input type="hidden" name="task"				value="projectreferee.display" />
	<input type="hidden" name="boxchecked"			value="0" />
	<input type="hidden" name="filter_order"		value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir"	value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>