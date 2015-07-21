<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport( 'joomla.application.component.modeladmin' );

/**
 * Item Model
 */
if(!class_exists('JoomleagueModelItem')) {
class JoomleagueModelItem extends JModelAdmin
{
	/**
	 * item id
	 *
	 * @var int
	 */
	var $_id = null;

	/**
	 * Project data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar( 'cid', array(0), '', 'array' );
		$edit = JRequest::getVar( 'edit', true );
		if( $edit )
		{
			$this->setId( (int)$array[0] );
		}
	}

	/**
	 * Method to set the item identifier
	 *
	 * @access	public
	 * @param	int item identifier
	 */
	function setId( $id )
	{
		// Set item id and wipe data
		$this->_id	  = $id;
		$this->_data	= null;
	}

	/**
	 * Method to get an item
	 *
	 */
	function &getData()
	{
		// Load the item data
		if ( !$this->_loadData() )
		{
			$this->_initData();
		}

		return $this->_data;
	}

	/**
	 * Tests if item is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 */
	function isCheckedOut( $uid = 0 )
	{
		if ( $this->_loadData() )
		{
			if ( $uid )
			{
				return ( $this->_data->checked_out && $this->_data->checked_out != $uid );
			}
			else
			{
				return $this->_data->checked_out;
			}
		}
	}

	/**
	 * Method to store the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store( $data, $table = '' )
	{
		if ( $table == '')
		{
			$row = $this->getTable();
		}
		else
		{
			$row = JTable::getInstance( $table, 'Table' );
		}

		// Bind the form fields to the items table
		if ( !$row->bind( $data ) )
		{
			$this->setError( JText::_('Binding failed') );
			return false;
		}

		// Create the timestamp for the date
		$row->checked_out_time = gmdate( 'Y-m-d H:i:s' );

		// if new item, order last, but only if an ordering exist
		if ( ( isset( $row->id ) ) && ( isset( $row->ordering ) ) )
		{
			if ( !$row->id && $row->ordering != NULL )
			{
				$row->ordering = $row->getNextOrder();
			}
		}

		// Make sure the item is valid
		if ( !$row->check() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		// Store the item to the database
		if ( !$row->store() )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Method to move an item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function move( $direction )
	{
		$row = $this->getTable();
		if ( !$row->load( $this->_id ) )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		if ( !$row->move( $direction ) )
		{
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'tablename', $prefix = '', $config = array())
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
}
?>