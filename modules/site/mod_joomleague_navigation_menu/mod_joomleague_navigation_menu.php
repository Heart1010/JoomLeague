<?php
/**
 * @package	 Joomla
 * @subpackage  Joomleague navigation module
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

JHtml::_('behavior.framework');
$document = JFactory::getDocument();
//add css file
$document->addStyleSheet(JUri::base().'modules/mod_joomleague_navigation_menu/css/mod_joomleague_navigation_menu.css');
$document->addScript(JUri::base().'modules/mod_joomleague_navigation_menu/js/mod_joomleague_navigation_menu.js');

$helper = new modJoomleagueNavigationMenuHelper($params);

$seasonselect	= $helper->getSeasonSelect();
$leagueselect	= $helper->getLeagueSelect();
$projectselect	= $helper->getProjectSelect();
$divisionselect = $helper->getDivisionSelect();
$teamselect		= $helper->getTeamSelect();

$defaultview   = $params->get('project_start');
$defaultitemid = $params->get('custom_item_id');

require(JModuleHelper::getLayoutPath('mod_joomleague_navigation_menu'));