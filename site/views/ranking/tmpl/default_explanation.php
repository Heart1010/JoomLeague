<?php defined('_JEXEC') or die('Restricted access');

$config   = &$this->tableconfig;

$columns = explode( ",", $config['ordered_columns'] );
$column_names	= explode( ',', $config['ordered_columns_names'] );

if (!empty($columns)) 
{
  ?>

  <br />
  <table width="96%" border="0" cellpadding="0" cellspacing="0">
	  <tr class="explanation">
		  <?php
		  $d = 0;
		  foreach (  $columns as $k => $column)
		  {
			  if (empty($column_names[$k])){$column_names[$k]='???';}	
			  $c=strtoupper(trim($column));
			  $c="COM_JOOMLEAGUE_".$c;
			  echo "<td class=\"col$d\">";
			  echo "<b>".$column_names[$k]."</b> = ".JText::_($c) ;
			  echo "</td>\n";
			  $d=(1-$d);
		  }
		  ?>
	  </tr>
  </table>

<?php
}
?>