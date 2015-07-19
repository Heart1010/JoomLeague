<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );
require_once JPATH_COMPONENT.'/models/item.php';

/**
 * Positionstatistic Model
 *
 * @author Marco Vaninetti <martizva@libero.it>
 */
class JoomleagueModelPositionstatistic extends JoomleagueModelItem
{

	/**
	 * Method to load  data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		return true;
	}

	/**
	 * Method to initialise data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		return true;
	}


	/**
	 * Method to update position statistic
	 *
	 * @access	public
	 * @return	boolean	True on success
	 *
	 */
	function store($data, $table = '')
	{
 		$result	= true;
		$peid	= (isset($data['position_statistic']) ? $data['position_statistic'] : array());
		JArrayHelper::toInteger( $peid );
		$peids = implode( ',', $peid );
		
		$query = ' DELETE	FROM #__joomleague_position_statistic '
		       . ' WHERE position_id = ' . $data['id']
		       ;
		if (count($peid)) {
			$query .= '   AND statistic_id NOT IN  (' . $peids . ')';
		}

		$this->_db->setQuery( $query );
		if( !$this->_db->execute() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			$result = false;
		}

		for ( $x = 0; $x < count($peid); $x++ )
		{
			$query = "UPDATE #__joomleague_position_statistic SET ordering='$x' WHERE position_id = '" . $data['id'] . "' AND statistic_id = '" . $peid[$x] . "'";
 			$this->_db->setQuery( $query );
			if( !$this->_db->execute() )
			{
				$this->setError( $this->_db->getErrorMsg() );
				$result= false;
			}
		}
		for ( $x = 0; $x < count($peid); $x++ )
		{
			$query = "INSERT IGNORE INTO #__joomleague_position_statistic (position_id, statistic_id, ordering) VALUES ( '" . $data['id'] . "', '" . $peid[$x] . "','" . $x . "')";
			$this->_db->setQuery( $query );
			if ( !$this->_db->execute() )
			{
				$this->setError( $this->_db->getErrorMsg() );
				$result= false;
			}
		}
		return $result;
	}
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'positionstatistic', $prefix = 'table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_joomleague.'.$this->name, $this->name,
				array('load_data' => $loadData) );
		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_joomleague.edit.'.$this->name.'.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}
}
