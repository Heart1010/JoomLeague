<?php

/**
 * @package	 Joomla
 * @subpackage  Joomleague playgroundplan module
 * @copyright	Copyright (C) 2005-2015 joomleague.at. All rights reserved.
 * @license	 GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';
require_once JPATH_SITE.'/components/com_joomleague/joomleague.core.php';

$list = modJLGPlaygroundplanHelper::getData($params);

$document = JFactory::getDocument();


//add css file
$document->addStyleSheet(JUri::base().'modules/mod_joomleague_playgroundplan/css/mod_joomleague_playgroundplan.css');

$mode = $params->def("mode");

switch ($mode)
	{
	case 0:
		$document->addScript(JUri::base().'modules/mod_joomleague_playgroundplan/js/qscroller.js');
		require_once dirname(__FILE__).'/js/ticker.js';
		break;
	case 1:
		break;
}

require(JModuleHelper::getLayoutPath('mod_joomleague_playgroundplan'));