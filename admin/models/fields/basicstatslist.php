stat_ids
<?php
/**
 * @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

/**
 * Session form field class
*/
class JFormFieldBasicStatslist extends JFormFieldList
{
	/**
	 * field type
	 * @var string
	 */
	public $type = 'BasicStatslist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
		
		//automigrate current saved values to the new field format (array)
		if(is_array($this->value) === FALSE && $this->value !='') {
			$this->value = explode(',', $this->value);
		}
		
		$db = &JFactory::getDbo();
		$query = $db->getQuery(true);
			
		$query->select('id AS value');
		$query->select('CASE LENGTH(name) when 0 then CONCAT('.$db->Quote(JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT')). ', " ", id)	else name END as text ');
		$query->from('#__joomleague_statistic ');
		$query->where('class="basic"');
		$query->order('id');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
