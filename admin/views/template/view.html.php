<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.form.form');

/**
 * HTML View class
*/
class JoomleagueViewTemplate extends JLGView
{
	public function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
		$model = $this->getModel();
		$lists=array();

		//get template data
		$template = $this->get('data');
		$isNew=($template->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg=JText::sprintf('DESCBEINGEDITTED',JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATE_THETEMPLATE'),$template->name);
			$app->redirect('index.php?option='.$option,$msg);
		}

		$projectws = $this->get('Data','project');
		$templatepath=JPATH_COMPONENT_SITE.'/settings';
		$xmlfile=$templatepath.'/default/'.$template->template.'.xml';

		$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));
		foreach ($extensions as $e => $extension) {
			$extensiontpath =  JPATH_COMPONENT_SITE.'/extensions/'. $extension;
			if (is_dir($extensiontpath.'/settings/default'))
			{
				if (file_exists($extensiontpath.'/settings/default/'.$template->template.'.xml'))
				{
					$xmlfile=$extensiontpath.'/settings/default/'.$template->template.'.xml';
				}
			}
		}
		
		$form = JForm::getInstance($template->template, $xmlfile,
									array('control'=> 'params'));
		$form->bind($template->params);
		
		$master_id=($projectws->master_template) ? $projectws->master_template : '-1';
		$templates=array();
		//$templates[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATE_OTHER_TEMPLATE' ),'value','text');
		if ($res=$model->getAllTemplatesList($projectws->id,$master_id)){
			$templates=array_merge($templates,$res);
		}
		$lists['templates']=JHtmlSelect::genericlist(	$templates,
				'select_id',
				'class="inputbox" size="1" onchange="javascript: Joomla.submitbutton(\'template.apply\');"',
				'value',
				'text',
				$template->id);
		unset($res);
		unset($templates);

		$this->request_url = $uri->toString();
		$this->template = $template;
		$this->form = $form;
		$this->project = $projectws;
		$this->lists = $lists;
		$this->user = $user;

		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		// Set toolbar items for the page
		$edit=JRequest::getVar('edit',true);

		JLToolBarHelper::save('template.save');
		JLToolBarHelper::apply('template.apply');

		if (!$edit)
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATE_ADD_NEW'));
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('template.cancel');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATE_EDIT').': '. $this->form->getName() );
			JLToolBarHelper::custom('template.reset','restore','restore','COM_JOOMLEAGUE_GLOBAL_RESET');
			JToolBarHelper::divider();
			// for existing items the button is renamed `close`
			JLToolBarHelper::cancel('template.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::help('screen.joomleague',true);
	}
}
