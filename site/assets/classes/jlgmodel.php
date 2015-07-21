<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model');

class JLGModel extends JModelLegacy
{
	/**
	 * Overrides method to try to load model from extension if it exists
	 */
	public static function getInstance($type, $prefix = '', $config = array() )
	{
		$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));

		foreach ($extensions as $e => $extension)
		{
			$modelType = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
			$modelClass	= $prefix . ucfirst($modelType) . ucfirst($extension);
			$result		= false;

			if (!class_exists($modelClass))
			{
				jimport('joomla.filesystem.path');

				$path = JPath::find(parent::addIncludePath(null, $prefix), self::_createFileName('model', array('name' => $type)));

				if (!$path)
				{
					$path = JPath::find(parent::addIncludePath(null, ''), self::_createFileName('model', array('name' => $type)));
				}

				if ($path)
				{
					require_once $path;

					if (class_exists($modelClass))
					{
						$result = new $modelClass($config);

						return $result;
					}
				}
			}
			else
			{
				$result = new $modelClass($config);

				return $result;
			}
		}

		// Still here ? Then the extension doesn't override this, use regular way
		return parent::getInstance($type, $prefix, $config);
	}
}
