<?php defined( '_JEXEC' ) or die( 'Restricted access' );

if (	( isset($this->teamStaff->injury) && $this->teamStaff->injury > 0 ) ||
		( isset($this->teamStaff->suspension) && $this->teamStaff->suspension > 0 ) ||
		( isset($this->teamStaff->away) && $this->teamStaff->away > 0 ) )
{
	$today = JHtml::date('now' .' UTC',
					JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
					JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
	?>
	<h2><?php echo JText::_('COM_JOOMLEAGUE_PERSON_STATUS');	?></h2>

	<table class="status">
		<?php
		if ($this->teamStaff->injury > 0)
		{
			$injury_date = "";
			$injury_end  = "";

			$injury_date = JHtml::date($this->teamStaff->injury_date .' UTC',
										JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
										JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
			if(isset($this->teamStaff->rinjury_from))
			$injury_date .= " - ".$this->teamStaff->rinjury_from;

			//injury end
			$injury_end = JHtml::date($this->teamStaff->injury_end .' UTC',
										JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
										JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
			if(isset($this->teamStaff->rinjury_to))
			$injury_end .= " - ".$this->teamStaff->rinjury_to;

			if ($this->teamStaff->injury_date == $this->teamStaff->injury_end)
			{
				?>
				<tr>
					<td class="label">
							<?php
							$imageTitle = JText::_( 'COM_JOOMLEAGUE_PERSON_INJURED' );
							echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/injured.gif',
																$imageTitle,
																array( 'title' => $imageTitle,
																	   'style' => 'padding-right: 10px; vertical-align: middle;' ) );
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_INJURED' );
							?>
					</td>
					<td  class="data">
						<?php
						if ($injury_end != $today)
						{
							echo $injury_end;
						}
						?>
					</td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td class="label" colspan="2">
							<?php
							$imageTitle = JText::_( 'COM_JOOMLEAGUE_PERSON_INJURED' );
							echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/injured.gif',
																$imageTitle,
																array( 'title' => $imageTitle ) );
							?>
					</td>
				</tr>
				<tr>
					<td class="label">
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_INJURY_DATE' );
							?>
					</td>
					<td class="data">
						<?php
						echo $injury_date;
						?>
					</td>
				</tr>
				<?php
				if ($injury_end != $today)
				{
				?>
					<tr>
						<td class="label">
								<?php
								echo JText::_( 'COM_JOOMLEAGUE_PERSON_INJURY_END' );
								?>
						</td>
						<td class="data">
							<?php
								echo $injury_end;
							?>
						</td>
					</tr>
				<?php
				}
			}

			if (!empty($this->teamStaff->injury_detail))
			{
			?>
			<tr>
				<td class="label">
						<?php
						echo JText::_( 'COM_JOOMLEAGUE_PERSON_INJURY_TYPE' );
						?>
				</td>
				<td class="data">
					<?php
					printf( "%s", htmlspecialchars( $this->teamStaff->injury_detail ) );
					?>
				</td>
			</tr>
			<?php
			}
		}

		if ($this->teamStaff->suspension > 0)
		{
			$suspension_date = "";
			$suspension_end  = "";

			//suspension start
			$suspension_date = JHtml::date($this->teamStaff->suspension_date .' UTC',
											JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
											JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
			if(isset($this->teamStaff->rsusp_from))
			$suspension_date .= " - ".$this->teamStaff->rsusp_from;

			$suspension_end = JHtml::date($this->teamStaff->suspension_end .' UTC',
											JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
											JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
			if(isset($this->teamStaff->rsusp_to))
			$suspension_end .= " - ".$this->teamStaff->rsusp_to;


			if ($this->teamStaff->suspension_date == $this->teamStaff->suspension_end)
			{
				?>
				<tr>
					<td class="label">
							<?php
							$imageTitle = JText::_( 'Suspended' );
							echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/suspension.gif',
																$imageTitle,
																array( 'title' => $imageTitle,
																	   'style' => 'padding-right: 10px; vertical-align: middle;' ) );
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_SUSPENDED' );
							?>
					</td>
					<td class="data">
						<?php
						if ($suspension_end != $today)
						{
							echo $suspension_end;
						}
						?>
					</td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td class="label" colspan="2">
							<?php
							$imageTitle = JText::_( 'Suspended' );
							echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/suspension.gif',
																$imageTitle,
																array( 'title' => $imageTitle ) );
							?>
					</td>
				</tr>
				<tr>
					<td class="label">
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_SUSPENSION_DATE' );
							?>
					</td>
					<td class="data">
						<?php
						echo $suspension_date;
						?>
					</td>
				</tr>
				<?php
				if ($suspension_end != $today)
				{
				?>
				<tr>
					<td class="label">
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_SUSPENSION_END' );
							?>
					</td>
					<td class="data">
						<?php
						echo $suspension_end;
						?>
					</td>
				</tr>
				<?php
				}
			}

			if (!empty($this->teamStaff->suspension_detail))
			{
			?>
			<tr>
				<td class="label">
					<b>
						<?php
						echo JText::_( 'COM_JOOMLEAGUE_PERSON_SUSPENSION_REASON' );
						?>
					</b>
				</td>
				<td class="data">
					<?php
					printf( "%s", htmlspecialchars( $this->teamStaff->suspension_detail ) );
					?>
				</td>
			</tr>
			<?php
			}
		}

		if ($this->teamStaff->away > 0)
		{
			$away_date = "";
			$away_end  = "";

			//suspension start
			$away_date = JHtml::date($this->teamStaff->away_date .' UTC',
										JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
										JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
			if(isset($this->teamStaff->raway_from))
			$away_date .= " - ".$this->teamStaff->raway_from;

			$away_end = JHtml::date($this->teamStaff->away_end .' UTC',
									JText::_('COM_JOOMLEAGUE_GLOBAL_MATCHDAYDATE'),
									JoomleagueHelper::getTimezone($this->project, $this->overallconfig));
			if(isset($this->teamStaff->raway_to))
			$away_end .= " - ".$this->teamStaff->raway_to;

			if ($this->teamStaff->away_date == $this->teamStaff->away_end)
			{
				?>
				<tr>
					<td class="label">
							<?php
							$imageTitle = JText::_( 'Away' );
							echo "&nbsp;&nbsp;" . JHtml::image('images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/away.gif',
																$imageTitle,
																array( 'title' => $imageTitle,
																	   'style' => 'padding-right: 10px; vertical-align: middle;' ) );
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_AWAY' );
							?>
					</td>
					<td class="data">
						<?php
						if ($away_end != $today)
						{
							echo $away_end;
						}
						?>
					</td>
				</tr>
				<?php
			}
			else
			{
				?>
				<tr>
					<td class="label" colspan="2">
							<?php
							$imageTitle = JText::_( 'Away' );
							echo "&nbsp;&nbsp;" . JHtml::image(	'images/com_joomleague/database/events/'.$this->project->fs_sport_type_name.'/away.gif',
																$imageTitle,
																array( 'title' => $imageTitle ) );
							?>
					</td>
				</tr>
				<tr>
					<td class="label">
						<b>
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_AWAY_DATE' );
							?>
						</b>
					</td>
					<td class="data">
						<?php
						echo $away_date;
						?>
					</td>
				</tr>
				<?php
				if ($away_end != $today)
				{
				?>
				<tr>
					<td class="label">
							<?php
							echo JText::_( 'COM_JOOMLEAGUE_PERSON_AWAY_END' );
							?>
					</td>
					<td class="data">
						<?php
						echo $away_end;
						?>
					</td>
				</tr>
				<?php
				}
			}


			if (!empty($this->teamStaff->away_detail))
			{
			?>
			<tr>
				<td class="label">
						<?php
						echo JText::_( 'COM_JOOMLEAGUE_PERSON_AWAY_REASON' );
						?>
				</td>
				<td class="data">
					<?php
					printf( "%s", htmlspecialchars( $this->teamStaff->away_detail ) );
					?>
				</td>
			</tr>
			<?php
			}
		}
		?>
	</table>

	<?php
}
?>