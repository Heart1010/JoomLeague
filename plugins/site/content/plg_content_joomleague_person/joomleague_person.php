<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class plgContentJoomleague_Person extends JPlugin
{

	/**
	 * Construct plugin.
	*/
	public function __construct(&$subject, $config)
	{
		// Do not enable plugin in administration.
		if (JFactory::getApplication()->isAdmin())
		{
			return false;
		}
		
		parent::__construct ($subject, $config);
	
		$this->loadLanguage('plg_content_joomleague_person');
		$params = $this->params;
	}
	
	
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$db = JFactory::getDbo();

		if ( JString::strpos( $row->text, 'jl_player' ) === false )
		{
			return true;
		}
			
		$regex = "#{jl_player}(.*?){/jl_player}#s";

		if (preg_match_all( $regex, $row->text, $matches ) > 0 )
		{
			require_once JPATH_SITE.'/components/com_joomleague/joomleague.core.php';
			foreach ($matches[0] as $match)
			{
				$name = preg_replace("/{.+?}/", "", $match);

				$aname = explode(" ", html_entity_decode($name) );
				$firstname = $aname[0];
				$lastname = $aname[1];				
				
				$query='	SELECT	pr.id AS pid,
								tp.person_id,
								tp.id AS tpid,
								pt.project_id,
								pr.firstname,
								pr.lastname,
								p.name AS project_name,
								s.name AS season_name,
								t.name AS team_name,
								pos.name AS position_name,
								tp.project_position_id,
								t.id AS team_id,
								pt.id AS ptid,
								pos.id AS posID,
								CASE WHEN CHAR_LENGTH(t.alias) THEN CONCAT_WS(\':\',t.id,t.alias) ELSE t.id END AS team_slug,
								CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(\':\',p.id,p.alias) ELSE p.id END AS project_slug
						FROM #__joomleague_person AS pr
						INNER JOIN #__joomleague_team_player AS tp ON tp.person_id=pr.id
						INNER JOIN #__joomleague_project_team AS pt ON pt.id=tp.projectteam_id
						INNER JOIN #__joomleague_team AS t ON t.id=pt.team_id
						INNER JOIN #__joomleague_project AS p ON p.id=pt.project_id
						INNER JOIN #__joomleague_season AS s ON s.id=p.season_id
						INNER JOIN #__joomleague_league AS l ON l.id=p.league_id
						INNER JOIN #__joomleague_project_position AS ppos ON ppos.id=tp.project_position_id
						INNER JOIN #__joomleague_position AS pos ON pos.id=ppos.position_id
						WHERE pr.firstname = '. $db->Quote($firstname).' 
						AND	pr.lastname = '. $db->Quote($lastname) .'
						AND p.published=1
						AND tp.published=1
						AND pr.published = 1
						ORDER BY p.id DESC';
				
				// run query
				$db->setQuery($query);
				$rows = $db->loadObjectList();

				// get result
				// replace only if project id set
				if (isset($rows[0]->project_id))
				{
					$personid = $rows[0]->pid;
					$projectid = $rows[0]->project_id;
					$teamid = $rows[0]->team_id;
					$url = JoomLeagueHelperRoute::getPlayerRoute($projectid, $teamid, $personid, null);
					$link = '<a class="player" href="' . $url . '">';
					$row->text = preg_replace("#{jl_player}" . $name . "{/jl_player}#s", $link . $name . "</a>", $row->text);
				}
				else
				{
					$row->text = preg_replace("#{jl_player}" . $name . "{/jl_player}#s", $name, $row->text);
				}
			}
			return true;
		}
	}
}
