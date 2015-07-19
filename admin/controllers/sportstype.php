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
 * Sportstype Controller
 */
class JoomleagueControllerSportsType extends JoomleagueController
{
	protected $view_list = 'sportstypes';
	
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
		$task = $this->getTask();
		
		switch ($task)
		{
			case 'add'	 :
			{
				JRequest::setVar('hidemainmenu',0);
				JRequest::setVar('layout','form');
				JRequest::setVar('view','sportstype');
				JRequest::setVar('edit',false);
				// Checkout the project
				$model=$this->getModel('sportstype');
				$model->checkout();
			} break;
			case 'edit'	:
			{
				JRequest::setVar('hidemainmenu',0);
				JRequest::setVar('layout','form');
				JRequest::setVar('view','sportstype');
				JRequest::setVar('edit',true);
				// Checkout the project
				$model=$this->getModel('sportstype');
				$model->checkout();
			} break;
		}
		parent::display();
	}

	public function save()
	{
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(0),'post','array');
		$post['id']=(int) $cid[0];
		$model=$this->getModel('sportstype');
		if ($model->store($post))
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_CTRL_STAT_SAVED');
		}
		else
		{
			$msg=JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_CTRL_ERROR_SAVE').$model->getError();
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		
		$task = $this->getTask();
		
		if ($task=='save')
		{
			$link='index.php?option=com_joomleague&view=sportstypes&task=sportstype.display';
		}
		else
		{
			$link='index.php?option=com_joomleague&task=sportstype.edit&cid[]='.$post['id'];
		}
		$this->setRedirect($link,$msg);
	}

	public function remove()
	{
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_TO_DELETE'));}
		
		$model=$this->getModel('sportstype');
		if (!$model->delete($cid))
		{
			$app = JFactory::getApplication()->enqueueMessage($model->getError(),'warning');
			$this->setRedirect('index.php?option=com_joomleague&view=sportstypes&task=sportstype.display');
		}
		else
		{
			$app = JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMLEAGUE_ADMIN_SPORTTYPE_CTRL_DELETED'),'message');
		}
		$this->setRedirect('index.php?option=com_joomleague&view=sportstypes&task=sportstype.display');
	}

	public function cancel()
	{
		// Checkin the project
		$model=$this->getModel('sportstype');
		$model->checkin();
		$this->setRedirect('index.php?option=com_joomleague&view=sportstypes&task=sportstype.display');
	}

	public function import()
	{
		JRequest::setVar('view','import');
		JRequest::setVar('table','sports_type');
		parent::display();
	}
	
	public function export()
	{
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		$post=JRequest::get('post');
		$cid=JRequest::getVar('cid',array(),'post','array');
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_TO_EXPORT'));}
		$model = $this->getModel("sportstype");
		$model->export($cid, "sports_type", "SportsType");
	}
	
	/**
	 * Proxy for getModel
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 *
	 * @return	object	The model.
	 */
	public function getModel($name = 'SportsType', $prefix = 'JoomleagueModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
