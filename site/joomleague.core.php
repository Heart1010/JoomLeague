<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

/**
 * this file perform the basic init and includes for joomleague
 */

if (version_compare(phpversion(), '5.3.0', '<')===true) {
	echo  '<div style="font:12px/1.35em arial, helvetica, sans-serif;"><div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;"><h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">'.JText::_("COM_JOOMLEAGUE_INVALID_PHP1").'</h3></div>'.JText::_("COM_JOOMLEAGUE_INVALID_PHP2").'</div>';
	return;
}

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}
define('JLG_PATH_SITE',  JPATH_SITE.'/components/com_joomleague');
define('JLG_PATH_ADMIN', JPATH_SITE.'/administrator/components/com_joomleague');
require_once JLG_PATH_ADMIN.'/defines.php';

require_once JLG_PATH_SITE.'/assets/classes/jlgcontroller.php' ;
require_once JLG_PATH_SITE.'/assets/classes/jlgcontrolleradmin.php' ;
require_once JLG_PATH_SITE.'/assets/classes/jlgmodel.php' ;
require_once JLG_PATH_SITE.'/assets/classes/jlgview.php' ;
require_once JLG_PATH_SITE.'/assets/classes/jllanguage.php' ;

require_once JLG_PATH_SITE.'/helpers/route.php';
require_once JLG_PATH_SITE.'/helpers/countries.php';
require_once JLG_PATH_SITE.'/helpers/extraparams.php';
require_once JLG_PATH_SITE.'/helpers/ranking.php';
require_once JLG_PATH_SITE.'/helpers/html.php';

require_once JLG_PATH_ADMIN.'/helpers/joomleaguehelper.php';
require_once JLG_PATH_ADMIN.'/tables/jltable.php';

JTable::addIncludePath(JLG_PATH_ADMIN.'/tables');

require_once JLG_PATH_ADMIN.'/helpers/plugins.php';

$task = JFactory::getApplication()->input->get('task');
$option = JRequest::getCmd('option');
if($task != '' && $option == 'com_joomleague')  {
	if (!JFactory::getUser()->authorise($task, 'com_joomleague')) {
		//display the task which is not handled by the access.xml
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR').' Task: '  .$task);
	}
}
