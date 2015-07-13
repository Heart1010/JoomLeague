<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

	<?php
	// Show club-description if defined.
	if ( !isset ( $this->club->notes ) )
	{
		$description = "";
	}
	else
	{
		$description = $this->club->notes;
	}

	if( trim( $description != "" ) )
	{
		?>
<div class="description">
		<br />
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr class="sectiontableheader">
				<td>
					<?php
					echo '&nbsp;' . JText::_( 'COM_JOOMLEAGUE_CLUBINFO_CLUBINFORMATION' );
					?>
				</td>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<?php
					$description = JHtml::_('content.prepare', $description);
					echo stripslashes( $description );
					?>
				</td>
			</tr>
		</table>
                </div>
		<?php
	}
	?>
	<br />