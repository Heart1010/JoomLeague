<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined( '_JEXEC' ) or die;


/**
 * HTML View class
 */
class JoomleagueViewDivision extends JLGView
{
	public function display($tpl = null)
	{
		if ($this->getLayout() == 'form')
		{
			$this->_displayForm($tpl);
			return;
		}

		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		$option = $this->input->getCmd('option');

		$app	= JFactory::getApplication();
		$project_id = $app->getUserState('com_joomleagueproject');
		$db		= JFactory::getDbo();
		$uri 	= JFactory::getURI();
		$user 	= JFactory::getUser();
		$model	= $this->getModel();

		$lists = array();
		//get the division
		$division	= $this->get('data');
		$isNew		= ($division->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('COM_JOOMLEAGUE_ADMIN_DIVISION_THE_DIVISION'), $division->name );
			$app->redirect('index.php?option=' . $option, $msg);
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout($user->get('id'));
		}
		else
		{
			// initialise new record
			$division->order	= 0;
		}

		$projectws = $this->get('Data','project');

		//build the html select list for parent divisions
		$parents[] = JHtml::_('select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_SELECT_PROJECT' ) );
		if ($res = $model->getParentsDivisions())
		{
			$parents = array_merge($parents, $res);
		}
		$lists['parents'] = JHtml::_('select.genericlist', $parents, 'parent_id', 'class="inputbox" size="1"', 'value', 'text',$division->parent_id );
		unset( $parents );

		$this->projectws = $projectws;
		$this->lists = $lists;
		$this->division = $division;
		$this->form = $this->get('form');		
		//$extended = $this->getExtended($projectreferee->extended, 'division');
		//$this->extended = $extended;

		$this->addToolbar();		
		parent::display( $tpl );
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{	
		$edit	= $this->input->get('edit',true);
		$text	= !$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT') . ': ' . JText::_($this->projectws->name) . ' / ' . $this->division->name;
		// Set toolbar items for the page
		JToolBarHelper::title( $text);

		JLToolBarHelper::save('division.save');
		if (!$edit)
		{
			JLToolBarHelper::cancel('division.cancel');
		}
		else
		{
			JLToolBarHelper::apply('division.apply');
			JLToolBarHelper::cancel('division.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::help('screen.joomleague',true);
	}
}
