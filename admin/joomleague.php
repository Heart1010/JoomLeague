<?php
/**
 * @author		Wolfgang Pinitsch <andone@mfga.at>
 * @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_joomleague')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport('joomla.application.component.controller');

require_once JPATH_ROOT.DS.'components'.DS.'com_joomleague'.DS.'joomleague.core.php';
// Require the base controller
require_once JPATH_COMPONENT .DS . 'controller.php';
require_once JPATH_COMPONENT .DS . 'helpers' . DS . 'jlparameter.php';
require_once JLG_PATH_ADMIN.DS.'helpers'.DS.'jltoolbarhelper.php';

require_once JLG_PATH_SITE .'/helpers/extensioncontroller.php';

$controller	= JLGController::getInstance('joomleague');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
