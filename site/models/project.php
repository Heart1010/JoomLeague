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
require_once JLG_PATH_ADMIN.'/models/rounds.php';

class JoomleagueModelProject extends JModelLegacy
{
	var $_project = null;
	var $projectid = 0;
	/**
	 * project league country
	 * @var string
	 */
	var $country = null;
	/**
	 * data array for teams
	 * @var array
	 */
	var $_teams = null;

	/**
	 * data array for matches
	 * @var array
	 */
	var $_matches = null;

	/**
	 * data array for rounds
	 * @var array
	 */
	var $_rounds = null;

	/**
	 * data project stats
	 * @var array
	 */
	var $_stats = null;

	/**
	 * data project positions
	 * @var array
	 */
	var $_positions = null;

	/**
	 * cache for project divisions
	 *
	 * @var array
	 */
	var $_divisions = null;

	/**
	 * caching for current round
	 * @var object
	 */
	var $_current_round;

	public function __construct()
	{
		parent::__construct();
		
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$this->projectid = JLHelperFront::stringToInt($jinput->getInt('p',0));
	}

	
	function getColors($configcolors='')
	{
		$s=substr($configcolors,0,-1);
		$s=str_replace(array('\r\n', '\n', '\r'), '', $s);

		$arr1=array();
		if(trim($s) != "")
		{
			$arr1=explode(";",$s);
		}

		$colors=array();

		$colors[0]["from"]="";
		$colors[0]["to"]="";
		$colors[0]["color"]="";
		$colors[0]["description"]="";

		for($i=0; $i < count($arr1); $i++)
		{
			$arr2=explode(",",$arr1[$i]);
			if(count($arr2) != 4)
			{
				break;
			}

			$colors[$i]["from"]=$arr2[0];
			$colors[$i]["to"]=$arr2[1];
			$colors[$i]["color"]=$arr2[2];
			$colors[$i]["description"]=$arr2[3];
		}
		return $colors;
	}
	
	
	
	/**
	 * returns project current round id
	 */
	function getCurrentRound()
	{
		$round	= $this->increaseRound();
		$result = $round ? $round->id : 0;
	
		return $result;
	}
	
	
	/**
	 * returns project current round code
	 */
	function getCurrentRoundNumber()
	{
		$round  = $this->increaseRound();
		$result = $round ? $round->roundcode : 0;
	
		return $result;
	}
	

	/**
	 * getDivision
	 */
	function getDivision($id)
	{
		$divs=$this->getDivisions();
		if ($divs && isset($divs[$id])) {
			return $divs[$id];
		}
		$div = new stdClass();
		$div->id = 0;
		$div->name = '';
		return $div;
	}
	
	
	function getDivisions($divLevel=0)
	{
		$project = $this->getProject();
		
		if (isset($project)) {
			if ($project->project_type == 'DIVISIONS_LEAGUE')
			{
				if (empty($this->_divisions))
				{
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->select('*');
					$query->from('#__joomleague_division');
					$query->where('project_id = '.$this->projectid);
					$db->setQuery($query);
					$this->_divisions = $db->loadObjectList('id');
				}
				if ($divLevel)
				{
					$ids=$this->getDivisionsId($divLevel);
					$res=array();
					foreach ($this->_divisions as $d)
					{
						if (in_array($d->id,$ids)) {
							$res[]=$d;
						}
					}
					return $res;
				}
				return $this->_divisions;
			}
		}
		return array();
	}

	
	/**
	 * return an array of division id and it's subdivision ids
	 * @param int division id
	 * @return int
	 */
	function getDivisionTreeIds($divisionid)
	{
		if ($divisionid == 0) {
			return $this->getDivisionsId();
		}
		$divisions=$this->getDivisions();
		$res=array($divisionid);
		foreach ($divisions as $d)
		{
			if ($d->parent_id == $divisionid) {
				$res[]=$d->id;
			}
		}
		return $res;
	}
	
	
	function getDivisionsId($divLevel=0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__joomleague_division');
		$query->where('project_id='.$this->projectid);
		if ($divLevel==1)
		{
			$query->where('(parent_id = 0 OR parent_id IS NULL)');
		}
		else if ($divLevel==2)
		{
			$query->where('parent_id > 0');
		}
		$query->order('ordering');
		$db->setQuery($query);
		$res = $db->loadColumn();
		if(count($res) == 0) {
			echo JText::_('COM_JOOMLEAGUE_RANKING_NO_SUBLEVEL_DIVISION_FOUND') . $divLevel;
		}
		return $res;
	}
	
	
	
