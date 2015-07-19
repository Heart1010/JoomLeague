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
class JoomleagueViewDatabaseTools extends JLGView
{
	public function display( $tpl = null )
	{
		$db		= JFactory::getDbo();
		$uri	= JFactory::getURI();

		$this->request_url = $uri->toString();

		$this->addToolbar();		
		parent::display( $tpl );
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'COM_JOOMLEAGUE_ADMIN_DBTOOLS_TITLE' ), 'config.png' );
		JToolBarHelper::back();
		JToolBarHelper::help( 'screen.joomleague', true );
	}		
}
