<?php
/**
 * Joomleague
 * 
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_joomleague')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport('joomla.application.component.controller');

require_once JPATH_ROOT.'/components/com_joomleague/joomleague.core.php';
// Require the base controller
require_once JPATH_COMPONENT.'/controller.php';
require_once JPATH_COMPONENT.'/helpers/jlparameter.php';
require_once JLG_PATH_ADMIN.'/helpers/jltoolbarhelper.php';

require_once JLG_PATH_SITE .'/helpers/extensioncontroller.php';

$controller	= JLGController::getInstance('joomleague');
//$controller->execute(JRequest::getCmd('task'));
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
