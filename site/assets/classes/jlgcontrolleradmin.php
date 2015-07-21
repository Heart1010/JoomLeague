<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 * 
 * @author		Julien Vonthron
 */
defined('JPATH_BASE') or die;

jimport( 'joomla.application.component.controlleradmin');

/**
 * Modifies JController to take extensions into account
*/
class JLGControllerAdmin extends JControllerAdmin
{

	/**
	 * Overrides method to first lookup into potential extension for the model.
	 */
	protected function createModel($name, $prefix = '', $config = array())
	{
		// Clean the model name
		$modelName	 = preg_replace( '/[^A-Z0-9_]/i', '', $name );
		$classPrefix = preg_replace( '/[^A-Z0-9_]/i', '', $prefix );

		$result = JLGModel::getInstance($modelName, $classPrefix, $config);

		return $result;
	}

	/**
	 * Overrides method to first lookup into potential extension for the view.
	 */
	protected function createView($name, $prefix = '', $type = '', $config = array() )
	{
		$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));

		foreach ($extensions as $e => $extension)
		{
			$result = null;

			// Clean the view name
			$viewName	 = preg_replace( '/[^A-Z0-9_]/i', '', $name );
			$classPrefix = preg_replace( '/[^A-Z0-9_]/i', '', $prefix );
			$viewType	 = preg_replace( '/[^A-Z0-9_]/i', '', $type );

			// Build the view class name
			$viewClassExtension = $classPrefix . $viewName . ucfirst($extension);

			if (!class_exists($viewClassExtension))
			{
				jimport('joomla.filesystem.path' );
				$path = JPath::find(
						$this->paths['view'],
						$this->createFileName('view', array( 'name' => $viewName, 'type' => $viewType)));

				if ($path)
				{
					require_once $path;

					if (class_exists($viewClassExtension))
					{
						$result = new $viewClassExtension($config);

						return $result;
					}
				}
			}
			else
			{
				$result = new $viewClassExtension($config);

				return $result;
			}
		}
		
		// Still here ? Then the extension doesn't override this, use regular view
		return parent::createView($name, $prefix, $type, $config);
	}
}