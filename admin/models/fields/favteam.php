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
 * Favteam form field class
 */
class JFormFieldFavteam extends JFormFieldList
{
	/**
	 * field type
	 * @var string
	 */
	public $type = 'Favteam';

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
			
			$query->select('pt.team_id AS value, t.name AS text');
			$query->from('#__joomleague_team AS t');
			$query->join('inner', '#__joomleague_project_team AS pt ON pt.team_id=t.id');
			$query->where('pt.project_id = '.$project_id);
			$query->order('t.name');
			$db->setQuery($query);
			$options = $db->loadObjectList();
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
