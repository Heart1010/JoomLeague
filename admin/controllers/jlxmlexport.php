<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * JLXMLExport Controller
 *
 * @author	Kurt Norgaz
 */
class JoomleagueControllerJLXMLExport extends JoomleagueController
{

	public function __construct()
	{
		parent::__construct();

		$model = $this->getModel('jlxmlexport');
		$export = $model->exportData();

		if ($export){echo $export;}
	}

}
