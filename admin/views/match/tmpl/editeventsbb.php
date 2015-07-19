<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<div id="gamesevents">
	<form method="post" id="adminForm">
		<?php
		$option		= JRequest::getCmd('option');
		$params		= JComponentHelper::getParams( $option );
		$model		= $this->getModel();
		if(!empty($this->teams)) {
			echo JHtml::_('tabs.start','tabs', array('useCookie'=>1, 'onclick'=>'alert(1)'));
			echo JHtml::_('tabs.panel', $this->teams->team1, 'panel1');
			$teamname	= $this->teams->team1;
			$this->_handlePreFillRoster($this->teams, $model, $params, $this->teams->projectteam1_id, $teamname);
			echo $this->loadTemplate('home');
			
			echo JHtml::_('tabs.panel', $this->teams->team2, 'panel2');
			$teamname = $this->teams->team2;
			$this->_handlePreFillRoster($this->teams, $model, $params, $this->teams->projectteam2_id, $teamname);
			echo $this->loadTemplate('away');
			echo JHtml::_('tabs.end');
		}
		?>
		<input type="hidden" name="task" value="match.saveeventbb" />
		<input type="hidden" name="view" value="match" />
		<input type="hidden" name="option" value="com_joomleague" id="option" />
		<input type="hidden" name="boxchecked"	value="0" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</div>
<div style="clear: both"></div>