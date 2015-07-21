<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class JoomleagueViewAbout extends JLGView
{
	public function display($tpl=null)
	{
		$document = JFactory::getDocument();

		$model = $this->getModel();
		$about = $model->getAbout();
		$this->about = $about;

        // Set page title
		$this->pagetitle = JText::_('COM_JOOMLEAGUE_ABOUT_PAGE_TITLE');
		$document->setTitle($this->pagetitle);
		
		parent::display($tpl);
	}
}
