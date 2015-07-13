<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 

// Make sure that in case extensions are written for mentioned (common) views,
// that they are loaded i.s.o. of the template of this view
$templatesToLoad = array('projectheading', 'backbutton', 'footer');
JoomleagueHelper::addTemplatePaths($templatesToLoad, $this);
?>
<div class="joomleague">
	<?php 
	if (($this->config['show_sectionheader'])==1)
	{ 
		echo $this->loadTemplate('sectionheader');
	}

	echo $this->loadTemplate('projectheading');

	// Needs some changing &Mindh4nt3r
	echo $this->loadTemplate('clubinfo');
		
	echo "<div class='jl_defaultview_spacing'>";
	echo "&nbsp;";
	echo "</div>";

	if ($this->config['show_description']==1)
	{
		echo $this->loadTemplate('description');
	}
	
	echo "<div class='jl_defaultview_spacing'>";
	echo "&nbsp;";
	echo "</div>";
	
	//fix me
	if (($this->config['show_extended'])==1)
	{
		echo $this->loadTemplate('extended');
		echo "<div class='jl_defaultview_spacing'>";
		echo "&nbsp;";
		echo "</div>";	
	}

	if (($this->config['show_maps'])==1 && (JPluginHelper::isEnabled('system', 'plugin_googlemap2') || JPluginHelper::isEnabled('system', 'plugin_googlemap3')))
	{ 
		echo $this->loadTemplate('maps');
		
		echo "<div class='jl_defaultview_spacing'>";
		echo "&nbsp;";
		echo "</div>";
	}

		
	if (($this->config['show_teams_of_club'])==1)
	{ 
		echo $this->loadTemplate('teams');
			
		echo "<div class='jl_defaultview_spacing'>";
		echo "&nbsp;";
		echo "</div>";
	}


	echo "<div>";
		echo $this->loadTemplate('backbutton');
		echo $this->loadTemplate('footer');
	echo "</div>";
	?>
</div>
