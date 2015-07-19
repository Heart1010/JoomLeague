<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Teamstaff Controller
 */
class JoomleagueControllerTeamSTaff extends JoomleagueController
{
	protected $view_list = 'teamstaffs&task=teamstaff.display';

	public function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('add','display');
		$this->registerTask('edit','display');
		$this->registerTask('apply','save');
	}

	public function display($cachable = false, $urlparams = false)
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$model=$this->getModel('teamstaffs');
		$viewType=$document->getType();
		$view=$this->getView('teamstaffs',$viewType);
		$view->setModel($model,true);  // true is for the default model;

		$projectws=$this->getModel('project');

		$projectws->setId($app->getUserState($option.'project',0));
		$view->setModel($projectws);

		$teamws=$this->getModel('projectteam');
		$teamws->setId($app->getUserState($option.'project_team_id',0));
		$view->setModel($teamws);

		$task = $this->getTask();
		
		switch($task)
		{
			case 'add'	 :
				{
					JRequest::setVar('hidemainmenu',0);
					JRequest::setVar('layout','form');
					JRequest::setVar('view','teamstaff');
					JRequest::setVar('edit',false);

					$model=$this->getModel('teamstaff');
					#$model->checkout();
				} break;

			case 'edit'	:
				{
					$model=$this->getModel('teamstaff');
					$viewType=$document->getType();
					$view=$this->getView('teamstaff',$viewType);
					$view->setModel($model,true);  // true is for the default model;

					$projectws->setId($app->getUserState($option.'project',0));
					$view->setModel($projectws);

					$teamws=$this->getModel('projectteam');

					$teamws->setId($app->getUserState($option.'project_team_id',0));
					$view->setModel($teamws);

					JRequest::setVar('hidemainmenu',0);
					JRequest::setVar('layout','form');
					JRequest::setVar('view','teamstaff');
					JRequest::setVar('edit',true);

					// Checkout the project
					$model=$this->getModel('teamstaff');
					#$model->checkout();
				} break;

		}
		parent::display();
	}

	public function editlist()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$model=$this->getModel('teamstaffs');
		$viewType=$document->getType();
		$view=$this->getView('teamstaffs',$viewType);
		$view->setModel($model,true);  // true is for the default model;

		$projectws=$this->getModel('project');
		$projectws->setId($app->getUserState($option.'project',0));
		$view->setModel($projectws);
		$teamws=$this->getModel('projectteam');

		$teamws->setId($app->getUserState($option.'project_team_id',0));
		$view->setModel($teamws);

		JRequest::setVar('hidemainmenu',1);
		JRequest::setVar('layout','editlist');
		JRequest::setVar('view','teamstaffs');
		JRequest::setVar('edit',true);

		parent::display();
	}

	public function save_teamstaffslist()
	{
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		$project=JRequest::getVar('project','post');
		$post['id']=(int)$cid[0];
		$post['project_id']=$project;
		$model=$this->getModel('teamstaffs');
		if ($model->store($post))
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_TEAMSTAFF_SAVED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_ERROR_TEAMSTAFF_SAVED').$model->getError();
		}
		// Check the table in so it can be edited.... we are done with it anyway
		//$model->checkin();
		$link='index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display';
		$this->setRedirect($link,$msg);
	}

	public function save()
	{
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		$post['id']=(int) $cid[0];
		// decription must be fetched without striping away html code
		$post['notes']=JRequest::getVar('notes','none','post','STRING',JREQUEST_ALLOWHTML);
		$model=$this->getModel('teamstaff');
		if ($model->store($post))
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_SAVED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_ERROR_SAVE').$model->getError();
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		
		$task = $this->getTask();
		
		if ($task=='save')
		{
			$link='index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display';
		}
		else
		{
			$link='index.php?option=com_joomleague&task=teamstaff.edit&cid[]='.$post['id'];
		}
		#echo $msg;
		$this->setRedirect($link,$msg);
	}

	// save the checked rows inside the project teams list
	public function saveshort()
	{
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		$model=$this->getModel('teamstaffs');
		$model->storeshort($cid,$post);
		if ($model->storeshort($cid,$post))
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_UPDATED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_ERROR_UPDATED').$model->getError();
		}
		$link='index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display';
		$this->setRedirect($link,$msg);
	}

	public function remove()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$project_id = $app->getUserState($option.'project',0);
		$user = JFactory::getUser();
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_TO_DELETE'));}
		// Access checks.
		foreach ($cid as $i => $id)
		{
			if (!$user->authorise('core.admin', 'com_joomleague') ||
				!$user->authorise('core.admin', 'com_joomleague.project.'.(int) $project_id) ||
				!$user->authorise('core.delete', 'com_joomleague.team_staff.'.(int) $id))
			{
				// Prune items that you can't delete.
				unset($cid[$i]);
				JError::raiseNotice(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
			}
		}
		$model=$this->getModel('team');
		if(!$model->delete($cid)){echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";}
		$this->setRedirect('index.php?option=com_joomleague&view=teams&task=teamstaff.display');
	}

	public function select()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$app->setUserState($option.'project_team_id',JRequest::getVar('project_team_id'));
		$app->setUserState($option.'team_id',JRequest::getVar('team_id'));
		$app->setUserState($option.'team',JRequest::getVar('project_team_id'));
		$this->setRedirect('index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display');
	}

	public function assign()
	{
		//redirect to teamstaffs page,with a message
		$msg=JText::_('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_ASSIGN');
		$this->setRedirect('index.php?option=com_joomleague&task=person.display&view=persons&layout=assignplayers&type=1&hidemainmenu=1',$msg);
	}

	public function unassign()
	{
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		$model=$this->getModel('teamstaffs');
		$nDeleted=$model->remove($cid);
		if ($nDeleted!=count($cid))
		{
			$msg=JText::sprintf('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_UNASSIGN',$nDeleted);
			$msg .= '<br/>'.$model->getError();
			$this->setRedirect('index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display',$msg,'error');
		}
		else
		{
			$msg=JText::sprintf('COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_CTRL_UNASSIGN',$nDeleted);
			$this->setRedirect('index.php?option=com_joomleague&view=teamstaffs&task=teamstaff.display',$msg);
		}
	}
	
	/**
	 * Proxy for getModel
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'Teamstaff', $prefix = 'JoomleagueModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
