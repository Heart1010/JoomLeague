<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT.'/models/list.php';

/**
 * Templates Model
 *
 * @author	JoomLeague Team
 */

class JoomleagueModelTemplates extends JoomleagueModelList
{
	var $_identifier = "templates";
	var $_project_id = null;

	public function __construct()
	{
		$app = JFactory::getApplication();

		parent::__construct();
		$project_id = $app->getUserState('com_joomleague'.'project',0);
		$this->set('_project_id',$project_id);
		$this->set('_getALL',0);
	}

	function setProjectId($project_id)
	{
		$this->set('_project_id',$project_id);
	}

	function getData()
	{
		$this->checklist();
		return parent::getData();
	}

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where=$this->_buildContentWhere();
		$orderby=$this->_buildContentOrderBy();
		$query='SELECT tmpl.*, u.name AS editor,(0) AS isMaster FROM #__joomleague_template_config AS tmpl LEFT JOIN #__users u ON u.id=tmpl.checked_out '.$where.$orderby;
		return $query;
	}

	function _buildContentWhere()
	{
		$app = JFactory::getApplication();
		$option = $this->input->getCmd('option');
		$project_id = $app->getUserState($option.'project');

		$where=array();
		$where[]=' tmpl.project_id='.(int) $this->_project_id;

		$oldTemplates="frontpage'";
		$oldTemplates .= ",'do_tipsl','tipranking','tipresults','user'";
		$oldTemplates .= ",'tippentry','tippoverall','tippranking','tippresults','tipprules','tippusers'";
		$oldTemplates .= ",'predictionentry','predictionoverall','predictionranking','predictionresults','predictionrules','predictionusers";

		$where[]=" tmpl.template NOT IN ('".$oldTemplates."')";
		$query=" WHERE ".implode(' AND ',$where);

		return $query;
	}

	function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();
		$option = $this->input->getCmd('option');

		$filter_order		= $app->getUserStateFromRequest($option.'tmpl_filter_order',		'filter_order',		'tmpl.template',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'tmpl_filter_order_Dir',	'filter_order_Dir',	'',					'word');

		if ($filter_order=='tmpl.template')
		{
			$orderby=' ORDER BY tmpl.template '.$filter_order_Dir;
		}
		else
		{
			$orderby=' ORDER BY '.$filter_order.' '.$filter_order_Dir.',tmpl.template ';
		}
		return $orderby;
	}

	/**
	 * check that all templates in default location have a corresponding record,except if project has a master template
	 */
	function checklist()
	{
		$project_id=$this->_project_id;
		$defaultpath=JPATH_COMPONENT_SITE.'/settings';
		$predictionTemplatePrefix='prediction';

		if (!$project_id){return;}

		// get info from project
		$query='SELECT master_template,extension FROM #__joomleague_project WHERE id='.(int)$project_id;

		$this->_db->setQuery($query);
		$params=$this->_db->loadObject();

		// if it's not a master template,do not create records.
		if ($params->master_template){return true;}

		// otherwise,compare the records with the files
		// get records
		$query='SELECT template FROM #__joomleague_template_config WHERE project_id='.(int) $project_id;

		$this->_db->setQuery($query);
		$records=$this->_db->loadColumn();
		if (empty($records)) { $records=array(); }
		
		// add default folder
		$xmldirs[]=$defaultpath.'/default';
		
		$extensions = JoomleagueHelper::getExtensions($this->input->getInt('p'));
		foreach ($extensions as $e => $extension) {
			$extensiontpath =  JPATH_COMPONENT_SITE.'/extensions/'.$extension;
			if (is_dir($extensiontpath.'/settings/default'))
			{
				$xmldirs[]=$extensiontpath.'/settings/default';
			}
		}

		// now check for all xml files in these folders
		foreach ($xmldirs as $xmldir)
		{
			if ($handle=opendir($xmldir))
			{
				/* check that each xml template has a corresponding record in the
				database for this project. If not,create the rows with default values
				from the xml file */
				while ($file=readdir($handle))
				{
					if	(	$file!='.' &&
							$file!='..' &&
							$file!='do_tipsl' &&
							strtolower(substr($file,-3))=='xml' &&
							strtolower(substr($file,0,strlen($predictionTemplatePrefix)))!=$predictionTemplatePrefix
						)
					{
						$template=substr($file,0,(strlen($file)-4));

						if ((empty($records)) || (!in_array($template,$records)))
						{
							$jRegistry = new JRegistry();
							$form = JForm::getInstance($file, $xmldir.'/'.$file);
							$fieldsets = $form->getFieldsets();
							foreach ($fieldsets as $fieldset) {
								foreach($form->getFieldset($fieldset->name) as $field) {
									$jRegistry->set($field->name, $field->value);
								}				
							}
							$defaultvalues = $jRegistry->toString('ini');
							
							$tblTemplate_Config = JTable::getInstance('template', 'table');
							$tblTemplate_Config->template = $template;
							$tblTemplate_Config->title = $file;
							$tblTemplate_Config->params = $defaultvalues;
							$tblTemplate_Config->project_id = $project_id;
							
								// Make sure the item is valid
							if (!$tblTemplate_Config->check())
							{
								$this->setError($this->_db->getErrorMsg());
								return false;
							}
					
							// Store the item to the database
							if (!$tblTemplate_Config->store())
							{
								$this->setError($this->_db->getErrorMsg());
								return false;
							}
							array_push($records,$template);
						}
					}
				}
				closedir($handle);
			}
		}
	}

	function getMasterTemplatesList()
	{
		// get current project settings
		$query='SELECT template FROM #__joomleague_template_config WHERE project_id='.(int)$this->_project_id;
		$this->_db->setQuery($query);
		$current=$this->_db->loadColumn();

		if ($this->_getALL)
		{
			$query='SELECT t.*,(1) AS isMaster ';
		}
		else
		{
			$query='SELECT t.id as value, t.title as text, t.template as template ';
		}
		$query .= '	FROM #__joomleague_template_config as t
					INNER JOIN #__joomleague_project as pm ON pm.id=t.project_id
					INNER JOIN #__joomleague_project as p ON p.master_template=pm.id ';
		$where=array();
		$where[]=' p.id='.(int)$this->_project_id;

		$oldTemplates="frontpage'";
		$oldTemplates .= ",'do_tipsl','tipranking','tipresults','user'";
		$oldTemplates .= ",'tippentry','tippoverall','tippranking','tippresults','tipprules','tippusers'";
		$oldTemplates .= ",'predictionentry','predictionoverall','predictionranking','predictionresults','predictionrules','predictionusers";
		$where[]=" t.template NOT IN ('".$oldTemplates."')";

		if (count($current))
		{
			$where[]=" t.template NOT IN ('".implode("','",$current)."')";
		}
		$query .= " WHERE ".implode(' AND ',$where);
		$query .= " ORDER BY t.title ";
		// Build in JText of template title here and sort it afterwards
		$this->_db->setQuery($query);
		$current=$this->_db->loadObjectList();
		return (count($current)) ? $current : array();
	}

	function getMasterName()
	{
		$query='	SELECT master.name
					FROM #__joomleague_project as master
					INNER JOIN #__joomleague_project as p ON p.master_template=master.id
					WHERE p.id='.(int) $this->_project_id;
		$this->_db->setQuery($query);
		return ($this->_db->loadResult());
	}
}
