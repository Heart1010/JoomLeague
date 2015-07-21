<?php 
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;
?>		
		
		<fieldset class="adminform">
			<legend>
				<?php
				echo JText::sprintf(	'COM_JOOMLEAGUE_ADMIN_P_REF_DETAILS_TITLE',
				  JoomleagueHelper::formatName(null, $this->projectreferee->firstname, $this->projectreferee->nickname, $this->projectreferee->lastname, 0),
				  $this->projectws->name);
				?>
			</legend>
			<table class="admintable">
				<tr>
					<td class="key">
						<label for="position">
							<?php
								echo JText::_('COM_JOOMLEAGUE_ADMIN_P_REF_DETAILS_STAND_REF_POS');
							?>
						</label>
					</td>
					<td colspan="9">
						<?php
						echo $this->lists['refereepositions'];
						?>
					</td>
				</tr>
			</table>
		</fieldset>