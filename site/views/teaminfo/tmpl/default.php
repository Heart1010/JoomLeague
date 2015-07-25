<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die; 

// Make sure that in case extensions are written for mentioned (common) views,
// that they are loaded i.s.o. of the template of this view
$templatesToLoad = array('projectheading', 'backbutton', 'footer');
JoomleagueHelper::addTemplatePaths($templatesToLoad, $this);
?>
<div class="joomleague">
	<?php
	if (isset($this->config) && $this->config['show_sectionheader']==1)
	{
		echo $this->loadTemplate('sectionheader');
	}
		
	if (isset($this->config) && $this->config['show_projectheader']==1)
	{	
		echo $this->loadTemplate('projectheading');
	}
		
	if (isset($this->config) && $this->config['show_teaminfo']==1)
	{
		echo $this->loadTemplate('teaminfo');
	}

	if (isset($this->config) && $this->config['show_description']==1)
	{
		echo $this->loadTemplate('description');
	}
	//fix me css	
	if (isset($this->config) && $this->config['show_extended']==1)
	{
		echo $this->loadTemplate('extended');
	}	

	if (isset($this->config) && $this->config['show_training']==1)
	{
		echo $this->loadTemplate('training');
	}

	if (isset($this->config) && $this->config['show_history']==1)
	{
		echo $this->loadTemplate('history');
	}

	echo "<div>";
		echo $this->loadTemplate('backbutton');
		echo $this->loadTemplate('footer');
	echo "</div>";
	?>
</div>
