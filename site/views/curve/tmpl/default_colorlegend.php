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
<!-- colors legend -->
<?php
if ($this->config['show_colorlegend'])
{
	?>
	<table width='96%' align='center' cellpadding='0' cellspacing='0'>
		<tr>
			<?php
			JoomleagueHelper::showColorsLegend($this->colors);
			?>
		</tr>
	</table>
	<br />
	<?php
}
?>