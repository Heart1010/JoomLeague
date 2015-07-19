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
* Template Table class
*/
class TableTemplate extends JLTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	public function __construct(& $db) {
		parent::__construct('#__joomleague_template_config', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	*/
	public function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] )) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString('ini');
		}
		return parent::bind($array, $ignore);
	}
}

