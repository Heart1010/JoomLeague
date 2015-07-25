<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


class PlgContentJoomleague_Comments extends JPlugin 
{
	
	/**
	 * Construct plugin.
	 *
	 * @param object $subject
	 * @param array $config
	 */
	public function __construct(&$subject, $config)
	{
		// Do not enable plugin in administration.
		if (JFactory::getApplication()->isAdmin())
		{
			return false;
		}
		
		// check for Jcomments
		$comments = JPATH_SITE.'/components/com_jcomments/jcomments.php';
		if (file_exists($comments))
		{
			require_once $comments;
		} else {
			return false;
			
		}
		
		parent::__construct ($subject, $config);
		
		$this->loadLanguage('plg_joomleague_comments');
		$params = $this->params;
		
	}
	

	/**
	 * adds comments to match reports
	 * @param object match
	 * @param string title
	 * @return boolean true on success
	 */
	public function onMatchReportComments(&$match, $title, &$html)
	{
		$separate_comments 	= $this->params->get('separate_comments', 0);

		if ($separate_comments) {
			$html = '<div class="jlgcomments">'.JComments::show($match->id, 'com_joomleague_matchreport', $title).'</div>';
			return true;
		}
	}

	/**
	 * adds comments to match preview
	 * @param object match
	 * @param string title
	 * @return boolean true on success
	 */
	public function onNextMatchComments(&$match, $title, &$html)
	{
		$separate_comments 	= $this->params->get('separate_comments',0);

		if ($separate_comments) {
			$html = '<div class="jlgcomments">'.JComments::show($match->id, 'com_joomleague_nextmatch', $title).'</div>';
			return true;
		}
	}

	/**
	 * adds comments to a match (independent if they were made before or after the match)
	 * @param object match
	 * @param string title
	 * @return boolean true on success
	 */
	public function onMatchComments(&$match, $title, &$html)
	{
		$separate_comments 	= $this->params->get('separate_comments', 0);

		if ($separate_comments == 0) 
		{
			$html = '<div class="jlgcomments">'.JComments::show($match->id, 'com_joomleague', $title).'</div>';
			return true;
		}
	}
}

