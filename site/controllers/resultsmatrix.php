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

require_once JPATH_COMPONENT.'/controllers/results.php';
require_once JPATH_COMPONENT.'/controllers/matrix.php';

class JoomleagueControllerResultsMatrix extends JoomleagueController
{
    public function display($cachable = false, $urlparams = false)
    {
        $this->showprojectheading();
        JoomleagueControllerResults::showResults( );
        JoomleagueControllerMatrix::showMatrix( );
        $this->showbackbutton();
        $this->showfooter();
    }
}
