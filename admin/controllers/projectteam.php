<?php
/**
* @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Joomleague Component Controller
 *
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueControllerProjectteam extends JoomleagueController
{
	protected $view_list = 'projectteams&task=projectteam.display';
	
	public function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('add', 'edit');
		$this->registerTask('apply', 'save');
	}

	public function edit()
	{
		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();
		$view		= $this->getView  ('projectteam', $viewType);

		$projectws = $this->getModel ('project');
		$projectws->setId($mainframe->getUserState('com_joomleagueproject', 0));
		$view->setModel($projectws);

		JRequest::setVar('view', 'projectteam');
		JRequest::setVar('layout', 'form' );
		JRequest::setVar('hidemainmenu',JRequest::getVar('hidemainmenu',0));

		$model 	= $this->getModel('projectteam');
		$user	= JFactory::getUser();

		// Error if checkedout by another administrator
		if ($model->isCheckedOut($user->get('id'))) {
			$this->setRedirect('index.php?option=com_joomleague&task=projectteam.display&view=projectteams', JText::_('EDITED BY ANOTHER ADMIN'));
		}

		$model->checkout();

		parent::display();
	}

	public function display($cachable = false, $urlparams = false)
	{
		$option = JRequest::getCmd('option');

		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
	 	$model		= $this->getModel ('projectteams');
		$viewType	= $document->getType();
		$view		= $this->getView  ('projectteams', $viewType);
		$view->setModel($model, true);  // true is for the default model;

		$projectws = $this->getModel ('project');
		$projectws->setId($mainframe->getUserState($option . 'project', 0));
		$view->setModel($projectws);

		parent::display();
	}

	public function storechangeteams()
	{
	  	$option		= JRequest::getCmd('option');
	  	$app		= JFactory::getApplication();
		$model		= $this->getModel('projectteams');
	  	$post		= JRequest::get('post');
	 
	  	$oldteamids	= JRequest::getVar('oldptid', array(), 'post', 'array');
		$newteamids	= JRequest::getVar('newptid', array(), 'post', 'array');
	
		if ( $oldteamids )
	    {
	    	if(!$model->changeTeamId($oldteamids, $newteamids, $app)) {
	    		$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_ERROR_SAVE') . $model->getError(); 
	    	}
	    }	
	    $link = 'index.php?option=com_joomleague&view=projectteams&task=projectteam.display';
	  	$this->setRedirect($link, $msg);  
	}
  
  	public function changeteams()
	{
		$option = JRequest::getCmd('option');

		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
		$model		= $this->getModel ('projectteams');
		$viewType	= $document->getType();
		$view		= $this->getView  ('projectteams', $viewType);
		$view->setModel($model, true);  // true is for the default model;

		$projectws = $this->getModel ('project');
		$projectws->setId($mainframe->getUserState($option . 'project', 0));
		$view->setModel($projectws);

		JRequest::setVar('hidemainmenu',JRequest::getVar('hidemainmenu',0));
				
		JRequest::setVar('layout', 'changeteams' );
		JRequest::setVar('view', 'projectteams');
		JRequest::setVar('edit', true);

		// Checkout the project
		//	$model = $this->getModel('projectteam');

		parent::display();
	}
  
  function editlist()
	{
		$option = JRequest::getCmd('option');

		$mainframe	= JFactory::getApplication();
		$document	= JFactory::getDocument();
		$model		= $this->getModel ('projectteams');
		$viewType	= $document->getType();
		$view		= $this->getView  ('projectteams', $viewType);
		$view->setModel($model, true);  // true is for the default model;

		$projectws = $this->getModel ('project');
		$projectws->setId($mainframe->getUserState($option . 'project', 0));
		$view->setModel($projectws);

		JRequest::setVar('hidemainmenu',JRequest::getVar('hidemainmenu',0));
		JRequest::setVar('layout', 'editlist' );
		JRequest::setVar('view', 'projectteams');
		JRequest::setVar('edit', true);

		// Checkout the project
		//	$model = $this->getModel('projectteam');

		parent::display();
	}

	public function save_teamslist()
	{
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar('cid', array(0), 'post', 'array');
		$post['id'] = (int) $cid[0];

		$model = $this->getModel('projectteams');

		if ($model->store($post))
		{
			//clear ranking cache
			$cache = JFactory::getCache('joomleague.project'.$post['id']);
			$cache->clean();
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_ERROR_SAVE') . $model->getError();
		}

		$link = 'index.php?option=com_joomleague&view=projectteams&task=projectteam.display';
		$this->setRedirect($link, $msg);
	}

	public function save()
	{
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		//get the projectid
		$option = JRequest::getCmd('option');
		$mainframe	= JFactory::getApplication();
 		$project_id = $mainframe->getUserState($option . 'project');
		
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar('cid', array(0), 'post', 'array');
		$post['id'] = (int) $cid[0];
		// decription must be fetched without striping away html code
		$post['notes'] = JRequest:: getVar('notes', 'none', 'post', 'STRING', JREQUEST_ALLOWHTML);
		//$post['extended'] = JRequest:: getVar('extended', 'none', 'post', 'STRING', JREQUEST_ALLOWHTML);
		//echo '<pre>'.print_r($post,true).'</pre>';

		$model = $this->getModel('projectteam');

		if (isset($post['add_trainingData']))
		{
			if ($model->addNewTrainigData($post['id'],(int) $post['project_id']))
			{
				$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TRAINING');
			}
			else
			{
				$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_ERROR_TRAINING').$model->getError();
			}
			//echo $msg;
		}

		if (isset($post['tdCount'])) // Existing Team Trainingdata
		{
			if ($model->saveTrainigData($post))
			{
				$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TRAINING_SAVED');
			}
			else
			{
				$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TRAINING_ERROR_SAVE').$model->getError();
			}

			if ($model->checkAndDeleteTrainigData($post))
			{
				$msg .= ' - '.JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TRAINING_DELETED');
			}
			else
			{
				$msg = ' - '.JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TRAINING_ERROR_DELETED').$model->getError();
			}
			$msg .= ' - ';
		}

		if ($model->store($post))
		{
			//clear ranking cache
			$cache = JFactory::getCache('joomleague.project'.$project_id);
			$cache->clean();
			
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TEAM_SAVED');
		}
		else
		{
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_TEAM_ERROR_SAVE').$model->getError();
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		if ($this->getTask()=='save')
		{
			$link = 'index.php?option=com_joomleague&view=projectteams&task=projectteam.display';
		}
		else
		{
			$link = 'index.php?option=com_joomleague&task=projectteam.edit&cid[]=' . $post['id'];
		}
		//echo $msg;
		$this->setRedirect($link,$msg);
	}

	// save the checked rows inside the project teams list
	public function saveshort()
	{
		//get the projectid
		$option = JRequest::getCmd('option');
		$mainframe	= JFactory::getApplication();
 		$project_id = $mainframe->getUserState($option . 'project');
		
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		
		$model = $this->getModel('projectteams');
		
		if ($model->storeshort($cid, $post))
		{
			//clear ranking cache
			$cache = JFactory::getCache('joomleague.project'.$project_id);
			$cache->clean();
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_UPDATED');
		}
		else
		{
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_CTRL_ERROR_UPDATED') . $model->getError();
		}

		$link = 'index.php?option=com_joomleague&view=projectteams&task=projectteam.display';
		$this->setRedirect($link, $msg);
	}

	public function remove()
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$project_id=$app->getUserState($option.'project',0);
		$user = JFactory::getUser();
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_TO_DELETE'));
		}
		// Access checks.
		foreach ($cid as $i => $id)
		{
			if (!$user->authorise('core.admin', 'com_joomleague') ||
				!$user->authorise('core.admin', 'com_joomleague.project.'.(int) $project_id) || 
				!$user->authorise('core.delete', 'com_joomleague.project_team.'.(int) $id))
			{
				// Prune items that you can't delete.
				unset($cid[$i]);
				JError::raiseNotice(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
			}
		}
		$model = $this->getModel('team');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect('index.php?option=com_joomleague&view=teams&task=projectteam.display');
	}

	public function publish()
	{
		$this->setRedirect('index.php?option=com_joomleague&view=teams&task=projectteam.display');
	}

	public function unpublish()
	{
		$this->setRedirect('index.php?option=com_joomleague&view=teams&task=projectteam.display');
	}

	public function cancel()
	{
		// Checkin the project
		$model = $this->getModel('projectteam');
		//$model->checkin();

		$this->setRedirect('index.php?option=com_joomleague&view=projectteams&task=projectteam.display');
	}

	/**
	 * copy team to another project
	 */
	public function copy()
	{
		$dest = JRequest::getInt('dest');
		$ptids = JRequest::getVar('ptids', array(), 'post', 'array');
		
		// check if this is the final step
		if (!$dest) 
		{
			JRequest::setVar('view',   'projectteams');
			JRequest::setVar('layout', 'copy');
			
			return parent::display();
		}
		
		$msg  = '';
		$type = 'message';
		
		$model = $this->getModel('projectteams');
		
		if (!$model->copy($dest, $ptids))
		{
			$msg = $model->getError();
			$type = 'error';
		}
		else
		{
			$msg = JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_COPY_SUCCESS');	
		}
		$this->setRedirect('index.php?option=com_joomleague&view=projectteams&task=projectteam.display', $msg, $type);
		$this->redirect();
	}
	
	/**
	 * Proxy for getModel
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 *
	 * @return	object	The model.
	 * @since	1.6
	 */
	public function getModel($name = 'Projectteam', $prefix = 'JoomleagueModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
?>
