<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

// Include library dependencies
jimport('joomla.filter.input');

/**
* Position Table class
*/
class TablePosition extends JLTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct( & $db )
	{
		parent::__construct( '#__joomleague_position', 'id', $db );
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	public function check()
	{
		if (empty($this->name)) {
			$this->setError(JText::_('ERROR NAME REQUIRED'));
			return false;
		}
		// setting alias
		if ( empty( $this->alias ) )
		{
			$this->alias = JFilterOutput::stringURLSafe( $this->name );
		}
		else {
			$this->alias = JFilterOutput::stringURLSafe( $this->alias ); // make sure the user didn't modify it to something illegal...
		}
		
		return true;
	}
}
