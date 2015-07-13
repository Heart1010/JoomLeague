<?php
/**
* @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
/**
 * EditeventsBB view
 *
 * @package	Joomleague
 * @since 0.1
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_( 'behavior.tooltip' );
JHtml::_( 'behavior.modal' );
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