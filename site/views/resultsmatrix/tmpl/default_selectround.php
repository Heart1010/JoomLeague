<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

 echo JHtml::_('select.genericlist', $this->matchdaysoptions, 'select-round', 'onchange="joomleague_changedoc(this);" style="float:right;"', 'value', 'text', $this->currenturl);
?>