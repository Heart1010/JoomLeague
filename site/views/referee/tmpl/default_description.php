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
<!-- Person description START -->
<?php
	$description = "";
	if ($this->referee)
	{
		if ( $this->referee->prnotes != '' )
		{
			$description = $this->referee->prnotes;
		}
		elseif ( $this->referee->notes != '' )
		{
			$description = $this->referee->notes;
		}
	}

	if ( $description != '' )
	{
		?>
		<h2><?php echo JText::_('COM_JOOMLEAGUE_PERSON_INFO'); ?></h2>
		<table width="96%" align="center" border="0" cellpadding="0" cellspacing="0">
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