<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Tools Controller
 */
class JoomleagueControllerTools extends JoomleagueController
{
	protected $view_list = 'tools';
	
	
	public function __construct()
	{
		parent::__construct();
		
		$jinput 	= JFactory::getApplication()->input;
		$task 		= $jinput->getCmd('task');
		
		if ($task == 'exporttablecsv') {
			$this->registerTask($task, 'exportTableCsv');
		}
		
		if ($task == 'exporttablesql') {
			$this->registerTask($task, 'exportTableSql');
		}
		

	}
	
	
	public function display($cachable = false, $urlparams = false)
	{

		parent::display();
	}
	

	
	public function exportTableCsv() 
	{
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$tables	= $jinput->get('cid', array(), 'array');
		$table	= $tables[0];
		$this->sendHeaders($table.'_'.date('Ymd') .'_' . date('Hi').".csv", "text/csv");
		
		$model = $this->getModel('tools');
		$model->getTableDataCsv($table);
		jexit();
	}

	public function exportTableSql() 
	{
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		$tables	= $jinput->get('cid', array(), 'array');
		$table	= $tables[0];
		$this->sendHeaders($table.'_'.date('Ymd') .'_' . date('Hi').".sql", "text/plain");
		
		$model = $this->getModel('tools');
		$model->getTableDataSql($table);
		jexit();
	}
	
	
	public function truncate() {
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$tables	= $jinput->get('cid', array(), 'array');
		$table	= $tables[0];
		
		$model = $this->getModel('tools');
		if ($model->truncateTable($table)) {
			$link = 'index.php?option=com_joomleague&view=tools';
			$this->setRedirect($link);
		}
	}
	
	
	private function sendHeaders($filename = 'export.csv', $contentType = 'text/csv') {
		header("Content-type: ".$contentType);
		header("Content-Disposition: attachment; filename=" . $filename);
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	
	
	public function back() {

		$link = 'index.php?option=com_joomleague';
		$this->setRedirect($link);	
	}
}
