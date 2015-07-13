<?php
/**
 * @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package		Joomleague
 * @since 0.1
 */
class JoomleagueViewDivision extends JLGView
{
	function display( $tpl = null )
	{
		if ( $this->getLayout() == 'form' )
		{
			$this->_displayForm( $tpl );
			return;
		}

		parent::display( $tpl );
	}

	function _displayForm( $tpl )
	{
		$option = JRequest::getCmd('option');

		$mainframe	= JFactory::getApplication();
		$project_id = $mainframe->getUserState( 'com_joomleagueproject' );
		$db		= JFactory::getDbo();
		$uri 	= JFactory::getURI();
		$user 	= JFactory::getUser();
		$model	= $this->getModel();

		$lists = array();
		//get the division
		$division	= $this->get( 'data' );
		$isNew		= ( $division->id < 1 );

		// fail if checked out not by 'me'
		if ( $model->isCheckedOut( $user->get( 'id' ) ) )
		{
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'COM_JOOMLEAGUE_ADMIN_DIVISION_THE_DIVISION' ), $division->name );
			$mainframe->redirect( 'index.php?option=' . $option, $msg );
		}

		// Edit or Create?
		if ( !$isNew )
		{
			$model->checkout( $user->get( 'id' ) );
		}
		else
		{
			// initialise new record
			$division->order	= 0;
		}

		$projectws = $this->get( 'Data', 'project' );

		//build the html select list for parent divisions
		$parents[] = JHtml::_( 'select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_SELECT_PROJECT' ) );
		if ( $res =& $model->getParentsDivisions() )
		{
			$parents = array_merge( $parents, $res );
		}
		$lists['parents'] = JHtml::_(	'select.genericlist', $parents, 'parent_id', 'class="inputbox" size="1"', 'value', 'text',
										$division->parent_id );
		unset( $parents );

		$this->assignRef( 'projectws',	$projectws );
		$this->assignRef( 'lists',		$lists );
		$this->assignRef( 'division',	$division );
		$this->assignRef('form',  $this->get('form'));		
		//$extended = $this->getExtended($projectreferee->extended, 'division');
		//$this->assignRef( 'extended', $extended );

		$this->addToolbar();		
		parent::display( $tpl );
	}
	
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{	
		$edit	= JRequest::getVar( 'edit', true );
		$text	= !$edit ? JText::_( 'New' ) : JText::_( 'Edit' ) . ': ' . JText::_( $this->projectws->name ) . ' / ' . $this->division->name;
		// Set toolbar items for the page
		JToolBarHelper::title( $text);

		JLToolBarHelper::save('division.save');
		if (!$edit)
		{
			JLToolBarHelper::cancel('division.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JLToolBarHelper::apply('division.apply');
			JLToolBarHelper::cancel( 'division.cancel', 'Close' );
		}
		JToolBarHelper::help( 'screen.joomleague', true );
	}		

}
?>
