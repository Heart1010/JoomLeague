<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


if (JComponentHelper::getParams('com_joomleague')->get('show_footer',1))
{
?>
	<br />
		<div class="poweredby">
			<?php
			echo ' :: Powered by ';
			echo JHtml::link('http://www.joomleague.at','JoomLeague',array('target' => '_blank'));
			echo ' - ';
			echo JHtml::link('index.php?option=com_joomleague&amp;view=about',sprintf('Version %1$s',JoomleagueHelper::getVersion()));
			echo ' :: ';
			?>
		</div>
<?php
}
?>