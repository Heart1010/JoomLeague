<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<table width="100%" class="contentpaneopen">
	<tr>
		<td class="contentheading">
			<?php
			echo $this->pagetitle;
			
			if ( $this->showediticon )
			{
				$modalheight = JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600);
				$modalwidth = JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900);
				$link = JoomleagueHelperRoute::getStaffRoute( $this->project->id, $this->teamStaff->project_team_id, $this->teamStaff->id, 'teamstaff.edit' );
				echo ' <a rel="{handler: \'iframe\',size: {x:'.$modalwidth.',y:'.$modalheight.'}}" href="'.$link.'" class="modal">';
				echo JHtml::image("media/com_joomleague/jl_images/edit.png",
										JText::_( 'COM_JOOMLEAGUE_STAFF_EDIT' ),
										array( "title" => JText::_( "COM_JOOMLEAGUE_STAFF_EDIT" ) )
				);
	    		echo '</a>';
			}
			?>
		</td>
	</tr>
</table>

