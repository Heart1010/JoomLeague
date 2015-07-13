<?php
/**
* @copyright	Copyright (C) 2007-2013 joomleague.at. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');

class JFormFieldPosition extends JFormField
{

	protected $type = 'position';

	function getInput()
	{
		$required 	= $this->element['required'] == "true" ? 'true' : 'false';
		$result = array();
		$db = JFactory::getDbo();
		$lang = JFactory::getLanguage();
		$extension = "com_joomleague";
		$source 	= JPath::clean(JPATH_ADMINISTRATOR . '/components/' . $extension);
		$lang->load($extension, JPATH_ADMINISTRATOR, null, false, false)
		||	$lang->load($extension, $source, null, false, false)
		||	$lang->load($extension, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
		||	$lang->load($extension, $source, $lang->getDefault(), false, false);
		
		$query='SELECT	pos.id,
						pos.name AS name
					FROM #__joomleague_position pos
					INNER JOIN #__joomleague_sports_type AS s ON s.id=pos.sports_type_id
					WHERE pos.published=1
					ORDER BY pos.ordering, pos.name	';
		$db->setQuery($query);
		if (!$result=$db->loadObjectList())
		{
			return false;
		}
		foreach ($result as $position)
		{
			$position->name=JText::_($position->name);
		}
		if($this->required == false) {
			$mitems = array(JHtml::_('select.option', '', JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_POSITION')));
		}
		
		foreach ( $result as $item )
		{
			$mitems[] = JHtml::_('select.option',  $item->id, '&nbsp;'.$item->name. ' ('.$item->id.')' );
		}
		return JHtml::_('select.genericlist',  $mitems, $this->name, 
						'class="inputbox" size="1"', 'value', 'text', $this->value, $this->id);
	}
}
 