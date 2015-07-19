<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * HTML View class
 *
 * @author	Kurt Norgaz
 */
class JoomleagueViewTeamStaff extends JLGView
{

	public function display( $tpl = null )
	{
		$app		= JFactory::getApplication();
		$jinput		= $app->input;
		$uri		= JFactory::getURI();
		$user		= JFactory::getUser();
		$model		= $this->getModel();
		$lists		= array();
		$projectws	= $this->get( 'Data', 'project' );
		$teamws		= $this->get( 'Data', 'project_team' );
		
		//get the project_TeamStaff data of the project_team
		$project_teamstaff	= $this->get( 'data' );
		$isNew				= ( $project_teamstaff->id < 1 );

		// fail if checked out not by 'me'
		if ( $model->isCheckedOut( $user->get( 'id' ) ) )
		{
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_THEPLAYER' ), $project_teamstaff->name );
			$app->redirect( 'index.php?option=com_joomleague', $msg );
		}

		// Edit or Create?
		if ( $isNew ) { $project_teamstaff->order = 0; }

		//build the html select list for positions
		$selectedvalue = $project_teamstaff->project_position_id;
		$projectpositions = array();
		$projectpositions[] = JHtml::_('select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_SELECT_FUNCTION' ) );
		if ( $res = $model->getProjectPositions() )
		{
			$projectpositions = array_merge( $projectpositions, $res );
		}
		$lists['projectpositions'] = JHtml::_(	'select.genericlist',
												$projectpositions,
												'project_position_id',
												'size="1"',
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
										'size="1"',
										'value',
										'text',
										$project_teamstaff->injury );
		unset($myoptions);

		$lists['injury_date']	 = JHtml::_( 'select.genericlist',
											$matchdays,
											'injury_date',
											'size="1"',
											'value',
											'text',
											$project_teamstaff->injury_date );
		$lists['injury_end']	= JHtml::_( 'select.genericlist',
											$matchdays,
											'injury_end',
											'size="1"',
											'value',
											'text',
											$project_teamstaff->injury_end );

		// suspension details
		$myoptions		= array();
		$myoptions[]	= JHtml::_('select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_NO' ) );
		$myoptions[]	= JHtml::_('select.option', '1', JText::_( 'COM_JOOMLEAGUE_GLOBAL_YES' ));
		$lists['suspension']		= JHtml::_( 'select.radiolist',
												$myoptions,
												'suspension',
												'size="1"',
												'value',
												'text',
												$project_teamstaff->suspension );
		unset($myoptions);

		$lists['suspension_date']	 = JHtml::_( 'select.genericlist',
												$matchdays,
												'suspension_date',
												'size="1"',
												'value',
												'text',
												$project_teamstaff->suspension_date );
		$lists['suspension_end']	= JHtml::_( 'select.genericlist',
												$matchdays,
												'suspension_end',
												'size="1"',
												'value',
												'text',
												$project_teamstaff->suspension_end );

		// away details
		$myoptions		= array();
		$myoptions[]	= JHtml::_( 'select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_NO' ) );
		$myoptions[]	= JHtml::_( 'select.option', '1', JText::_( 'COM_JOOMLEAGUE_GLOBAL_YES' ) );
		$lists['away']	= JHtml::_( 'select.radiolist',
									$myoptions,
									'away',
									'size="1"',
									'value',
									'text',
									$project_teamstaff->away );
		unset($myoptions);

		$lists['away_date'] = JHtml::_( 'select.genericlist',
										$matchdays,
										'away_date',
										'size="1"',
										'value',
										'text',
										$project_teamstaff->away_date );
		$lists['away_end']	= JHtml::_( 'select.genericlist',
										$matchdays,
										'away_end',
										'size="1"',
										'value',
										'text',
										$project_teamstaff->away_end );

		$extended = $this->getExtended($project_teamstaff->extended, 'teamstaff');
		$this->extended = $extended;
		$this->form = $this->get('form');			
		#$this->default_person = $default_person;
		$this->projectws = $projectws;
		$this->teamws = $teamws;
		$this->lists = $lists;
		$this->project_teamstaff = $project_teamstaff;

		$this->addToolbar();
		parent::display( $tpl );
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		// Set toolbar items for the page
		$edit = $jinput->get('edit', true);
		$option = $jinput->getCmd('option');
		$params = JComponentHelper::getParams( $option );
		$default_name_format = $params->get("name_format");
		$name = JoomleagueHelper::formatName(null, $this->project_teamstaff->firstname, $this->project_teamstaff->nickname, $this->project_teamstaff->lastname, $default_name_format);
		$text = !$edit ? JText::_( 'COM_JOOMLEAGUE_GLOBAL_NEW' ) : JText::_( 'COM_JOOMLEAGUE_ADMIN_TEAMSTAFF_TITLE' ). ': ' . $name;
		JToolBarHelper::title( $text);
		JLToolBarHelper::save('teamstaff.save');
			
		if (!$edit)
		{
			JLToolBarHelper::cancel('teamstaff.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JLToolBarHelper::apply('teamstaff.apply');
			JLToolBarHelper::cancel( 'teamstaff.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE' );
		}
		JToolBarHelper::back();
		JToolBarHelper::help( 'screen.joomleague', true );
	}
}
