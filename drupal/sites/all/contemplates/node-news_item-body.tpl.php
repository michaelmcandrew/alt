<?php

  print $node->field_news_item_image[0]['view'];
  print $node->content['body']['#value'];
  $out = "";
  foreach ($field_news_item_public_doc as $doc) {
	
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
	foreach($field_news_item_private_do as $doc) {		
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
