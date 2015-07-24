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
class JoomleagueViewTreetomatchs extends JLGView
{

	public function display($tpl=null)
	{
		if ($this->getLayout() == 'editlist')
		{
			$this->_displayEditlist($tpl);
			return;
		}

		if ($this->getLayout()=='default')
		{
			$this->_displayDefault($tpl);
			return;
		}
		parent::display($tpl);
	}

	function _displayEditlist($tpl)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$project_id = $app->getUserState($option . 'project');
		$node_id = $app->getUserState($option . 'node_id');
		
		$uri = JFactory::getURI();

		$treetomatchs = $this->get('Data');
		$total = $this->get('Total');
		$model = $this->getModel();

		$projectws = $this->get('Data','project');
		
		// @todo check!
		// renamed "node" to "treetonode" as the output for 
		// nodews was returning a string and it seems the view was expecting an id 
		// so a object had to be returned
		
		$nodews = $this->get('Data','treetonode');
		//build the html select list for node assigned matches
		$ress = array();
		$res1 = array();
		$notusedmatches = array();

		if ($ress = $model->getNodeMatches($node_id))
		{
			$matcheslist=array();
			foreach($ress as $res)
			{
				if(empty($res1->info))
				{
					$node_matcheslist[] = JHtmlSelect::option($res->value,$res->text);
				}
				else
				{
					$node_matcheslist[] = JHtmlSelect::option($res->value,$res->text.' ('.$res->info.')');
				}
			}

			$lists['node_matches'] = JHtmlSelect::genericlist($node_matcheslist, 'node_matcheslist[]',
	' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30,count($ress)).'"',
				'value',
				'text');
		}
		else
		{
			$lists['node_matches']= '<select name="node_matcheslist[]" id="node_matcheslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		if ($ress1 = $model->getMatches())
		{
			if ($ress = $model->getNodeMatches($node_id))
			{
				foreach ($ress1 as $res1)
				{
					$used=0;
					foreach ($ress as $res)
					{
						if ($res1->value == $res->value){$used=1;}
					}

					if ($used == 0 && !empty($res1->info)){
						$notusedmatches[]=JHtmlSelect::option($res1->value,$res1->text.' ('.$res1->info.')');
					}
					elseif($used == 0 && empty($res1->info))
					{
						$notusedmatches[] = JHtmlSelect::option($res1->value,$res1->text);
					}
				}
			}
			else
			{
				foreach ($ress1 as $res1)
				{
					if(empty($res1->info))
					{
						$notusedmatches[] = JHtmlSelect::option($res1->value,$res1->text);
					}
					else
					{
						$notusedmatches[] = JHtmlSelect::option($res1->value,$res1->text.' ('.$res1->info.')');
					}
				}
			}
		}
		else
		{
			JError::raiseWarning('ERROR_CODE','<br />'.JText::_('COM_JOOMLEAGUE_ADMIN_TREETOMATCH_ADD_MATCH').'<br /><br />');
		}

		//build the html select list for matches
		if (count($notusedmatches) > 0)
		{
			$lists['matches'] = JHtmlSelect::genericlist( $notusedmatches,
				'matcheslist[]',
	' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30,count($notusedmatches)).'"',
			'value',
			'text');
		}
		else
		{
			$lists['matches'] = '<select name="matcheslist[]" id="matcheslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		unset($res);
		unset($res1);
		unset($notusedmatches);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->treetomatchs = $treetomatchs;
		$this->projectws = $projectws;
		$this->nodews = $nodews;
		
		// @todo fix!
		/* $this->pagination = $pagination; */
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$uri = JFactory::getURI();

		$match = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$model = $this->getModel();
		$projectws = $this->get('Data','project');
		$nodews = $this->get('Data','treetonode');

		$this->match = $match;
		$this->projectws = $projectws;
		$this->nodews = $nodews;
		$this->total = $total;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
