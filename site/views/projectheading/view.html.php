<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewProjectHeading extends JLGView
{
    public function display( $tpl = null )
    {
        $model = $this->getModel();
        $overallconfig = $model->getOverallConfig();
        $project = $model->getProject();
        $this->project = $project;
        $division = $model->getDivision(JRequest::getInt('division', 0));
		$this->division = $division;
        $this->overallconfig = $overallconfig;
        parent::display($tpl);
    }
}
