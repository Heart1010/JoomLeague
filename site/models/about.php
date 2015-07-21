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
require_once JLG_PATH_SITE.'/models/project.php';


/**
 * About Model
 */
class JoomleagueModelAbout extends JoomleagueModelProject
{
	function getAbout()
	{
		$about = new stdClass();
		
		//Translations Hosted by
		$about->translations = '<a href="https://opentranslators.transifex.com/projects/p/joomleague/" target="_blank">https://opentranslators.transifex.com/projects/p/joomleague/</a>';
		//Repository Hosted by
		$about->repository = '<a href="https://gitlab.com/joomleague/joomleague" target="_blank">https://gitlab.com/joomleague/joomleague</a>';
		//version
		$version = JoomleagueHelper::getVersion();
		$revision = explode('.', $version);
		$about->version = $version;
		
		//author
		$about->author = 'Joomleague-Team';

		//page
		$about->page = 'http://www.joomleague.at';

		//e-mail
		$about->email = 'http://www.joomleague.at/forum/index.php?action=contact';

		//forum
		$about->forum = 'http://forum.joomleague.at';
		
		//bugtracker
		$about->bugs = 'http://bugtracker.joomleague.at';
		
		//wiki
		$about->wiki = 'http://wiki.joomleague.at';
		
		//date
		$about->date = '2013-01-07';

		//developer
		$about->developer = '<a href="http://stats.joomleague.at/authors.html" target="_blank">JoomLeague-Team</a>';

		//designer
		$about->designer = 'Kasi';
		$about->designer .= ', <a href="http://www.cg-design.net" target="_blank">cg design</a>&nbsp;(Carsten Grob) ';

		//icons
		$about->icons = '<a href="http://www.hollandsevelden.nl/iconset/" target="_blank">Jersey Icons</a> (Hollandsevelden.nl)';
		$about->icons .= ', <a href="http://www.famfamfam.com/lab/icons/silk/" target="_blank">Silk / Flags Icons</a> (Mark James)';
		$about->icons .= ', Panel images (Kasi)';

		//flash
		$about->flash = 'Open Flash Chart 2.x';

		//graphoc library
		$about->graphic_library = '<a href="http://www.walterzorn.com" target="_blank">www.walterzorn.com</a>';
		
		//phpthumb class
		$about->phpthumb = 'phpthumb.gxdlabs.com';


		$this->_about = $about;

		return $this->_about;
	}
}
