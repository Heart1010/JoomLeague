<?php
/**
 * @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if( !defined('THUMBLIB_BASE_PATH') ) {
	require_once(JLG_PATH_SITE.DS.'assets'.DS.'classes'.DS.'PHPThumb'.DS.'ThumbLib.inc.php');
}

class JoomleagueHelper
{

	/**
	 * Method to return a project array (id,name)
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	function getProjects()
	{
		$db = JFactory::getDbo();

		$query='	SELECT	id,
				name

				FROM #__joomleague_project
				ORDER BY ordering, name ASC';

		$db->setQuery($query);

		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Method to return the project teams array (id,name)
	 *
	 * @access	public
	 * @return	array
	 * @since	0.1
	 */
	function getProjectteams($project_id)
	{
		$db = JFactory::getDbo();
		$query='	SELECT	pt.id AS value,
				t.name AS text,
				t.notes

				FROM #__joomleague_team AS t
				LEFT JOIN #__joomleague_project_team AS pt ON pt.team_id=t.id
				WHERE pt.project_id='.$project_id.'
						ORDER BY name ASC ';

		$db->setQuery($query);
		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Method to return the project teams array (id,name)
	 *
	 * @access	public
	 * @return	array
	 * @since	1.5.03a
	 */
	function getProjectteamsNew($project_id)
	{
		$db = JFactory::getDbo();

		$query='	SELECT	pt.team_id AS value,
				t.name AS text,
				t.notes

				FROM #__joomleague_team AS t
				LEFT JOIN #__joomleague_project_team AS pt ON pt.team_id=t.id
				WHERE pt.project_id='.(int) $project_id.'
						ORDER BY name ASC ';

		$db->setQuery($query);
		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}

	function getProjectFavTeams($project_id)
	{
		$db = JFactory::getDbo();

		$query='	SELECT fav_team,
				fav_team_color,
				fav_team_text_color,
				fav_team_highlight_type,
				fav_team_text_bold

				FROM #__joomleague_project
				WHERE id='.(int) $project_id;

		$db->setQuery($query);
		if (!$result=$db->loadObject())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Method to return a SportsType name
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	function getSportsTypeName($sportsType)
	{
		$db = JFactory::getDbo();
		$query='SELECT name FROM #__joomleague_sports_type WHERE id='.(int) $sportsType;
		$db->setQuery($query);
		if (!$result=$db->loadResult())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		return JText::_($result);
	}

	/**
	 * Method to return a sportsTypees array (id,name)
	 *
	 * @access	public
	 * @return	array seasons
	 * @since	1.5.0a
	 */
	function getSportsTypes()
	{
		$db = JFactory::getDbo();
		$query='SELECT id, name FROM #__joomleague_sports_type ORDER BY name ASC ';
		$db->setQuery($query);
		if (!$result=$db->loadObjectList())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		foreach ($result as $sportstype){
			$sportstype->name=JText::_($sportstype->name);
		}
		return $result;
	}

	/**
	 * Method to return a SportsType name
	 *
	 * @access	public
	 * @return	array project
	 * @since	1.5
	 */
	function getPosPersonTypeName($personType)
	{
		switch ($personType)
		{
			case 2	:	$result =	JText::_('COM_JOOMLEAGUE_F_TEAM_STAFF');
			break;
			case 3	:	$result =	JText::_('COM_JOOMLEAGUE_F_REFEREES');
			break;
			case 4	:	$result =	JText::_('COM_JOOMLEAGUE_F_CLUB_STAFF');
			break;
			default	:
			case 1	:	$result =	JText::_('COM_JOOMLEAGUE_F_PLAYERS');
			break;
		}
		return $result;
	}

	/**
	 * return name of extension assigned to current project.
	 * @param int project_id
	 * @return string or false
	 */
	function getExtension($project_id=0)
	{
		$option = JRequest::getCmd('option');
		if (!$project_id)
		{
			$app=&JFactory::getApplication();
			$project_id=$app->getUserState($option.'project',0);
		}
		if (!$project_id){
			return false;
		}

		$db=&JFactory::getDbo();
		$query='SELECT extension FROM #__joomleague_project WHERE id='. $db->Quote((int)$project_id);
		$db->setQuery($query);
		$res=$db->loadResult();

		return (!empty($res) ? $res : false);
	}

	public static function getExtensions($project_id)
	{
		jimport('joomla.filesystem.folder');

		$option = JRequest::getCmd('option');
		$arrExtensions = array();
		$excludeExtension = array();
		if ($project_id) {
			$db= JFactory::getDbo();
			$query='SELECT extension FROM #__joomleague_project WHERE id='. $db->Quote((int)$project_id);

			$db->setQuery($query);
			$res=$db->loadObject();
			if(!empty($res)) {
				$excludeExtension = explode(",", $res->extension);
			}
		}
		if(JFolder::exists(JPATH_SITE.DS.'components'.DS.'com_joomleague'.DS.'extensions')) {
			$folderExtensions  = JFolder::folders(JPATH_SITE.DS.'components'.DS.'com_joomleague'.DS.'extensions',
					'.', false, false, $excludeExtension);
			if($folderExtensions !== false) {
				foreach ($folderExtensions as $ext)
				{
					$arrExtensions[] = $ext;
				}
			}
		}

		return $arrExtensions;
	}

	/**
	 * returns number of years between 2 dates
	 *
	 * @param string $birthday date in YYYY-mm-dd format
	 * @param string $current_date date in YYYY-mm-dd format,default to today
	 * @return int age
	 */
	function getAge($date, $seconddate)
	{

		if ( ($date != "0000-00-00") &&
				(preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/',$date,$regs) ) &&
				($seconddate == "0000-00-00") )
		{
			$intAge=date('Y') - $regs[1];
			if($regs[2] > date('m'))
			{
				$intAge--;
			}
			else
			{
				if($regs[2] == date('m'))
				{
					if($regs[3] > date('d')) $intAge--;
				}
			}
			return $intAge;
		}

		if ( ($date != "0000-00-00") &&
				( preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/',$date,$regs) ) &&
				($seconddate != "0000-00-00") &&
				( preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/',$seconddate,$regs2) ) )
		{
			$intAge=$regs2[1] - $regs[1];
			if($regs[2] > $regs2[2])
			{
				$intAge--;
			}
			else
			{
				if($regs[2] == $regs2[2])
				{
					if($regs[3] > $regs2[3] ) $intAge--;
				}
			}
			return $intAge;
		}

		return '-';
	}

	/**
	 * returns the default placeholder
	 *
	 * @param string $type ,default is player
	 * @return string placeholder (path)
	 */
	public static function getDefaultPlaceholder($type="player")
	{
		$params		 	=	JComponentHelper::getParams('com_joomleague');
		$ph_player		=	$params->get('ph_player',0);
		$ph_logo_big	=	$params->get('ph_logo_big',0);
		$ph_logo_medium	=	$params->get('ph_logo_medium',0);
		$ph_logo_small	=	$params->get('ph_logo_small',0);
		$ph_icon		=	$params->get('ph_icon',0);
		$ph_team		=	$params->get('ph_team',0);
		$ph_flag_small	=	$params->get('ph_flag_small',0);
		$ph_flag_big	=	$params->get('ph_flag_big',0);

		//setup the different placeholders
		switch ($type) {
			case "player": //player
				return $ph_player;
				break;
			case "clublogobig": //club logo big
				return $ph_logo_big;
				break;
			case "clublogomedium": //club logo medium
				return $ph_logo_medium;
				break;
			case "clublogosmall": //club logo small
				return $ph_logo_small;
				break;
			case "icon": //icon
				return $ph_icon;
				break;
			case "team": //team picture
				return $ph_team;
				break;
			case "flag_small": //small flag icon
				return $ph_flag_small;
				break;
			case "flag_big": //big flag icon
				return $ph_flag_big;
				break;
			default:
				$picture=null;
				break;
		}
	}

	/**
	 *
	 * static method which return a <img> tag with the given picture
	 * @param string $picture
	 * @param string $alttext
	 * @param int $width=40, if set to 0 the original picture width will be used
	 * @param int $height=40, if set to 0 the original picture height will be used
	 * @param int $type=0, 0=player, 1=club logo big, 2=club logo medium, 3=club logo small, 4=icon, 5=team, 6=small flag, 7=big flag
	 * @return string
	 */
	public static function getPictureThumb($picture, $alttext, $width=40, $height=40, $type=0)
	{
		$ret = "";
		$picturepath 	= 	JPath::clean(JPATH_SITE.DS.str_replace(JPATH_SITE.DS, '', $picture));
		$params		 	=	JComponentHelper::getParams('com_joomleague');
		$ph_player		=	$params->get('ph_player',0);
		$ph_logo_big	=	$params->get('ph_logo_big',0);
		$ph_logo_medium	=	$params->get('ph_logo_medium',0);
		$ph_logo_small	=	$params->get('ph_logo_small',0);
		$ph_icon		=	$params->get('ph_icon',0);
		$ph_team		=	$params->get('ph_team',0);
		$ph_flag_small	=	$params->get('ph_flag_small',0);
		$ph_flag_big	=	$params->get('ph_flag_big',0);

		if (!file_exists($picturepath) || $picturepath == JPATH_SITE.DS)
		{
			//setup the different placeholders
			switch ($type) {
				case 0: //player
					$picture=$ph_player;
					break;
				case 1: //club logo big
					$picture=$ph_logo_big;
					break;
				case 2: //club logo medium
					$picture=$ph_logo_medium;
					break;
				case 3: //club logo small
					$picture=$ph_logo_small;
					break;
				case 4: //icon
					$picture=$ph_icon;
					break;
				case 5: //team picture
					$picture=$ph_team;
					break;
				case 6: //small flag picture
					$picture=$ph_flag_small;
					break;
				case 7: //big flag picture
					$picture=$ph_flag_big;
					break;
				default:
					$picture=null;
					break;
			}
		}

		if (!empty($picture) && is_file(JPath::clean(JPATH_SITE.DS.str_replace(JPATH_SITE.DS, '', $picture))))
		{
			$params = JComponentHelper::getParams('com_joomleague');
			$format = "JPG"; //PNG is not working in IE8
			$format = $params->get('thumbformat', 'PNG');
			$bUseThumbLib = $params->get('usethumblib', false);
			$useThumbCache = $params->get('usethumbnailcache', false);
// Set vars to check if thumbnailcreation is needed
			list($source_width, $source_height) = getimagesize(JPath::clean(JPATH_SITE.DS.str_replace(JPATH_SITE.DS, '', $picture)));
			$needthumb=1;

// Check if thumbnailcreation with phpThumb is really needed
			if ($height==$source_height && $width==$source_width)
			{
				$needthumb=0;
			}
			elseif ($height==0 && $width==$source_width)
			{
				$needthumb=0;
			}
			elseif ($height==$source_height && $width==0)
			{
				$needthumb=0;
			}
			elseif ($height==0 && $width==0)
			{
				$needthumb=0;
			}


// End Check
			if($bUseThumbLib && $needthumb==1 && $useThumbCache==0 && file_exists($picturepath)) {
				try {
					$thumb=PhpThumbFactory::create($picturepath);
					$thumb->setFormat($format);
					//height and width set, resize it with the thumblib
					if($height>0 && $width>0) {
						$thumb->resize ($width, $height);
						$pic=$thumb->getImageAsString();
						$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
						$ret .='" alt="'.$alttext.'" title="'.$alttext.'"/>';
					}
					//height==0 and width set, let the browser resize it
					if($height==0 && $width>0) {
						$thumb->setMaxWidth($width);
						$pic=$thumb->getImageAsString();
						$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
						$ret .='" width="'.$width.'" alt="'.$alttext.'" title="'.$alttext.'"/>';
					}
					//width==0 and height set, let the browser resize it
					if($height>0 && $width==0) {
						$thumb->setMaxHeight($height);
						$pic=$thumb->getImageAsString();
						$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
						$ret .='" height="'.$height.'" alt="'.$alttext.'" title="'.$alttext.'"/>';
					}
					//width==0 and height==0, use original picture size
					if($height==0 && $width==0) {
						$thumb->setMaxHeight($height);
						$pic=$thumb->getImageAsString();
						$ret .= '<img src="data:image/'.$format.';base64,'. base64_encode($pic);
						$ret .='" alt="'.$alttext.'" title="'.$alttext.'"/>';
					}
				} catch (Exception $e) {
					$ret = '';
				}
			} elseif($useThumbCache==0){
				$picturepath = $picture;
				$picture = JUri::root(true).'/'.str_replace(JPATH_SITE.DS, "", $picture);
				$title = $alttext;
				//height and width set, let the browser resize it
				$bUseHighslide = $params->get('use_highslide', false);
				// no highslide if the source picture has exact the same size as the parameters width/height
				// e.g placeholders or correct sized images
				if(function_exists('getimagesize') && JFile::exists($picturepath) && $width>0 && $height>0 ) {
					list($iWidth, $iHeight, $type, $attr) = getimagesize($picturepath);
					$bUseHighslide = ($width!=$iWidth && $iHeight!=$height) ? true : false;
				}
				$arrNoHighSlidePicTypes = array(3,4,6,7,99);
				if($bUseHighslide && !in_array($type, $arrNoHighSlidePicTypes)) {
					$title .= ' (' . JText::_('COM_JOOMLEAGUE_GLOBAL_CLICK_TO_ENLARGE') . ')';
					$ret .= '<a onclick="return hs.expand(this)" href="'.$picture.'" class="highslide">';
				}
				$ret .= '<img ';
				$ret .= ' ';
				if($height>0 && $width>0) {
					$ret .= ' src="'.$picture;
					$ret .='" width="'.$width.'" height="'.$height.'"
							alt="'.$alttext.'" title="'.$title.'"';
				}
				//height==0 and width set, let the browser resize it
				if($height==0 && $width>0) {
					$ret .= ' src="'.$picture;
					$ret .='" width="'.$width.'" alt="'.$alttext.'" title="'.$title.'"';
				}
				//width==0 and height set, let the browser resize it
				if($height>0 && $width==0) {
					$ret .= ' src="'.$picture;
					$ret .='" height="'.$height.'" alt="'.$alttext.'" title="'.$title.'"';
				}
				//width==0 and height==0, use original picture size
				if($height==0 && $width==0) {
					$ret .= ' src="'.$picture;
					$ret .='" alt="'.$alttext.'" title="'.$title.'"';
				}
				$ret .= '/>';
				if($bUseHighslide && !in_array($type, $arrNoHighSlidePicTypes)) {
					$ret .= '</a>';
				}
			}

// Use phpThumb to create cached images and check if the source-file really exists
			$picturepath 	= 	JPath::clean(JPATH_SITE.DS.str_replace(JPATH_SITE.DS, '', $picture));
			if($bUseThumbLib && $useThumbCache==1 && file_exists($picturepath))
			{
				$thumb_cache=PhpThumbFactory::create($picturepath);
				$thumb_cache->setFormat($format);
				if ($needthumb==1)
				{
// check if the cache-directory exitst if not create one
					$image_path_parts = pathinfo($picture);
					$image_cache_path=JPATH::clean(JPATH_SITE.DS.'cache'.DS.'joomleague'.DS.$image_path_parts[dirname]);
					if (!file_exists($image_cache_path))
					{
						mkdir($image_cache_path, 0750, true);
					}
// check if there is a chached actual image if not, create one
					$image_timestamp=date("mdY_His", filectime($picturepath));
					$cached_thumb=JPATH::clean(JPATH_SITE.DS.'cache'.DS.'joomleague'.DS.$image_path_parts[dirname].DS.$image_timestamp.'_'.$height.'_'.$width.'_'.$image_path_parts[filename].'.'.$format);
					$web_cached_thumb=JUri::root(true).'/'.str_replace(JPATH_SITE.DS, "", $cached_thumb);

					if (!file_exists($cached_thumb))
					{
// Check if there is are older files. If Yes, delete them.
					$matches = glob(JPATH::clean(JPATH_SITE.DS.'cache'.DS.'joomleague'.DS.$image_path_parts[dirname].DS.'*_'.$height.'_'.$width.'_'.$image_path_parts[filename].'*'));
					foreach ($matches as $delete_matches) {
						unlink($delete_matches);
					}
					//height and width set
					if($height>0 && $width>0) {
						$thumb_cache->adaptiveResize($width, $height)->save($cached_thumb, $format);
					}
					//height==0 and width set
					if($height==0 && $width>0) {
						$thumb_cache->resize($width,0)->save($cached_thumb, $format);
					}
					//width==0 and height set
					if($height>0 && $width==0) {
						$thumb_cache->resize(0,$height)->save($cached_thumb, $format);
					}
					//width==0 and height==0, do nothing
					if($height==0 && $width==0) {
						$web_cached_thumb=JUri::root(true).'/'.str_replace(JPATH_SITE.DS, "", $picture);
					}
					}
				}
				else
				{
					$web_cached_thumb=JUri::root(true).'/'.str_replace(JPATH_SITE.DS, "", $picture);
				}
// If windows Server is used, replace backslashes with slashes befor return.
				$web_cached_thumb=str_replace('\\', '/', $web_cached_thumb);

// return cached or uncached (if not necessary) images
				$title = $alttext;
				$bUseHighslide = $params->get('use_highslide', false);
				// no highslide if the source picture has exact the same size as the parameters width/height
				// e.g placeholders or correct sized images
				if(function_exists('getimagesize') && JFile::exists($picturepath) && $width>0 && $height>0 ) {
					list($iWidth, $iHeight, $type, $attr) = getimagesize($picturepath);
					$bUseHighslide = ($width!=$iWidth && $iHeight!=$height) ? true : false;
				}
				$arrNoHighSlidePicTypes = array(3,4,6,7,99);
				if($bUseHighslide && !in_array($type, $arrNoHighSlidePicTypes)) {
					$title .= ' (' . JText::_('COM_JOOMLEAGUE_GLOBAL_CLICK_TO_ENLARGE') . ')';
					$ret .= '<a onclick="return hs.expand(this)" href="'.$picture.'" class="highslide">';
				}
				$ret .= '<img ';
				$ret .= ' ';
				if($height>0 && $width>0) {
					$ret .= ' src="'.$web_cached_thumb;
					$ret .='" width="'.$width.'" height="'.$height.'"
							alt="'.$alttext.'" title="'.$title.'"';
				}
				//height==0 and width set, let the browser resize it
				if($height==0 && $width>0) {
					$ret .= ' src="'.$web_cached_thumb;
					$ret .='" width="'.$width.'" alt="'.$alttext.'" title="'.$title.'"';
				}
				//width==0 and height set, let the browser resize it
				if($height>0 && $width==0) {
					$ret .= ' src="'.$web_cached_thumb;
					$ret .='" height="'.$height.'" alt="'.$alttext.'" title="'.$title.'"';
				}
				//width==0 and height==0, use original picture size
				if($height==0 && $width==0) {
					$ret .= ' src="'.$web_cached_thumb;
					$ret .='" alt="'.$alttext.'" title="'.$title.'"';
				}
				$ret .= '/>';
				if($bUseHighslide && !in_array($type, $arrNoHighSlidePicTypes)) {
					$ret .= '</a>';
				}

			}

		}

		return $ret;
	}

	/**
	 * static method which extends template path for given view names
	 * Can be used by views to search for extensions that implement parts of common views
	 * and add their path to the template search path.
	 * (e.g. 'projectheading', 'backbutton', 'footer')
	 * @param array(string) $viewnames, names of views for which templates need to be loaded,
	 *                      so that extensions are used when available
	 * @param JLGView       $view to which the template paths should be added
	 */
	public static function addTemplatePaths($templatesToLoad, &$view)
	{
		$extensions = JoomleagueHelper::getExtensions(JRequest::getInt('p'));
		foreach ($templatesToLoad as $template)
		{
			$view->addTemplatePath(JPATH_COMPONENT . DS . 'views' . DS . $template . DS . 'tmpl');
			if (is_array($extensions) && count($extensions) > 0)
			{
				foreach ($extensions as $e => $extension)
				{
					$extension_views = JPATH_COMPONENT_SITE . DS . 'extensions' . DS . $extension . DS . 'views';
					$tmpl_path = $extension_views . DS . $template . DS . 'tmpl';
					if (JFolder::exists($tmpl_path))
					{
						$view->addTemplatePath($tmpl_path);
					}
				}
			}
		}
	}

	/**
	 * Convert the UTC timestamp of a match (stored as UTC in the database) to:
	 * - the timezone of the Joomla user if that is set
	 * - to the project timezone as set in the project otherwise (so also for guest users,
	 *   aka visitors that have not logged in).
	 *
	 * @param match $match Typically obtained from a DB-query and contains the match_date and timezone (of the project)
	 */
	public static function convertMatchDateToTimezone(&$match)
	{
		if ($match->match_date > 0)
		{
			$app = JFactory::getApplication();
			if ($app->isAdmin())
			{
				// In case we are editing match(es) always use the project timezone
				$timezone = $match->timezone;
			}
			else
			{
				// Otherwise use user timezone for display, and if not set use the project timezone
				$user =& JFactory::getUser();
	 			$timezone = $user->getParam('timezone', $match->timezone);
			}

	 		$matchDate = new JDate($match->match_date, 'UTC');
	 		$matchDate->setTimezone(new DateTimeZone($timezone));

	 		$match->match_date = $matchDate;
	 		$match->timezone = $timezone;
		} else {
			$match->match_date = null;
		}
	}

	public static function getMatchDate($match, $format = 'Y-m-d')
	{
		return $match->match_date ? $match->match_date->format($format, true) : "xxxx-xx-xx";
	}

	public static function getMatchTime($match, $format = 'H:i')
	{
		return $match->match_date ? $match->match_date->format($format, true) : "xx:xx";
	}

	public static function getMatchStartTimestamp($match, $format = 'Y-m-d H:i')
	{
		return $match->match_date ? $match->match_date->format($format, true) : "xxxx-xx-xx xx:xx";
	}

	public static function getMatchEndTimestamp($match, $totalMatchDuration, $format = 'Y-m-d H:i')
	{
		$endTimestamp = "xxxx-xx-xx xx:xx";
		if ($match->match_date)
		{
			$start = new DateTime(self::getMatchStartTimestamp($match));
			$end = $start->add(new DateInterval('PT'.$totalMatchDuration.'M'));
			$endTimestamp = $end->format($format);
		}
		return $endTimestamp;
	}

	public static function getMatchTimezone($match)
	{
		return $match->timezone;
	}

	/**
	 * Method to convert a date from 0000-00-00 to 00-00-0000 or back
	 * return a date string
	 * $direction == 1 means from convert from 0000-00-00 to 00-00-0000
	 * $direction != 1 means from convert from 00-00-0000 to 0000-00-00
	 * call by JoomleagueHelper::convertDate($date) inside the script
	 *
	 * When no "-" are given in $date two short date formats (DDMMYYYY and DDMMYY) are supported
	 * for example "31122011" or "311211" for 31 december 2011
	 *
	 * @access	public
	 * @return	array
	 *
	 */
	public static function convertDate($DummyDate,$direction=1)
	{
		$result = '';
		if(!strpos($DummyDate,"-")!==false)
		{
			// for example 31122011 is used for 31 december 2011
			if (strlen($DummyDate) == 8 )
			{
				$result  = substr($DummyDate,4,4);
				$result .= '-';
				$result .= substr($DummyDate,2,2);
				$result .= '-';
				$result .= substr($DummyDate,0,2);
			}
			// for example 311211 is used for 31 december 2011
			elseif (strlen($DummyDate) == 6 )
			{
				$result  = substr(date("Y"),0,2);
				$result .= substr($DummyDate,4,2);
				$result .= '-';
				$result .= substr($DummyDate,2,2);
				$result .= '-';
				$result .= substr($DummyDate,0,2);
			}
		}
		else
		{

			if ($direction == 1)
			{
				$result  = substr($DummyDate,8);
				$result .= '-';
				$result .= substr($DummyDate,5,2);
				$result .= '-';
				$result .= substr($DummyDate,0,4);
			}
			else
			{
				$result  = substr($DummyDate,6,4);
				$result .= '-';
				$result .= substr($DummyDate,3,2);
				$result .= '-';
				$result .= substr($DummyDate,0,2);
			}
		}

		return $result;
	}

	function showTeamIcons(&$team,&$config)
	{
		if(!isset($team->projectteamid)) return "";
		$projectteamid = $team->projectteamid;
		$teamname      = $team->name;
		$teamid        = $team->team_id;
		$teamSlug      = (isset($team->team_slug) ? $team->team_slug : $teamid);
		$clubSlug      = (isset($team->club_slug) ? $team->club_slug : $team->club_id);
		$division_slug = (isset($team->division_slug) ? $team->division_slug : $team->division_id);
		$projectSlug   = (isset($team->project_slug) ? $team->project_slug : $team->project_id);
		$output        = '';

		if ($config['show_team_link'])
		{
			$link =JoomleagueHelperRoute::getPlayersRoute($projectSlug,$teamSlug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_ROSTER_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/team_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if (((!isset($team_plan)) || ($teamid!=$team_plan->id)) && ($config['show_plan_link']))
		{
			$link =JoomleagueHelperRoute::getTeamPlanRoute($projectSlug,$teamSlug,$division_slug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_TEAMPLAN_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/calendar_icon.gif';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_curve_link'])
		{
			$link =JoomleagueHelperRoute::getCurveRoute($projectSlug,$teamSlug,0,$division_slug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_CURVE_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/curve_icon.gif';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_teaminfo_link'])
		{
			$link =JoomleagueHelperRoute::getTeamInfoRoute($projectSlug,$teamid);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_TEAMINFO_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/teaminfo_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_club_link'])
		{
			$link =JoomleagueHelperRoute::getClubInfoRoute($projectSlug,$clubSlug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_CLUBINFO_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/mail.gif';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_teamstats_link'])
		{
			$link =JoomleagueHelperRoute::getTeamStatsRoute($projectSlug,$teamSlug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_TEAMSTATS_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/teamstats_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_clubplan_link'])
		{
			$link =JoomleagueHelperRoute::getClubPlanRoute($projectSlug,$clubSlug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_CLUBPLAN_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/clubplan_icon.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		if ($config['show_rivals_link'])
		{
			$link =JoomleagueHelperRoute::getRivalsRoute($projectSlug,$teamSlug);
			$title=JText::_('COM_JOOMLEAGUE_TEAMICONS_RIVALS_LINK').'&nbsp;'.$teamname;
			$picture = 'media/com_joomleague/jl_images/rivals.png';
			$desc = self::getPictureThumb($picture, $title, 0, 0, 4);
			$output .= JHtml::link($link,$desc);
		}

		return $output;
	}

	function formatTeamName($team, $containerprefix, &$config, $isfav=0, $link=null)
	{
		$output			= '';
		$desc			= '';

		if ((isset($config['results_below'])) && ($config['results_below']) && ($config['show_logo_small']))
		{
			$js_func		= 'visibleMenu';
			$style_append	= 'visibility:hidden';
			$container		= 'span';
		}
		else
		{
			$js_func		= 'switchMenu';
			$style_append	= 'display:none';
			$container		= 'div';
		}

		$showIcons=	(
				($config['show_info_link']==2) && ($isfav)
		) ||
		(
				($config['show_info_link']==1) &&
				(
						$config['show_club_link'] ||
						$config['show_team_link'] ||
						$config['show_curve_link'] ||
						$config['show_plan_link'] ||
						$config['show_teaminfo_link'] ||
						$config['show_teamstats_link'] ||
						$config['show_clubplan_link'] ||
						$config['show_rivals_link']
				)
		);
		$containerId = $containerprefix.'t'.$team->id.'p'.$team->project_id;
		if ($showIcons)
		{
			$onclick	= $js_func.'(\''.$containerId.'\');return false;';
			$params		= array('onclick' => $onclick);
		}

		$style = 'padding:2px;';
		if ($config['highlight_fav'] && $isfav)
		{
			$favs = self::getProjectFavTeams($team->project_id);
			$style .= ($favs->fav_team_text_bold != '') ? 'font-weight:bold;' : '';
			$style .= (trim($favs->fav_team_text_color) != '') ? 'color:'.trim($favs->fav_team_text_color).';' : '';
			$style .= (trim($favs->fav_team_color) != '') ? 'background-color:'.trim($favs->fav_team_color).';' : '';
		}

		$desc .= '<span style="'.$style.'">';

		$formattedTeamName = "";
		if ($config['team_name_format']== 0)
		{
			$formattedTeamName = $team->short_name;
		}
		else if ($config['team_name_format']== 1)
		{
			$formattedTeamName = $team->middle_name;
		}
		if (empty($formattedTeamName))
		{
			$formattedTeamName = $team->name;
		}

		if (($config['team_name_format']== 0) && (!empty($team->short_name)))
		{
			$desc .=  '<acronym title="'.$team->name.'">'.$team->short_name.'</acronym>';
		}
		else
		{
			$desc .= $formattedTeamName;
		}

		$desc .=  '</span>';

		if ($showIcons)
		{
			$output .= JHtml::link('javascript:void(0);',$desc,$params);
			$output .= '<'.$container.' id="'.$containerId.'" style="'.$style_append.';">';
			$output .= self::showTeamIcons ($team,$config);
			$output .= '</'.$container.'>';
		}
		else
		{
			$output = $desc;
		}

		if ($link != null)
		{
			$output = JHtml::link($link, $output);
		}

		return $output;
	}

	function showClubIcon(&$team,$type=1,$with_space=0)
	{
		if (($type==1) && (isset($team->country)))
		{
			if ($team->logo_small!='')
			{
				echo JHtml::image($team->logo_small,'');
				if ($with_space==1){
					echo ' style="padding:1px;"';
				}
			}
			else
			{
				echo '&nbsp;';
			}
		}
		elseif (($type==2) && (isset($team->country)))
		{
			echo Countries::getCountryFlag($team->country);
		}
	}

	function showColorsLegend($colors, $showfavteam = null)
	{
		if ($showfavteam == 1)
		{
			$favshow = JRequest::getVar('view');
			if (($favshow!='curve') && ($this->project->fav_team))
			{
				$fav=array('color'=>$this->project->fav_team_color,'description'=> JText::_('COM_JOOMLEAGUE_RANKING_FAVTEAM'));
				array_push($colors,$fav);
			}
		}
		foreach($colors as $color)
		{
			if (trim($color['description'])!='')
			{
				echo '<td style="background-color:'.$color['color'].'; width: 15px !important;">&nbsp;</td>'."\n";
				echo '<td style="padding: 0 15px 0 10px"><b>'.$color['description'].'</b>&nbsp;</td>'."\n";
			}

		}
	}


	/**
	 * Removes invalid XML
	 *
	 * @access public
	 * @param string $value
	 * @return string
	 */
	public function stripInvalidXml($value)
	{
		$ret='';
		$current='';
		if (is_null($value)){
			return $ret;
		}

		$length=strlen($value);
		for ($i=0; $i < $length; $i++)
		{
			$current=ord($value{$i});
			if (($current == 0x9) ||
					($current == 0xA) ||
					($current == 0xD) ||
					(($current >= 0x20) && ($current <= 0xD7FF)) ||
					(($current >= 0xE000) && ($current <= 0xFFFD)) ||
					(($current >= 0x10000) && ($current <= 0x10FFFF)))
			{
				$ret .= chr($current);
			}
			else
			{
				$ret .= ' ';
			}
		}
		return $ret;
	}

	public static function getVersion()
	{
		$database = JFactory::getDbo();

		$query="SELECT CONCAT(major,'.',minor,'.',build,'.',revision) AS version
				FROM #__joomleague_version
				ORDER BY date DESC LIMIT 1";
		$database->setQuery($query);
		$result=$database->loadResult();
		return $result;
	}

	/**
	 * returns formatName
	 *
	 * @param prefix
	 * @param firstName
	 * @param nickName
	 * @param lastName
	 * @param format
	 */
	public static function formatName($prefix, $firstName, $nickName, $lastName, $format)
	{
		$name = array();
		if ($prefix)
		{
			$name[] = $prefix;
		}
		switch ($format)
		{
			case 0: //Firstname 'Nickname' Lastname
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 1: //Lastname, 'Nickname' Firstname
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 2: //Lastname, Firstname 'Nickname'
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				break;
			case 3: //Firstname Lastname
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 4: //Lastname, Firstname
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 5: //'Nickname' - Firstname Lastname
				if ($nickName != "") {
					$name[] = "'" . $nickName . "' - ";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 6: //'Nickname' - Lastname, Firstname
				if ($nickName != "") {
					$name[] = "'" . $nickName . "' - ";
				}
				if ($lastName != "") {
					$name[] = $lastName . ",";
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 7: //Firstname Lastname (Nickname)
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName ;
				}
				if ($nickName != "") {
					$name[] = "(" . $nickName . ")";
				}
				break;
			case 8: //F. Lastname
				if ($firstName != "") {
					$name[] = $firstName[0] . ".";
				}
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 9: //Lastname, F.
				if ($lastName != "") {
					$name[] = $lastName.",";
				}
				if ($firstName != "") {
					$name[] = $firstName[0] . ".";
				}
				break;
			case 10: //Lastname
				if ($lastName != "") {
					$name[] = $lastName;
				}
				break;
			case 11: //Firstname 'Nickname' L.
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = "'" . $nickName . "'";
				}
				if ($lastName != "") {
					$name[] = $lastName[0]. ".";
				}
				break;
			case 12: //Nickname
				if ($nickName != "") {
					$name[] = $nickName;
				}
				break;
			case 13: //Firstname L.
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($lastName != "") {
					$name[] = $lastName[0]. ".";
				}
				break;
			case 14: //Lastname Firstname
				if ($lastName != "") {
					$name[] = $lastName;
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 15: //Lastname newline Firstname
				if ($lastName != "") {
					$name[] = $lastName;
					$name[] = '<br \>';
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 16: //Firstname newline Lastname
				if ($lastName != "") {
					$name[] = $lastName;
					$name[] = '<br \>';
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				break;
			case 17: //Lastname Firstname Nickname
				if ($lastName != "") {
					$name[] = $lastName;
				}
				if ($firstName != "") {
					$name[] = $firstName;
				}
				if ($nickName != "") {
					$name[] = $nickName;
				}
				break;
			case 18: //Lastname F.
				if ($lastName != "") {
					$name[] = $lastName;
				}
				if ($firstName != "") {
					$name[] = mb_substr($firstName,0,1).".";
				}
				break;

		}

		return implode(" ", $name);
	}

	/**
	 * returns titleInfo
	 *
	 * @param prefix Text that must be placed at the start of the title.
	 */
	public static function createTitleInfo($prefix)
	{
		return (object)array(
			"prefix" => $prefix,
			"clubName" => null,
			"team1Name" => null,
			"team2Name" => null,
			"roundName" => null,
			"personName" => null,
			"playgroundName" => null,
			"projectName" => null,
			"divisionName" => null,
			"leagueName" => null,
			"seasonName" => null
		);
	}

	/**
	 * returns formatName
	 *
	 * @param titleInfo (info on prefix, teams (optional), project, division (optional), league and season)
	 * @param format
	 */
	public static function formatTitle($titleInfo, $format)
	{
		$name = array();

		if (!empty($titleInfo->personName)) {
			$name[] = $titleInfo->personName;
		}

		if (!empty($titleInfo->playgroundName)) {
			$name[] = $titleInfo->playgroundName;
		}

		if (!empty($titleInfo->team1Name)) {
			if (!empty($titleInfo->team2Name)) {
				$name[] = $titleInfo->team1Name." - ".$titleInfo->team2Name;
			} else {
				$name[] = $titleInfo->team1Name;
			}
		}

		if (!empty($titleInfo->clubName)) {
			$name[] = $titleInfo->clubName;
		}

		if (!empty($titleInfo->roundName)) {
			$name[] = $titleInfo->roundName;
		}

		$projectDivisionName = !empty($titleInfo->projectName) ? $titleInfo->projectName : "";
		if (!empty($titleInfo->divisionName)) $projectDivisionName .= " - ".$titleInfo->divisionName;

		switch ($format)
		{
			case 0: //Projectname
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				break;
			case 1: //Project and league name
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				break;
			case 2: //Project, league and season name
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 3: //Project and season name
				if (!empty($projectDivisionName)) {
					$name[] = $projectDivisionName;
				}
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 4: //League name
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				break;
			case 5: //League and season name
				if (!empty($titleInfo->leagueName)) {
					$name[] = $titleInfo->leagueName;
				}
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 6: //Season name
				if (!empty($titleInfo->seasonName)) {
					$name[] = $titleInfo->seasonName;
				}
				break;
			case 7: // None
				break;
		}

		return $titleInfo->prefix . ": " . implode(" | ", $name);
	}

	/**
	 * Creates the print button
	 *
	 * @param string $print_link
	 * @param array $config
	 * @since 1.5.2
	 */
	public static function printbutton($print_link, &$config)
	{
		if ($config['show_print_button'] == 1) {
			JHtml::_('behavior.tooltip');
			$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no';
			// checks template image directory for image, if non found default are loaded
			if ($config['show_icons'] == 1 ) {
				$image = JHtml::_('image.site', 'printButton.png', 'media/com_joomleague/jl_images/', NULL, NULL, JText::_( 'Print' ));
			} else {
				$image = JText::_( 'Print' );
			}
			if (JRequest::getInt('pop')) {
				//button in popup
				$output = '<a href="javascript: void(0)" onclick="window.print();return false;">'.$image.'</a>';
			} else {
				//button in view
				$overlib = JText::_( 'COM_JOOMLEAGUE_GLOBAL_PRINT_TIP' );
				$text = JText::_( 'COM_JOOMLEAGUE_GLOBAL_PRINT' );
				$print_urlparams = "tmpl=component&print=1";

				if(is_null($print_link)) {
					$output	= '<a href="javascript: void(0)" class="editlinktip hasTip" onclick="window.open(window.location.href + (window.location.href.indexOf(\'?\') != -1 ? \'&amp;\' : \'?\' ) + \''.$print_urlparams.'\',\'win2\',\''.$status.'\'); return false;" rel="nofollow" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
				} else {
					$output	= '<a href="'. JRoute::_($print_link) .'" class="editlinktip hasTip" onclick="window.open(window.location.href + (window.location.href.indexOf(\'?\') != -1 ? \'&amp;\' : \'?\' ) +  \''.$print_urlparams.'\',\'win2\',\''.$status.'\'); return false;" rel="nofollow" title="'.$text.'::'.$overlib.'">'.$image.'</a>';
				}
			}
			return $output;
		}
		return '';
	}

	/**
	 * return true if mootools upgrade is enabled
	 *
	 * @return boolean
	 */
	function isMootools12()
	{
		$version = new JVersion();
		if ($version->RELEASE == '1.5' && $version->DEV_LEVEL >= 19 && JPluginHelper::isEnabled( 'system', 'mtupgrade' ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * return project rounds as array of objects(roundid as value, name as text)
	 *
	 * @param string $ordering
	 * @return array
	 */
	public static function getRoundsOptions($project_id, $ordering='ASC', $required = false)
	{
		$db = JFactory::getDbo();
		$query = ' SELECT id as value '
				. '      , CASE LENGTH(name) when 0 then CONCAT('.$db->Quote(JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAY_NAME')). ', " ", id)	else name END as text '
				. '      , id, name, round_date_first, round_date_last, roundcode '
				. ' FROM #__joomleague_round '
				. ' WHERE project_id= ' .$project_id
				. ' ORDER BY roundcode '.$ordering;

		$db->setQuery($query);
		if(!$required) {
			$mitems = array(JHtml::_('select.option', '', JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT')));
			$items = $db->loadObjectList();
			if(!empty($items)) {
				return array_merge($mitems, $items);
			} else {
				return $mitems;
			}
		} else {
			return $db->loadObjectList();
		}
	}

	/**
	 * returns -1/0/1 if the team lost/drew/won in specified game, or false if not played/cancelled
	 *
	 * @param object $game date from match table
	 * @param int $ptid project team id
	 * @return false|int
	 */
	function getTeamMatchResult($game, $ptid)
	{
		if (!isset($game->team1_result)) {
			return false;
		}
		if ($game->cancel) {
			return false;
		}

		if (!$game->alt_decision)
		{
			$result1 = $game->team1_result;
			$result2 = $game->team2_result;
		}
		else
		{
			$result1 = $game->team1_result_decision;
			$result2 = $game->team2_result_decision;
		}
		if ($result1 == $result2) {
			return 0;
		}

		if ($ptid == $game->projectteam1_id) {
			return ($result1 > $result2) ? 1 : -1;
		}
		else {
			return ($result1 > $result2) ? -1 : 1;
		}
	}

	public static function getCommentsIntegrationPlugin() {
		if(file_exists(JPATH_ROOT . '/components/com_jcomments/classes/config.php')) {
			require_once (JPATH_ROOT . '/components/com_jcomments/classes/config.php');
			require_once (JPATH_ROOT . '/components/com_jcomments/jcomments.class.php');
			require_once (JPATH_ROOT . '/components/com_jcomments/models/jcomments.php');
		}
		// load joomleague comments plugin files
		JPluginHelper::importPlugin( 'content', 'joomleague_comments' );
		// get joomleague comments plugin params
		return JPluginHelper::getPlugin('content', 'joomleague_comments');
	}

	public function removeBOM($str) {
		$bom = pack ( "CCC", 0xef, 0xbb, 0xbf );
		if (0 == strncmp ( $str, $bom, 3 )) {
			//BOM detected - str is UTF-8
			$str = substr ( $str, 3 );
		}
		return $str;
	}

	public static function getTimezone($project, $overallconfig) {
		if($project) {
			return $project->timezone;
		} else {
			return $overallconfig['time_zone'];
		}
	}
}
?>
