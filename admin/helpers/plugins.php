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

//load com_joomleague_sport_types.ini
$extension 	= "com_joomleague_sport_types";
$lang 		= JFactory::getLanguage();
$source 	= JPATH_ADMINISTRATOR . '/components/' . $extension;
$lang->load("$extension", JPATH_ADMINISTRATOR, null, false, false)
||	$lang->load($extension, $source, null, false, false)
||	$lang->load($extension, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
||	$lang->load($extension, $source, $lang->getDefault(), false, false)
||	$lang->load('com_joomleague_sport_types', JPATH_ADMINISTRATOR.'/components/com_joomleague', 'en-GB', true);

JPluginHelper::importPlugin('extension', 'joomleague_esport');
