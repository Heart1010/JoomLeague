<?php
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT.'/models/item.php';

/**
 * Joomleague Component Template Model
 *
 * @author	Marco Vaninetti <martizva@tiscali.it>
 * @package	JoomLeague
 */
class JoomleagueModelTemplate extends JoomleagueModelItem
{
	/**
	 * Method to remove templates of only one project
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function deleteOne($project_id)
	{
		if ($project_id > 0)
		{
			$query='DELETE FROM #__joomleague_template_config WHERE project_id='.(int) $project_id;
			$this->_db->setQuery($query);
			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	/**
	 * Method to load content template data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$this->_data = parent::getItem($this->_id);
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the template data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$template=new stdClass();
			$template->id					= 0;
			$template->title				= null;
			$template->func					= null;
			$template->params				= null;
			$template->project_id			= null;
			$template->checked_out			= 0;
			$template->checked_out_time		= 0;
			$template->modified				= null;
			$template->modified_by			= null;
			
			$this->_data					= $template;
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to copy a template in current project
	 *
	 * @access	public
	 * @return	boolean True on success
	 */
	function import($templateid,$projectid)
	{
		$row = $this->getTable();

		// load record to copy
		if (!$row->load($templateid))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		//copy to new element
		$row->id=null;
		$row->project_id=(int) $projectid;

		// Make sure the item is valid
		if (!$row->check())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the item to the database
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}

	function getAllTemplatesList($project_id,$master_id)
	{
		$query='SELECT template FROM #__joomleague_template_config WHERE project_id='.$project_id;
		$this->_db->setQuery($query);
		$current=$this->_db->loadColumn();
		$query="SELECT id as value, title as text
				FROM #__joomleague_template_config
				WHERE project_id=$master_id AND template NOT IN ('".implode("','",$current)."')
				ORDER BY title";
		$this->_db->setQuery($query);
		$result1=$this->_db->loadObjectList();
		$query="SELECT id as value, title as text
				FROM #__joomleague_template_config
				WHERE project_id=$project_id
				ORDER BY title";
		$this->_db->setQuery($query);
		$result2=$this->_db->loadObjectList();
		return array_merge($result2,$result1);
	}
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'template', $prefix = 'table', $config = array())
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
