<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JoomleagueViewAbout extends JLGView
{
	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		$model = $this->getModel();
		$about = $model->getAbout();
		$this->assignRef('about',	$about);

        // Set page title
		$this->assignRef('pagetitle', JText::_('COM_JOOMLEAGUE_ABOUT_PAGE_TITLE'));
		$document->setTitle($this->pagetitle);
		
		parent::display($tpl);
	}

}
?>