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
 * Quickicon Plugin
 */
class plgQuickiconJoomLeague_Quickicon extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onGetIcons($context)
	{
		$text = $this->params->get('displayedtext');
		if(empty($text)) $text = JText::_('COM_JOOMLEAGUE');

		return array(array(
			'link' => 'index.php?option=com_joomleague',
			'image' => 'asterisk',
			'icon' => 'header/icon-48-download.png',
			'text' => $text,
			'id' => 'plg_quickicon_joomleague'
		));
	}
}

