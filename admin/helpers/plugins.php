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
