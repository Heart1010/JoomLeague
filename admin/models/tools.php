<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/models/list.php';


function add_apostroph($str) {
	return sprintf("`%s`", $str);
}

function add_quotes($str) {
	return sprintf("'%s'", $str);
}


/**
 * Tools Model
 */
class JoomleagueModelTools extends JoomleagueModelList
{
	
	/**
	 * @todo Add check to see if tables do exists
	 */
	function getTables() {
		
		$tables = array(
				'joomleague_club',
				'joomleague_division',
				'joomleague_eventtype',
				'joomleague_league',
				'joomleague_match',
				'joomleague_match_event',
				'joomleague_match_player',
				'joomleague_match_referee',
				'joomleague_match_staff',
				'joomleague_match_staff_statistic',
				'joomleague_match_statistic',
				'joomleague_person',
				'joomleague_playground',
				'joomleague_position',
				'joomleague_position_eventtype',
				'joomleague_position_statistic',
				'joomleague_project',
				'joomleague_project_position',
				'joomleague_project_referee',
				'joomleague_project_team',
				'joomleague_round',
				'joomleague_season',
				'joomleague_sports_type',
				'joomleague_statistic',
				'joomleague_team',
				'joomleague_team_player',
				'joomleague_team_staff',
				'joomleague_team_trainingdata',
				'joomleague_template_config',
				'joomleague_treeto',
				'joomleague_treeto_match',
				'joomleague_treeto_node',
				'joomleague_version'
		);
	
		
		$db 		= JFactory::getDbo();
		$tableList	= $db->getTableList();
		$prefix 	= $db->getPrefix();
		
		$data = array();
		foreach ($tableList As $row) {
			
			$row = str_replace($prefix, "", $row);	
			if (in_array($row, $tables)) {
				$data[] = $row;	
			}
		}
		
		return $data;
	}
	
	
	/**
	 * Returns a CSV file with Table data
	 */
	public function getTableDataCsv($table)
	{
		// start
		$csv = fopen('php://output', 'w');
		fputs($csv, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
		$db 	= JFactory::getDBO();
	
		// header
		$header = array();
		$header = array_keys($db->getTableColumns('#__'.$table));
		fputcsv($csv, $header, ';');
	
		// content
		$items = $db->setQuery($this->getListQueryTableData($table))->loadObjectList();
		foreach ($items as $lines) {
			fputcsv($csv, (array) $lines, ';', '"');
		}
	
		// close
		return fclose($csv);
	}
	
	
	/**
	 * Build an query to load the Table data.
	 */
	protected function getListQueryTableData($table)
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
	
		// Select the required fields from the table.
		$query->select('*');
		$query->from('#__'.$table);
	
		return $query;
	}
	
	
	/**
	 * Returns a SQL file with data
	 * @return boolean
	 */
	public function getTableDataSQL($table)
	{
		# start output
		$sql	= fopen('php://output', 'w');
		$db		= $this->getDbo();
	
		if (is_array($table)) {
			$tables	= $table;
			foreach ($tables as $table) {
	
				$query = $this->getListQueryTableDataSQL($table);
				$rows = $this->_getList($query);
	
				$result	= count($rows);
				if ($result == 0) {
					continue;
				}
	
				# retrieve columns
				$columns = array();
				$columns = array_keys($db->getTableColumns('#__jem_'.$table));
				$columns =  implode(',', array_map('add_apostroph', $columns));
	
				$data = '';
				$start = "INSERT INTO `".$db->getPrefix().$table."` (".$columns.") VALUES";
				$start .= "\r\n";
	
				fwrite($sql,$start);
	
				foreach ($rows as $row) {
					$values = get_object_vars($row);
					$values = implode(',',array_map('add_quotes',$values));
	
					$data.= '('.$values.')';
					$data.=",";
					$data.= "\r\n";
				}
	
				$data = substr_replace($data ,"",-3);
	
				fwrite($sql,$data);
	
				$end = ";\n\n\n";
				fwrite($sql,$end);
			}
	
		} else {
			# retrieve columns
			$columns = array();
			$columns = array_keys($db->getTableColumns('#__'.$table));
			$columns =  implode(',', array_map('add_apostroph', $columns));
	
			$data = '';
			$start = "INSERT INTO `".$db->getPrefix().$table."` (".$columns.") VALUES";
			$start .= "\r\n";
	
			fwrite($sql,$start);
	
			$query = $this->getListQueryTableDataSQL($table);
			$rows = $this->_getList($query);
	
			foreach ($rows as $row) {
				$values = get_object_vars($row);
				$values = implode(',',array_map('add_quotes',$values));
	
				$data.= '('.$values.')';
				$data.=",";
				$data.= "\r\n";
			}
	
			$data = substr_replace($data ,"",-3);
	
			fwrite($sql,$data);
	
			$end = ";\n";
			fwrite($sql,$end);
		}
	
		# return output
		return fclose($sql);
	}
	
	
	/**
	 * Build a query to load the Table data.
	 *
	 * @return JDatabaseQuery
	 */
	protected function getListQueryTableDataSQL($table)
	{
		# Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
	
		# retrieve data
		$query->select('*');
		$query->from('#__'.$table);
	
		return $query;
	}	
	
	
	/**
	 * Truncate Table
	 */
	
	public function truncateTable($table) {
		
		$db = JFactory::getDbo();
		$db->truncateTable("#__".$table);

		if(!$db->execute()) {
			return false;
		}
		
		return true;
	}
}
