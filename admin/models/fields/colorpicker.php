<?php
/**
* @copyright	Copyright (C) 2007-2015 joomleague.at. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die;


/**
 * @author Wolfgang Pinitsch <and_one@aon.at>
 * @return string html code for the colorpicker
 */
class JFormFieldColorpicker extends JFormField
{

	protected $type = 'colorpicker';
	
	protected function getInput() 
	{
		// css+js
		$document = JFactory::getDocument();
		$document->addStylesheet(JUri::root().'/media/com_joomleague/colorpicker/colorpicker.css');
		$document->addScript(JUri::root().'/media/com_joomleague/colorpicker/colorfunctions.js');
		$document->addScript(JUri::root().'/media/com_joomleague/colorpicker/colorpicker.js');
		
		// output
		$html	= array();
		$html[] = "<input type=\"text\" style=\"background: ".$this->value."\" name=\"".$this->name."\" id=\"".$this->id."\" value=\"".$this->value."\">"; 
		$html[] = "<input type=\"button\" value=\"".JText::_('JSELECT')."\" onclick=\"showColorPicker(this, document.getElementsByName('".$this->name."')[0])\">";
		
		return implode("\n", $html);
	}
}
 