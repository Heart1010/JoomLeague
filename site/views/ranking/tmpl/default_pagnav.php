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
<!-- matchdays pageNav -->
<br />
<table width="96%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php
			if (!empty($this->rounds))
			{
				$pageNavigation  = "<div class='pagenav'>";
				$pageNavigation .= JoomleaguePagination::pagenav($this->project);
				$pageNavigation .= "</div>";
				echo $pageNavigation;
			}
			?>
		</td>
	</tr>
</table>
<!-- matchdays pageNav END -->