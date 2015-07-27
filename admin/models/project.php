<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @author	Marco Vaninetti <martizva@libero.it>
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/models/item.php';
jimport('joomla.application.component.modeladmin');

/**
 * Project Model
 */
class JoomleagueModelProject extends JoomleagueModelItem
{

	/**
	 * remove all players from a project
	 */
	function deleteProjectPlayers($project_id)
	{
		$result = false;
		if ($project_id > 0)
		{
			$query = "	DELETE
						FROM #__joomleague_team_player
						WHERE projectteam_id in (SELECT id FROM #__joomleague_project_team WHERE project_id=$project_id)";
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
	 * remove all staff from a project
	 */
	function deleteProjectStaff($project_id)
	{
		$result = false;
		if ($project_id > 0)
		{
			$query = "	DELETE
						FROM #__joomleague_team_staff
						WHERE projectteam_id in (SELECT id FROM #__joomleague_project_team WHERE project_id=$project_id)";
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
	* Method to return a season array (id, name)
	*
	* @access	public
	* @return	array seasons
	*/
	function getSeasons()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('s.id,s.name');
		$query->from('#__joomleague_season AS s');
		$query->where('s.published = 1');
		$query->order('s.name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return array();
		}
		return $result;
	}

	/**
	* Method to return template independent projects (id, name)
	*
	* @access	public
	* @return	array
	*/
	function getMasters()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__joomleague_project');
		$query->where('master_template = 0');
		$query->order('name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

	/**
	 * Method to load content project data
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
	 * Method to initialise the project data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$project							= new stdClass();
			$project->id						= 0;

			$project->name						= null;
			$project->league_id					= 0;
			$project->season_id					= 0;
			$project->asset_id					= 0;
				
			$project->master_template			= 0;
			$project->sub_template_id			= 0;
			$project->extension				 	= null;
			$project->timezone                  = 'UTC';
			$project->project_type				= 0;

			$project->teams_as_referees		 	= 0;
			$project->sports_type_id			= 1;

			$project->start_date				= null;
			$project->start_time				= '15:30';

			$project->current_round_auto		= 1;
			$project->current_round			 	= 1;
			$project->auto_time				 	= 2880;

			$project->game_regular_time		 	= 90;

			$project->game_parts				= 2;
			$project->halftime					= 15;
			$project->points_after_regular_time	= '3,1,0';

			$project->use_legs					= null;

			$project->allow_add_time			= 0;
			$project->add_time					= 15;
			$project->points_after_add_time	 	= '3,1,0';
			$project->points_after_penalty		= '3,1,0';

			$project->fav_team					= null;
			$project->fav_team_color			= '';
			$project->fav_team_text_color		= '';
			$project->fav_team_highlight_type	= '';
			$project->fav_team_text_bold		= '';
			
			$project->template					= "default";

			$project->enable_sb				 	= null;
			$project->sb_catid					= 0;

			$project->published				 	= 0;
			$project->ordering					= 0;

			$project->checked_out				= 0;
			$project->checked_out_time			= 0;
			$project->ordering 					= 0;
			$project->alias						= null;
			$project->extended					= '';
			$project->modified					= null;
			$project->modified_by				= null;
			$project->picture					= null;
			$project->is_utc_converted			= 0;
				
			$this->_data						= $project;
			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	* Method to return a project array (id, name)
	*
	* @access	public
	* @return	array project
	*/
	function getProjects()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.id,p.name');
		$query->from('#__joomleague_project AS p');
		$query->where('p.published = 1');
		$query->order('p.ordering,p.name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

	/**
	* Method to return a project array (id, name)
	*
	* @access	public
	* @return	array project
	*/
	function getProjectsBySportsType($sportstype_id, $season = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.id,p.name');
		$query->from('#__joomleague_project as p');
		$query->where('p.sports_type_id = '.$sportstype_id);
		$query->where('p.published = 1');
		if ($season) {
			$query->where('p.season_id = '.$season);
		}
		$query->order('p.ordering, p.name ASC');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

	/**
	* Method to return a project array (id, name)
	*
	* @access	public
	* @return	array project
	*/
	function getSeasonProjects($season = null)
	{
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.id');
		$query->from('#__joomleague_project AS p');
		
		// Season
		$query->join('LEFT', '#__joomleague_season AS s ON p.season_id = s.id');
		$query->select('concat(s.name," - ", p.name) AS name');
		if ($season) {
			$query->where('p.season_id = '.$season);
		}
		$query->order('p.ordering, p.name ASC ');
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

	
	/**
	* Method to return the project teams array (id, name)
	*
	* @access	public
	* @return	array
	*/
	function getProjectteams()
	{
		$db 	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('t.name AS text,t.notes');
		$query->from('#__joomleague_team AS t');

		// Project-Team
		$query->select('pt.id AS value');
		$query->join('LEFT', '#__joomleague_project_team AS pt ON pt.team_id = t.id');

		$query->where('pt.project_id = '.$this->_id);
		$query->order('t.name ASC');
		
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

	
	/**
	* Method to return the project teams array by team_id (team_id, name)
	*
	* @access	public
	* @return	array
	*/
	function getProjectteamsbyID()
	{
		if (empty($this->_id)){
			return false;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('t.name AS text');
		$query->from('#__joomleague_team AS t');
		
		$query->select('pt.team_id AS value');
		$query->join('LEFT', '#__joomleague_project_team AS pt ON pt.team_id = t.id');
		$query->where('pt.project_id = '.$this->_id);
		$query->order('t.name ASC');
		
		$db->setQuery($query);
		if (!$result = $db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $result;
	}

	
	/**
	 * returns associative array of parameters values from specified template
	 *
	 * @param string $template name
	 * @return array
	 */
	function getTemplateConfig ($template)
	{
		$result 		= '';
		$configvalues	= array();
		$project 		= $this->getData();
		$db 			= JFactory::getDbo();

		// load template param associated to project, or to master template if none find.
		$query = $db->getQuery(true);
		$query->select('params');
		$query->from('#__joomleague_template_config');
		$query->where('template = '.$db->Quote($template));
		$query->where('project_id = '.$project->id);
		$db->setQuery($query);
		if (!$result=$db->loadResult())
		{
			if ($project->master_template)
			{
				$query = $db->getQuery(true);
				$query->select('params');
				$query->from('#__joomleague_template_config');
				$query->where('template = '.$db->Quote($template));
				$query->where('project_id = '.$project->master_template);
				$db->setQuery($query);
				if (!$result = $db->loadResult())
				{
					JError::raiseWarning(500,sprintf(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECT_MODEL_MISSING_MASTER_TEMPLATE'),$template));
					return array();
				}
			}
			else
			{
				JError::raiseWarning(500,sprintf(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECT_MODEL_MISSING_TEMPLATE'),$template));
				return array();
			}
		}
		$params = explode("\n", trim($result));
		foreach ($params AS $param)
		{
			list($name, $value) = explode("=", $param);
			$configvalues[$name]=$value;
		}
		
		return $configvalues;
	}

	/**
	 * 
	 * @param $project_id
	 */
	function getProjectName($project_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('name');
		$query->from('#__joomleague_project');
		$query->where('id = '.$project_id);
		
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	
	/**
	 * Checks if an id is a valid Project
	 * @param $project_id
	 */
	function exists($project_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__joomleague_project');
		$query->where('id = '.$project_id);
		$db->setQuery($query);
		return (boolean)$db->loadResult();
	}

	
	/**
	* Method to return the query that will obtain all ordering versus projects
	* It can be used to fill a list box with value/text data.
	*
	* @access  public
	* @return  string
	*/
	function getOrderingAndProjectQuery()
	{
		return 'SELECT ordering AS value, name AS text FROM #__joomleague_project ORDER BY ordering';	
	}	
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'project', $prefix = 'table', $config = array())
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
		$form = $this->loadForm('com_joomleague.project', 'project',
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
		$data = JFactory::getApplication()->getUserState('com_joomleague.edit.project.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}
	
	/**
	 * Convert all games dates from specified project to utc
	 * 
	 * this assumes they were originally saved in tz set in project settings
	 */
	public function utc_fix_dates($project_id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('m.id, m.match_date, p.timezone');
		$query->from('#__joomleague_match AS m');
		$query->join('INNER', '#__joomleague_round AS r ON r.id = m.round_id');
		$query->join('INNER', '#__joomleague_project AS p ON p.id = r.project_id');
		$query->where('p.id = '.(int) $project_id);
		$query->where('NOT (m.match_date is null OR m.match_date = \'0000-00-00 00:00:00\')');
		$db->setQuery($query);
		$res = $db->loadObjectList();
		$bConverted = false;
		foreach ($res as $match) 
		{
			if (is_numeric($match->timezone)) {
				$this->setError(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_WRONG_TIMEZONE_FORMAT'));
				return false;
			}
			$utc_date = JFactory::getDate($match->match_date, $match->timezone)
			          ->setTimezone(new DateTimeZone('UTC'))
			          ->toSql();
			
			$query = $db->getQuery(true);
			
			$query->update('#__joomleague_match AS m');
			$query->set('m.match_date = '.$db->quote($utc_date));
			$query->where('m.id = '.$match->id);
			$db->setQuery($query);
			if (!$db->execute()) {
				$this->setError($db->getError());
				return false;
			} else {
				$bConverted = true;
			}
		}
		if($bConverted) {
			$tblProject = new stdClass();
			$tblProject->id = $project_id;
			$tblProject->is_utc_converted = 1;
			if (!$db->updateObject('#__joomleague_project', $tblProject, 'id')) {
				$this->setError($db->getError());
				return false;
			}
		}
		return true;
	}
}
