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
 * @package	JoomLeague
 * @since	0.1
*/
class JoomleagueViewTeamPlayer extends JLGView
{

	function display( $tpl = null )
	{
		$mainframe	= JFactory::getApplication();
		$uri		= JFactory::getURI();
		$user		= JFactory::getUser();
		$model		= $this->getModel();
		$lists		= array();
		$projectws	= $this->get( 'Data', 'project' );
		$teamws	 	= $this->get( 'Data', 'project_team' );

		//get the project_player data of the project_team
		$project_player	= $this->get( 'Data' );
		$isNew			= ( $project_player->id < 1 );

		// fail if checked out not by 'me'
		if ( $model->isCheckedOut( $user->get( 'id' ) ) )
		{
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'COM_JOOMLEAGUE_ADMIN_TEAMPLAYER_THEPLAYER' ), $project_player->name );
			$mainframe->redirect( 'index.php?option=com_joomleague', $msg );
		}

		// Edit or Create?
		if ( $isNew ) {
			$project_player->order = 0;
		}

		//build the html select list for positions
		#$selectedvalue = ( $project_player->position_id ) ? $project_player->position_id : $default_person->position_id;
		$selectedvalue = $project_player->project_position_id;
		$projectpositions = array();
		$projectpositions[] = JHtml::_('select.option',	'0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_SELECT_POSITION' ) );
		if ( $res =& $model->getProjectPositions() )
		{
			$projectpositions = array_merge( $projectpositions, $res );
		}
		$lists['projectpositions'] = JHtml::_(	'select.genericlist',
				$projectpositions,
				'project_position_id',
				'class="inputbox" size="1"',
				'value',
				'text', $selectedvalue );
		unset($projectpositions);

		$matchdays = JoomleagueHelper::getRoundsOptions($projectws->id, 'ASC', false);

		// injury details
		$myoptions = array();
		$myoptions[]		= JHtml::_( 'select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_NO' ) );
		$myoptions[]		= JHtml::_( 'select.option', '1', JText::_( 'COM_JOOMLEAGUE_GLOBAL_YES' ) );
		$lists['injury']	= JHtml::_( 'select.radiolist',
				$myoptions,
				'injury',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->injury );
		unset($myoptions);

		$lists['injury_date']	 = JHtml::_( 'select.genericlist',
				$matchdays,
				'injury_date',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->injury_date );
		$lists['injury_end']	= JHtml::_( 'select.genericlist',
				$matchdays,
				'injury_end',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->injury_end );

		// suspension details
		$myoptions		= array();
		$myoptions[]	= JHtml::_('select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_NO' ) );
		$myoptions[]	= JHtml::_('select.option', '1', JText::_( 'COM_JOOMLEAGUE_GLOBAL_YES' ));
		$lists['suspension']		= JHtml::_( 'select.radiolist',
				$myoptions,
				'suspension',
				'class="radio" size="1"',
				'value',
				'text',
				$project_player->suspension );
		unset($myoptions);

		$lists['suspension_date']	 = JHtml::_( 'select.genericlist',
				$matchdays,
				'suspension_date',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->suspension_date );
		$lists['suspension_end']	= JHtml::_( 'select.genericlist',
				$matchdays,
				'suspension_end',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->suspension_end );

		// away details
		$myoptions		= array();
		$myoptions[]	= JHtml::_( 'select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_NO' ) );
		$myoptions[]	= JHtml::_( 'select.option', '1', JText::_( 'COM_JOOMLEAGUE_GLOBAL_YES' ) );
		$lists['away']	= JHtml::_( 'select.radiolist',
				$myoptions,
				'away',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->away );
		unset($myoptions);

		$lists['away_date'] = JHtml::_( 'select.genericlist',
				$matchdays,
				'away_date',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->away_date );
		$lists['away_end']	= JHtml::_( 'select.genericlist',
				$matchdays,
				'away_end',
				'class="inputbox" size="1"',
				'value',
				'text',
				$project_player->away_end );

		$this->assignRef('form'      	, $this->get('form'));
		$extended = $this->getExtended($project_player->extended, 'teamplayer');
		$this->assignRef( 'extended', $extended );

		#$this->assignRef( 'default_person',	$default_person );
		$this->assignRef( 'projectws',		$projectws );
		$this->assignRef( 'teamws',			$teamws );
		$this->assignRef( 'lists',			$lists );
		$this->assignRef( 'project_player',	$project_player );
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
		// Set toolbar items for the page
		$edit = JRequest::getVar( 'edit', true );
		$option = JRequest::getCmd('option');
		$params = JComponentHelper::getParams( $option );
		$default_name_format = $params->get("name_format");
		$name = JoomleagueHelper::formatName(null, $this->project_player->firstname, $this->project_player->nickname, $this->project_player->lastname, $default_name_format);
		$text = !$edit ? JText::_( 'COM_JOOMLEAGUE_GLOBAL_NEW' ) : JText::_( 'COM_JOOMLEAGUE_ADMIN_TEAMPLAYER_TITLE' ). ': ' . $name;
		JToolBarHelper::title( $text);
		JLToolBarHelper::save('teamplayer.save');
			
		if ( !$edit )
		{
			JLToolBarHelper::cancel('teamplayer.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JLToolBarHelper::apply('teamplayer.apply');
			JLToolBarHelper::cancel( 'teamplayer.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE' );
		}
		JToolBarHelper::back();
		JToolBarHelper::help( 'screen.joomleague', true );
	}
}
?>
