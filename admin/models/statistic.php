<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT.'/models/item.php';

/**
 * Statistic Model
 */
class JoomleagueModelStatistic extends JoomleagueModelItem
{
	/**
	 * overrides to load params and classparams
	 * @see JModelAdmin::getItem()
	 */
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();
		
		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');
		
		if ($item) {
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->baseparams);
			$item->baseparams = $registry->toArray();
			
			$registry = new JRegistry;
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
		return $item;
	}
	/**
	 * Method to remove an event
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete(&$pks=array())
	{
		if (count($pks))
		{
			$cids = implode(',', $pks);

			// first check that it not used in any match events
			$query = ' SELECT ms.id '
			       . ' FROM #__joomleague_match_statistic AS ms '
			       . ' WHERE ms.statistic_id IN ('. implode(',', $cid) .')'
			       ;
			$this->_db->setQuery($query);
			$this->_db->execute();
			if ($this->_db->getAffectedRows()) {
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_STATISTIC_MODEL_CANT_DELETE_STATS_MATCHES'));
				return false;
			}

			// then check that it is not assigned to positions
			$query = ' SELECT id '
			       . ' FROM #__joomleague_position_statistic '
			       . ' WHERE statistic_id IN ('. implode(',', $cid) .')'
			       ;
			$this->_db->setQuery($query);
			$this->_db->execute();
			if ($this->_db->getAffectedRows()) {
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_STATISTIC_MODEL_CANT_DELETE_STATS_MATCHES'));
				return false;
			}
			return parent::delete($pks);
		}
		return true;
	}


	/**
	 * Method to remove a statistics and associated data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function fulldelete($cid = array())
	{
		$result = false;

		if (count($cid))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode(',', $cid);

			// first check that it not used in any match events
			$query = ' DELETE '
			       . ' FROM #__joomleague_match_statistic '
			       . ' WHERE statistic_id IN ('. implode(',', $cid) .')'
			       ;
			$this->_db->setQuery($query);
			if (!$this->_db->execute()) {
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_STATISTIC_MODEL_ERROR_DELETE_STATS_MATCHES').': '.$this->_db->getErrorMsg());
				return false;
			}

			// then check that it is not assigned to positions
			$query = ' DELETE '
			       . ' FROM #__joomleague_position_statistic '
			       . ' WHERE statistic_id IN ('. implode(',', $cid) .')'
			       ;
			$this->_db->setQuery($query);
			if (!$this->_db->execute()) {
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_STATISTIC_MODEL_ERROR_DELETE_STATS_POS').': '.$this->_db->getErrorMsg());
				return false;
			}

			$query = ' DELETE
						FROM #__joomleague_statistic
						WHERE id IN (' . $cids . ')';

			$this->_db->setQuery($query);
			if(!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	/**
	 * Method to load content event data
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
	 * Method to initialise the event data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$statistic					= new stdClass();
			$statistic->id				= 0;
			$statistic->name			= null;
			$statistic->short			= null;
			$statistic->icon			= '';
			$statistic->class			= '';
			$statistic->calculated		= 0;
			$statistic->note			= '';
			$statistic->baseparams		= null;
			$statistic->params			= null;
			$statistic->sports_type_id	= 1;
			$statistic->published		= 1;
			$statistic->ordering		= 0;
			$statistic->checked_out		= 0;
			$statistic->checked_out_time= 0;
			$statistic->alias 			= null;
			$statistic->modified		= null;
			$statistic->modified_by		= null;
			$this->_data				= $statistic;
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	* Method to return the query that will obtain all ordering versus statistics
	* It can be used to fill a list box with value/text data.
	*
	* @access  public
	* @return  string
	*/
	function getOrderingAndStatisticQuery()
	{
		return 'SELECT ordering AS value,name AS text FROM #__joomleague_statistic ORDER BY ordering';
	}	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'statistic', $prefix = 'table', $config = array())
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
			$data = $this->getItem();
		}
		return $data;
	}
	
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);
	
		// Create a signature hash.
		$hash = md5($source . serialize($options));
	
		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}
	
		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
	
		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);
			// load base configuration xml for stats
			$form->loadFile(JLG_PATH_ADMIN.'/statistics/base.xml');
			
			// specific xml configuration depends on stat type
			$item = $this->loadFormData();			
			if ($item && $item->class) 
			{
				$class = JLGStatistic::getInstance($item->class);
				$xmlpath = $class->getXmlPath();
				$form->loadFile($xmlpath);
			} 
			
			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}
			
			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);
	
			// Load the data into the form after the plugins have operated.
			$form->bind($data);
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
	
		// Store the form for later.
		$this->_forms[$hash] = $form;
	
		return $form;
	}
}
