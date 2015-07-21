<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */ 
?>
<!-- Person description START -->
<?php
	$description = "";
	if ( !empty($this->teamStaff->notes) )
	{
		echo "<!-- Person Description -->";
		$description = $this->teamStaff->notes;
	} else {
		if ( !empty($this->person->notes) )
		{
			echo "<!-- Team Staff Description -->";
			$description = $this->person->notes;
		}
	}

	if ( $description != '' )
	{
		?>
		<h2><?php echo JText::_('COM_JOOMLEAGUE_PERSON_INFO'); ?></h2>
		<table width="96%" align="center" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td>
					<?php
					$description = JHtml::_('content.prepare', $description);
					echo stripslashes( $description );
					?>
				</td>
			</tr>
		</table>
		<br /><br />
		<?php
	}
?>
<!-- Person description END -->