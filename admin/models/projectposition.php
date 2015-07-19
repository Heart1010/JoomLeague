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
require_once JPATH_COMPONENT.'/models/list.php';

/**
 * ProjectPosition Model
 */
class JoomleagueModelProjectposition extends JoomleagueModelList
{
	var $_identifier = "pposition";
	
	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();

		$query='	SELECT	pt.id AS positiontoolid,
							pt.*,

							po.name AS name,
							po.*,

							pid.name AS parent_name,

							(select count(*) FROM #__joomleague_position_eventtype AS pe
					 		WHERE pe.position_id=po.id) countEvents,

							(select count(*) FROM #__joomleague_position_statistic
					 		WHERE position_id=po.id) countStats

					FROM #__joomleague_project_position AS pt
					LEFT JOIN #__joomleague_position po ON pt.position_id=po.id
					LEFT JOIN #__joomleague_position pid ON po.parent_id=pid.id '.$where.$orderby;
		return $query;
	}

	function _buildContentOrderBy()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$filter_order		= $app->getUserStateFromRequest($option.'po_filter_order',		'filter_order',		'po.name',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'po_filter_order_Dir',	'filter_order_Dir',	'',			'word');

		if ($filter_order=='po.name')
		{
			$orderby=' ORDER BY po.parent_id,po.name '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',po.name ';
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$project_id = $app->getUserState($option.'project');
		$where =' WHERE  pt.project_id='.$project_id;
		return $where;
	}

	/**
	 * Method to update project positions list
	 *
	 * @access	public
	 * @return	boolean	True on success
	 *
	 */
	function store($data)
	{
		//echo '<br /><pre>1~'.print_r($data,true).'~</pre><br />';
		$result=true;
		//$peid=(isset($data['project_teamslist']));
		$peid=(isset($data['project_positionslist']));
		if ($peid==null)
		{
			$query="DELETE FROM #__joomleague_project_position WHERE project_id=".$data['id'];
		}
		else
		{
			$pidArray=$data['project_positionslist'];
			JArrayHelper::toInteger($pidArray);
			$peids=implode(",",$pidArray);
			$query="DELETE FROM #__joomleague_project_position WHERE project_id=".$data['id']." AND position_id NOT IN ($peids)";
		}
		$this->_db->setQuery($query);
		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());
			$result=false;
		}
		for ($x=0; $x < count($data['project_positionslist']); $x++)
		{
			$query="INSERT IGNORE INTO #__joomleague_project_position (project_id,position_id) VALUES ('".$data['id']."','".$data['project_positionslist'][$x]."')";
			$this->_db->setQuery($query);
			if(!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());
				$result=false;
			}
		}
		return $result;
	}

	/**
	 * Method to return the positions which are subpositions and are equal to a sportstype array (id,name)
	 *
	 * @access  public
	 * @return  array
	 */
	function getSubPositions($sports_type_id=1)
	{
		$query='	SELECT	id AS value,
							name AS text,
							sports_type_id AS type,
							parent_id AS parentID
					FROM #__joomleague_position
					WHERE published=1 AND sports_type_id='.$sports_type_id.'
					ORDER BY parent_id ASC,name ASC ';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		//echo '<br /><pre>2~'.print_r($result,true).'~</pre><br />';
		return $result;
	}

	/**
	 * Method to return the project positions array (id,name)
	 *
	 * @access  public
	 * @return  array
	 */
	function getProjectPositions()
	{
		$app = JFactory::getApplication();
		$project_id=$app->getUserState('com_joomleagueproject');
		$query='	SELECT	p.id AS value,
							p.name AS text,
							p.sports_type_id AS type,
							p.parent_id AS parentID
					FROM #__joomleague_position AS p
					LEFT JOIN #__joomleague_project_position AS pp ON pp.position_id=p.id
					WHERE pp.project_id='.$project_id.'
					ORDER BY p.parent_id ASC,p.name ASC ';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
	}

	/**
	* Method to assign positions of an existing project to a copied project
	*
	* @access  public
	* @return  array
	*/
	function cpCopyPositions($post)
	{
		$old_id=(int)$post['old_id'];
		$project_id=(int)$post['id'];
		//copy positions
		$query="SELECT * FROM #__joomleague_project_position WHERE project_id=".$old_id;
		$this->_db->setQuery($query);
		if ($results=$this->_db->loadAssocList())
		{
			foreach($results as $result)
			{
				$p_position = $this->getTable();
				$p_position->bind($result);
				$p_position->set('id',NULL);
				$p_position->set('project_id',$project_id);
				if (!$p_position->store())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				$newid = $p_position->id;
				$query = "UPDATE #__joomleague_team_player " . 
							"SET project_position_id = " . $newid .
							" WHERE project_position_id = " . $result['id'];
				$this->_db->setQuery($query);
				if(!$this->_db->execute())
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}	
			}
		}
		return true;
	}
	
	/**
	 * return count of projectpositions
	 *
	 * @param int project_id
	 * @return int
	 */
	function getProjectPositionsCount($project_id)
	{
		$query='SELECT count(*) AS count
		FROM #__joomleague_project_position AS pp
		JOIN #__joomleague_project AS p on p.id = pp.project_id
		WHERE p.id='.$project_id;
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
	}	
}
