<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	mod_toolbar
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined ( '_JEXEC' ) or die ();

// Import dependancies.
jimport ( 'joomla.html.toolbar' );

// Get the toolbar.
if (empty ( $toolbar )) {
	$toolbar = JToolBar::getInstance ( 'toolbar' )->render ( 'toolbar' );
	// Get the component title div
	if (!isset ( $title ) && isset ( JFactory::getApplication ()->JComponentTitle )) {
		$title = JFactory::getApplication ()->JComponentTitle;
		unset(JFactory::getApplication ()->JComponentTitle);
	}
}
require JModuleHelper::getLayoutPath ( 'mod_toolbar', $params->get ( 'layout', 'default' ) );
