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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Update Controller
 *
 * @author		Kurt Norgaz
 */
class JoomleagueControllerUpdate extends JoomleagueController
{

	public function __construct()
	{
		// Register Extra tasks
		parent::__construct();
	}

	public function display($cachable = false, $urlparams = false)
	{
		$document = JFactory::getDocument();
		$model=$this->getModel('updates');
		$viewType=$document->getType();
		$view=$this->getView('updates',$viewType);
		$view->setModel($model,true);	// true is for the default model;
		$view->setLayout('updates');

		parent::display();
	}

	public function save()
	{
		JToolBarHelper::back(JText::_('COM_JOOMLEAGUE_BACK_UPDATELIST'),JRoute::_('index.php?option=com_joomleague&view=updates&task=update.display'));
		$post=JRequest::get('post');
		$file_name=JRequest::getVar('file_name');
		$path=explode('/',$file_name);
		if (count($path) > 1)
		{
			$filepath=JPATH_COMPONENT_SITE.'/extensions/'.$path[0].'/admin/install/'.$path[1];
		}
		else
		{
			$filepath=JPATH_COMPONENT_ADMINISTRATOR.'/assets/updates/'.$path[0];
		}
		$model=$this->getModel('updates');
		echo JText::sprintf('Updating from file [ %s ]','<b>'.JPath::clean($filepath).'</b>');
		if (JFile::exists($filepath))
		{
			$model->loadUpdateFile($filepath,$file_name);
		}
		else
		{
			echo JText::_('Update file not found!');
		}
	}
}
