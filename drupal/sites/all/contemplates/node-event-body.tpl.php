<div class='node-event-body'>
	<h1><?php echo $node->title ?></h1>
	
	<div class='header'>
		<div class='details'>
			<table>
				<tr>
					<th>Event Type:</th>
					<td><?php 
				
					$terms = taxonomy_node_get_terms_by_vocabulary($node, 5);
					
					foreach($terms as $term) {
						echo $term->name . " ";
					}
									
					?></td>
					
				</tr>
<?php
  $terms = taxonomy_node_get_terms_by_vocabulary($node, 2);
  if ($terms):
?>
				<tr>
					<th>Topics:</th>
					<td><?php foreach($terms as $term) echo $term->name . " "; ?></td>
				</tr>
<?php endif; ?>
				<tr>
					<th>Start:</th>
					<td><?php echo $node->field_event_date[0]['value'] ?></td>
				</tr>
				<tr>
					<th>End:</th>
					<td><?php echo $node->field_event_date[0]['value2'] ?></td>
				</tr>
<?php if ($node->field_event_venue[0]['view']): ?>
				<tr>
					<th>Venue:</th>
					<td><?php echo $node->field_event_venue[0]['view'] ?></td>
				</tr>
<?php endif; ?>
			</table>
		</div>

<?php // Only show 'book now' if event is in the future ?>		
<?php if ($node->field_event_date[0]['value2'] && strtotime($node->field_event_date[0]['value2'])>time()): ?>
		<div class='book'>
			<h3>Want to participate?</h3>
			<p>Cost: <strong><?php echo $node->field_event_cost[0]['view'] ?></strong></p>
			<div class='link'><?php echo $node->field_event_book_url[0]['view'] ?></div>
		
		</div>
<?php endif; ?>
		<div class='clear'>&nbsp;</div>
	</div>

	<div class='description'>	
		<div class='image'><?php echo $node->field_event_image[0]['view'] ?></div>
		<h2 class='subheading'><?php echo $node->field_event_subheading[0]['view'] ?></h2>
		
		<div class='body'><?php echo $node->content['body']['#value'] ?></div>
		
<?php

  $out = "";
  foreach ($field_event_public_document as $doc) {
	
	  $n = node_load($doc['nid']);
	  if ($n) {
		  $f = $n->field_public_document_file[0];		   
		  $ext = substr($f['filepath'], strrpos($f['filepath'], ".") + 1);	
		  $out.="<li><a class='file " . $ext . "' href='" . base_path() 
		    . $f['filepath'] . "'>" . $n->title . "</a> (" . $ext . " : " 
		    . format_size($f['filesize']) . ")</li>";
	  }
	
  }
	
?>

<?php if ($out): ?>
<h3>Public Documents</h3>
<ul class="documents">
	<?php echo $out;?>
</ul>
<?php endif; ?>

<?php 
  // clear $out ready to check for private documents
  $out = "";
	if ($field_event_private_document) foreach ($field_event_private_document as $doc) {		
		$n = node_load($doc['nid']);

		if ($n) {
			$f = $n->field_private_documument_file[0];			 
			$ext = substr($f['filepath'], strrpos($f['filepath'], ".") + 1);
			$out.="<li><a class='file " . $ext . "' href='" . base_path() 
			  . $f['filepath'] . "'>" . $n->title . "</a> (" . $ext . " : " 
			  . format_size($f['filesize']) . ")</li>";
		}
								
	}
?>
	
<?php if ($out): ?>
<h3>Private Documents</h3>
<ul class="documents">
	<?php echo $out; ?>
</ul>
<?php endif; ?>
	</div>
</div>
