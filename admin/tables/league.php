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
* League Table class
*/
class TableLeague extends JLTable 
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db)
	{
		parent :: __construct( '#__joomleague_league', 'id', $db );
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	public function check()
	{
		// setting alias
		if ( empty( $this->alias ) )
		{
			$this->alias = JFilterOutput::stringURLSafe( $this->name );
		}
		else {
			$this->alias = JFilterOutput::stringURLSafe( $this->alias ); // make sure the user didn't modify it to something illegal...
		}
		
		// check if name is unique
		if (!$this->id) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('name');
			$query->from('#__joomleague_league');
			$query->where('name ='.$db->Quote($this->name));
			$db->setQuery($query);
			$result = $db->loadColumn();
				
			if ($result) {
				$app = JFactory::getApplication()->enqueueMessage('League already exists','warning');
				return false;
			}
				
		}
		
		return true;
	}
}
