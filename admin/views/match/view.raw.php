<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * HTML-RAW View class
 */

class JoomleagueViewMatch extends JLGView
{
	function display($tpl=null)
	{
		$result=$this->input->get('result');
		echo $result;
	}

	function _displaySaveSubst($tpl=null)
	{
		$result=$this->input->get('result');
		echo $result;
	}

}
