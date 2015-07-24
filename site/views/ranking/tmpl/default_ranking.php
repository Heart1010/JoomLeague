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

<!-- Main START -->
<a name="jl_top" id="jl_top"></a>

<!-- content -->
<?php
foreach ( $this->currentRanking as $division => $cu_rk )
{
	if ($division)
	{
	?>
	<table class="table">
		<tr>
			<td class="contentheading">
				<?php
					//get the division name from the first team of the division 
					foreach( $cu_rk as $ptid => $team )
					{
						echo $this->divisions[$division]->name;
						break;
					}
				?>
			</td>
		</tr>
	</table>

	<table class="table">
	<?php
		$this->teams	= $this->model->getTeamsIndexedByPtid($division);
		foreach( $cu_rk as $ptid => $team )
		{
			echo $this->loadTemplate('rankingheading');
			break;
		}
		$this->division = division;
		$this->current  = $cu_rk;
		echo $this->loadTemplate('rankingrows');
	?>
	</table>
	<?php
	}
	else
	{
	?>
	<table class="table">
		<?php
			$this->teams	= $this->model->getTeamsIndexedByPtid($division);
			echo $this->loadTemplate('rankingheading');
			$this->division = $division;
			$this->current  = $cu_rk;
			echo $this->loadTemplate('rankingrows');
		?>
	</table>
	<br />
	<?php
	}
}
	?>
<!-- ranking END -->



