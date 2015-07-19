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
 */
class JoomleagueViewImport extends JLGView
{

	function display($tpl = null)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$table = JRequest::getVar('table');
		//initialise variables
		$document	= JFactory::getDocument();
		$user 		= JFactory::getUser();

		//build toolbar
		#JToolBarHelper::title(JText::_('IMPORT'), 'home');
		JToolBarHelper::title(JText::_('JoomLeague CSV-Import - Step 1 of 2'), 'generic.png');
		JToolBarHelper::back();
		JToolBarHelper::help('joomleague.import',true);

		// Get data from the model
		$model = $this->getModel("import");
		$tablefields = $model->getTableColumns('#__joomleague_' . $table);

		//assign vars to the template
		$this->tablefields = $tablefields;
		$this->table = $table;
		parent::display($tpl);
	}
}
