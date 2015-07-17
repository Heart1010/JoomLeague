<?php
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT.'/controllers/ranking.php';
require_once JPATH_COMPONENT.'/controllers/matrix.php';

class JoomleagueControllerResultsRankingMatrix extends JoomleagueController
{
    public function display($cachable = false, $urlparams = false)
    {
        $this->showprojectheading();
        JoomleagueControllerRanking::showRanking( );
        JoomleagueControllerMatrix::showMatrix( );
        $this->showbackbutton();
        $this->showfooter();
    }
}
