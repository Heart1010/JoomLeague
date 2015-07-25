<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
 
// import Joomla formrule library
jimport('joomla.form.formrule');

use Joomla\Registry\Registry;
 
/**
 * Form Rule class for the Joomla Framework.
 */
class JFormRuleTime extends JFormRule
{
	/**
	 * The regular expression.
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $regex = '^[0-9]{1,2}:[0-9]{1,2}$';
	
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, JForm $form = null)
	{
		if ($value == null or $value == '') {
			return true;
		}
		return parent::test($element, $value, $group, $input, $form);
	}
}