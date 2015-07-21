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
$templatesToLoad = array('projectheading', 'backbutton', 'footer', 'results', 'matrix');
JoomleagueHelper::addTemplatePaths($templatesToLoad, $this);
?>
<div class="joomleague"><a name="jl_top" id="jl_top"></a>
	<?php 
	if ($this->config['show_sectionheader'])
	{
		echo $this->loadTemplate('sectionheader');
	}
		
	echo $this->loadTemplate('projectheading');
		
	echo $this->loadTemplate('selectround');
		
	$results = '';
	if ($this->config['show_sectionheader'])
	{
		$results .= $this->loadTemplate('sectionheaderres');
	}

	$results .= $this->loadTemplate('results');
		
	if ($this->params->get('what_to_show_first', 0) == 0)
	{
		echo $results;
	}

	if ($this->config['show_matrix']==1)
	{
		if ($this->config['show_sectionheader'])
		{
			echo $this->loadTemplate('sectionheadermatrix');
		}
		echo $this->loadTemplate('matrix');
	}

	if ($this->params->get('what_to_show_first', 0) == 1)
	{
		echo '<br />'.$results;
	}

	echo "<div>";
		echo $this->loadTemplate('backbutton');
		echo $this->loadTemplate('footer');
	echo "</div>";
	?>
</div>
