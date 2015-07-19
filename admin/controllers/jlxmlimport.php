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
jimport('joomla.filesystem.archive');

/**
 * JLXMLImport Controller
 *
 * @author	Kurt Norgaz
 */
class JoomleagueControllerJLXMLImport extends JoomleagueController
{
	public function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('edit','display');
		$this->registerTask('insert','display');
		$this->registerTask('selectpage','display');
	}

	public function display($cachable = false, $urlparams = false)
	{
		$task = $this->getTask();
		
		switch ($task)
		{
			case 'edit':
				JRequest::setVar('hidemainmenu',0);
				JRequest::setVar('layout','form');
				JRequest::setVar('view','jlxmlimports');
				JRequest::setVar('edit',true);
				break;

			case 'insert':
				JRequest::setVar('hidemainmenu',0);
				JRequest::setVar('layout','info');
				JRequest::setVar('view','jlxmlimports');
				JRequest::setVar('edit',true);
				break;
		}

		parent::display($cachable = false, $urlparams = false);
	}

	public function select()
	{
		$app = JFactory::getApplication();
		$selectType=JRequest::getVar('type',0,'get','int');
		$recordID=JRequest::getVar('id',0,'get','int');
		$app->setUserState('com_joomleague'.'selectType',$selectType);
		$app->setUserState('com_joomleague'.'recordID',$recordID);

		JRequest::setVar('hidemainmenu',1);
		JRequest::setVar('layout','selectpage');
		JRequest::setVar('view','jlxmlimports');

		parent::display();
	}

	public function save()
	{
		// Check for request forgeries
		JSession::checkToken() or die('COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN');
		$msg='';
		JToolBarHelper::back(JText::_('COM_JOOMLEAGUE_GLOBAL_BACK'),JRoute::_('index.php?option=com_joomleague&task=jlxmlimport.display'));
		$app = JFactory::getApplication();
		$post=JRequest::get('post');

		// first step - upload
		if (isset($post['sent']) && $post['sent']==1)
		{
			$upload=JRequest::getVar('import_package',null,'files','array');
			$tempFilePath=$upload['tmp_name'];
			$app->setUserState('com_joomleague'.'uploadArray',$upload);
			$filename='';
			$msg='';
			$dest=JPATH_SITE.'/tmp/'.$upload['name'];
			$extractdir=JPATH_SITE.'/tmp';
			$importFile=JPATH_SITE.'/tmp/joomleague_import.jlg';
			if (JFile::exists($importFile))
			{
				JFile::delete($importFile);
			}
			if (JFile::exists($tempFilePath))
			{
					if (JFile::exists($dest))
					{
						JFile::delete($dest);
					}
					if (!JFile::upload($tempFilePath,$dest))
					{
						JError::raiseWarning(500,JText::_('COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_CANT_UPLOAD'));
						return;
					}
					else
					{
						if (strtolower(JFile::getExt($dest))=='zip')
						{
							$result=JArchive::extract($dest,$extractdir);
							if ($result === false)
							{
								JError::raiseWarning(500,JText::_('COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_EXTRACT_ERROR'));
								return false;
							}
							JFile::delete($dest);
							$src=JFolder::files($extractdir,'jlg',false,true);
							if(!count($src))
							{
								JError::raiseWarning(500,'COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_EXTRACT_NOJLG');
								//todo: delete every extracted file / directory
								return false;
							}
							if (strtolower(JFile::getExt($src[0]))=='jlg')
							{
								if (!@ rename($src[0],$importFile))
								{
									JError::raiseWarning(21,JText::_('COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_ERROR_RENAME'));
									return false;
								}
							}
							else
							{
								JError::raiseWarning(500,JText::_('COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_TMP_DELETED'));
								return;
							}
						}
						else
						{
							if (strtolower(JFile::getExt($dest))=='jlg')
							{
								if (!@ rename($dest,$importFile))
								{
									JError::raiseWarning(21,JText::_('COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_RENAME_FAILED'));
									return false;
								}
							}
							else
							{
								JError::raiseWarning(21,JText::_('COM_JOOMLEAGUE_ADMIN_XML_IMPORT_CTRL_WRONG_EXTENSION'));
								return false;
							}
						}
					}
			}
		}
		$link='index.php?option=com_joomleague&task=jlxmlimport.edit';
		$this->setRedirect($link,$msg);
	}

	public function insert()
	{
		JToolBarHelper::back(JText::_('COM_JOOMLEAGUE_GLOBAL_BACK'),JRoute::_('index.php?option=com_joomleague'));
		$post=JRequest::get('post');

		$link='index.php?option=com_joomleague&task=jlxmlimport.insert';
		echo $link;

		#$this->setRedirect($link);
	}

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_joomleague&jlxmlimport.display');
	}

}
