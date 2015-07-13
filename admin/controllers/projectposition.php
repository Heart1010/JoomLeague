<?php
/**
* @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
* @license		GNU/GPL,see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License,and as distributed it includes or
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
class JoomleagueControllerProjectposition extends JoomleagueController
{
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
		$option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();
		$document = JFactory::getDocument();
		$model=$this->getModel('projectposition');
		$viewType=$document->getType();
		$view=$this->getView('projectposition',$viewType);
		$view->setModel($model,true);  // true is for the default model;

		$projectws=$this->getModel('project');
		$projectws->setId($mainframe->getUserState($option.'project',0));
		$view->setModel($projectws);

		switch($this->getTask())
		{
			case 'add' :
			{
				JRequest::setVar('layout','form');
				JRequest::setVar('view','projectposition');
				JRequest::setVar('edit',false);

				$model=$this->getModel('projectposition');
				$model->checkout();
			} break;

			case 'edit' :
			{

				JRequest::setVar('layout','form');
				JRequest::setVar('view','projectposition');
				JRequest::setVar('edit',true);

				// Checkout the project
				$model=$this->getModel('projectposition');
				$model->checkout();
			} break;


		}
		parent::display();
	}

	public function save_positionslist()
	{
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		$post['id']=(int) $cid[0];
		$model=$this->getModel('projectposition');
		if ($model->store($post))
		//if (1==2)
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_POSITION_LIST_SAVED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_ERROR_SAVING_POS').$model->getError();
		}
		$link='index.php?option=com_joomleague&view=projectposition&task=projectposition.display';
		$this->setRedirect($link,$msg);
	}

	public function save()
	{
		die('Save in projectposition controller');
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		echo '<br /><pre>2'.print_r($post,true).'~</pre><br />';
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		$post['id']=(int) $cid[0];
		$model=$this->getModel('projectposition');
		//if ($model->store($post))
		if (1==2)
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_TEAM_SAVED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_ERROR_SAVING_TEAM').$model->getError();
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		if ($this->getTask()=='save')
		{
			$link='index.php?option=com_joomleague&view=projectposition&task=projectposition.display';
		}
		else
		{
			$link='index.php?option=com_joomleague&task=projectposition.edit&cid[]='.$post['id'];
		}
		//$this->setRedirect($link,$msg);
	}

	// save the checked rows inside the project positions list
	public function saveshort()
	{
		die('Saveshort in projectposition controller');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		$model=$this->getModel('projectposition');
		$model->storeshort($cid,$post);
		if ($model->storeshort($cid,$post))
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_POSITIONS_UPDATED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_ERROR_UPDATING_POS').$model->getError();
		}
		$link='index.php?option=com_joomleague&view=projectposition&task=projectposition.display';
		$this->setRedirect($link,$msg);
	}

	public function remove()
	{
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_SELECT_TO_DELETE'));}
		$model=$this->getModel('team');
		if(!$model->delete($cid))
		{
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect('index.php?option=com_joomleague&view=positions&task=position.display');
	}

	public function publish()
	{
		$this->setRedirect('index.php?option=com_joomleague&view=positions&task=position.display');
	}

	public function unpublish()
	{
		$this->setRedirect('index.php?option=com_joomleague&view=positions&task=position.display');
	}

	public function cancel()
	{
		// Checkin the project
		$model=$this->getModel('projectposition');
		//$model->checkin();
		$this->setRedirect('index.php?option=com_joomleague&view=projectposition&task=projectposition.display');
	}

	public function orderup()
	{
		$model=$this->getModel('projectposition');
		$model->move(-1);
		$this->setRedirect('index.php?option=com_joomleague&view=projectposition&task=projectposition.display');
	}

	public function orderdown()
	{
		$model=$this->getModel('team');
		$model->move(1);
		$this->setRedirect('index.php?option=com_joomleague&view=projectposition&task=projectposition.display');
	}

	public function saveorder()
	{
		$cid=JRequest::getVar('cid',array(),'post','array');
		$order=JRequest::getVar('order',array(),'post','array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model=$this->getModel('team');
		$model->saveorder($cid,$order);
		$msg='COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_SAVED_NEW_ORDERING';
		$this->setRedirect('index.php?option=com_joomleague&view=projectposition',$msg);
	}

	public function assign()
	{
		$msg=JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_CTRL_SELECT_POS_SAVE');
		$link='index.php?option=com_joomleague&view=projectposition&layout=editlist&task=projectposition.display';
		$this->setRedirect($link,$msg);
	}

}
?>