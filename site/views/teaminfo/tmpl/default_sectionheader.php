<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<!-- START: Contentheading -->
<div class="contentpaneopen">
	<div class="contentheading">
		<?php 
		echo $this->pagetitle;
		if ( $this->showediticon )
		{
			$modalheight = JComponentHelper::getParams('com_joomleague')->get('modal_popup_height', 600);
			$modalwidth = JComponentHelper::getParams('com_joomleague')->get('modal_popup_width', 900);
			$link = JoomleagueHelperRoute::getProjectTeamInfoRoute( $this->project->id, $this->team->project_team_id, 'projectteam.edit' );
			echo ' <a rel="{handler: \'iframe\',size: {x:'.$modalwidth.',y:'.$modalheight.'}}" href="'.$link.'" class="modal">';
			echo JHtml::image( "media/com_joomleague/jl_images/edit.png",
								JText::_( 'COM_JOOMLEAGUE_TEAMINFO_EDIT' ),
								array( "title" => JText::_( "COM_JOOMLEAGUE_TEAMINFO_EDIT" ) ));
			echo '</a>';
		}
		?>
	</div>
</div>
<!-- END: Contentheading -->