	/**
	 * getProject
	 */
	function getProject()
	{
		if (is_null($this->_project) && $this->projectid > 0)
		{
			// Create a new query object.
			$db 	= $this->getDbo();
			$query	= $db->getQuery(true);
			$app 	= JFactory::getApplication();
				
			// Select the required fields from the table.
			$query->select('p.*,CASE WHEN CHAR_LENGTH( p.alias ) THEN CONCAT_WS( \':\', p.id, p.alias ) ELSE p.id END AS slug');
			$query->from('#__joomleague_project AS p');
				
			// Join League
			$query->select('l.country, l.name AS league_name')
			->join('LEFT', $db->quoteName('#__joomleague_league') . ' AS l ON l.id = p.league_id');
				
			// Join SportsType
			$query->select('st.id AS sport_type_id, st.name AS sport_type_name,LOWER(SUBSTR(st.name, CHAR_LENGTH( "COM_JOOMLEAGUE_ST_")+1)) AS fs_sport_type_name')
			->join('INNER', $db->quoteName('#__joomleague_sports_type') . ' AS st ON st.id = p.sports_type_id');
				
			// Join Season
			$query->select('s.name AS season_name')
			->join('LEFT', $db->quoteName('#__joomleague_season') . ' AS s ON s.id = p.season_id');
				
			$query->where('p.id = '.$db->Quote($this->projectid));
			$db->setQuery($query,0,1);
			$this->_project = $db->loadObject();
		}
		return $this->_project;
	}
	
	
	
