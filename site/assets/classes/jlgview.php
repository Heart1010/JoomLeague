<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('JPATH_BASE') or die;

jimport( 'joomla.application.component.view');

class JLGView extends JViewLegacy
{
	protected $input;
	
	
	public function __construct($config = array())
	{
		parent::__construct();
		
		$this->input = JFactory::getApplication()->input;
	}
	

	/**
	 * Sets an entire array of search paths for templates or resources.
	 *
	 * @access protected
	 * @param string $type The type of path to set, typically 'template'.
	 * @param string|array $path The new set of search paths.  If null or
	 * false, resets to the current directory only.
	 */
	function _setPath($type, $path)
	{
		$option     = JApplicationHelper::getComponentName();
		$app		= JFactory::getApplication();

		$extensions	= JoomleagueHelper::getExtensions(JRequest::getInt('p'));
		if (!count($extensions))
		{
			return parent::_setPath($type, $path);
		}

		// clear out the prior search dirs
		$this->_path[$type] = array();

		// actually add the user-specified directories
		$this->_addPath($type, $path);

		// add extensions paths
		if (strtolower($type) == 'template')
		{
			foreach ($extensions as $e => $extension)
			{
				$JLGPATH_EXTENSION =  JPATH_COMPONENT_SITE.'/extensions/'.$extension;

				// set the alternative template search dir
				if (isset($app))
				{
					if ($app->isAdmin()) {
						$this->_addPath('template',$JLGPATH_EXTENSION.'/admin/views/'.$this->getName().'/tmpl');
					}
					else {
						$this->_addPath('template',$JLGPATH_EXTENSION.'/views/'.$this->getName().'/tmpl');
					}

					// always add the fallback directories as last resort
					$option = preg_replace('/[^A-Z0-9_\.-]/i', '', $option);
					$fallback = JPATH_THEMES.'/'.$app->getTemplate().'/html/'.$option.'/'.$extension.'/'.$this->getName();
					$this->_addPath('template', $fallback);
				}
			}
		}
	}

