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
 * HTML View class
 *
 * @author	Zoltan Koteles
 */
class JoomleagueViewJLXMLExports extends JLGView
{
	function display($tpl=null)
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('JoomLeague XML Export'),'generic.png');

		$db = JFactory::getDbo();

		parent::display($tpl);
	}

}
