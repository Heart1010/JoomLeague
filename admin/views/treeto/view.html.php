<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

//jimport('joomla.filesystem.file');

/**
 * HTML View class
*/
class JoomleagueViewTreeto extends JLGView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		if ( $this->getLayout() == 'form' )
		{
			$this->_displayForm( $tpl );
			return;
		}
		elseif ($this->getLayout() == 'gennode')
		{
			$this->_displayGennode($tpl);
			return;
		}
		parent::display( $tpl );
	}

	function _displayForm($tpl)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$db = JFactory::getDbo();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
		$model = $this->getModel();
		$lists=array();

		$treeto = $this->get('data');
		$script = $this->get('Script');
		$this->script = $script;
		//if there is no image selected, use default picture
		//		$default = JoomleagueHelper::getDefaultPlaceholder("team");
		//		if (empty($treeto->trophypic)){$treeto->trophypic=$default;}

		// fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg=JText::sprintf('DESCBEINGEDITTED',JText::_('The treeto'),$treeto->id);
			$app->redirect('index.php?option='.$option,$msg);
		}

		$this->form = $this->get('form');
		$this->treeto = $treeto;

		$this->addToolBar();
		parent::display($tpl);
		$this->setDocument();
	}

	function _displayGennode($tpl)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$db = JFactory::getDbo();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
		$model = $this->getModel();
		$lists=array();

		$treeto = $this->get('data');
		$projectws = $this->get('Data','project');
		$this->form = $this->get('form');
		$this->projectws = $projectws;
		$this->lists = $lists;
		$this->treeto = $treeto;

		$this->addToolBar_Gennode();
		parent::display($tpl);
	}

	protected function addToolBar_Gennode()
	{
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TREETO_TITLE_GENERATE'));
		JToolBarHelper::back('Back','index.php?option=com_joomleague&view=treetos&task=treeto.display');
		JToolBarHelper::help('screen.joomleague', true);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TREETO_TITLE'));
		JLToolBarHelper::save('treeto.save');
		JLToolBarHelper::apply('treeto.apply');
		JToolBarHelper::back('Back','index.php?option=com_joomleague&view=treetos&task=treeto.display');
		JToolBarHelper::help('screen.joomleague', true);
	}

	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$version = urlencode(JoomleagueHelper::getVersion());
		$document->addScript(JUri::root() . $this->script);
	}
}
