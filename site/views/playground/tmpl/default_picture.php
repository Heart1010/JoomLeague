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
if ( ( $this->playground->picture ) )
{
    ?>

 <h2><?php echo JText::_('COM_JOOMLEAGUE_PLAYGROUND_CLUB_PICTURE'); ?></h2>  
		<div class="venuecontent picture">
                <?php
                if (($this->playground->picture)) {
                    echo JHtml::image($this->playground->picture, $this->playground->name);
                } else {
                    echo JHtml::image(JoomleagueHelper::getDefaultPlaceholder("team"), $this->playground->name);
                }
                ?>
		</div>
    <?php
}
?>
