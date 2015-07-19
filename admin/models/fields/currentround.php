<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

/**
 * Currentround form field class
 */
class JFormFieldCurrentround extends JFormFieldList
{
	/**
	 * field type
	 * @var string
	 */
	public $type = 'Currentround';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		$varname = (string) $this->element['varname'];
		$project_id = JRequest::getVar($varname);
		if (is_array($project_id)) {
			$project_id = $project_id[0];
		}
		
		if ($project_id)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('id AS value');
			$query->select('CASE LENGTH(name) when 0 then CONCAT('.$db->Quote(JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAY_NAME')). ', " ", id)	else name END as text ');
			$query->from('#__joomleague_round ');
			$query->where('project_id = '.$project_id);
			$query->order('roundcode');
			$db->setQuery($query);
			$options = $db->loadObjectList();
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
