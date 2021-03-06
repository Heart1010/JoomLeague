<?php
/**
 * @package	 Joomla
 * @subpackage  Joomleague eventsranking module
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

// Load the standard events from the component language file
$language = JFactory::getLanguage();
$language->load('com_joomleague', JPATH_SITE);

$list = modJLGEventsrankingHelper::getData($params);

$document = JFactory::getDocument();
//add css file
$document->addStyleSheet(JUri::base().'modules/mod_joomleague_eventsranking/css/mod_joomleague_eventsranking.css');

require(JModuleHelper::getLayoutPath('mod_joomleague_eventsranking'));