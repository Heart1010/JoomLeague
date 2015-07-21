<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @author		Wolfgang Pinitsch <andone@mfga.at>
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
