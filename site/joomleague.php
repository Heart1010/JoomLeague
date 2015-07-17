<?php
/**
 * @author		Wolfgang Pinitsch <andone@mfga.at>
 * @copyright	Copyright (C) 2005-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;

require_once JPATH_ROOT.'/components/com_joomleague/joomleague.core.php';
require_once JPATH_COMPONENT.'/controller.php';

require_once JLG_PATH_SITE .'/helpers/extensioncontroller.php';

// Component Helper
jimport('joomla.application.component.helper');

$controller = JLGController::getInstance('joomleague');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
