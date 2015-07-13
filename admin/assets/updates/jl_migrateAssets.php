<?php
/**
* @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// ToDo:
// - Remove old checks for already existing records in different functions as it was done with matches table
// - check ranking class changes in tables or templates etc...
// no direct access
defined('_JEXEC') or die('Restricted access');

$version			= '2.93.239.9df0b25-b';
$updateFileDate		= '2014-01-02';
$updateFileTime		= '23:25';
$updatefilename		= 'migrateAssets';
$lastVersion		= '2.0';
$JLtablePrefix		= 'joomleague';
$updateDescription	= '<span style="color:orange">Perform an update of existing old JL 1.6 tables inside the database to work with latest JoomLeague Assets System (ACL)</span>';
$excludeFile		= 'false';

if(!function_exists('PrintStepResult')) {
function PrintStepResult($result)
{
	if ($result)
	{
		$output=' - <span style="color:green">'.JText::_('SUCCESS').'</span>';
	}
	else
	{
		$output=' - <span style="color:red">'.JText::_('FAILED').'</span>';
	}

	return $output;
}}

function migrateAssets()
{
	$maxImportTime=JComponentHelper::getParams('com_joomleague')->get('max_import_time',0);
	if (empty($maxImportTime))
	{
		$maxImportTime=9000;
	}
	if ((int)ini_get('max_execution_time') < $maxImportTime){
		@set_time_limit($maxImportTime);
	}
	
	$query="SHOW TABLES LIKE '%_joomleague%'";
	$db = JFactory::getDbo();
	$tables = array();
	$db->setQuery($query);
	$results = $db->loadColumn();
	foreach ($results as $tablename)
	{
		$fields = $db->getTableFields($tablename);
		foreach($fields as $field)
		{
			if(in_array('asset_id', array_keys ($field))) {
				$tables[] = $tablename;
			}
		}
	}
	for ($i=0; $i < count($tables); $i++) {
		$table = $tables[$i];
		$query='SELECT id FROM '.$table;
		$db->setQuery($query);
		if ($items=$db->loadObjectList()) {
			$table = str_replace($db->getPrefix().'joomleague_','', $table);
			foreach($items as $item) {
				$tbl = JTable::getInstance(str_replace('_','', $table), "Table");
				if ($tbl->load($item->id));
				{
					$tbl->store(true);
				}
			}
		}
		echo '<br>Migrated ' . count($items) . ' records in ' . $tables[$i] . ' ';
		echo PrintStepResult(true);
	}
	return '';
}

migrateAssets();