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
 */
class JoomleagueViewTreetonodes extends JLGView
{

	public function display($tpl=null)
	{
		$app = JFactory::getApplication();
		if ($this->getLayout()=='default')
		{
			$this->_displayDefault($tpl);
			return;
		}
		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$db = JFactory::getDbo();
		$uri = JFactory::getURI();

		$node = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$model = $this->getModel();
		$projectws = $this->get('Data','project');
		$treetows = $this->get('Data','treeto');

		//build the html options for teams
		$team_id[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_TEAM'));
		if ($projectteams = $model->getProjectTeamsOptions())
		{
			$team_id=array_merge($team_id,$projectteams);
		}
		$lists['team']=$team_id;
		unset($team_id);

		$style  = 'style="background-color: #dddddd; ';
		$style .= 'border: 0px solid white;';
		$style .= 'font-weight: normal; ';
		$style .= 'font-size: 8pt; ';
		$style .= 'width: 150px; ';
		$style .= 'font-family: verdana; ';
		$style .= 'text-align: center;"';
		$path = 'media/com_joomleague/treebracket/onwhite/';
		
		// build the html radio for adding into new round / exist round
		$createYesNo=array(0 => JText::_('COM_JOOMLEAGUE_GLOBAL_NO'),1 => JText::_('COM_JOOMLEAGUE_GLOBAL_YES'));
		$createLeftRight=array(0 => JText::_('L'),1 => JText::_('R'));
		$ynOptions=array();
		$lrOptions=array();
		foreach($createYesNo AS $key => $value){$ynOptions[]=JHtmlSelect::option($key,$value);}
		foreach($createLeftRight AS $key => $value){$lrOptions[]=JHtmlSelect::option($key,$value);}
		$lists['addToRound']=JHtmlSelect::radiolist($ynOptions,'addToRound','class="inputbox"','value','text',1);

		// build the html radio for auto publish new matches
		$lists['autoPublish']=JHtmlSelect::radiolist($ynOptions,'autoPublish','class="inputbox"','value','text',0);

		// build the html radio for Left or Right redepth
		$lists['LRreDepth']=JHtmlSelect::radiolist($lrOptions,'LRreDepth','class="inputbox"','value','text',0);
		// build the html radio for create new treeto
		$lists['createNewTreeto']=JHtmlSelect::radiolist($ynOptions,'createNewTreeto','class="inputbox"','value','text',1);

		$this->lists = $lists;
		$this->node = $node;
		// @todo fix!
	//	$this->roundcode',$roundcode);
		$this->style = $style;
		$this->path = $path;
		$this->projectws = $projectws;
		$this->treetows = $treetows;
		$this->total = $total;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
