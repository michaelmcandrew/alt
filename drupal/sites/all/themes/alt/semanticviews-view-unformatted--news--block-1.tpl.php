<div id='news_featured'>
<?php 
switch(count($rows)) {
	
	case 1:
		
		echo grid("<div class='{$row_attributes[0]['class']}'>" . $rows[0] . "</div>", 12, "alpha omega lead");
		break;

	case 2:

		echo grid("<div class='{$row_attributes[0]['class']}'>" . $rows[0] . "</div>", 6, "alpha lead");
		echo grid("<div class='{$row_attributes[1]['class']}'>" . $rows[1] . "</div>", 6, "omega");
		break;
	
	case 3:

		echo grid("<div class='{$row_attributes[0]['class']}'>" . $rows[0] . "</div>", 4, "alpha lead");
		echo grid("<div class='{$row_attributes[1]['class']}'>" . $rows[1] . "</div>", 4);
		echo grid("<div class='{$row_attributes[2]['class']}'>" . $rows[2] . "</div>", 4, "omega");
		break;
		
}
?>
	<div class='clear'>&nbsp;</div>
</div>