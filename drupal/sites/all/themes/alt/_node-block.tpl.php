<?php
// $Id: node-nodeblock-default.tpl.php,v 1.1 2009/04/01 03:12:10 rz Exp $

/**
 * @file node-nodeblock-default.tpl.php
 *
 * Theme implementation to display a nodeblock enabled block. This template is
 * provided as a default implementation that will be called if no other node
 * template is provided. Any node-[type] templates defined by the theme will
 * take priority over this template. Also, a theme can override this template
 * file to provide its own default nodeblock theme.
 *
 * Additional variables:
 * - $nodeblock: Flag for the nodeblock context.
 */
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

<?php  print $picture ?>

<?php if (!$page && !$nodeblock): ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

  <div class="content">
<?php 
    
// sidebar block - move attached image into content after any h3 (if present)
$pattern = '/(<div class="all-.*<\/div>.*<\/div>).*(<h3>.*<\/h3>)(.*)/s';

preg_match($pattern, $content, $matches);

//krumo($matches);

switch(count($matches)) {
	
	case 4:
		$content = $matches[2] . $matches[1] . $matches[3];
		break;
		
}

echo $content;

?>
  </div>

  <?php print $links; ?>
</div>