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
	<form method="post" id="adminForm" name="adminForm">
		<?php
		$option		= JRequest::getCmd('option');
		$params		= JComponentHelper::getParams( $option );
		$model		= $this->getModel();
		if(!empty($this->teams)) {
			
			$p=1;
			echo JHtml::_('bootstrap.startTabSet', 'tabs', array('active' => 'panel1'));
			echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, $this->teams->team1);
				$teamname	= $this->teams->team1;
				$this->_handlePreFillRoster($this->teams, $model, $params, $this->teams->projectteam1_id, $teamname);
				echo $this->loadTemplate('home');
			echo JHtml::_('bootstrap.endTab');
			
			echo JHtml::_('bootstrap.addTab', 'tabs', 'panel'.$p++, $this->teams->team2);
				$teamname = $this->teams->team2;
				$this->_handlePreFillRoster($this->teams, $model, $params, $this->teams->projectteam2_id, $teamname);
				echo $this->loadTemplate('away');
			echo JHtml::_('bootstrap.endTab');
			
			echo JHtml::_('bootstrap.endTabSet');
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