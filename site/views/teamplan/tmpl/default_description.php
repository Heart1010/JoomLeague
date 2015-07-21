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

<?php
// Show projectteam-description if defined.
if ( !isset ( $this->projectteam->projectteam_notes ) )
{
	$description  = "";
}
else
{
	$description  = $this->projectteam->projectteam_notes;
}

if( trim( $description  != "" ) )
{
	?>
	<br />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr class="sectiontableheader">
			<th>
				<?php
				echo JText::_( 'COM_JOOMLEAGUE_TEAMPLAN_TEAMINFORMATION' );
				?>
			</th>
		</tr>
	</table>

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<?php
				$description = JHTML::_('content.prepare', $description);
				echo stripslashes( $description );
				?>
			</td>
		</tr>
	</table>
<?php
}
?>
<br />