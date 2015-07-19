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
 * @author	Kurt Norgaz
 */
class JoomleagueViewJLXMLImport extends JLGView
{
	public function display($tpl = null)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('JoomLeague XML Import'), 'generic.png');
		JToolBarHelper::back();
		#JLToolBarHelper::save('save', 'Import');
		JToolBarHelper::help('screen.joomleague', true);

		$db		= JFactory::getDbo();
		$uri	= JFactory::getURI();

		#$user = JFactory::getUser();
		#$config = JFactory::getConfig();
		$config = JComponentHelper::getParams('com_media');

		#$this->user = JFactory::getUser();
		$this->request_url = $uri->toString();
		#$this->user = $user;
		$this->config = $config;

		parent::display( $tpl );
	}
}
