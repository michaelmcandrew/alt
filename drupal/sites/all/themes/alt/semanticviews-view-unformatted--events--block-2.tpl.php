<div id='events_upcoming'>
<?php 
//krumo(get_defined_vars());

$i=0;
$count = count($rows);

for($i=0; $i < $count; $i++) {
	
	echo "<div class='{$row_attributes[$i]['class']}'>{$rows[$i]}</div>";
	
} 

?>
<div class='clear'>&nbsp;</div>
</div>