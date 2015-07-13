<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<table width="100%" class="contentpaneopen">
	<tr>
		<td class="contentheading">
			<?php
			echo $this->pagetitle;
			if ( $this->showediticon )
			{
				$link = JoomleagueHelperRoute::getRefereeRoute( $this->project->id, 
																$this->referee->projectreferee_id, 
																'projectreferee.edit' );
				$desc = JHtml::image(
						"media/com_joomleague/jl_images/edit.png",
						JText::_( 'COM_JOOMLEAGUE_REFEREE_EDIT' ),
						array( "title" => JText::_( "COM_JOOMLEAGUE_REFEREE_EDIT" ) )
				);
				echo " ";
				echo JHtml::_('link', $link, $desc );
			} else {
				//echo "no permission";
			}
			?>
		</td>
	</tr>
</table>
