<?php

  print $node->field_media_image[0]['view'];
  print $node->content['body']['#value'];
  $out = "";
  if ($field_media_public_document) foreach ($field_media_public_document as $doc) {
	
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
	if ($field_media_private_document) foreach ($field_media_private_document as $doc) {		
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
