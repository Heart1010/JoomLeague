<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @author		Wolfgang Pinitsch <andone@mfga.at>
 * @see			JLGView
 * @see			JLGModel
 */
defined('_JEXEC') or die;


class JoomleagueHelperExtensioncontroller
{
	private $input;

	private $extensions;

	public function __construct()
	{
		$this->input = JFactory::getApplication()->input;
	}

	public function findFile($filename, $extension)
	{
		$path = JFactory::getApplication()->isAdmin()
			? JLG_PATH_SITE . '/extensions/' . $extension . '/admin/controllers/' . $filename
			: JLG_PATH_SITE . '/extensions/' . $extension . '/controllers/' . $filename;

		return file_exists($path) ? $path : false;
	}

	public function getExtensions()
	{
		if (!$this->extensions)
		{
			$this->extensions = JoomleagueHelper::getExtensions($this->input->getInt('p', 0));
		}

		return $this->extensions;
	}

	public function initExtensions()
	{
		$lang = JFactory::getLanguage();
		$app = JFactory::getApplication();

		foreach ($this->getExtensions() as $extension)
		{
			$extensionpath = JLG_PATH_SITE . '/extensions/' . $extension;

			$mainfile = $extensionpath . '/'. $extension . '.php';

			if (file_exists($mainfile))
			{
				//e.g example.php
				require_once $mainfile;
			}

			$lang_path = $app->isAdmin()
				? $extensionpath . '/admin'
				: $extensionpath;
			// language file
			$lang->load('com_joomleague_' . $extension, $lang_path);
		}
	}

	public function addModelPaths($controller)
	{
		$app = JFactory::getApplication();

		foreach ($this->getExtensions() as $extension)
		{
			$path= $app->isAdmin()
				? JLG_PATH_SITE . '/extensions/' . $extension . '/admin/models'
				: JLG_PATH_SITE . '/extensions/' . $extension . '/models';

			if (file_exists($path))
			{
				$controller->addModelPath($path, 'joomleagueModel');
			}
		}
	}

	public function addViewPaths($controller)
	{
		$app = JFactory::getApplication();

		foreach ($this->getExtensions() as $extension)
		{
			$path= $app->isAdmin()
				? JLG_PATH_SITE . '/extensions/' . $extension . '/admin/views'
				: JLG_PATH_SITE . '/extensions/' . $extension . '/views';

			if (file_exists($path))
			{
				$controller->addViewPath($path, 'joomleagueView');
			}
		}
	}
}
