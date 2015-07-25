<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at 
 * @author		Wolfgang Pinitsch <andone@mfga.at>
 */
defined('_JEXEC') or die();

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class com_joomleagueInstallerScript
{
	private function _install($update=false, $parent) 
	{
		echo JHtml::_('sliders.start','steps',array('allowAllClose' => true,'startTransition' => true,true));
		$image = '<img src="../media/com_joomleague/jl_images/ext_com.png">';
		echo JHtml::_('sliders.panel', $image.' Component', 'panel-component');
		?>
		<h2>Welcome to JoomLeague!</h2>
		<img
			src="../media/com_joomleague/jl_images/joomleague_logo.png"
			alt="JoomLeague" title="JoomLeague" />
		<?php
		$this->install_admin_rootfolder	= JPATH::clean($parent->getParent()->getPath('source').'/admin');
		$this->install_rootfolder 		= $parent->getParent()->getPath('source');
		$this->debug = false;
		$maxExecutionTime = $maxInputTime = 900;
		if ((int)ini_get('max_execution_time') < $maxExecutionTime){
			@set_time_limit($maxExecutionTime);
		}
		if ((int)ini_get('max_input_time') < $maxInputTime){
			@set_time_limit($maxInputTime);
		}
		$time_start = microtime(true);
		
		// slider - database
		$image = '<img src="../media/com_joomleague/jl_images/ext_esp.png">';
		echo JHtml::_('sliders.panel', $image.' Database', 'panel-database');
		
		include_once($this->install_admin_rootfolder.'/models/databasetools.php');
		
		if ($update) {
		
		}
				
		// slider - imagefolder
		$image = '<img src="../media/com_joomleague/jl_images/ext_esp.png">';
		echo JHtml::_('sliders.panel', $image.' Create/Update Images Folders', 'panel-images');
		self::createImagesFolder();
		
		// slider - basicData
		$image = '<img src="../media/com_joomleague/jl_images/ext_esp.png">';
		echo JHtml::_('sliders.panel', $image.' Basic Data', 'panel-basicdata');
		
		include_once($this->install_rootfolder.'/site/joomleague.core.php');
		include_once($this->install_admin_rootfolder.'/assets/updates/jl_install.php');
		
		self::installPermissions();
		echo JHtml::_('sliders.end');
		echo self::getFxInitJSCode('steps');
		?>
		
		<hr />
	<?php
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		echo '<hr />';
		if($this->debug) {
			echo '<br>Overall Duration: '.round($time).'s<br>';
		}
	}

		
	public function installPermissions()
	{
		$time_start = microtime(true);
		jimport('joomla.access.rules');
		$app = JFactory::getApplication();
	
		// Get the root rules
		$root = JTable::getInstance('asset');
		$root->loadByName('root.1');
		$root_rules = new JAccessRules($root->rules);
	
		// Define the new rules
		$ACL_PERMISSIONS = '{"core.admin":[],"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[],"settings.edit":[],"settings.save":[]}';
		$new_rules = new JAccessRules($ACL_PERMISSIONS);
	
		// Merge the rules into default rules and save it
		$root_rules->merge($new_rules);
		$root->rules = (string)$root_rules;
		if ( $root->store() ) {
			echo 'Installed ACL Permissions';
			echo ' - <span style="color:green">'.JText::_('Success').'</span><br />';
		}
		else {
			echo ' - <span style="color:red">'.JText::_('Failed').'</span><br />';
		}
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		if($this->debug) {
			echo 'Duration: '.round($time).'s<br>';
		}
	}
	
	private function getFxInitJSCode ($group) {
		$params = array();
		$params['allowAllClose'] = 'true';
		$display = (isset($params['startOffset']) && isset($params['startTransition']) && $params['startTransition'])
		? (int) $params['startOffset'] : null;
		$show = (isset($params['startOffset']) && !(isset($params['startTransition']) && $params['startTransition']))
		? (int) $params['startOffset'] : null;
		$options = '{';
		$opt['onActive'] = "function(toggler, i) {toggler.addClass('pane-toggler-down');" .
				"toggler.removeClass('pane-toggler');i.addClass('pane-down');i.removeClass('pane-hide');Cookie.write('jpanesliders_"
				. $group . "',$$('div#" . $group . ".pane-sliders > .panel > h3').indexOf(toggler));}";
		$opt['onBackground'] = "function(toggler, i) {toggler.addClass('pane-toggler');" .
				"toggler.removeClass('pane-toggler-down');i.addClass('pane-hide');i.removeClass('pane-down');if($$('div#"
				. $group . ".pane-sliders > .panel > h3').length==$$('div#" . $group
				. ".pane-sliders > .panel > h3.pane-toggler').length) Cookie.write('jpanesliders_" . $group . "',-1);}";
		$opt['duration'] = (isset($params['duration'])) ? (int) $params['duration'] : 300;
		$opt['display'] = (isset($params['useCookie']) && $params['useCookie']) ? JRequest::getInt('jpanesliders_' . $group, $display, 'cookie')
		: $display;
		$opt['show'] = (isset($params['useCookie']) && $params['useCookie']) ? JRequest::getInt('jpanesliders_' . $group, $show, 'cookie') : $show;
		$opt['opacity'] = (isset($params['opacityTransition']) && ($params['opacityTransition'])) ? 'true' : 'false';
		$opt['alwaysHide'] = (isset($params['allowAllClose']) && (!$params['allowAllClose'])) ? 'false' : 'true';
		foreach ($opt as $k => $v)
		{
			if ($v)
			{
				$options .= $k . ': ' . $v . ',';
			}
		}
		if (substr($options, -1) == ',')
		{
			$options = substr($options, 0, -1);
		}
		$options .= '}';
		
		$js = "window.addEvent('domready', function(){ new Fx.Accordion($$('div#" . $group
		. ".pane-sliders > .panel > h3.pane-toggler'), $$('div#" . $group . ".pane-sliders > .panel > div.pane-slider'), " . $options
		. "); });";
		
		return '<script>'.$js.'</script>';
	}
	
	
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	public function install($parent)
	{
		?>
		<hr>
		<h1>JoomLeague Installation</h1>
		<?php 
		if(self::_versionCompare()) {
			self::_install(false, $parent);
		}
	}

	private function _versionCompare () {
		if (version_compare(phpversion(), '5.3.0', '<')===true) {
			// echo  '<div style="font:12px/1.35em arial, helvetica, sans-serif;"><div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;"><h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">Whoops, it looks like you have an invalid PHP version.</h3></div><p>JoomLeague requires PHP 5.2.4 or newer.</p><p>PHP4 is no longer supported by its developers and your webhost almost certainly offers PHP5.  Please contact your webhost for advice on how to enable PHP5 on your website.</p></div>';
			return false;
		}
		return true;
	}
	 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	public function update($parent)
	{
		?>
		<hr>
		<h1>JoomLeague Update</h1>
		<?php
		if(self::_versionCompare()) { 
			self::_install(true, $parent);
		}
	}

	/**
	 * 
	 */
	public function postflight($route, $adapter) 
	{
		
	}
	
	public function uninstall($adapter)
	{
		$params = JComponentHelper::getParams('com_joomleague');
		//Also uninstall db tables of JoomLeague?
		$uninstallDB = $params->get('cfg_drop_joomleague_tables_when_uninstalled',0); 
		$uninstallDB = true;
		
		if ($uninstallDB)
		{
			echo JText::_('Also removing database tables of JoomLeague');
			include_once(JPATH_ADMINISTRATOR.'/components/com_joomleague/models/databasetools.php');
			JoomleagueModelDatabaseTools::dropJoomLeagueTables();
		}
		else
		{
			echo JText::_('Database tables of JoomLeague are not removed');
		}
		?>
		<div class="header">JoomLeague has been removed from your system!</div>
		<p>To completely remove Joomleague from your system, be sure to also
			uninstall the JoomLeague modules, plugins and languages.</p>

		<?php
		return true;
	}
	
	public function createImagesFolder()
	{
		$time_start = microtime(true);
		echo JText::_('Creating new Image Folder structure');
			
		$src = JPath::clean($this->install_rootfolder.'/media/database');
		$dest = JPath::clean(JPATH_ROOT.'/images/com_joomleague/database');
	
		if(JFolder::exists($src)) {
			$ret = JFolder::copy($src, $dest, '', true);
		}
		JFile::copy(JPATH_ROOT.'/media/index.html', JPATH_ROOT.'/images/com_joomleague/index.html', '', true);
		$folders = JFolder::folders($dest,'.',true);
		foreach ($folders as $folder) {
			$src = JPath::clean(JPATH_ROOT.'/media/com_joomleague/'.$folder);
			if(JFolder::exists($src)) {
				$to = JPath::clean($dest.'/'.$folder);
				if(!JFolder::exists($to)) {
					$ret = JFolder::move($src, $to);
				} else {
					$ret = JFolder::copy($src, $to, '', true);
					$ret = JFolder::delete($src);
				}
			}
		}
		//$from = JPath::clean(JPATH_ROOT.'/media/com_joomleague/database');
		//$ret = JFolder::delete($from);
		echo ' - <span style="color:green">'.JText::_('Success').'</span>';
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		
		if($this->debug) {
			echo '<br>Duration: '.round($time).'s<br>';
		}
	}
}