	public function display($tpl = null )
	{
		$option 	= JRequest::getCmd('option');
		$app		= JFactory::getApplication();
		$document	= JFactory::getDocument();
		$version 	= urlencode(JoomleagueHelper::getVersion());
		//support for global client side lang res
		JHtml::_('behavior.formvalidation');
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');
		$lang 		= JFactory::getLanguage();
		$jllang 	= new JLLanguage();
		$jllang->setLanguage($lang);
		$props 		= $jllang->getProperties();
		$strings 	= &$props['strings'];
		foreach ($strings as $key => $value) {
			if($app->isAdmin()) {
				if(strpos($key, 'COM_JOOMLEAGUE_ADMIN_'.strtoupper($this->getName()).'_CSJS') !== false) {
					JText::script($key, true);
				}
			} else {
				if(strpos($key, 'COM_JOOMLEAGUE_'.strtoupper($this->getName()).'_CSJS_')  !== false) {
					JText::script($key, true);
				}
			}
		}
		// General Joomleague CSS include
		$file = JPATH_COMPONENT.'/assets/css/joomleague.css';
		if(file_exists(JPath::clean($file))) {
			$document->addStyleSheet( $this->baseurl . '/components/'.$option.'/assets/css/joomleague.css?v=' . $version );
		}
		// Genereal CSS include per view
		$file = JPATH_COMPONENT.'/assets/css/'.$this->getName().'.css';
		if(file_exists(JPath::clean($file))) {
			//add css file
			$document->addStyleSheet(  $this->baseurl . '/components/'.$option.'/assets/css/'.$this->getName().'.css?v=' . $version );
		}
		// General Joomleague JS include
		$file = JPATH_COMPONENT.'/assets/js/joomleague.js';
		if(file_exists(JPath::clean($file))) {
			$js = $this->baseurl . '/components/'.$option.'/assets/js/joomleague.js?v=' . $version;
			$document->addScript($js);
		}
		
		// General JS include per view
		self::includeLanguageStrings();
		
		$file = JPATH_COMPONENT.'/assets/js/'.$this->getName().'.js';
		if(file_exists(JPath::clean($file))) {
			$js = $this->baseurl . '/components/'.$option.'/assets/js/'.$this->getName().'.js?v=' . $version;
			$document->addScript($js);
		}

		//extension management
		$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));
		foreach ($extensions as $e => $extension) {
			$JLGPATH_EXTENSION =  JPATH_COMPONENT_SITE.'/extensions/'.$extension;

			//General extension CSS include
			$file = $JLGPATH_EXTENSION.'/assets/css/'.$extension.'.css';
			if(file_exists(JPath::clean($file))) {
				$document->addStyleSheet($this->baseurl.'/components/'.$option.'/extensions/'. $extension.'/assets/css/'.$extension.'.css?v='.$version);
			}
			//CSS override
			$file = $JLGPATH_EXTENSION.'/assets/css/'.$this->getName().'.css';
			if(file_exists(JPath::clean($file))) {
				//add css file
				$document->addStyleSheet($this->baseurl.'/components/'.$option.'/extensions/'.$extension.'/assets/css/'.$this->getName().'.css?v='.$version);
			}
			//General extension JS include
			$file = $JLGPATH_EXTENSION.'/assets/js/'.$extension.'.js';
			if(file_exists(JPath::clean($file))) {
				//add js file
				$document->addScript($this->baseurl.'/components/'.$option.'/extensions/'.$extension.'/assets/js/'.$extension.'.js?v='.$version);
			}
			//JS override
			$file = $JLGPATH_EXTENSION.'/assets/js/'.$this->getName().'.js';
			if(file_exists(JPath::clean($file))) {
				//add js file
				$document->addScript(  $this->baseurl . '/components/'.$option.'/extensions/' . $extension . '/assets/js/'.$this->getName().'.js?v=' . $version );
			}
			if($app->isAdmin()) {
				$JLGPATH_EXTENSION =  JPATH_COMPONENT_SITE.'/extensions/'.$extension.'/admin';

				//General extension CSS include
				$file = $JLGPATH_EXTENSION.'/assets/css/'.$extension.'.css';
				if(file_exists(JPath::clean($file))) {
					$document->addStyleSheet($this->baseurl.'/../components/'.$option.'/extensions/'.$extension.'/admin/assets/css/'.$extension.'.css?v=' . $version );
				}
				//CSS override
				$file = $JLGPATH_EXTENSION.'/assets/css/'.$this->getName().'.css';
				if(file_exists(JPath::clean($file))) {
					//add css file
					$document->addStyleSheet($this->baseurl.'/../components/'.$option.'/extensions/'.$extension.'/admin/assets/css/'.$this->getName().'.css?v=' . $version );
				}
				//General extension JS include
				$file = $JLGPATH_EXTENSION.'/assets/js/'.$extension.'.js';
				if(file_exists(JPath::clean($file))) {
					//add js file
					$document->addScript(  $this->baseurl.'/../components/'.$option.'/extensions/'.$extension.'/admin/assets/js/'.$extension.'.js?v=' . $version );
				}
				//JS override
				$file = $JLGPATH_EXTENSION.'/assets/js/'.$this->getName().'.js';
				if(file_exists(JPath::clean($file))) {
					//add js file
					$document->addScript($this->baseurl.'/../components/'.$option.'/extensions/'.$extension.'/admin/assets/js/'.$this->getName().'.js?v=' . $version);
				}
			}
		}
		parent::display( $tpl );
	}

	/**
	 * support for extensions which can overload extended data
	 * @param string $data
	 * @param string $file
	 * @return object
	 */
	function getExtended($data='', $file, $format='ini')
	{
		$xmlfile=JLG_PATH_ADMIN.'/assets/extended/'.$file.'.xml';
		//extension management
		$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));
		foreach ($extensions as $e => $extension) {
			$JLGPATH_EXTENSION = JPATH_COMPONENT_SITE.'/extensions/'.$extension.'/admin';
			//General extension extended xml
			$file = $JLGPATH_EXTENSION.'/assets/extended/'.$file.'.xml';
			if(file_exists(JPath::clean($file))) {
				$xmlfile = $file;
				break; //first extension file will win
			}
		}
		/*
		 * extended data
		*/
		$jRegistry = new JRegistry;
		$jRegistry->loadString($data, $format);
		$extended = JForm::getInstance('extended', $xmlfile,
				array('control'=> 'extended'),
				false, '/config');
		$extended->bind($jRegistry);
		return $extended;
	}
	
	
	
	private function includeLanguageStrings() 
	{
		if ($this->getName() == 'club') {
			JText::script('COM_JOOMLEAGUE_ADMIN_CLUB_CSJS_NO_NAME');
		}
		if ($this->getName() == 'division') {
			JText::script('COM_JOOMLEAGUE_ADMIN_DIVISION_CSJS_NO_NAME');
		}
		if ($this->getName() == 'eventtype') {
			JText::script('COM_JOOMLEAGUE_ADMIN_EVENTTYPE_CSJS_NAME_REQUIRED');
		}
		if ($this->getName() == 'league') {
			JText::script('COM_JOOMLEAGUE_ADMIN_LEAGUE_CSJS_NO_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_LEAGUE_CSJS_NO_SHORT_NAME');
		}
		if ($this->getName() == 'person') {
			JText::script('COM_JOOMLEAGUE_ADMIN_PERSON_CSJS_NO_NAME');
		}
		if ($this->getName() == 'playground') {
			JText::script('COM_JOOMLEAGUE_ADMIN_PLAYGROUND_CSJS_NO_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_PLAYGROUND_CSJS_NO_S_NAME');
		}
		if ($this->getName() == 'position') {
			JText::script('COM_JOOMLEAGUE_ADMIN_POSITION_CSJS_NEEDS_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_POSITION_CSJS_NEEDS_SPORTSTYPE');
		}
		if ($this->getName() == 'project') {
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_LEAGUE_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_SEASON_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_SPORT_TYPE');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_ADMIN');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_MATCHDAY');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_MATCHTIME');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_MATCHDATE');
			JText::script('COM_JOOMLEAGUE_ADMIN_PROJECT_CSJS_ERROR_MATCHDAY');
		}
		if ($this->getName() == 'round') {
			JText::script('COM_JOOMLEAGUE_ADMIN_ROUND_CSJS_NO_ROUNDCODE');
			JText::script('COM_JOOMLEAGUE_ADMIN_ROUND_CSJS_NO_NAME');
		}
		if ($this->getName() == 'rounds') {
			JText::script('COM_JOOMLEAGUE_ADMIN_ROUNDS_CSJS_MSG_NOTANUMBER');
		}
		if ($this->getName() == 'season') {
			JText::script('COM_JOOMLEAGUE_ADMIN_SEASON_CSJS_NO_NAME');
		}
		if ($this->getName() == 'sportstype') {
			JText::script('COM_JOOMLEAGUE_ADMIN_SPORTSTYPE_CSJS_UNTRANSLATED_NAME');
		}
		if ($this->getName() == 'statistic') {
			JText::script('COM_JOOMLEAGUE_FORM_JS_CHECK_ERROR');
		}
		if ($this->getName() == 'team') {
			JText::script('COM_JOOMLEAGUE_ADMIN_TEAM_CSJS_NO_NAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_TEAM_CSJS_NO_SHORTNAME');
			JText::script('COM_JOOMLEAGUE_ADMIN_TEAM_CSJS_NO_CLUB');
		}
		if ($this->getName() == 'template') {
			JText::script('COM_JOOMLEAGUE_ADMIN_TEMPLATE_CSJS_WRONG_VALUES');
		}
	}
}
