<?php
/**
 * Joomleague
 * 
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

/**
 * Joomleague Common Controller
 */

class JoomleagueController extends JLGControllerAdmin
{

	public function display($cachable = false, $urlparams = false)
	{
		// display the left menu only if hidemainmenu is not true
		$hidemainmenu = $this->input->get('hidemainmenu',false);
		
		if (!$hidemainmenu) {
			$show_menu = true;
		} else {
			$show_menu = false;
		}
		
		// display left menu
		$viewName=$this->input->getCmd('view', '');	
		$layoutName=$this->input->getCmd('layout', 'default');
		if($viewName == '' && $layoutName=='default') {
			$this->input->getCmd('view', 'projects');
			$viewName = "projects";
		}
		if ($viewName != 'about' && $show_menu) {
			$this->ShowMenu();
		} else {
			$pid=JRequest::getVar('pid',array(0),'','array');
			if($pid[0] > 0)
			{
				$option = $this->input->getCmd('option');
				$app = JFactory::getApplication();
				$app->setUserState($option.'project',$pid[0]);
			}
		}
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView($viewName,$viewType);
		$view->setLayout($layoutName);
		$model = $this->getModel($viewName);
		$view->setModel($model, true);
		$view->display();
		parent::display($cachable, $urlparams);
	}

	public function ShowMenu()
	{
		$option		= $this->input->getCmd('option');
		$app		= JFactory::getApplication();
		$document	= JFactory::getDocument();
		$viewType	= $document->getType();
		$view		= $this->getView('joomleague',$viewType);
		if ($model = $this->getModel('project'))
		{
			// Push the model into the view (as default)
			$model->setId($app->getUserState($option.'project',0));
			$view->setModel($model,true);
		}
		$view->display();
	}

	public function ShowMenuExtension()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView('joomleague',$viewType);
		$view->setLayout('extension');
		$view->display();
	}

	public function ShowMenuFooter()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView('joomleague',$viewType);
		$view->setLayout('footer');
		$view->display();
	}

	public function selectws()
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();

		$stid	= JRequest::getVar('stid',	array(0),'','array');
		$pid	= JRequest::getVar('pid',	array(0),'','array');
		$tid	= JRequest::getVar('tid',	array(0),'','array');
		$rid	= JRequest::getVar('rid',	array(0),'','array');
		$sid	= JRequest::getVar('seasonnav',	array(0),'','array');
		$act	= $this->input->get('act',0);

		$seasonnav = $this->input->getInt('seasonnav');
		$app->setUserState($option.'seasonnav', $seasonnav);

		switch ($act)
		{
			case 'projects':
				if ($app->setUserState($option.'project',(int)$pid[0]))
				{
					$app->setUserState($option.'project_team_id','0');
					$this->setRedirect('index.php?option=com_joomleague&task=joomleague.workspace&layout=panel&pid[]='.$pid[0],JText::_('COM_JOOMLEAGUE_ADMIN_CTRL_PROJECT_SELECTED'));
				}
				else
				{
					$this->setRedirect('index.php?option=com_joomleague&view=projects&task=project.display');
				}
				break;

			case 'teams':
				$app->setUserState($option.'project_team_id',(int)$tid[0]);
				if ((int) $tid[0] != 0)
				{
					$this->setRedirect('index.php?option=com_joomleague&view=teamplayers&task=teamplayer.display',JText::_('COM_JOOMLEAGUE_ADMIN_CTRL_TEAM_SELECTED'));
				}
				else
				{
					$this->setRedirect('index.php?option=com_joomleague&task=joomleague.workspace&layout=panel&pid[]='.$pid[0]);
				}
				break;

			case 'rounds':
				if ((int) $rid[0] != 0)
				{
					$this->setRedirect('index.php?option=com_joomleague&view=matches&task=match.display&rid[]='.$rid[0],JText::_('COM_JOOMLEAGUE_ADMIN_CTRL_ROUND_SELECTED'));
				}
				break;

			case 'seasons':
				$this->setRedirect('index.php?option=com_joomleague&view=projects&task=project.display&sid[]='.$sid[0], JText::_('COM_JOOMLEAGUE_ADMIN_CTRL_SEASON_SELECTED'));
				break;

			default:
				if ($app->setUserState($option.'sportstypes',(int)$stid[0]))
				{
					$app->setUserState($option.'project','0');
					$this->setRedirect('index.php?option=com_joomleague&task=project.display&view=projects&stid[]='.$stid[0],JText::_('COM_JOOMLEAGUE_ADMIN_CTRL_SPORTSTYPE_SELECTED'));
				}
				else
				{
					$this->setRedirect('index.php?option=com_joomleague&view=sportstypes&task=sportstype.display');
				}
		}

	}
	
}

/**
 *
 * just to display the cpanel
 * @author And_One
 */
class JoomleagueControllerJoomleague extends JoomleagueController {
}
