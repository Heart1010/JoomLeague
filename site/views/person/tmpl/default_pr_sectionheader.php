<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<table width="100%" class="contentpaneopen">
	<tr>
		<td class="contentheading">
			<?php
			echo $this->pagetitle;

			if ( isset($this->inprojectinfo->injury) && $this->inprojectinfo->injury )
			{
				$imageTitle = JText::_( 'Injured' );
				echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/injured.gif',
													$imageTitle,
													array( 'title' => $imageTitle ) );
			}

			if ( isset($this->inprojectinfo->suspension) && $this->inprojectinfo->suspension )
			{
				$imageTitle = JText::_( 'Suspended' );
				echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/suspension.gif',
													$imageTitle,
													array( 'title' => $imageTitle ) );
			}


			if ( isset($this->inprojectinfo->away) && $this->inprojectinfo->away )
			{
				$imageTitle = JText::_( 'Away' );
				echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/away.gif',
													$imageTitle,
													array( 'title' => $imageTitle ) );
			}
			?>
		</td>
	</tr>
</table>
<br />