	/**
	 * return project rounds as array of objects(roundid as value,name as text)
	 *
	 * @param string $ordering
	 * @return array
	 */
	function getRoundOptions($ordering='ASC')
	{
		$query="SELECT
					id as value,
				    CASE LENGTH(name)
				    	when 0 then CONCAT('".JText::_('COM_JOOMLEAGUE_MATCHDAY_NAME'). "',' ', id)
				    	else name
				    END as text
				  FROM #__joomleague_round
				  WHERE project_id=".(int)$this->projectid."
				  ORDER BY roundcode ".$ordering;

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	
	/**
	 * return project rounds objects ordered by roundcode
	 *
	 * @param string ordering 'ASC or 'DESC'
	 * @return array
	 */
	function getRounds($ordering='ASC')
	{
		if (empty($this->_rounds))
		{
			$query=" 	SELECT id,round_date_first,round_date_last,
				   			CASE LENGTH(name)
				    			when 0 then roundcode
				    			else name
				    		END as name,
				   			roundcode
			       		FROM #__joomleague_round
			         	WHERE project_id=". $this->projectid.
				         	" ORDER BY roundcode ASC ";
	
			$this->_db->setQuery($query);
			$this->_rounds=$this->_db->loadObjectList();
		}
		if ($ordering == 'DESC') {
			return array_reverse($this->_rounds);
		}
		return $this->_rounds;
	}
	
	
	/**
	 * getSportsType
	 */
	function getSportsType()
	{
		if (!$project = $this->getProject())
		{
			$this->setError(0, Jtext::_('COM_JOOMLEAGUE_ERROR_PROJECTMODEL_PROJECT_IS_REQUIRED'));
			return false;
		}
	
		return $project->sports_type_id;
	}
	
	
	/**
	 * getTeamInfo
	 */
	function getTeaminfo($projectteamid)
	{
		$query=' SELECT t.*, pt.division_id, t.id as team_id,
				pt.picture AS projectteam_picture,
				pt.notes AS projectteam_notes,
				t.picture as team_picture,
				c.logo_small,
				c.logo_middle,
				c.logo_big,
				c.country,
				IF((ISNULL(pt.picture) OR (pt.picture="")),
					(IF((ISNULL(t.picture) OR (t.picture="")), c.logo_big , t.picture)) , pt.picture) as picture,
				t.extended as teamextended, pt.project_id AS project_id, pt.id AS ptid
				FROM #__joomleague_project_team AS pt
				INNER JOIN #__joomleague_team AS t ON pt.team_id=t.id
				LEFT JOIN #__joomleague_club AS c ON t.club_id=c.id
				WHERE pt.id='. $this->_db->Quote($projectteamid);
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	function & _getTeams()
	{
		if (empty($this->_teams))
		{
			$query='	SELECT	tl.id AS projectteamid,
								tl.division_id,
								tl.standard_playground,
								tl.start_points,
								tl.points_finally,
								tl.neg_points_finally,
								tl.matches_finally,
								tl.won_finally,
								tl.draws_finally,
								tl.lost_finally,
								tl.homegoals_finally,
								tl.guestgoals_finally,
								tl.diffgoals_finally,
								tl.info,
								tl.reason,
								tl.team_id,
								tl.checked_out,
								tl.checked_out_time,
								tl.is_in_score,
								tl.picture AS projectteam_picture,
								t.picture as team_picture,
								IF((ISNULL(tl.picture) OR (tl.picture="")),
									(IF((ISNULL(t.picture) OR (t.picture="")), c.logo_small , t.picture)) , t.picture) as picture,
								tl.project_id,

								t.id,t.name,
								t.short_name,
								t.middle_name,
								t.notes,
								t.club_id,

								c.email as club_email,
								c.logo_small,
								c.logo_middle,
								c.logo_big,
								c.country,
								c.website,

								d.name AS division_name,
								d.picture AS division_picture,
								d.shortname AS division_shortname,
								d.parent_id AS parent_division_id,

								plg.name AS playground_name,
								plg.short_name AS playground_short_name,

								CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(\':\',p.id,p.alias) ELSE p.id END AS project_slug,
								CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\',t.id,t.alias) ELSE t.id END AS team_slug,
								CASE WHEN CHAR_LENGTH(d.alias) THEN CONCAT_WS(\':\',d.id,d.alias) ELSE d.id END AS division_slug,
								CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\',c.id,c.alias) ELSE c.id END AS club_slug

						FROM #__joomleague_project_team tl
							LEFT JOIN #__joomleague_team t ON tl.team_id=t.id
							LEFT JOIN #__joomleague_club c ON t.club_id=c.id
							LEFT JOIN #__joomleague_division d ON d.id=tl.division_id
							LEFT JOIN #__joomleague_playground plg ON plg.id=tl.standard_playground
							LEFT JOIN #__joomleague_project AS p ON p.id=tl.project_id

						WHERE tl.project_id='.(int)$this->projectid;

			$this->_db->setQuery($query);
			$this->_teams=$this->_db->loadObjectList();
		}
		return $this->_teams;
	}

	/**
	 * return teams of the project
	 *
	 * @param int $division
	 * @return array
	 */
	function getTeams($division=0)
	{
		$teams=array();
		if ($division != 0)
		{
			$divids=$this->getDivisionTreeIds($division);
			foreach ((array)$this->_getTeams() as $t)
			{
				if (in_array($t->division_id,$divids))
				{
					$teams[]=$t;
				}
			}
		}
		else
		{
			$teams=$this->_getTeams();
		}

		return $teams;
	}

	/**
	 * return array of team ids
	 *
	 * @return array	 *
	 */
	function getTeamIds($division=0)
	{
		$teams=array();
		foreach ((array)$this->_getTeams() as $t)
		{
			if (!$division || $t->division_id == $division) {
				$teams[]=$t->id;
			}
		}
		return $teams;
	}

	function getTeamsIndexedById($division=0)
	{
		$result=$this->getTeams($division);
		$teams=array();
		if (count($result))
		{
			foreach($result as $r)
			{
				$teams[$r->id]=$r;
			}
		}

		return $teams;
	}

	function getTeamsIndexedByPtid($division=0)
	{
		$result=$this->getTeams($division);
		$teams=array();

		if (count($result))
		{
			foreach($result as $r)
			{
				$teams[$r->projectteamid]=$r;
			}
		}
		return $teams;
	}

	/**
	 * @todo check!
	 * Added $project = false to be inline with model-Results
	 */
	function getFavTeams($project = false)
	{
		if ($project) {
			//
		} else {
			$project = $this->getProject();
		}
		
		if(!is_null($project)) {
			return explode(",",$project->fav_team);
		} else {
			return array();
		}
	}
	

	function getEventTypes($evid=0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('et.id AS etid, me.event_type_id AS id,et.*');
		$query->from('#__joomleague_eventtype AS et');
		$query->join('LEFT', '#__joomleague_match_event AS me ON et.id = me.event_type_id');
		
		if ($evid != 0)
		{
			$query->where('me.event_type_id = '.$evid);
		}

		$db->setQuery($query);
		return $db->loadObjectList('etid');
	}
	
	
	/**
	 * Method to return a playgrounds array (id,name)
	 *
	 * @access  public
	 * @return  array
	 */
	function getPlaygrounds()
	{
		$query='	SELECT	id AS value,
							name AS text
					FROM #__joomleague_playground
					ORDER BY text ASC ';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}
	
	

	function getprojectteamID($teamid)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id');
		$query->from('#__joomleague_project_team');
		$query->where('team_id = '.$teamid);
		$query->where('project_id = '.$this->projectid);
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	
	function getReferees()
	{
		$project=$this->getProject();
		if ($project->teams_as_referees)
		{
			$query='	SELECT	id AS value,
								name AS text
						FROM #__joomleague_team
						ORDER BY name';

			$this->_db->setQuery($query);
			$refs=$this->_db->loadObjectList();
		}
		else
		{
			$query='	SELECT	id AS value,
								firstname,
								lastname

						FROM #__joomleague_project_referee
						ORDER BY lastname';

			$this->_db->setQuery($query);
			$refs=$this->_db->loadObjectList();
			foreach($refs as $ref)
			{
				$ref->text=$ref->lastname.",".$ref->firstname;
			}
		}
		return $refs;
	}

	function getTemplateConfig($template)
	{
		//first load the default settings from the default <template>.xml file
		$paramsdata="";
		$arrStandardSettings=array();
		if(file_exists(JLG_PATH_SITE.'/settings/default/'.$template.'.xml')) {
			$strXmlFile = JLG_PATH_SITE.'/settings/default/'.$template.'.xml';
			$form = JForm::getInstance($template, $strXmlFile);
			$fieldsets = $form->getFieldsets();
			foreach ($fieldsets as $fieldset) {
				foreach($form->getFieldset($fieldset->name) as $field) {
					$arrStandardSettings[$field->name]=$field->value;
				}
			}
		}
		//second load the default settings from the default extensions <template>.xml file
		$extensions=JoomleagueHelper::getExtensions(JRequest::getInt('p'));
		foreach ($extensions as $e => $extension) {
			$JLGPATH_EXTENSION= JPATH_COMPONENT_SITE.'/extensions/'.$extension;
			$paramsdata="";
			$strXmlFile=$JLGPATH_EXTENSION.'/settings/default/'.$template.'.xml';
			if(file_exists($JLGPATH_EXTENSION.'/settings/default/'.$template.'.xml')) {
				$form = JForm::getInstance($template, $strXmlFile);
				$fieldsets = $form->getFieldsets();
				foreach ($fieldsets as $fieldset) {
					foreach($form->getFieldset($fieldset->name) as $field) {
						$arrStandardSettings[$field->name]=$field->value;
					}
				}
			}
		}

		if($this->projectid == 0) return $arrStandardSettings;

		$query= "SELECT t.params
				   FROM #__joomleague_template_config AS t
				   INNER JOIN #__joomleague_project AS p ON p.id=t.project_id
				   WHERE t.template=".$this->_db->Quote($template)."
				   AND p.id=".$this->_db->Quote($this->projectid);

		$this->_db->setQuery($query);
		if (! $result=$this->_db->loadResult())
		{
			$project=$this->getProject();
			if (!empty($project) && $project->master_template>0)
			{
				$query="SELECT t.params
						  FROM #__joomleague_template_config AS t
						  INNER JOIN #__joomleague_project AS p ON p.id=t.project_id
						  WHERE t.template=".$this->_db->Quote($template)."
						  AND p.id=".$this->_db->Quote($project->master_template);

				$this->_db->setQuery($query);
				if (! $result=$this->_db->loadResult())
				{
					JError::raiseNotice(500,JText::_('COM_JOOMLEAGUE_MASTER_TEMPLATE_MISSING')." ".$template);
					JError::raiseNotice(500,JText::_('COM_JOOMLEAGUE_MASTER_TEMPLATE_MISSING_PID'). $project->master_template);
					JError::raiseNotice(500,JText::_('COM_JOOMLEAGUE_TEMPLATE_MISSING_HINT'));
					return $arrStandardSettings;
				}
			}
			else
			{
				//JError::raiseNotice(500,'project ' . $this->projectid . '  setting not found');
				//there are no saved settings found, use the standard xml file default values
				return $arrStandardSettings;
			}
		}
		$jRegistry = new JRegistry;
		$jRegistry->loadString($result, 'ini');
		$configvalues = $jRegistry->toArray();

		//merge and overwrite standard settings with individual view settings
		$settings = array_merge($arrStandardSettings,$configvalues);

		return $settings;
	}

	function getOverallConfig()
	{
		return $this->getTemplateConfig('overall');
	}

	function getMapConfig()
	{
		return $this->getTemplateConfig('map');
	}

  	/**
   	* @author diddipoeler
   	* @since  2011-11-12
   	* @return country from project-league
   	*/
   	function getProjectCountry()
	{
		 $query = 'SELECT l.country
					from #__joomleague_league as l
					inner join #__joomleague_project as pro
					on pro.league_id = l.id
					WHERE pro.id = '. $this->_db->Quote($this->projectid);
		  $this->_db->setQuery( $query );
		  $this->country = $this->_db->loadResult();
		  return $this->country;
  	}

	/**
	 * return events assigned to the project
	 * @param int position_id if specified,returns only events assigned to this position
	 * @return array
	 */
	function getProjectEvents($position_id=0)
	{
		$query=' SELECT	et.id,
						et.name,
						et.icon
						FROM #__joomleague_eventtype AS et
						INNER JOIN #__joomleague_position_eventtype AS pet ON pet.eventtype_id=et.id
						INNER JOIN #__joomleague_project_position AS ppos ON ppos.position_id=pet.position_id
						WHERE ppos.project_id='.$this->_db->Quote($this->projectid);
		if ($position_id)
		{
			$query=' AND ppos.position_id='. $this->_db->Quote($position_id);
		}
		$query .= ' GROUP BY et.id';
		$this->_db->setQuery($query);
		$events=$this->_db->loadObjectList('id');
		return $events;
	}

	/**
	 * returns stats assigned to positions assigned to project
	 * @param int statid 0 for all stats
	 * @param int positionid 0 for all positions
	 * @return array objects
	 */
	function getProjectStats($statid=0,$positionid=0)
	{
		if (empty($this->_stats))
		{
			require_once JLG_PATH_ADMIN.'/statistics/base.php';
			$project = $this->getProject();
			$project_id=$project->id;
			$query='	SELECT	stat.id,
								stat.name,
								stat.short,
								stat.class,
								stat.icon,
								stat.calculated,
								ppos.id as pposid,
								ppos.position_id AS position_id,
								stat.params, stat.baseparams
						FROM #__joomleague_statistic AS stat
						INNER JOIN #__joomleague_position_statistic AS ps ON ps.statistic_id=stat.id
						INNER JOIN #__joomleague_project_position AS ppos ON ppos.position_id=ps.position_id
						  AND ppos.project_id='.$project_id.'
						INNER JOIN #__joomleague_position AS pos ON pos.id=ps.position_id
						WHERE stat.published=1
						  AND pos.published =1
						  ';
			$query .= ' ORDER BY pos.ordering,ps.ordering ';
			$this->_db->setQuery($query);
			$this->_stats=$this->_db->loadObjectList();

		}
		// sort into positions
		$positions=$this->getProjectPositions();
		$stats=array();
		// init
		foreach ($positions as $pos)
		{
			$stats[$pos->id]=array();
		}
		if (count($this->_stats) > 0)
		{
			foreach ($this->_stats as $k => $row)
			{
				if (!$statid || $statid == $row->id || (is_array($statid) && in_array($row->id, $statid)))
				{
					$stat=JLGStatistic::getInstance($row->class);
					$stat->bind($row);
					$stat->set('position_id',$row->position_id);
					$stats[$row->position_id][$row->id]=$stat;
				}
			}
			if ($positionid)
			{
				return (isset($stats[$positionid]) ? $stats[$positionid] : array());
			}
			else
			{
				return $stats;
			}
		}
		else
		{
			return $stats;
		}
	}

	function getProjectPositions()
	{
		if (empty($this->_positions))
		{
			$query='	SELECT	pos.id,
								pos.persontype,
								pos.name,
								pos.ordering,
								pos.published,
								ppos.id AS pposid
						FROM #__joomleague_project_position AS ppos
						INNER JOIN #__joomleague_position AS pos ON ppos.position_id=pos.id
						WHERE ppos.project_id='.$this->_db->Quote($this->projectid);
			$this->_db->setQuery($query);
			$this->_positions=$this->_db->loadObjectList('id');
		}
		return $this->_positions;
	}

	public static function getClubIconHtml(&$team,$type=1,$with_space=0)
	{
		$small_club_icon=$team->logo_small;
		if ($type==1)
		{
			$params=array();
			$params['align']="top";
			$params['border']=0;
			if ($with_space==1)
			{
				$params['style']='padding:1px;';
			}
			if ($small_club_icon=='')
			{
				$small_club_icon = JoomleagueHelper::getDefaultPlaceholder("clublogosmall");
			}

			return JHtml::image($small_club_icon,'',$params);
		}
		elseif (($type==2) && (isset($team->country)))
		{
			return Countries::getCountryFlag($team->country);
		}
	}
	
	
	/**
	 * method to update and return the project current round
	 * @return object
	 */
	function increaseRound()
	{
		if (!$this->_current_round)
		{
			if (!$project = $this->getProject()) {
				$this->setError(0, Jtext::_('COM_JOOMLEAGUE_ERROR_PROJECTMODEL_PROJECT_IS_REQUIRED'));
				return false;
			}
	
			$current_date = strftime("%Y-%m-%d %H:%M:%S");
			$db = JFactory::getDbo();
	
			// determine current round according to project settings
			switch ($project->current_round_auto)
			{
				case 0 :	 // manual mode
					$query = $db->getQuery(true);
					$query->select('r.id, r.roundcode');
					$query->from('#__joomleague_round AS r');
					$query->where('r.id = '.$project->current_round);
					break;
	
				case 1 :	 // get current round from round_date_first
					$query = $db->getQuery(true);
					$query->select('r.id, r.roundcode');
					$query->from('#__joomleague_round AS r');
					$query->where('r.project_id = '.$project->id);
					$query->where('(r.round_date_first - INTERVAL '.$db->Quote($project->auto_time).' MINUTE < '.$db->Quote($current_date).')');
					$query->order('r.round_date_first DESC');
					break;
					
				case 2 : // get current round from round_date_last
					$query = $db->getQuery(true);
					$query->select('r.id, r.roundcode');
					$query->from('#__joomleague_round AS r');
					$query->where('r.project_id = '.$project->id);
					$query->where('(r.round_date_last + INTERVAL '.$db->Quote($project->auto_time).' MINUTE > '.$db->Quote($current_date).')');
					$query->order('r.round_date_first DESC');
					break;
	
				case 3 : // get current round from first game of the round
					$query = $db->getQuery(true);
					$query->select('r.id, r.roundcode');
					$query->from('#__joomleague_round AS r');
						
					// Join Match
					$query->join('LEFT', $db->quoteName('#__joomleague_match') . ' AS m ON m.round_id = r.id');
						
					$query->where('r.project_id = '.$project->id);
					$query->where('(m.match_date - INTERVAL '.$db->Quote($project->auto_time).' MINUTE < '.$db->Quote($current_date).')');
					$query->order('m.match_date DESC');
					break;
	
				case 4 : // get current round from last game of the round
					$query = $db->getQuery(true);
					$query->select('r.id, r.roundcode');
					$query->from('#__joomleague_round AS r');
	
					// Join Match
					$query->join('LEFT', $db->quoteName('#__joomleague_match') . ' AS m ON m.round_id = r.id');
	
					$query->where('r.project_id = '.$project->id);
					$query->where('(m.match_date + INTERVAL '.$db->Quote($project->auto_time).' MINUTE > '.$db->Quote($current_date).')');
					$query->order('m.match_date ASC');
					break;
			}
			$db->setQuery($query,0,1);
			$result = $db->loadObject();
	
			// If result is empty, it probably means either this is not started, either this is over, depending on the mode.
			// Either way, do not change current value
			if (!$result)
			{
	
				$query = $db->getQuery(true);
				$query->select('r.id, r.roundcode');
				$query->from('#__joomleague_round AS r');
				$query->where('r.project_id = '.$project->current_round);
				$db->setQuery($query);
				$result = $db->loadObject();
	
				if (!$result)
				{
					if ($project->current_round_auto == 2) {
						// the current value is invalid... season is over, just take the last round
	
						$query = $db->getQuery(true);
						$query->select('r.id, r.roundcode');
						$query->from('#__joomleague_round AS r');
						$query->where('r.project_id = '.$project->id);
						$query->order('r.roundcode DESC');
						$db->setQuery($query);
						$result = $db->loadObject();
					} else {
						// the current value is invalid... just take the first round
						$query = $db->getQuery(true);
						$query->select('r.id, r.roundcode');
						$query->from('#__joomleague_round AS r');
						$query->where('r.project_id = '.$project->id);
						$query->order('r.roundcode ASC');
						$db->setQuery($query);
						$result = $db->loadObject();
					}
	
				}
			}
	
			// Update the database if determined current round is different from that in the database
			if ($result && ($project->current_round <> $result->id))
			{
				$query = $db->getQuery(true);
				$query->update('#__joomleague_project');
				$query->set('current_round = '.$result->id);
				$query->where('id = '.$db->Quote($project->id));
				$db->setQuery($query);
	
				if (!$db->execute()) {
					JError::raiseWarning(0, JText::_('COM_JOOMLEAGUE_ERROR_CURRENT_ROUND_UPDATE_FAILED'));
				}
			}
			$this->_current_round = $result;
		}
		return $this->_current_round;
	}
	
	
	

	/**
	 * Method to store the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store($data,$table='')
	{
		if ($table=='')
		{
			$row = $this->getTable();
		}
		else
		{
			$row = JTable::getInstance($table,'Table');
		}

		// Bind the form fields to the items table
		if (!$row->bind($data))
		{
			$this->setError(JText::_('Binding failed'));
			return false;
		}

		// Create the timestamp for the date
		$row->checked_out_time=gmdate('Y-m-d H:i:s');

		// if new item,order last,but only if an ordering exist
		if ((isset($row->id)) && (isset($row->ordering)))
		{
			if (!$row->id && $row->ordering != NULL)
			{
				$row->ordering=$row->getNextOrder();
			}
		}

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
		return $row->id;
	}

	public static function isUserProjectAdminOrEditor($userId=0, $project)
	{
		$result=false;
		if($userId > 0)
		{
			$id = $project->id;
			//$result= ($userId==$project->admin || $userId==$project->editor);
			$result = (	JFactory::getUser()->authorise('core.admin', 'com_joomleague.project.'.$id) ||
						JFactory::getUser()->authorise('core.manage', 'com_joomleague.project.'.$id) ||
						JFactory::getUser()->authorise('core.edit', 'com_joomleague.project.'.$id) ? true : false);
		}
		return $result;
	}
	
	
	/**
	 * returns match events
	 * @param int match id
	 * @return array
	 */
	function getMatchEvents($match_id,$showcomments=0,$sortdesc=0)
	{
		if ($showcomments == 1) {
			$join = 'LEFT';
			$addline = ' me.notes,';
		} else {
			$join = 'INNER';
			$addline = '';
		}
		$esort = '';
		if ($sortdesc == 1) {
			$esort = ' DESC';
		}
		$query = ' 	SELECT 	me.event_type_id,
							me.id as event_id,
							me.event_time,
							me.notice,'
				. $addline .
				'pt.team_id AS team_id,
							et.name AS eventtype_name,
							t.name AS team_name,
							me.projectteam_id AS ptid,
							me.event_sum,
							p.id AS playerid,
							p.firstname AS firstname1,
							p.nickname AS nickname1,
							p.lastname AS lastname1,
							p.picture AS picture1,
							tp.picture AS tppicture1
					FROM #__joomleague_match_event AS me
					'.$join.' JOIN #__joomleague_eventtype AS et ON me.event_type_id = et.id
					'.$join.' JOIN #__joomleague_project_team AS pt ON me.projectteam_id = pt.id
					'.$join.' JOIN #__joomleague_team AS t ON pt.team_id = t.id
					LEFT JOIN #__joomleague_team_player AS tp ON tp.id = me.teamplayer_id
						  AND tp.published = 1
					LEFT JOIN #__joomleague_person AS p ON tp.person_id = p.id
						  AND p.published = 1
					WHERE me.match_id = ' . $match_id . '
					ORDER BY (me.event_time + 0)'. $esort .', me.event_type_id, me.id';
	
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	

	/**
	 * returns match substitutions
	 * @param int match id
	 * @return array
	 */
	function getMatchSubstitutions($match_id)
	{
		$query=' SELECT	mp.in_out_time,
							pt.team_id,
							pt.id AS ptid,
							p2.id AS out_ptid,
							p.firstname,
							p.nickname,
							p.lastname,
							pos.name AS in_position,
							pos2.name AS out_position,
							p2.firstname AS out_firstname,
							p2.nickname AS out_nickname,
							p2.lastname AS out_lastname
						FROM #__joomleague_match_player AS mp
							LEFT JOIN #__joomleague_team_player AS tp ON mp.teamplayer_id=tp.id
							  AND tp.published=1
							LEFT JOIN #__joomleague_project_team AS pt ON tp.projectteam_id=pt.id
							LEFT JOIN #__joomleague_person AS p ON tp.person_id=p.id
							LEFT JOIN #__joomleague_team_player AS tp2 ON mp.in_for=tp2.id
							  AND tp2.published=1
							LEFT JOIN #__joomleague_person AS p2 ON tp2.person_id=p2.id
							LEFT JOIN #__joomleague_project_position AS ppos ON ppos.id=mp.project_position_id
							LEFT JOIN #__joomleague_position AS pos ON ppos.position_id=pos.id
							LEFT JOIN #__joomleague_match_player AS mp2 ON mp.match_id=mp2.match_id and mp.in_for=mp2.teamplayer_id
							LEFT JOIN #__joomleague_project_position AS ppos2 ON ppos2.id=mp2.project_position_id
							LEFT JOIN #__joomleague_position AS pos2 ON ppos2.position_id=pos2.id
						WHERE	mp.match_id='.(int)$match_id.' AND
								mp.came_in=1 AND p.published = 1 AND p2.published = 1
						ORDER by (mp.in_out_time+0) ';
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}

	
	function hasEditPermission($task=null)
	{
		$allowed = false;
		$user = JFactory::getUser();
		if($user->id > 0) {
			if(!is_null($task)) {
				if (!$user->authorise($task, 'com_joomleague')) {
					$allowed = false;
					error_log("no ACL permission for task " . $task);
				} else {
					$allowed = true;
				}
			}
			//if no ACL permission, check for overruling permission by project admin/editor (compatibility < 2.5)
			if(!$allowed) {
				// If not, then check if user is project admin or editor
				$project = $this->getProject();
				if($this->isUserProjectAdminOrEditor($user->id, $project))
				{
					$allowed = true;
				} else {
					error_log("no isUserProjectAdminOrEditor");
				}
			}
		}
		return $allowed;
	}
	
	function setProjectID($id=0)
	{
		$this->projectid=$id;
		$this->_project=null;
	}
	
}
