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
require_once JPATH_COMPONENT.'/models/item.php';

/**
 * Projectreferee Model
 *
 * @author	Kurt Norgaz
 */
class JoomleagueModelProjectReferee extends JoomleagueModelItem
{

	/**
	 * Method to remove all project referees of only one project
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function deleteOne($project_id)
	{
		if ($project_id > 0)
		{
			$query='DELETE FROM #__joomleague_project_referee WHERE project_id='.$project_id;
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
	 * Method to load content project player data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query='	SELECT	p.firstname,
								p.lastname,
								p.nickname,
								r.*,
								u.name AS editor,
								r.picture,
								ass.rules
						FROM #__joomleague_person AS p
						INNER JOIN #__joomleague_project_referee AS r ON r.person_id=p.id
						LEFT JOIN #__users AS u ON u.id=r.checked_out
						LEFT JOIN #__assets AS ass ON ass.id = r.asset_id
						WHERE r.id='.(int) $this->_id.'
						AND p.published = 1';
			$this->_db->setQuery($query);
			$this->_data=$this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the team data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$projectreferee=new stdClass();
			$projectreferee->id					= 0;
			$projectreferee->project_id			= 0;
			$projectreferee->person_id			= 0;
			$projectreferee->project_position_id= null;
			$projectreferee->notes				= null;
			$projectreferee->picture			= '';
			$projectreferee->published			= 1;
			$projectreferee->extended			= null;
			$projectreferee->ordering			= 0;
			$projectreferee->checked_out		= 0;
			$projectreferee->checked_out_time	= 0;
			$projectreferee->modified			= null;
			$projectreferee->modified_by		= null;
			$this->_data						= $projectreferee;
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to return a positions array of referees (id,position)
	 *
	 * @access	public
	 * @return	array
	 */
	function getRefereePositions()
	{
		$option = JRequest::getCmd('option');
		$app 	= JFactory::getApplication();
		$project_id=$app->getUserState($option.'project');
		$query='	SELECT	ppos.id AS value,
							pos.name AS text
					FROM #__joomleague_position AS pos
					INNER JOIN #__joomleague_project_position AS ppos ON pos.id=ppos.position_id
					WHERE ppos.project_id='. $this->_db->Quote($project_id).' AND pos.persontype=3';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		else
		{
			foreach ($result as $position){$position->text=JText::_($position->text);}
			return $result;
		}
	}

	/**
	 * Method to return a matchdays array (id,position)
	 *
	 * @access	public
	 * @return	array
	 *
	 */
	function getProjectMatchdays()
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$project_id=$app->getUserState($option.'project');
		$query='	SELECT	roundcode AS value,
							name AS text
					FROM #__joomleague_round
					WHERE project_id='.$project_id.'
					ORDER by roundcode ';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObjectList())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
	}

	/**
	* Method to assign teams of an existing project to a copied project
	*
	* @access	public
	* @return	array
	*/
	// Needs to be adapted to work with persons ans not projectreferee
	function cpCopyProjectReferees($post)
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$old_id=(int)$post['old_id'];
		$project_id=(int)$post['id'];
		//copy ProjectReferees
		$query='SELECT * FROM #__joomleague_project_referee WHERE project_id='.$old_id;
		$this->_db->setQuery($query);
		if ($results=$this->_db->loadAssocList())
		{
			foreach($results as $result)
			{
				$p_player = $this->getTable();
				$p_player->bind($result);
				$p_player->set('id',NULL);
				$p_player->set('project_id',$project_id);

				if (!$p_player->store())
				{
					echo $this->_db->getErrorMsg();
					return false;
				}
			}
		}
		return true;
	}

	/**
	* Method to return a persons record
	*
	* @access  public
	* @return  array
	*/
	function getPerson($id)
	{
		$query='SELECT * FROM #__joomleague_person WHERE id='.$this->_db->Quote($id).' AND published = 1';
		$this->_db->setQuery($query);
		if (!$result=$this->_db->loadObject())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $result;
	}
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'projectreferee', $prefix = 'table', $config = array())
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
		$form = $this->loadForm('com_joomleague.'.$this->name, $this->name,
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
		$data = JFactory::getApplication()->getUserState('com_joomleague.edit.'.$this->name.'.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}
	
	public function getName() {
		return 'project_referee';
	}
}
