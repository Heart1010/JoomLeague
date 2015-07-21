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


jimport( 'joomla.application.component.controller');

/**
 * Modifies JController to take extensions into account
 */
class JLGController extends JControllerLegacy
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

	public static function getInstance($prefix, $config = array())
	{
		if (is_object(self::$instance))
		{
			return self::$instance;
		}

		$extensionHelper = new JoomleagueHelperExtensioncontroller;
		$extensionHelper->initExtensions();

		if (isset($config['base_path']))
		{
			parent::getInstance($prefix, $config);
		}
		else
		{
			self::getInstanceWithExtensions($prefix);
		}

		$extensionHelper->addModelPaths(self::$instance);
		$extensionHelper->addViewPaths(self::$instance);

		return self::$instance;
	}

	private static function getInstanceWithExtensions($prefix)
	{
		$extensionHelper = new JoomleagueHelperExtensioncontroller;

		// Get the environment configuration.
		$format = JRequest::getWord('format');
		$command = JRequest::getVar('task', 'display');

		// Check for array format.
		$filter = JFilterInput::getInstance();

		if (is_array($command))
		{
			$command = $filter->clean(array_pop(array_keys($command)), 'cmd');
		}
		else
		{
			$command = $filter->clean($command, 'cmd');
		}

		$class = null;

		// Check for a controller.task command.
		// it's the only kind supported for now
		if (strpos($command, '.') !== false)
		{
			// Explode the controller.task command.
			list ($type, $task) = explode('.', $command);

			// Define the controller filename and path.
			$file = self::createFileName('controller', array('name' => $type, 'format' => $format));

			foreach ($extensionHelper->getExtensions() as $extension)
			{
				if (!$path = $extensionHelper->findFile($file, $extension))
				{
					continue;
				}

				require_once $path;

				$className = ucfirst($prefix) . 'Controller' . ucfirst($type);

				if (class_exists($className))
				{
					$class = $className;
					break;
				}
				elseif (class_exists($className . ucfirst($extension)))
				{
					$class = $className . ucfirst($extension);
					break;
				}
				else
				{
					throw new InvalidArgumentException(JText::_('COM_JOOMLEAGUE_WRONG_CLASSNAME_IN_CONTROLLER_FILE'));
				}
			}

			if ($class)
			{
				// Reset the task without the controller context.
				JRequest::setVar('task', $task);

				self::$instance = new $class;

				return self::$instance;
			}
		}

		return parent::getInstance($prefix);
	}
}
