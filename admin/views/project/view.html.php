<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.html.parameter.element.timezones');

/**
 * HTML View class
 */
class JoomleagueViewProject extends JLGView 
{
	public function display($tpl = null) 
	{
		$this->form = $this->get('form');
		
		$isNew = ($this->form->getValue('id') < 1);
		if ($isNew) {
			$this->form->setValue('is_utc_converted', null, 1);
		}
		$edit = JRequest::getVar('edit');
		$copy = JRequest::getVar('copy');
		
		// add javascript
		$document = JFactory::getDocument();
		$version = urlencode(JoomleagueHelper::getVersion () );
		
		// @todo: check!
		$document->addScript(JUri::root().'administrator/components/com_joomleague/models/forms/project.js?v='.$version);
		
		$this->edit = $edit;
		$this->copy = $copy;
		
		$extended = $this->getExtended($this->form->getValue('extended'), 'project');
		$this->extended = $extended;
		$this->addToolbar ();
		parent::display ( $tpl );
	}
	
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		// Set toolbar items for the page
		if ($this->copy) {
			$toolbarTitle = JText::_ ( 'COM_JOOMLEAGUE_ADMIN_PROJECT_COPY_PROJECT' );
		} else {
			$toolbarTitle = (! $this->edit) ? JText::_ ( 'COM_JOOMLEAGUE_ADMIN_PROJECT_ADD_NEW' ) : JText::_ ( 'COM_JOOMLEAGUE_ADMIN_PROJECT_EDIT' ) . ': ' . $this->form->getValue ( 'name' );
			JToolBarHelper::divider ();
		}
		JToolBarHelper::title($toolbarTitle, 'jl-ProjectSettings');
		
		if (! $this->copy) {
			JLToolBarHelper::apply('project.apply');
			JLToolBarHelper::save('project.save');
		} else {
			JLToolBarHelper::save('project.copysave');
		}
		JToolBarHelper::divider ();
		if ((! $this->edit) || ($this->copy)) {
			JLToolBarHelper::cancel ( 'project.cancel' );
		} else {
			// for existing items the button is renamed `close`
			JLToolBarHelper::cancel ( 'project.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE' );
		}
		JToolBarHelper::help('screen.joomleague', true);
	}
}
