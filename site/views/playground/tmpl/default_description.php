<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */ 
?>
<?php
if ( $this->playground->notes )
{
?>

<h2><?php echo JText::_('COM_JOOMLEAGUE_PLAYGROUND_NOTES'); ?></h2>
		
	<div class="venuecontent">
    <?php 
    $description = $this->playground->notes;
    $description = JHtml::_('content.prepare', $description);
    echo $description; 
    ?>
    </div>
    <?php
}
?>