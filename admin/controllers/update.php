<?php
/**
* @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Joomleague Component Update/Samples Controller
 *
 * @author		Kurt Norgaz
 * @package		JoomLeague
 * @since		1.5
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
			$filepath=JPATH_COMPONENT_SITE.DS.'extensions'.DS.$path[0].DS.'admin'.DS.'install'.DS.$path[1];
		}
		else
		{
			$filepath=JPATH_COMPONENT_ADMINISTRATOR.DS.'assets'.DS.'updates'.DS.$path[0];
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
?>