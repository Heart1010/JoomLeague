<?php defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!-- colors legend START -->
<br />
<table cellpadding='0' cellspacing='0' border='0'>
	<tr>
		<?php
		JoomleagueHelper::showColorsLegend($this->colors, $this->config['show_colorlegend']);
		?>
	</tr>
</table>
<!-- colors legend END -->